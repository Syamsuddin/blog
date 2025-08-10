<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ColorSchemeService;
use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::where('is_active', true)
            ->orderBy('group')
            ->orderBy('order')
            ->get()
            ->groupBy('group');

        // If no settings exist, initialize with empty collection for each group
        if ($settings->isEmpty()) {
            $settings = collect([
                'general' => collect(),
                'appearance' => collect(),
                'seo' => collect(),
                'system' => collect(),
                'social' => collect()
            ]);
        }

        // Get additional data for the view
        $colorSchemes = ColorSchemeService::getColorSchemes();
        $backupFrequencies = BackupService::getBackupFrequencies();
        $backupList = BackupService::getBackupList();

        return view('admin.settings.index', compact('settings', 'colorSchemes', 'backupFrequencies', 'backupList'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array'
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    /**
     * Apply a color scheme
     */
    public function applyColorScheme(Request $request)
    {
        $request->validate([
            'scheme' => 'required|string'
        ]);

        $success = ColorSchemeService::applyColorScheme($request->scheme);

        if ($success) {
            return redirect()->route('admin.settings.index')
                ->with('success', 'Color scheme applied successfully!');
        }

        return redirect()->route('admin.settings.index')
            ->with('error', 'Failed to apply color scheme.');
    }

    /**
     * Create backup
     */
    public function createBackup()
    {
        $result = BackupService::createBackup();

        if ($result['success']) {
            return redirect()->route('admin.settings.index')
                ->with('success', 'Backup created successfully!');
        }

        return redirect()->route('admin.settings.index')
            ->with('error', 'Backup failed: ' . $result['error']);
    }

    /**
     * Download backup
     */
    public function downloadBackup(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'string', 'regex:/^backup_\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}\.zip$/']
        ]);
        
        $fileName = $validated['file'];
        $backupPath = BackupService::downloadBackup($fileName);

        if ($backupPath) {
            return response()->download($backupPath);
        }

        return redirect()->route('admin.settings.index')
            ->with('error', 'Backup file not found.');
    }

    /**
     * Delete backup
     */
    public function deleteBackup(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'string', 'regex:/^backup_\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}\.zip$/']
        ]);
        
        $fileName = $validated['file'];
        $success = BackupService::deleteBackup($fileName);

        if ($success) {
            return redirect()->route('admin.settings.index')
                ->with('success', 'Backup deleted successfully!');
        }

        return redirect()->route('admin.settings.index')
            ->with('error', 'Failed to delete backup.');
    }
}
