<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\Cache;

class ThemeService
{
    /**
     * Get the active theme.
     *
     * @return Theme
     */
    public function getActiveTheme()
    {
        return Cache::rememberForever('active_theme', function () {
            return Theme::where('is_active', true)->first() 
                ?? Theme::where('slug', config('themes.default'))->first();
        });
    }

    /**
     * Resolve theme view path.
     *
     * @param string $view
     * @return string
     */
    public function view(string $view)
    {
        $activeTheme = $this->getActiveTheme();
        $slug = $activeTheme ? $activeTheme->slug : config('themes.default');
        
        $themeView = "themes::{$slug}.views.{$view}";

        if (view()->exists($themeView)) {
            return $themeView;
        }

        // Fallback to default if theme view doesn't exist
        $defaultTheme = config('themes.default');
        return "themes::{$defaultTheme}.views.{$view}";
    }
}
