<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login DAN role-nya sesuai dengan yang diizinkan
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            abort(403, 'ANDA TIDAK PUNYA AKSES KE HALAMAN INI.');
        }

        return $next($request);
    }
}