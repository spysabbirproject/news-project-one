<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Reporter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->user()->role == 'Super Admin' || Auth::guard('admin')->user()->role == 'Admin' || Auth::guard('admin')->user()->role == 'Reporter') {
            return $next($request);
        }else{
            return redirect()->route('admin.dashboard')->with('error', "You are not a reporter. You can't access this link.");
        }
    }
}