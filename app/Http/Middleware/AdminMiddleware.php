<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('id_admin')) {
            // permintaan AJAX/JSON: balas 401 (bukan redirect HTML)
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Sesi admin berakhir. Silakan login ulang.',
                ], 401);
            }
            return redirect('/admin');
        }

        return $next($request);
    }
}