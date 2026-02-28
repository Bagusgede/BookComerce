<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //cek apakah user adalah admin
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silahkan Login terlebih dahulu ');
        }

        // cek apakah user adalah admin 
        if (!auth()->user()->isAdmin()) {
            abort(403, "Akses ditolak. hanya Admin yang dapat login");
        }
        return $next($request);
    }
}
