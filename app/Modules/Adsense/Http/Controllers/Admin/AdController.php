<?php

namespace App\Modules\Adsense\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Adsense\Models\AdUnit;
use App\Modules\Adsense\Models\AdImpression;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdController extends Controller
{
    /**
     * Ad Management Overview.
     */
    public function index()
    {
        return Inertia::render('Admin/Ads/Index', [
            'adUnits' => AdUnit::withCount('impressions')->get(),
            'stats' => [
                'total_impressions' => AdImpression::count(),
                'total_clicks' => AdImpression::where('clicked', true)->count(),
            ]
        ]);
    }

    /**
     * Create/Edit Form.
     */
    public function create()
    {
        return Inertia::render('Admin/Ads/Form');
    }

    /**
     * Save Ad Unit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:adsense,custom',
            'code' => 'required|string',
            'position' => 'nullable|string',
            'auto_insert' => 'boolean',
            'display_rules' => 'nullable|array',
        ]);

        AdUnit::create($validated);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad Unit created successfully');
    }

    /**
     * Record Impression (Public API).
     */
    public function trackImpression(Request $request)
    {
        AdImpression::create([
            'ad_unit_id' => $request->ad_unit_id,
            'page_url' => $request->url,
            'user_agent' => $request->userAgent(),
            'ip_address' => hash('sha256', $request->ip()),
        ]);

        return response()->json(['status' => 'success']);
    }
}
