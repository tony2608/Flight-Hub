<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * $roles di sini adalah daftar jabatan yang diizinkan masuk
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Kalau belum login, tendang ke halaman login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Kalau jabatannya ada di dalam daftar yang diizinkan, silakan masuk
        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        // Kalau jabatannya nggak sesuai, kasih layar error 403 (Akses Ditolak)
        return abort(403, 'AKSES DITOLAK! Ruangan ini hanya untuk ' . implode(', ', $roles) . '.');
    }
}