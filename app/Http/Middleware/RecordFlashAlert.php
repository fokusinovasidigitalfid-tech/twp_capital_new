<?php

namespace App\Http\Middleware;

use App\Support\ActivityLog;
use Closure;
use Illuminate\Http\Request;

/**
 * Pesan 'alert' yang di-flash request ini (login gagal, belum login, dll.) dicatat ke log aktivitas.
 * Harus di dalam grup 'web' (setelah StartSession): saat session disimpan, Laravel memindahkan
 * flash baru menjadi lama, jadi LogActivity di luar tidak bisa lagi membedakannya.
 */
class RecordFlashAlert
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->hasSession() && in_array('alert', (array) $request->session()->get('_flash.new', array()), true)) {
            ActivityLog::alert((string) $request->session()->get('alert'));
        }
        return $response;
    }
}
