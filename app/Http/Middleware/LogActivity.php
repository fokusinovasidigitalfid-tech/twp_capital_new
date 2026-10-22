<?php

namespace App\Http\Middleware;

use App\Support\ActivityLog;
use Closure;
use ErrorException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Mencatat setiap request ke log aktivitas (App\Support\ActivityLog), termasuk:
 *  - output yang di-echo controller (banyak controller lama tidak me-return response)
 *  - controller yang berhenti dengan exit()/die() atau fatal error (lewat shutdown handler)
 */
class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (!ActivityLog::enabled()) {
            return $next($request);
        }

        ActivityLog::begin($request);
        $level = ob_get_level();
        ob_start();

        register_shutdown_function(function () use ($request, $level) {
            if (ActivityLog::isWritten() || ActivityLog::currentRequest() !== $request) {
                return;
            }
            $echoed = ob_get_level() > $level ? (string) ob_get_contents() : '';
            $fatal = error_get_last();
            $error = null;
            if ($fatal && in_array($fatal['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR))) {
                $error = new ErrorException($fatal['message'], 0, $fatal['type'], $fatal['file'], $fatal['line']);
            }
            ActivityLog::finish($request, null, $echoed, $error, true);
        });

        try {
            $response = $next($request);
        } finally {
            $echoed = ob_get_level() > $level ? (string) ob_get_clean() : '';
        }

        // output echo dikembalikan ke respon (sama seperti sebelumnya: tampil sebelum isi respon)
        if ($echoed !== '') {
            if ($response instanceof StreamedResponse || $response instanceof BinaryFileResponse || $response->isRedirection()) {
                echo $echoed;
            } else {
                $response->setContent($echoed . $response->getContent());
            }
        }

        ActivityLog::finish($request, $response, $echoed);
        return $response;
    }
}
