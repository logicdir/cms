<?php

namespace App\Modules\Appearance\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use App\Services\ThemeService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;

class ThemeController extends Controller
{
    protected $themeService;

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    public function index()
    {
        $themes = Theme::all();
        $activeTheme = $this->themeService->getActiveTheme();

        return Inertia::render('Admin/Appearance/Themes/Index', [
            'themes' => $themes,
            'activeTheme' => $activeTheme,
        ]);
    }

    public function activate(Request $request, $id)
    {
        $theme = Theme::findOrFail($id);

        // Deactivate others
        Theme::where('is_active', true)->update(['is_active' => false]);

        // Activate selected
        $theme->update(['is_active' => true]);

        // Clear cache
        Cache::forget('active_theme');

        return back()->with('success', "Theme {$theme->name} activated successfully!");
    }
}
