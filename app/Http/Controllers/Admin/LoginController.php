<?php

namespace App\Http\Controllers\Admin;

use App\Support\Portal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * Login admin dilakukan satu pintu di "/" (App\Http\Controllers\PortalLoginController).
 * Controller ini hanya mengisi session admin dan menangani /admin & /admin/logout.
 */
class LoginController extends Controller
{
    public function index()
    {
        if (Session::get('is_login')) {
            return redirect('/dash');
        }
        return redirect()->away(Portal::url());
    }

    // isi session sama persis dengan login webadmin lama
    public function createSession($adminId, $email)
    {
        $dataAdmin = DB::connection('ifcaadm')
            ->table('mgr.administrator')
            ->where('id', $adminId)
            ->first();
        if (!$dataAdmin) {
            return false;
        }

        Session::put('is_login', TRUE);
        Session::put('Tsuname', $dataAdmin->name);
        Session::put('Tsemail', $email);
        Session::put('Tsuser_id', $dataAdmin->id);
        return true;
    }

    public function logout()
    {
        // hapus data & ganti id session + token CSRF (session lama tidak bisa dipakai lagi)
        Session::invalidate();
        return redirect()->away(Portal::url());
    }
}
