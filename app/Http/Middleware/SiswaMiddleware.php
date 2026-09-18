<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SiswaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah session 'token' ada
        if (!$request->session()->has('token')) {
            
            // Jika request berupa AJAX / Fetch API (JSON)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Silakan login dengan token terlebih dahulu.'
                ], 401);
            }

            // Jika akses langsung via URL browser, redirect ke halaman login
            return redirect()->route('siswa.login')->with('error', 'Silakan masukkan token terlebih dahulu.');
        }

        return $next($request);
    }
}
