<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsDokter
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
        // Cek jika pengguna yang login adalah dokter
        if (Auth::check() && $request->user()->role == 'dokter') {
            return $next($request); // Akses diteruskan jika dokter
        }

        // Redirect jika bukan dokter
        return redirect('/'); 
    }
}
