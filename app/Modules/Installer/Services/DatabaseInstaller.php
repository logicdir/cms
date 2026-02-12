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
    public function runMigrations(): array
    {
        try {
            // Use buffered output to capture the migration results
            $output = new \Symfony\Component\Console\Output\BufferedOutput;
            
            // Run migrations (fresh to ensure a clean state)
            Artisan::call('migrate:fresh', ['--force' => true], $output);
            $migrateOutput = $output->fetch();
            
            // Optionally run seeders if needed
            // Artisan::call('db:seed', ['--force' => true], $output);
            // $seedOutput = $output->fetch();
            
            return [
                'success' => true,
                'output' => $migrateOutput,
            ];
        } catch (Exception $e) {
            Log::error('Installer Migration Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'output' => $e->getMessage(),
            ];
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
