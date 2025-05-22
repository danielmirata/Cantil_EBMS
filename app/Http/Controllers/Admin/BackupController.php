<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;
use Carbon\Carbon;

class BackupController extends Controller
{
    protected $backupPath;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        if (!file_exists($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    public function index()
    {
        $backups = $this->getBackups();
        return view('admin.backup_restore', compact('backups'));
    }

    public function create(Request $request)
    {
        try {
            $backupName = $request->backup_name ?? 'backup_' . Carbon::now()->format('Y-m-d_H-i-s');
            $filename = $backupName . '.zip';
            $zipPath = $this->backupPath . '/' . $filename;

            // Create a new zip archive
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                // Export database
                $dbFilename = 'database.sql';
                $dbPath = $this->backupPath . '/' . $dbFilename;
                
                // Get database configuration
                $dbName = config('database.connections.mysql.database');
                $dbUser = config('database.connections.mysql.username');
                $dbPass = config('database.connections.mysql.password');
                
                // Create database dump
                $command = sprintf(
                    'mysqldump -u %s -p%s %s > %s',
                    $dbUser,
                    $dbPass,
                    $dbName,
                    $dbPath
                );
                exec($command);

                // Add database dump to zip
                $zip->addFile($dbPath, $dbFilename);

                // Add storage files to zip
                $this->addDirToZip($zip, storage_path('app/public'), 'storage');

                $zip->close();

                // Clean up temporary database file
                unlink($dbPath);

                return redirect()->back()->with('success', 'Backup created successfully');
            }

            return redirect()->back()->with('error', 'Failed to create backup');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating backup: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        try {
            if (!$request->hasFile('backup_file')) {
                return redirect()->back()->with('error', 'No backup file selected');
            }

            $file = $request->file('backup_file');
            $tempPath = $file->getRealPath();
            $zip = new ZipArchive();

            if ($zip->open($tempPath) === TRUE) {
                // Extract to temporary directory
                $extractPath = storage_path('app/temp_restore');
                if (!file_exists($extractPath)) {
                    mkdir($extractPath, 0755, true);
                }
                $zip->extractTo($extractPath);
                $zip->close();

                // Restore database
                $dbFile = $extractPath . '/database.sql';
                if (file_exists($dbFile)) {
                    $dbName = config('database.connections.mysql.database');
                    $dbUser = config('database.connections.mysql.username');
                    $dbPass = config('database.connections.mysql.password');

                    $command = sprintf(
                        'mysql -u %s -p%s %s < %s',
                        $dbUser,
                        $dbPass,
                        $dbName,
                        $dbFile
                    );
                    exec($command);
                }

                // Restore storage files
                if (file_exists($extractPath . '/storage')) {
                    $this->copyDir($extractPath . '/storage', storage_path('app/public'));
                }

                // Clean up
                $this->deleteDir($extractPath);

                return redirect()->back()->with('success', 'Backup restored successfully');
            }

            return redirect()->back()->with('error', 'Failed to restore backup');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error restoring backup: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $filePath = $this->backupPath . '/' . $filename;
        if (file_exists($filePath)) {
            return response()->download($filePath);
        }
        return redirect()->back()->with('error', 'Backup file not found');
    }

    public function delete($filename)
    {
        $filePath = $this->backupPath . '/' . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
            return redirect()->back()->with('success', 'Backup deleted successfully');
        }
        return redirect()->back()->with('error', 'Backup file not found');
    }

    protected function getBackups()
    {
        $backups = [];
        if ($handle = opendir($this->backupPath)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && pathinfo($entry, PATHINFO_EXTENSION) == 'zip') {
                    $backups[] = [
                        'filename' => $entry,
                        'name' => pathinfo($entry, PATHINFO_FILENAME),
                        'date' => Carbon::createFromTimestamp(filemtime($this->backupPath . '/' . $entry))->format('Y-m-d H:i:s'),
                        'size' => $this->formatSize(filesize($this->backupPath . '/' . $entry))
                    ];
                }
            }
            closedir($handle);
        }
        return collect($backups)->sortByDesc('date')->values()->all();
    }

    protected function addDirToZip($zip, $dir, $basePath = '')
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $basePath . '/' . substr($filePath, strlen($dir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    }

    protected function copyDir($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->copyDir($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    protected function deleteDir($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDir($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    protected function formatSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }
} 