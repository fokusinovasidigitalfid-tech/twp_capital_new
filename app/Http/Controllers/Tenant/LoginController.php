<?php

namespace App\Http\Controllers\Tenant;

use App\Support\Portal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * Login tenant dilakukan satu pintu di "/" (App\Http\Controllers\PortalLoginController).
 * Controller ini hanya mencari business aktif, mengisi session tenant dan menangani
 * /tenant & /tenant/logout.
 */
class LoginController extends Controller
{
    public function index()
    {
        if (Session::get('is_Tenant_logged')) {
            return redirect('/dash');
        }
        return redirect()->away(Portal::url());
    }

    function ip_address()
    {
        return getenv('HTTP_X_FORWARDED_FOR') ?: getenv('REMOTE_ADDR');
    }

    /**
     * Baris tenant milik email ini yang tenancy-nya masih aktif
     * (logika sama dengan dropdown "Company Name" di login webtenant lama).
     */
    public function activeTenants($email)
    {
        $businessNos = DB::connection('mysql')->table('mgr.tenant')
            ->where('email', $email)
            ->pluck('business_no')
            ->filter()
            ->unique()
            ->values();
        if ($businessNos->isEmpty()) {
            return array();
        }

        $activeBusinessNos = DB::connection('mysql')->table('mgr.pm_tenancy')
            ->whereIn('business_no', $businessNos)
            ->where('status', 'A')
            // GETDATE(): tanggal dari PHP ditolak SQL Server (setting bahasa/DATEFORMAT server)
            ->whereRaw('expiry_date >= GETDATE()')
            ->pluck('business_no')
            ->unique()
            ->values();
        if ($activeBusinessNos->isEmpty()) {
            return array();
        }

        return DB::connection('mysql')->table('mgr.tenant')
            ->where('email', $email)
            ->whereIn('business_no', $activeBusinessNos)
            ->get()
            ->all();
    }

    // isi session sama persis dengan login webtenant lama
    public function createSession($tenantId)
    {
        $dataTenant = DB::connection('mysql')->table('mgr.tenant')->where('id', $tenantId)->first();
        if (!$dataTenant) {
            return false;
        }
        $dataTenancy = DB::connection('mysql')->table('mgr.pm_tenancy')
            ->where('tenant_no', $dataTenant->tenant_no_df)
            ->first();

        Session::put('is_Tenant_logged', true);
        Session::put('Tuname', $dataTenant->contact_name);
        Session::put('TCompany', $dataTenant->name);
        Session::put('Tuser_id', $dataTenant->id);
        Session::put('business_no', $dataTenant->business_no);
        Session::put('tenant_df', $dataTenant->tenant_no_df);
        Session::put('Tenemail', $dataTenant->email);
        Session::put('Tflag', $dataTenant->flag);
        Session::put('Ttenancy', $dataTenancy ? $dataTenancy->id : null);

        DB::connection('mysql')->table('mgr.log_login')->insert(array(
            'idforeign' => $dataTenant->id,
            // format ISO 8601: tidak bergantung setting bahasa/DATEFORMAT SQL Server
            'logintime' => date('Y-m-d\TH:i:s'),
            'ipaddress' => $this->ip_address(),
        ));
        return true;
    }

    public function logout()
    {
        // hapus data & ganti id session + token CSRF (session lama tidak bisa dipakai lagi)
        Session::invalidate();
        return redirect()->away(Portal::url());
    }
}
