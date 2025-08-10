<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use ZipArchive;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupService
{
    /**
     * Get backup frequency options
     */
    public static function getBackupFrequencies(): array
    {
        return [
            'disabled' => [
                'name' => 'Disabled',
                'description' => 'No automatic backups',
                'cron' => null
            ],
            'daily' => [
                'name' => 'Daily',
                'description' => 'Create backup every day at 2:00 AM',
                'cron' => '0 2 * * *'
            ],
            'weekly' => [
                'name' => 'Weekly',
                'description' => 'Create backup every Sunday at 2:00 AM',
                'cron' => '0 2 * * 0'
            ],
            'monthly' => [
                'name' => 'Monthly',
                'description' => 'Create backup on the 1st of each month at 2:00 AM',
                'cron' => '0 2 1 * *'
            ]
        ];
    }

    /**
     * Create a full backup
     */
    public static function createBackup(): array
    {
        try {
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $backupName = "backup_{$timestamp}";
            $backupPath = storage_path("app/backups/{$backupName}");

            // Create backup directory
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            // 1. Database backup
            $dbBackup = self::createDatabaseBackup($backupPath);

            // 2. Files backup
            $filesBackup = self::createFilesBackup($backupPath);

            // 3. Create zip archive
            $zipPath = self::createZipArchive($backupPath, $backupName);

            // 4. Clean up temporary files
            File::deleteDirectory($backupPath);

            // 5. Clean old backups
            self::cleanOldBackups();

            return [
                'success' => true,
                'backup_name' => $backupName,
                'backup_path' => $zipPath,
                'size' => File::size($zipPath),
                'created_at' => Carbon::now()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create database backup
     */
    private static function createDatabaseBackup(string $backupPath): string
    {
        $databaseName = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port', 3306);

        $sqlFile = $backupPath . '/database.sql';

        // Use Symfony Process for secure command execution
        $command = [
            'mysqldump',
            '--single-transaction',
            '--routines',
            '--triggers',
            '--user=' . $username,
            '--password=' . $password,
            '--host=' . $host,
            '--port=' . $port,
            $databaseName
        ];

        $process = new Process($command);
        $process->setTimeout(300); // 5 minutes timeout
        
        try {
            $process->mustRun();
            File::put($sqlFile, $process->getOutput());
        } catch (ProcessFailedException $exception) {
            throw new \Exception('Database backup failed: ' . $exception->getMessage());
        }

        return $sqlFile;
    }

    /**
     * Create files backup
     */
    private static function createFilesBackup(string $backupPath): string
    {
        $sourceDir = base_path();
        $targetDir = $backupPath . '/files';

        // Create target directory
        File::makeDirectory($targetDir, 0755, true);

        // Copy important directories
        $importantDirs = [
            'app',
            'config',
            'database',
            'public',
            'resources',
            'routes',
            'storage/app/public'
        ];

        foreach ($importantDirs as $dir) {
            $source = $sourceDir . '/' . $dir;
            $target = $targetDir . '/' . $dir;

            if (File::exists($source)) {
                if (File::isDirectory($source)) {
                    File::copyDirectory($source, $target);
                } else {
                    File::copy($source, $target);
                }
            }
        }

        // Copy important files
        $importantFiles = [
            '.env',
            'composer.json',
            'composer.lock',
            'package.json',
            'artisan'
        ];

        foreach ($importantFiles as $file) {
            $source = $sourceDir . '/' . $file;
            $target = $targetDir . '/' . $file;

            if (File::exists($source)) {
                File::copy($source, $target);
            }
        }

        return $targetDir;
    }

    /**
     * Create zip archive
     */
    private static function createZipArchive(string $backupPath, string $backupName): string
    {
        $zipPath = storage_path("app/backups/{$backupName}.zip");
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Cannot create zip archive');
        }

        $files = File::allFiles($backupPath);

        foreach ($files as $file) {
            $relativePath = str_replace($backupPath . '/', '', $file->getPathname());
            $zip->addFile($file->getPathname(), $relativePath);
        }

        $zip->close();

        return $zipPath;
    }

    /**
     * Clean old backups based on retention settings
     */
    private static function cleanOldBackups(): void
    {
        $retentionDays = (int) setting('backup_retention', 30);
        $backupDir = storage_path('app/backups');

        if (!File::exists($backupDir)) {
            return;
        }

        $files = File::files($backupDir);
        $cutoffDate = Carbon::now()->subDays($retentionDays);

        foreach ($files as $file) {
            $fileDate = Carbon::createFromTimestamp(File::lastModified($file->getPathname()));
            
            if ($fileDate->lt($cutoffDate)) {
                File::delete($file->getPathname());
            }
        }
    }

    /**
     * Get list of available backups
     */
    public static function getBackupList(): array
    {
        $backupDir = storage_path('app/backups');
        $backups = [];

        if (!File::exists($backupDir)) {
            return $backups;
        }

        $files = File::files($backupDir);

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'size' => File::size($file->getPathname()),
                    'created_at' => Carbon::createFromTimestamp(File::lastModified($file->getPathname())),
                    'path' => $file->getPathname()
                ];
            }
        }

        // Sort by creation date (newest first)
        usort($backups, function ($a, $b) {
            return $b['created_at']->compare($a['created_at']);
        });

        return $backups;
    }

    /**
     * Download backup file
     */
    public static function downloadBackup(string $fileName): ?string
    {
        // Validate filename to prevent path traversal
        if (!self::isValidBackupFileName($fileName)) {
            return null;
        }

        $backupPath = storage_path("app/backups/{$fileName}");

        if (File::exists($backupPath)) {
            return $backupPath;
        }

        return null;
    }

    /**
     * Delete backup file
     */
    public static function deleteBackup(string $fileName): bool
    {
        // Validate filename to prevent path traversal
        if (!self::isValidBackupFileName($fileName)) {
            return false;
        }

        $backupPath = storage_path("app/backups/{$fileName}");

        if (File::exists($backupPath)) {
            return File::delete($backupPath);
        }

        return false;
    }

    /**
     * Validate backup filename to prevent path traversal attacks
     */
    private static function isValidBackupFileName(string $fileName): bool
    {
        // Check for path traversal attempts
        if (str_contains($fileName, '..') || str_contains($fileName, '/') || str_contains($fileName, '\\')) {
            return false;
        }

        // Only allow backup zip files
        if (!preg_match('/^backup_\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}\.zip$/', $fileName)) {
            return false;
        }

        return true;
    }
}
