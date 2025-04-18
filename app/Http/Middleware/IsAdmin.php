<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Cek jika pengguna yang login adalah admin
        if (Auth::check() && $request->user()->role == 'admin') {
            return $next($request); // Akses diteruskan jika admin
        }

        // Redirect jika bukan admin
        return redirect('/'); 
    }
}
