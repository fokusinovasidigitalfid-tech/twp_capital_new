<?php

namespace App\Http\Middleware;

use App\Support\Portal;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

/**
 * Menyiapkan request untuk bagian admin atau tenant supaya kode lama dari
 * webadmin / webtenant berjalan tanpa diubah:
 *  - url('/dash'), redirect('/dash'), asset('public/..') -> {base}/admin/... atau {base}/tenant/...
 *  - view('dash.index') dicari dulu di resources/views/{bagian}
 *  - koneksi DB default sama seperti aplikasi lamanya
 */
class Section
{
    public function handle(Request $request, Closure $next, $section)
    {
        URL::forceRootUrl(Portal::base() . '/' . $section);
        View::getFinder()->prependLocation(resource_path('views/' . $section));
        config(['database.default' => Portal::SECTIONS[$section]['db']]);

        return $next($request);
    }
}
