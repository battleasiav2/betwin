<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PayoutPanelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // সেশন চেক: ইউজার পাসওয়ার্ড দিয়ে ভেরিফাই করেছে কিনা
        if (!session('payout_panel_ok')) {
            // ভেরিফাই না করা থাকলে পাসওয়ার্ড পেজে পাঠাও
            return redirect()->route('admin.payout.auth.form');
        }

        return $next($request);
    }
}