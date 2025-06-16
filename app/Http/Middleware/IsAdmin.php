<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (Auth::check()) {
            // Periksa apakah peran pengguna sesuai dengan peran yang diizinkan
            if (in_array(Auth::user()->role_id, $roles)) {
                return $next($request);
            }
            // if (Auth::user()->role_id == $role) {
            //     return $next($request);
            // }
        }

        // Jika tidak sesuai, alihkan ke halaman lain atau kembalikan respon yang sesuai
        abort(403, 'Unauthorized action.');
    }
}
