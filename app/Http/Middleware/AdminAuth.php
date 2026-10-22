<?php

namespace App\Http\Middleware;

use App\Support\Portal;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

// halaman admin hanya untuk yang sudah login sebagai admin; selain itu ke login satu pintu
class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('is_login')) {
            return redirect()->away(Portal::url())->with('alert', 'Please login first!');
        }
        return $next($request);
    }
}
