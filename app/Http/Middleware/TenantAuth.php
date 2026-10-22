<?php

namespace App\Http\Middleware;

use App\Support\Portal;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

// halaman tenant hanya untuk yang sudah login sebagai tenant; selain itu ke login satu pintu
class TenantAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('is_Tenant_logged')) {
            return redirect()->away(Portal::url())->with('alert', 'Please login first!');
        }
        return $next($request);
    }
}
