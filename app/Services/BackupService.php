<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use ZipArchive;

class BackupService
{
    protected string $backupDir;
    protected string $tempDir;

    public function __construct()
    {
        $this->backupDir = storage_path('app/backups/Modhu');
        $this->tempDir   = storage_path('app/backup-temp');

        File::ensureDirectoryExists($this->backupDir);
        File::ensureDirectoryExists($this->tempDir);
    }

    /**
     * Create a new backup archive (full, db, or files).
     */
    public function createBackup(string $type = 'full'): string
    {
        $timestamp = Carbon::now()->format('Y-m-d-H-i-s');
        $zipFileName = "modhu-backup-{$timestamp}.zip";
        $zipPath = $this->backupDir . DIRECTORY_SEPARATOR . $zipFileName;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Could not create ZIP archive at: {$zipPath}");
        }

        $tempFilesToDelete = [];

        try {
            // 1. Process Database Dump if requested
            if (in_array($type, ['full', 'db'])) {
                $dumpFiles = $this->dumpDatabase();
                foreach ($dumpFiles as $internalName => $filePath) {
                    if (File::exists($filePath)) {
                        $zip->addFile($filePath, $internalName);
                        $tempFilesToDelete[] = $filePath;
                    }
                }
            }

            // 2. Process Media & Uploaded Files if requested
            if (in_array($type, ['full', 'files'])) {
                $this->addMediaFilesToZip($zip);
            }

            // 3. Add a backup manifest / readme
            $manifest = $this->generateManifest($type, $timestamp);
            $zip->addFromString('backup-manifest.json', json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            $zip->close();

            // 4. Apply Retention Cleanup
            $this->cleanOldBackups();

            Log::info("Backup archive successfully created: {$zipFileName} (Type: {$type}, Size: " . File::size($zipPath) . " bytes)");

            return $zipPath;
        } finally {
            // Guarantee all temporary dump files are deleted even if an exception occurs
            foreach ($tempFilesToDelete as $tFile) {
                if (File::exists($tFile)) {
                    @unlink($tFile);
                }
            }
            $this->cleanTempDirectory();
        }
    }

