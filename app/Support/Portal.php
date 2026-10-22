<?php

namespace App\Support;

/**
 * Satu aplikasi, dua bagian:
 *   /admin/...   -> App\Http\Controllers\Admin,  resources/views/admin,  aset statis di folder admin/
 *   /tenant/...  -> App\Http\Controllers\Tenant, resources/views/tenant, aset statis di folder tenant/
 * Login satu pintu di "/" (PortalLoginController).
 */
class Portal
{
    // koneksi default per bagian (sama seperti DB_CONNECTION di webadmin / webtenant lama)
    const SECTIONS = array(
        'admin' => array('db' => 'ifcapb'),
        'tenant' => array('db' => 'mysql'),
    );

    /** URL dasar aplikasi, contoh http://localhost/twp_capital_new */
    public static function base()
    {
        return rtrim(config('app.base_url'), '/');
    }

    /** Halaman login satu pintu */
    public static function url($path = '')
    {
        return self::base() . '/' . ltrim($path, '/');
    }
}
