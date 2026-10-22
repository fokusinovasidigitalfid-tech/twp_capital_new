<?php

namespace App\Support;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Log aktivitas: setiap request (halaman, AJAX, API, login) dicatat ke
 *   storage/logs/activity/{YYYY-MM-DD}/{bagian}-{Controller}.log
 * dengan hasil OK / FAIL / ERROR, user, input (password disamarkan), query yang
 * mengubah data (insert/update/delete/exec) dan pesan error. Semua yang gagal juga
 * dikumpulkan di storage/logs/activity/{YYYY-MM-DD}/_errors.log.
 * Tetap aktif walaupun APP_DEBUG=false. Diatur di config/logging.php ('activity').
 *
 * Dipakai oleh App\Http\Middleware\LogActivity (per request) dan DB::listen di
 * AppServiceProvider. Controller boleh menambah catatan sendiri: ActivityLog::note('...').
 */
class ActivityLog
{
    private static $request = null;
    private static $start = null;
    private static $queries = array();
    private static $notes = array();
    private static $written = false;
    private static $alert = null;

    // kunci input yang nilainya tidak boleh tertulis di log
    const SECRET_KEYS = '/pass|pwd|token|secret|_key$|^key$|authorization/i';

    public static function enabled()
    {
        return (bool) config('logging.activity.enabled', true);
    }

    public static function begin(Request $request)
    {
        self::$request = $request;
        self::$start = defined('LARAVEL_START') ? LARAVEL_START : microtime(true);
        self::$queries = array();
        self::$notes = array();
        self::$written = false;
        self::$alert = null;
    }

    /** Catatan tambahan dari controller, mis. ActivityLog::note('login gagal', ['email' => $email]) */
    public static function note($message, array $context = array())
    {
        self::$notes[] = $context ? $message . ' ' . self::json(self::mask($context)) : $message;
    }

    /** Pesan alert yang di-flash ke halaman pada request ini (App\Http\Middleware\RecordFlashAlert) */
    public static function alert($message)
    {
        self::$alert = $message;
    }

    /** Dipanggil DB::listen: hanya query yang mengubah data (transaksi) yang disimpan */
    public static function query($sql, array $bindings, $timeMs, $connection)
    {
        if (self::$request === null || !preg_match('/^\s*(insert|update|delete|merge|exec|execute|create|alter|drop|truncate)\b/i', $sql)) {
            return;
        }
        if (count(self::$queries) >= (int) config('logging.activity.max_queries', 50)) {
            return;
        }
        // nilai password (md5) di query login/ganti password tidak ditulis
        if (preg_match('/pass/i', $sql)) {
            $bindings = array_map(function () { return '***'; }, $bindings);
        }
        $bindings = array_map(function ($b) {
            if ($b instanceof \DateTimeInterface) return $b->format('Y-m-d H:i:s');
            return is_string($b) ? self::cut($b, 200) : $b;
        }, $bindings);
        self::$queries[] = sprintf('[%s %.1fms] %s %s', $connection, $timeMs, self::cut(preg_replace('/\s+/', ' ', trim($sql)), 600), $bindings ? self::json($bindings) : '');
    }