    /**
     * Clean any stray files in the temporary directory older than 10 minutes.
     */
    protected function cleanTempDirectory(): void
    {
        try {
            if (File::exists($this->tempDir)) {
                $files = File::files($this->tempDir);
                foreach ($files as $file) {
                    if (str_starts_with($file->getFilename(), 'database-') || (time() - $file->getMTime()) > 600) {
                        @unlink($file->getPathname());
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignore temp cleanup errors
        }
    }

    /**
     * Dump the database supporting both MySQL and SQLite.
     * Returns an associative array of [internalNameInZip => absoluteTempPath].
     */
    protected function dumpDatabase(): array
    {
        $driver = config('database.default', 'mysql');
        $timestamp = Carbon::now()->format('Y-m-d-H-i-s');
        $result = [];

        if ($driver === 'sqlite') {
            $sqlitePath = config('database.connections.sqlite.database');
            if ($sqlitePath && File::exists($sqlitePath)) {
                $tempSqlite = $this->tempDir . DIRECTORY_SEPARATOR . "database-{$timestamp}.sqlite";
                File::copy($sqlitePath, $tempSqlite);
                $result['database.sqlite'] = $tempSqlite;
            }

            // Also generate human-readable SQL dump for SQLite
            $tempSql = $this->tempDir . DIRECTORY_SEPARATOR . "database-dump-{$timestamp}.sql";
            $this->dumpSqliteToSql($tempSql);
            $result['database-dump.sql'] = $tempSql;

            return $result;
        }

        // For MySQL / MariaDB
        $tempSql = $this->tempDir . DIRECTORY_SEPARATOR . "database-dump-{$timestamp}.sql";
        $dumped = false;

        // Attempt 1: Try mysqldump binary if available
        $dumpBinary = config('database.connections.mysql.dump.dump_binary_path', '');
        $mysqldumpPath = $dumpBinary ? rtrim($dumpBinary, '\\/') . DIRECTORY_SEPARATOR . 'mysqldump' : 'mysqldump';
        if (PHP_OS_FAMILY === 'Windows' && !str_ends_with(strtolower($mysqldumpPath), '.exe')) {
            $mysqldumpPath .= '.exe';
        }

        if (File::exists($mysqldumpPath) || $this->commandExists('mysqldump')) {
            $dumped = $this->tryMysqldumpCli($mysqldumpPath, $tempSql);
        }

        // Attempt 2: If mysqldump CLI failed or is unavailable (e.g. Windows socket 10106 or missing binary), use Native PDO Dumper
        if (!$dumped || !File::exists($tempSql) || File::size($tempSql) === 0) {
            $this->dumpMySqlViaPdo($tempSql);
        }

        $result['database-dump.sql'] = $tempSql;
        return $result;
    }

    /**
     * Try dumping MySQL using CLI mysqldump binary.
     */
    protected function tryMysqldumpCli(string $binary, string $outputFile): bool
    {
        try {
            $config = config('database.connections.mysql');
            $host = $config['host'] ?? '127.0.0.1';
            $port = $config['port'] ?? '3306';
            $user = $config['username'] ?? 'root';
            $pass = $config['password'] ?? '';
            $db   = $config['database'] ?? '';

            $cmd = sprintf(
                '"%s" --user=%s --password=%s --host=%s --port=%s %s > "%s"',
                $binary,
                escapeshellarg($user),
                escapeshellarg($pass),
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($db),
                $outputFile
            );

            // Execute with system environment variables to prevent Winsock LSP error 10106 on Windows
            $descriptors = [
                0 => ['pipe', 'r'],
                1 => ['file', $outputFile, 'w'],
                2 => ['pipe', 'w'],
            ];

            $env = $_SERVER;
            if (PHP_OS_FAMILY === 'Windows' && empty($env['SystemRoot'])) {
                $env['SystemRoot'] = getenv('SystemRoot') ?: 'C:\\Windows';
            }

            $process = proc_open($cmd, $descriptors, $pipes, base_path(), $env);

            if (is_resource($process)) {
                fclose($pipes[0]);
                $stderr = stream_get_contents($pipes[2]);
                fclose($pipes[2]);
                $exitCode = proc_close($process);

                if ($exitCode === 0 && File::exists($outputFile) && File::size($outputFile) > 100) {
                    return true;
                }
                Log::warning("mysqldump CLI exit code {$exitCode}: {$stderr}. Falling back to Native PDO dumper.");
            }
        } catch (\Throwable $e) {
            Log::warning("mysqldump CLI execution failed: " . $e->getMessage() . ". Falling back to Native PDO dumper.");
        }

        return false;
    }

    /**
     * 100% reliable Native PHP PDO Database Dumper for MySQL / MariaDB.
     * Works in ANY environment without requiring external binaries or sockets.
     */
    protected function dumpMySqlViaPdo(string $outputFile): void
    {
        $pdo = DB::connection()->getPdo();
        $databaseName = DB::connection()->getDatabaseName();

        $handle = fopen($outputFile, 'w');
        if (!$handle) {
            throw new \RuntimeException("Cannot open file for writing: {$outputFile}");
        }

        $header = "-- ============================================================\n"
                . "-- Modhu Database Backup (Native PDO Engine)\n"
                . "-- Database: {$databaseName}\n"
                . "-- Created At: " . Carbon::now()->toIso8601String() . "\n"
                . "-- ============================================================\n\n"
                . "SET NAMES utf8mb4;\n"
                . "SET FOREIGN_KEY_CHECKS = 0;\n"
                . "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n"
                . "SET time_zone = '+00:00';\n\n";

        fwrite($handle, $header);

        // Retrieve all tables in the database
        $tables = Schema::getTableListing();

        foreach ($tables as $fullTable) {
            $tableName = last(explode('.', $fullTable));

            // 1. Structure (CREATE TABLE)
            $createTableResult = DB::select("SHOW CREATE TABLE `{$tableName}`");
            if (empty($createTableResult)) {
                continue;
            }

            $createRow = (array) $createTableResult[0];
            $createTableSql = $createRow['Create Table'] ?? ($createRow['create table'] ?? null);

            if (!$createTableSql) {
                continue;
            }

            fwrite($handle, "-- ------------------------------------------------------------\n");
            fwrite($handle, "-- Table structure for `{$tableName}`\n");
            fwrite($handle, "-- ------------------------------------------------------------\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
            fwrite($handle, $createTableSql . ";\n\n");

            // 2. Data (INSERT INTO)
            $totalRows = DB::table($tableName)->count();
            if ($totalRows === 0) {
                continue;
            }

            fwrite($handle, "-- Dumping data for table `{$tableName}` ({$totalRows} rows)\n");

            $batch = [];
            foreach (DB::table($tableName)->cursor() as $record) {
                $batch[] = (array) $record;
                if (count($batch) >= 500) {
                    $this->writeInsertBatch($handle, $tableName, $batch, $pdo);
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                $this->writeInsertBatch($handle, $tableName, $batch, $pdo);
            }

            fwrite($handle, "\n");
        }

        $footer = "SET FOREIGN_KEY_CHECKS = 1;\n"
                . "-- Backup completed successfully.\n";
        fwrite($handle, $footer);

        fclose($handle);
    }

    /**
     * Write an INSERT batch statement into the dump handle.
     */
    protected function writeInsertBatch($handle, string $tableName, array $batch, \PDO $pdo): void
    {
        if (empty($batch)) {
            return;
        }

        $columns = array_keys($batch[0]);
        $colNames = implode(', ', array_map(fn($col) => "`{$col}`", $columns));

        $valueLines = [];
        foreach ($batch as $row) {
            $rowValues = [];
            foreach ($row as $val) {
                if ($val === null) {
                    $rowValues[] = 'NULL';
                } elseif (is_numeric($val) && !str_starts_with((string)$val, '0')) {
                    $rowValues[] = $val;
                } else {
                    $rowValues[] = $pdo->quote($val);
                }
            }
            $valueLines[] = '(' . implode(', ', $rowValues) . ')';
        }

        $insertSql = "INSERT INTO `{$tableName}` ({$colNames}) VALUES\n" . implode(",\n", $valueLines) . ";\n";
        fwrite($handle, $insertSql);
    }

    /**
     * Human-readable SQL dump for SQLite databases.
     */
    protected function dumpSqliteToSql(string $outputFile): void
    {
        $handle = fopen($outputFile, 'w');
        if (!$handle) {
            return;
        }

        $header = "-- SQLite Database Backup\n-- Created At: " . Carbon::now()->toIso8601String() . "\nPRAGMA foreign_keys=OFF;\nBEGIN TRANSACTION;\n\n";
        fwrite($handle, $header);

        $tables = Schema::getTableListing();

        foreach ($tables as $table) {
            $tableName = last(explode('.', $table));
            if (in_array($tableName, ['sqlite_sequence', 'sqlite_stat1'])) {
                continue;
            }

            $schemaRows = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name = ?", [$tableName]);
            if (!empty($schemaRows) && !empty($schemaRows[0]->sql)) {
                fwrite($handle, $schemaRows[0]->sql . ";\n");

                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $cols = array_keys((array) $row);
                    $vals = array_map(function ($val) {
                        if ($val === null) return 'NULL';
                        return "'" . str_replace("'", "''", $val) . "'";
                    }, (array) $row);

                    fwrite($handle, "INSERT INTO \"{$tableName}\" (\"" . implode('", "', $cols) . "\") VALUES (" . implode(', ', $vals) . ");\n");
                }
                fwrite($handle, "\n");
            }
        }

        fwrite($handle, "COMMIT;\n");
        fclose($handle);
    }

    /**
     * Add all uploaded media files to the ZIP archive.
     */
    protected function addMediaFilesToZip(ZipArchive $zip): void
    {
        $mediaSources = [
            'storage' => storage_path('app/public'),
            'uploads' => public_path('uploads'),
            'assets'  => public_path('assets'),
        ];

        foreach ($mediaSources as $prefix => $sourcePath) {
            if (!File::exists($sourcePath)) {
                continue;
            }

            $files = File::allFiles($sourcePath);
            foreach ($files as $file) {
                // Get relative path inside the media folder
                $relativePath = substr($file->getPathname(), strlen($sourcePath) + 1);
                $zipInternalPath = 'media/' . $prefix . '/' . str_replace('\\', '/', $relativePath);
                $zip->addFile($file->getPathname(), $zipInternalPath);
            }
        }
    }

    /**
     * Generate metadata manifest for the backup archive.
     */
    protected function generateManifest(string $type, string $timestamp): array
    {
        return [
            'app_name'        => config('app.name', 'Modhu'),
            'backup_type'     => $type,
            'created_at'      => Carbon::now()->toIso8601String(),
            'database_driver' => config('database.default'),
            'database_name'   => DB::connection()->getDatabaseName(),
            'laravel_version' => app()->version(),
            'php_version'     => PHP_VERSION,
        ];
    }

    /**
     * Delete backups older than retention policy (keeps last 15 backups).
     */
    protected function cleanOldBackups(int $maxBackupsToKeep = 15): void
    {
        try {
            $allFiles = File::files($this->backupDir);
            $zipFiles = [];

            foreach ($allFiles as $file) {
                if ($file->getExtension() === 'zip') {
                    $zipFiles[] = $file;
                }
            }

            // Sort newest to oldest
            usort($zipFiles, function ($a, $b) {
                return $b->getMTime() <=> $a->getMTime();
            });

            // Keep up to $maxBackupsToKeep, delete the rest
            if (count($zipFiles) > $maxBackupsToKeep) {
                $filesToDelete = array_slice($zipFiles, $maxBackupsToKeep);
                foreach ($filesToDelete as $oldFile) {
                    File::delete($oldFile->getPathname());
                    Log::info("Retention policy: Deleted old backup file {$oldFile->getFilename()}");
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Could not execute backup cleanup: " . $e->getMessage());
        }
    }

    /**
     * Helper to test if a CLI command exists.
     */
    protected function commandExists(string $cmd): bool
    {
        $where = PHP_OS_FAMILY === 'Windows' ? 'where' : 'which';
        $output = @shell_exec("{$where} {$cmd} 2>&1");
        return !empty($output) && !str_contains($output, 'Could not find') && !str_contains($output, 'not found');
    }
}
