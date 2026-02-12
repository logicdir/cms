<?php

namespace App\Modules\Installer\Services;

class RequirementChecker
{
    /**
     * PHP required version.
     */
    protected string $minPhpVersion = '8.2.0';

    /**
     * Required PHP extensions.
     */
    protected array $extensions = [
        'openssl',
        'pdo',
        'mbstring',
        'tokenizer',
        'xml',
        'ctype',
        'json',
        'bcmath',
        'fileinfo',
        'intl',
        'zip',
    ];

    /**
     * Paths that must be writable.
     */
    protected array $writablePaths = [
        'storage' => 'storage',
        'bootstrap/cache' => 'bootstrap/cache',
        'public/uploads' => 'public/uploads',
        '.env' => '.env',
    ];

    /**
     * Check all requirements.
     */
    public function check(): array
    {
        return [
            'php' => $this->checkPhpVersion(),
            'extensions' => $this->checkExtensions(),
            'permissions' => $this->checkPermissions(),
            'settings' => $this->checkSettings(),
            'passed' => $this->allPassed(),
        ];
    }

    protected function checkPhpVersion(): array
    {
        return [
            'version' => PHP_VERSION,
            'required' => $this->minPhpVersion,
            'passed' => version_compare(PHP_VERSION, $this->minPhpVersion, '>='),
        ];
    }

    protected function checkExtensions(): array
    {
        $results = [];
        foreach ($this->extensions as $extension) {
            $results[] = [
                'name' => $extension,
                'passed' => extension_loaded($extension),
            ];
        }
        return $results;
    }

    protected function checkPermissions(): array
    {
        $results = [];
        foreach ($this->writablePaths as $path => $name) {
            $fullPath = base_path($path);
            
            // Special check for .env, it might not exist yet but we need to create it
            $passed = is_writable($fullPath) || (!file_exists($fullPath) && is_writable(base_path()));

            $results[] = [
                'path' => $name,
                'writable' => $passed,
                'passed' => $passed,
            ];
        }
        return $results;
    }

    protected function checkSettings(): array
    {
        return [
            [
                'name' => 'max_execution_time',
                'value' => ini_get('max_execution_time'),
                'required' => '>= 300',
                'passed' => (int) ini_get('max_execution_time') >= 300 || (int) ini_get('max_execution_time') === 0,
            ],
            [
                'name' => 'memory_limit',
                'value' => ini_get('memory_limit'),
                'required' => '>= 256M',
                'passed' => $this->parseSize(ini_get('memory_limit')) >= $this->parseSize('256M'),
            ],
            [
                'name' => 'upload_max_filesize',
                'value' => ini_get('upload_max_filesize'),
                'required' => '>= 64M',
                'passed' => $this->parseSize(ini_get('upload_max_filesize')) >= $this->parseSize('64M'),
            ],
        ];
    }

    protected function allPassed(): bool
    {
        $php = $this->checkPhpVersion();
        if (!$php['passed']) return false;

        foreach ($this->checkExtensions() as $ext) {
            if (!$ext['passed']) return false;
        }

        foreach ($this->checkPermissions() as $perm) {
            if (!$perm['passed']) return false;
        }

        foreach ($this->checkSettings() as $set) {
            if (!$set['passed']) return false;
        }

        return true;
    }

    protected function parseSize(string $size): int
    {
        $unit = preg_replace('/[^bkmgt]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgt', $unit[0])));
        }
        return round($size);
    }
}