    /**
     * Tulis satu entri untuk request ini. $echoed = output yang di-echo controller
     * (banyak controller lama mengirim JSON dengan echo, bukan return).
     */
    public static function finish(Request $request, ?Response $response = null, $echoed = '', ?Throwable $error = null, $exited = false)
    {
        if (self::$written || !self::enabled()) {
            return;
        }
        self::$written = true;

        try {
            $route = $request->route();
            $action = $route ? $route->getActionName() : '';
            [$section, $controller, $method] = self::actionParts($request, $action);

            $status = $response ? $response->getStatusCode() : ($exited ? 200 : 500);
            $error = $error ?: ($response && property_exists($response, 'exception') ? $response->exception : null);
            [$result, $message] = self::result($request, $response, (string) $echoed, $status, $error, $exited);

            $ms = (microtime(true) - self::$start) * 1000;
            $now = date('Y-m-d H:i:s');
            $lines = array(sprintf('[%s] %-5s %d %s /%s  %s@%s  user=%s ip=%s %dms',
                $now, $result, $status, $request->method(), ltrim($request->path(), '/'),
                $controller, $method, self::user($request), $request->ip(), $ms));
            if ($message !== '') $lines[] = '    pesan : ' . $message;
            $input = self::input($request);
            if ($input !== '') $lines[] = '    input : ' . $input;
            if ($response && $response->isRedirection()) $lines[] = '    ke    : ' . $response->headers->get('Location');
            foreach (self::$queries as $q) $lines[] = '    sql   : ' . $q;
            foreach (self::$notes as $n) $lines[] = '    catat : ' . $n;
            if ($error && $result === 'ERROR') {
                $lines[] = '    error : ' . get_class($error) . ' di ' . self::shortPath($error->getFile()) . ':' . $error->getLine();
                // hanya baris dari kode aplikasi (app/, routes/, resources/views); trace lengkap ada di laravel-{tanggal}.log
                $own = array_filter(explode("\n", $error->getTraceAsString()), function ($t) {
                    return preg_match('#[\\\\/](app|routes|resources[\\\\/]views|storage[\\\\/]framework[\\\\/]views)[\\\\/]#', $t)
                        && !str_contains($t, 'vendor');
                });
                foreach (array_slice($own, 0, 6) as $t) $lines[] = '            ' . self::shortPath($t);
            }
            $entry = implode(PHP_EOL, $lines) . PHP_EOL;

            $dir = self::dir() . DIRECTORY_SEPARATOR . date('Y-m-d');
            if (!is_dir($dir)) @mkdir($dir, 0775, true);
            $file = $dir . DIRECTORY_SEPARATOR . preg_replace('/[^A-Za-z0-9_.-]/', '_', $section . '-' . $controller) . '.log';
            @file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
            if ($result !== 'OK') {
                @file_put_contents($dir . DIRECTORY_SEPARATOR . '_errors.log', $entry, FILE_APPEND | LOCK_EX);
            }
            self::prune();
        } catch (Throwable $e) {
            // log aktivitas tidak boleh membuat aplikasi gagal
            error_log('ActivityLog gagal menulis: ' . $e->getMessage());
        }
    }

    public static function isWritten()
    {
        return self::$written;
    }

    public static function currentRequest()
    {
        return self::$request;
    }

    // ---------------------------------------------------------------------

    private static function dir()
    {
        return config('logging.activity.path', storage_path('logs/activity'));
    }

    /** [bagian, Controller, method] dari nama action route */
    private static function actionParts(Request $request, $action)
    {
        $path = trim($request->path(), '/');
        $first = explode('/', $path)[0];
        $section = in_array($first, array('admin', 'tenant', 'api')) ? $first : ($first === 'webadmin' && str_contains($path, '/api/') ? 'api' : 'portal');
        if ($action === '') {
            return array($section, 'no-route', '-');
        }
        if (str_contains($action, '@')) {
            [$class, $method] = explode('@', $action, 2);
            return array($section, class_basename($class), $method);
        }
        if ($action === 'Closure') return array($section, 'route', 'closure');
        return array($section, class_basename($action), '__invoke');
    }

    /** OK / FAIL / ERROR + pesan singkat */
    private static function result(Request $request, ?Response $response, $echoed, $status, ?Throwable $error, $exited)
    {
        // 4xx dari Laravel (404 tidak ada halaman, 405, 419 token CSRF habis, 429 terlalu banyak percobaan)
        if ($error instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface && $error->getStatusCode() < 500) {
            $text = array(403 => 'akses ditolak', 404 => 'halaman tidak ditemukan', 405 => 'method tidak diizinkan',
                419 => 'sesi/token CSRF kedaluwarsa', 429 => 'terlalu banyak percobaan');
            return array('FAIL', 'HTTP ' . $error->getStatusCode() . ' ' . ($text[$error->getStatusCode()] ?? '') . ($error->getMessage() !== '' ? ': ' . self::cut($error->getMessage(), 300) : ''));
        }
        if ($error || $status >= 500) {
            return array('ERROR', $error ? self::cut($error->getMessage(), 1000) : 'HTTP ' . $status);
        }

        $body = $echoed;
        if ($response && !$response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse
            && !$response instanceof \Symfony\Component\HttpFoundation\StreamedResponse) {
            $type = (string) $response->headers->get('Content-Type');
            if ($body === '' && (str_contains($type, 'json') || $type === '' || str_contains($type, 'text/plain'))) {
                $body = (string) $response->getContent();
            }
        }
        [$bodyResult, $bodyMsg] = self::parseBody($body);

        // pesan "alert" yang di-flash ke halaman (mis. login gagal, belum login), lihat RecordFlashAlert
        $alert = (string) self::$alert;

        if ($status >= 400) {
            return array('FAIL', $bodyMsg !== '' ? $bodyMsg : ($alert !== '' ? $alert : 'HTTP ' . $status));
        }
        if ($bodyResult === 'FAIL') {
            return array('FAIL', $bodyMsg);
        }
        if ($alert !== '') {
            return array('FAIL', $alert);
        }
        $msg = $bodyMsg;
        if ($exited) $msg = trim('(controller memanggil exit) ' . $msg);
        return array('OK', $msg);
    }

