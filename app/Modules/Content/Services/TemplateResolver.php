<?php

namespace App\Modules\Content\Services;

use App\Modules\Content\Models\Content;
use Illuminate\Support\Facades\File;

class TemplateResolver
{
    protected string $themePath;

    public function __construct()
    {
        $this->themePath = resource_path('views/themes/' . config('app.theme', 'default'));
    }

    public function resolve(Content $content): string
    {
        $type = $content->type;
        $slug = $content->slug;
        $template = $content->template;

        // Custom template selected
        if ($template && $this->exists("{$template}.blade.php")) {
            return $template;
        }

        // Type-specific with slug
        if ($this->exists("single-{$type}-{$slug}.blade.php")) {
            return "single-{$type}-{$slug}";
        }

        // Type-specific
        if ($this->exists("single-{$type}.blade.php")) {
            return "single-{$type}";
        }

        // Generic single
        return 'single';
    }

    public function resolveFrontend(Content $content): string
    {
        $type = ucfirst($content->type);
        $template = $content->template;

        // Custom template selected
        if ($template) {
            return "Frontend/{$type}/" . ucfirst($template);
        }

        // Default
        return "Frontend/{$type}/Single";
    }

    public function available(string $type): array
    {
        $templates = [];
        $path = "{$this->themePath}/single-{$type}";

        if (!File::exists($path)) {
            return $templates;
        }

        $files = File::files($path);

        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $name = $file->getFilenameWithoutExtension();
                $templates[$name] = ucwords(str_replace(['-', '_'], ' ', $name));
            }
        }

        return $templates;
    }

    protected function exists(string $view): bool
    {
        return File::exists("{$this->themePath}/{$view}");
    }
}
