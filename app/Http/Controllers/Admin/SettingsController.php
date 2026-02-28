<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Show settings page
     */
    public function index()
    {
        $settings = [
            'app_name' => config('app.name'),
            'app_description' => config('app.description'),
            'support_email' => config('app.support_email'),
            'support_phone' => config('app.support_phone'),
            'ebook_expiry_days' => 7,
            'max_download_per_hour' => 3,
        ];

        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'support_email' => 'required|email',
            'support_phone' => 'required|string|max:20',
            'ebook_expiry_days' => 'required|integer|min:1|max:365',
            'max_download_per_hour' => 'required|integer|min:1|max:100',
        ]);

        // Update .env file or config
        try {
            $this->updateEnvFile($validated);

            return back()->with('success', 'Pengaturan berhasil disimpan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan pengaturan: ' . $e->getMessage()]);
        }
    }

    /**
     * Update env file
     */
    private function updateEnvFile($settings)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            throw new \Exception('.env file not found');
        }

        $content = file_get_contents($envPath);

        foreach ($settings as $key => $value) {
            $pattern = "/^(" . preg_quote(strtoupper($key)) . ")=(.*)$/m";
            $replacement = "$1=" . $value;
            $content = preg_replace($pattern, $replacement, $content);
        }

        file_put_contents($envPath, $content);
    }

    /**
     * Show logs
     */
    public function logs()
    {
        $logFile = storage_path('logs/laravel-' . date('Y-m-d') . '.log');

        if (!file_exists($logFile)) {
            $logs = 'Tidak ada log hari ini';
        } else {
            $logs = file_get_contents($logFile);
            $logs = array_reverse(file($logFile));
            $logs = implode('', array_slice($logs, 0, 100));
        }

        return view('admin.settings.logs', [
            'logs' => $logs,
        ]);
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:cache');
        \Artisan::call('view:cache');

        return back()->with('success', 'Cache berhasil dihapus');
    }

    /**
     * Run migrations
     */
    public function migrate()
    {
        try {
            \Artisan::call('migrate');
            return back()->with('success', 'Migrasi database berhasil');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal migrasi: ' . $e->getMessage()]);
        }
    }

    /**
     * Backup database
     */
    public function backup()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $backupPath = storage_path('backups/' . $filename);

            // Create backups directory if not exists
            if (!file_exists(storage_path('backups'))) {
                mkdir(storage_path('backups'), 0755, true);
            }

            // Use mysqldump command
            $command = sprintf(
                'mysqldump -h %s -u %s -p%s %s > %s',
                config('database.connections.mysql.host'),
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                escapeshellarg($backupPath)
            );

            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                return response()->download($backupPath)->deleteFileAfterSend(true);
            }

            throw new \Exception('Backup command failed');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal backup: ' . $e->getMessage()]);
        }
    }
}
