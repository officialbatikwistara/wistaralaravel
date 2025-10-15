<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai admin.');
        }

        return $next($request);
    }
}
