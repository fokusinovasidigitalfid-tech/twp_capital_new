<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\LoginController as AdminLogin;
use App\Http\Controllers\Tenant\LoginController as TenantLogin;
use App\Support\ActivityLog;
use App\Support\Portal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * Login satu pintu untuk Admin dan Tenant (halaman "/").
 *
 * Email + password dicek ke tabel all_login (password md5, sama seperti login lama):
 *  - tableforeign = 'administrator' dan password cocok   -> session admin  -> /admin/dash
 *  - tableforeign = 'tenant' (business aktif) dan cocok   -> session tenant -> /tenant/dash
 * Kalau satu email punya beberapa akses, pilihan "Login As" di halaman login menentukan
 * tujuannya (diisi lewat AJAX /login/businesses).
 */
class PortalLoginController extends Controller
{
    public function index()
    {
        if (Session::get('is_login')) {
            return redirect()->away(Portal::url('admin/dash'));
        }
        if (Session::get('is_Tenant_logged')) {
            return redirect()->away(Portal::url('tenant/dash'));
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $email = trim($request->email);
        $bsn = (string) $request->bsn;

        // ---------- Admin ----------
        $admin = DB::connection('ifcaadm')
            ->table('mgr.all_login')
            ->where('email', $email)
            ->where('tableforeign', 'administrator')
            ->first();
        $adminOk = $admin && md5($request->password) === $admin->password;

        // ---------- Tenant: business aktif yang password-nya cocok ----------
        $tenantIds = array();
        $tenants = app(TenantLogin::class)->activeTenants($email);
        if (count($tenants) > 0) {
            $tenantIds = DB::connection('mysql')
                ->table('mgr.all_login')
                ->where('tableforeign', 'tenant')
                ->whereIn('idforeign', array_map(function ($t) { return $t->id; }, $tenants))
                ->where('password', md5(trim($request->password)))
                ->pluck('idforeign')
                ->all();
        }

        if (!$adminOk && count($tenantIds) === 0) {
            // alasan sebenarnya hanya di log (pengguna tetap melihat pesan umum)
            ActivityLog::note('login gagal: ' . (!$admin && count($tenants) === 0
                ? 'email tidak terdaftar sebagai admin / tenant aktif'
                : 'password salah'), array('email' => $email));
            return redirect('/')
                ->withInput($request->only('email', 'bsn'))
                ->with('alert', 'Email or password is incorrect!');
        }

        Session::flush();
        Session::regenerate();

        // pilihan "Login As"
        if ($bsn === 'admin' && $adminOk) {
            return $this->enterAdmin($admin->idforeign, $email);
        }
        if ($bsn !== '' && in_array($bsn, array_map('strval', $tenantIds), true)) {
            return $this->enterTenant($bsn);
        }

        // tanpa pilihan: admin diprioritaskan, lalu tenant kalau hanya satu company
        if ($adminOk) {
            return $this->enterAdmin($admin->idforeign, $email);
        }
        if (count($tenantIds) === 1) {
            return $this->enterTenant($tenantIds[0]);
        }
        return redirect('/')
            ->withInput($request->only('email'))
            ->with('alert', 'Please choose a company in "Login As".');
    }

    /**
     * AJAX dari halaman login: akses apa saja yang dimiliki sebuah email
     * ({admin: bool, tenants: [{id, name}]}) untuk dropdown "Login As".
     */
    public function businesses(Request $request)
    {
        $email = trim((string) $request->query('email'));
        if ($email === '') {
            return response()->json(array('admin' => false, 'tenants' => array()));
        }

        $isAdmin = DB::connection('ifcaadm')
            ->table('mgr.all_login')
            ->where('email', $email)
            ->where('tableforeign', 'administrator')
            ->exists();

        $list = array();
        foreach (app(TenantLogin::class)->activeTenants($email) as $t) {
            $list[] = array('id' => $t->id, 'name' => $t->name);
        }
        return response()->json(array('admin' => $isAdmin, 'tenants' => $list));
    }

    public function logout()
    {
        // hapus data & ganti id session + token CSRF (session lama tidak bisa dipakai lagi)
        Session::invalidate();
        return redirect('/');
    }

    private function enterAdmin($adminId, $email)
    {
        if (!app(AdminLogin::class)->createSession($adminId, $email)) {
            ActivityLog::note('login gagal: data administrator tidak ditemukan', array('admin_id' => $adminId));
            return redirect('/')->with('alert', 'User is not valid!');
        }
        ActivityLog::note('login berhasil sebagai admin', array('admin_id' => $adminId, 'email' => $email));
        return redirect()->away(Portal::url('admin/dash'));
    }

    private function enterTenant($tenantId)
    {
        if (!app(TenantLogin::class)->createSession($tenantId)) {
            ActivityLog::note('login gagal: data tenant tidak ditemukan', array('tenant_id' => $tenantId));
            return redirect('/')->with('alert', 'User not found');
        }
        ActivityLog::note('login berhasil sebagai tenant', array('tenant_id' => $tenantId, 'company' => Session::get('TCompany')));
        return redirect()->away(Portal::url('tenant/dash'));
    }
}
