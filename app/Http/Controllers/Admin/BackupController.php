<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    /**
     * Display a listing of all available backups.
     */
    public function index()
    {
        $backupDir = storage_path('app/backups');
        $files = [];
        $totalBytes = 0;

        if (File::exists($backupDir)) {
            $allZipFiles = File::allFiles($backupDir);

            // Sort files from newest to oldest
            usort($allZipFiles, function ($a, $b) {
                return $b->getMTime() <=> $a->getMTime();
            });

            foreach ($allZipFiles as $file) {
                if ($file->getExtension() === 'zip') {
                    $bytes = $file->getSize();
                    $totalBytes += $bytes;

                    $files[] = [
                        'name'       => $file->getFilename(),
                        'path'       => $file->getPathname(),
                        'size'       => $this->formatBytes($bytes),
                        'raw_size'   => $bytes,
                        'date'       => Carbon::createFromTimestamp($file->getMTime())->format('d M, Y h:i A'),
                        'age'        => Carbon::createFromTimestamp($file->getMTime())->diffForHumans(),
                    ];
                }
            }
        }

        $totalSizeFormatted = $this->formatBytes($totalBytes);

        return view('admin.backups.index', compact('files', 'totalSizeFormatted', 'totalBytes'));
    }

    /**
     * Run a manual backup on-demand.
     */
    public function store(Request $request)
    {
        $type = $request->input('type', 'full');

        try {
            // Apply database mail settings if notifications are sent
            \App\Services\MailConfigService::apply();

            $backupService = new \App\Services\BackupService();
            $zipPath = $backupService->createBackup($type);
            $fileName = basename($zipPath);

            if ($type === 'db') {
                $message = "ডাটাবেজ ব্যাকআপ ({$fileName}) সফলভাবে তৈরি করা হয়েছে!";
            } elseif ($type === 'files') {
                $message = "মিডিয়া ফাইলস ব্যাকআপ ({$fileName}) সফলভাবে তৈরি করা হয়েছে!";
            } else {
                $message = "সম্পূর্ণ ডাটাবেজ ও মিডিয়া ব্যাকআপ ({$fileName}) সফলভাবে তৈরি করা হয়েছে!";
            }

            return redirect()->route('admin.backups.index')->with('success', $message);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Backup Error: ' . $e->getMessage());
            return redirect()->route('admin.backups.index')
                ->with('error', 'ব্যাকআপ তৈরি ব্যর্থ হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Download the specified backup zip archive.
     */
    public function download(string $fileName)
    {
        // Sanitize to prevent path traversal attacks
        $fileName = basename($fileName);

        if (!str_ends_with($fileName, '.zip')) {
            abort(400, 'Invalid backup file format.');
        }

        $backupDir = storage_path('app/backups');
        $filePath = null;

        if (File::exists($backupDir)) {
            $matchingFiles = File::allFiles($backupDir);
            foreach ($matchingFiles as $file) {
                if ($file->getFilename() === $fileName) {
                    $filePath = $file->getPathname();
                    break;
                }
            }
        }

        if (!$filePath || !File::exists($filePath)) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'ব্যাকআপ ফাইলটি সার্ভারে পাওয়া যায়নি।');
        }

        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/zip',
        ]);
    }

    /**
     * Delete the specified backup zip archive.
     */
    public function destroy(string $fileName)
    {
        $fileName = basename($fileName);
        $backupDir = storage_path('app/backups');
        $deleted = false;

        if (File::exists($backupDir)) {
            $matchingFiles = File::allFiles($backupDir);
            foreach ($matchingFiles as $file) {
                if ($file->getFilename() === $fileName) {
                    File::delete($file->getPathname());
                    $deleted = true;
                    break;
                }
            }
        }

        if ($deleted) {
            return redirect()->route('admin.backups.index')
                ->with('success', 'ব্যাকআপ ফাইল "' . $fileName . '" সফলভাবে মুছে ফেলা হয়েছে।');
        }

        return redirect()->route('admin.backups.index')
            ->with('error', 'ব্যাকআপ ফাইলটি খুঁজে পাওয়া যায়নি।');
    }

    /**
     * Helper to format bytes to human readable format (KB, MB, GB).
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
