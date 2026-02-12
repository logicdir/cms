<?php

namespace App\Modules\Installer\Services;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class DatabaseInstaller
{
    /**
     * Test the database connection.
     */
    public function testConnection(array $config): array
    {
        try {
            $this->setupRuntimeConfig($config);
            DB::connection('installer_test')->getPdo();
            return ['passed' => true];
        } catch (Exception $e) {
            return [
                'passed' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Run migrations and seeders.
     */
    public function runMigrations(): bool
    {
        try {
            // Ensure we are using the correct connection from .env
            Artisan::call('migrate', ['--force' => true]);
            
            // In a real scenario, we'd check if seeders exist
            // Artisan::call('db:seed', ['--force' => true]);
            
            return true;
        } catch (Exception $e) {
            Log::error('Installer Migration Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Setup a temporary connection for testing.
     */
    protected function setupRuntimeConfig(array $config): void
    {
        Config::set('database.connections.installer_test', [
            'driver' => 'mysql',
            'host' => $config['host'],
            'port' => $config['port'],
            'database' => $config['database'],
            'username' => $config['username'],
            'password' => $config['password'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => $config['prefix'] ?? '',
            'strict' => true,
            'engine' => null,
        ]);
    }
}
