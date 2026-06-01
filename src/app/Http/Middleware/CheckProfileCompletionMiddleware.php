<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckProfileCompletionMiddleware
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
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail()) {
            if (!$request->routeIs('verification.notice', 'verification.verify', 'verification.send', 'logout')) {
                return redirect()->route('verification.notice');
            }
            return $next($request);
        }

        if (!$user->is_profile_completed) {

            if (!$request->routeIs('profile.edit', 'profile.update', 'logout')) {
                return redirect()->route('profile.edit');;
            }

            return $next($request);
        }
        return $next($request);
    }
}
