<?php

namespace App\Modules\Installer\Services;

class EnvironmentWriter
{
    /**
     * Save the database configuration to .env file.
     */
    public function saveDatabaseConfig(array $data): bool
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) {
            if (file_exists(base_path('.env.example'))) {
                copy(base_path('.env.example'), $envPath);
            } else {
                touch($envPath);
            }
        }

        $content = file_get_contents($envPath);

        $replacements = [
            'DB_HOST' => $data['host'],
            'DB_PORT' => $data['port'],
            'DB_DATABASE' => $data['database'],
            'DB_USERNAME' => $data['username'],
            'DB_PASSWORD' => $data['password'],
        ];

        foreach ($replacements as $key => $value) {
            $value = $this->quoteValue($value);
            if (preg_match("/^{$key}=/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                $content .= "\n{$key}={$value}";
            }
        }

        return file_put_contents($envPath, $content) !== false;
    }

    /**
     * Save site configuration to .env file.
     */
    public function saveSiteConfig(array $data): bool
    {
        $envPath = base_path('.env');
        $content = file_get_contents($envPath);

        $replacements = [
            'APP_NAME' => $data['site_name'],
            'APP_URL' => $data['site_url'],
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
        ];

        foreach ($replacements as $key => $value) {
            $value = $this->quoteValue($value);
            if (preg_match("/^{$key}=/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                $content .= "\n{$key}={$value}";
            }
        }

        // Generate APP_KEY if it doesn't exist or is empty
        if (!preg_match("/^APP_KEY=base64:.+/m", $content)) {
            // Since we can't use shell_exec, we'll generate a key manually or rely on Laravel's later
            // But for the installer to work, we might need it now.
            $key = 'base64:' . base64_encode(random_bytes(32));
            if (preg_match("/^APP_KEY=/m", $content)) {
                $content = preg_replace("/^APP_KEY=.*/m", "APP_KEY={$key}", $content);
            } else {
                $content .= "\nAPP_KEY={$key}";
            }
        }

        return file_put_contents($envPath, $content) !== false;
    }

    protected function quoteValue(string $value): string
    {
        if (str_contains($value, ' ') || str_contains($value, '#')) {
            return '"' . str_replace('"', '\"', $value) . '"';
        }
        return $value;
    }
}
