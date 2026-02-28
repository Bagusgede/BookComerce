<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silahkan Login Terlebih dahulu');
        }

        if (!auth()->user()->isUser()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Customer');
        }
        return $next($request);
    }
}
