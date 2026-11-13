<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;



class CheckOwner
{
   public function handle(Request $request, Closure $next): Response
{
    if (Auth::check() && Auth::user()->role === 'owner') {
        return $next($request);
    }

    // Jika bukan owner, arahkan keluar (jangan arahkan ke halaman login jika sudah login sebagai tenant)
    if (Auth::check()) {
        return redirect()->route('tenant.dashboard')->with('error', 'Akses ditolak.');
    }

    return redirect()->route('login');
}
}
