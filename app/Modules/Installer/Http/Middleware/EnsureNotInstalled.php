<?php

namespace App\Modules\Installer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotInstalled
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the installation file exists, the system is installed.
        // We use a file lock/marker for shared hosting compatibility.
        if (file_exists(storage_path('installed'))) {
            if ($request->is('install*')) {
                return redirect('/');
            }
        } else {
            if (!$request->is('install*')) {
                return redirect('/install');
            }
        }

        return $next($request);
    }
}
