<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLevelAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$levels  Allowed level_akses values (e.g. 'admin', 'bidan', 'pimpinan')
     */
    public function handle(Request $request, Closure $next, string ...$levels): Response
    {
        if (! $request->user() || ! in_array($request->user()->level_akses, $levels)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
