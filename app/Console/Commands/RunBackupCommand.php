<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class RunBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup {--type=full : The type of backup (full, db, files)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a complete database and media backup (supports MySQL and SQLite)';

    /**
     * Execute the console command.
     */
    public function handle(BackupService $backupService): int
    {
        $type = $this->option('type') ?: 'full';

        $this->info("Starting {$type} backup...");

        try {
            $zipPath = $backupService->createBackup($type);
            $fileName = basename($zipPath);
            $size = round(filesize($zipPath) / 1024 / 1024, 2);

            $this->info("Backup archive created successfully!");
            $this->line("File: <comment>{$fileName}</comment>");
            $this->line("Size: <comment>{$size} MB</comment>");
            $this->line("Location: <comment>{$zipPath}</comment>");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Backup failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
