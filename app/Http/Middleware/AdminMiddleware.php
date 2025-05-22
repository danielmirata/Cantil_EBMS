<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->account_type !== 'admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
} 