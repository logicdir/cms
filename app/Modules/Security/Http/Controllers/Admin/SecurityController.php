<?php

namespace App\Modules\Security\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Security\Models\AuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SecurityController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Admin/Security/Dashboard', [
            'recentLogs' => AuditLog::orderBy('created_at', 'desc')->take(20)->get(),
            'stats' => [
                'failed_logins' => AuditLog::where('event', 'failed_login')->count(),
                'sensitive_changes' => AuditLog::whereIn('event', ['updated', 'deleted'])->count(),
            ]
        ]);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'login_throttling' => 'boolean',
            'password_history_limit' => 'integer|min:0|max:10',
        ]);

        // Save to global settings
        return back()->with('success', 'Security settings updated.');
    }
}