    /** JSON {"status":"OK"/"Failed","pesan":..} atau {"Error":bool,"Pesan":..} */
    private static function parseBody($body)
    {
        $body = trim($body);
        if ($body === '') {
            return array('', '');
        }
        // teks pendek tanpa HTML, mis. API Wsbangun: "Insert failed: ..." / "Insert success"
        if ($body[0] !== '{' && $body[0] !== '[') {
            if (strlen($body) <= 1000 && strip_tags($body) === $body) {
                $failed = preg_match('/\b(fail(ed)?|gagal|error|exception)\b/i', $body);
                return array($failed ? 'FAIL' : '', self::cut($body, 500));
            }
            return array('', '');
        }
        $json = json_decode(substr($body, 0, 20000), true);
        if (!is_array($json) || array_is_list($json)) {
            return array('', '');
        }
        $lower = array_change_key_case($json, CASE_LOWER);
        $msg = '';
        foreach (array('pesan', 'message', 'msg') as $k) {
            if (isset($lower[$k]) && is_scalar($lower[$k]) && $lower[$k] !== '') { $msg = self::cut(strip_tags((string) $lower[$k]), 500); break; }
        }
        if (array_key_exists('error', $lower) && is_bool($lower['error'])) {
            return array($lower['error'] ? 'FAIL' : 'OK', $msg);
        }
        if (isset($lower['status']) && is_scalar($lower['status'])) {
            $s = strtolower(trim((string) $lower['status']));
            if (in_array($s, array('ok', 'success', 'sukses', 'true', '1', '200'), true)) return array('OK', $msg);
            if (in_array($s, array('failed', 'fail', 'gagal', 'error', 'false', '0'), true)) return array('FAIL', $msg);
        }
        return array('', $msg);
    }

    private static function user(Request $request)
    {
        if (!$request->hasSession()) return '-';
        $s = $request->session();
        if ($s->get('is_login')) return 'admin:' . $s->get('Tsuser_id') . '(' . $s->get('Tsemail') . ')';
        if ($s->get('is_Tenant_logged')) return 'tenant:' . $s->get('Tuser_id') . '(' . $s->get('TCompany') . ')';
        $email = $request->input('email');
        return is_string($email) && $email !== '' ? 'guest(' . self::cut($email, 100) . ')' : 'guest';
    }

    private static function input(Request $request)
    {
        $data = self::mask($request->except(array('_token', '_method')));
        foreach ($request->allFiles() as $key => $file) {
            $files = is_array($file) ? $file : array($file);
            $data[$key] = array_map(function ($f) {
                return $f ? $f->getClientOriginalName() . ' (' . round($f->getSize() / 1024) . ' KB)' : null;
            }, $files);
        }
        return $data ? self::cut(self::json($data), 2000) : '';
    }

    private static function mask($data)
    {
        foreach ($data as $k => $v) {
            if (is_string($k) && preg_match(self::SECRET_KEYS, $k)) {
                $data[$k] = '***';
            } elseif (is_array($v)) {
                $data[$k] = self::mask($v);
            } elseif (is_string($v)) {
                $data[$k] = self::cut($v, 300);
            }
        }
        return $data;
    }

    /** hapus folder tanggal yang lebih tua dari logging.activity.days (sekali sehari) */
    private static function prune()
    {
        $days = (int) config('logging.activity.days', 90);
        $dir = self::dir();
        $marker = $dir . DIRECTORY_SEPARATOR . '.last_prune';
        $today = date('Y-m-d');
        if ($days <= 0 || (is_file($marker) && trim((string) @file_get_contents($marker)) === $today)) {
            return;
        }
        @file_put_contents($marker, $today);
        $limit = date('Y-m-d', strtotime("-{$days} days"));
        foreach (glob($dir . DIRECTORY_SEPARATOR . '????-??-??', GLOB_ONLYDIR) ?: array() as $d) {
            if (basename($d) < $limit) {
                array_map('unlink', glob($d . DIRECTORY_SEPARATOR . '*') ?: array());
                @rmdir($d);
            }
        }
    }

    private static function json($v)
    {
        return json_encode($v, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
    }

    private static function cut($s, $len)
    {
        $s = (string) $s;
        return mb_strlen($s) > $len ? mb_substr($s, 0, $len) . '…' : $s;
    }

    private static function shortPath($s)
    {
        return str_replace(array(base_path() . DIRECTORY_SEPARATOR, str_replace('\\', '/', base_path()) . '/'), '', (string) $s);
    }
}
