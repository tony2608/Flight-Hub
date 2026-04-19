<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- INI DIA OBATNYA BRO!

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Pastikan dia sudah login DAN is_admin nya 1
        if (Auth::check() && Auth::user()->is_admin == 1) {
            return $next($request); // Silakan masuk
        }

        // Kalau bukan admin, tendang balik ke beranda
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman Admin!');
    }
}