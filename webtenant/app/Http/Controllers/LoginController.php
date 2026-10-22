<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Validator;

class LoginController extends Controller
{
    public function index()
    {
        $sql = "SELECT * FROM image_login where webname='tenant' ORDER BY seq_no ASC";
        $dtimg = DB::select($sql);
        $content = array('dataimages' => $dtimg);
        return view('login/index', $content);
    }

    function ip_address()
    {
        return getenv('HTTP_X_FORWARDED_FOR') ?: getenv('REMOTE_ADDR');
    }

    public function based(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
        ]);

        $email = $request->email;

        $kriteria = array('email' => $email);
        
        $query = DB::table('tenant')
            ->where($kriteria)
            ->get();
        //var_dump($query);

        // portal tunggal: email administrator juga login dari halaman ini
        $isAdmin = DB::table('all_login')
            ->where('email', $email)
            ->where('tableforeign', 'administrator')
            ->exists();

        if(count($query)>0 || $isAdmin)
        {
            if (count($query) > 0) {
                $cbBus = $this->get_combo(null, $kriteria);
            } else {
                $cbBus = '<option value=0>-- Choose --</option>' . "\n";
            }
            if ($isAdmin) {
                $selected = count($query) > 0 ? '' : ' selected="1"';
                $cbBus .= '<option value="admin"' . $selected . '>Administrator</option>' . "\n";
            }
            $sql = "SELECT * FROM image_login where webname='tenant' ORDER BY seq_no ASC";
            $dtimg = DB::select($sql);
            $content = array(
                'combo' => $cbBus,
                'email' => $email,
                'dataimages' => $dtimg
            );

            return view('login/step', $content);
        } else {
            return redirect('/')->with('alert', 'User not found');
        }
    }

    // Login administrator dari portal: cek password lalu oper ke webadmin lewat token SSO
    private function loginAdmin(Request $request)
    {
        $email = $request->email;
        $datauser = DB::table('all_login')
            ->where('email', $email)
            ->where('tableforeign', 'administrator')
            ->first();

        if (!$datauser) {
            return redirect('/')->with('alert', 'User is not valid!');
        }
        if (md5($request->password) !== $datauser->password) {
            return redirect('/')->with('alert', 'Wrong Password!');
        }

        $token = $this->makeSsoToken(array(
            'role'  => 'administrator',
            'id'    => $datauser->idforeign,
            'email' => $email,
        ));
        return redirect()->away(rtrim(config('app.admin_url'), '/') . '/sso?token=' . urlencode($token));
    }

    private function makeSsoToken(array $data)
    {
        $data['exp'] = time() + 60;
        $data['nonce'] = bin2hex(random_bytes(16));
        $payload = rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
        $sig = hash_hmac('sha256', $payload, (string) config('app.sso_secret'));
        return $payload . '.' . $sig;
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'bsn' => 'required',
            'password' => 'required',
        ]);

        if ($request->bsn === 'admin') {
            return $this->loginAdmin($request);
        }

        $bsn = $request->bsn;
        $password = md5(trim($request->password));

        $datas = DB::table('all_login')
            ->where('tableforeign', 'tenant')
            ->where('idforeign', $bsn)
            ->where('password', $password)
            ->get();
        //var_dump($datas);

        if(count($datas)>0)
        {
            $default = 'Capital123';
            if($password==$default){
                $this->verify($bsn, null);
                return;
            }

            if($datas[0]->tableforeign=='tenant')
            {
                $kriteriaTenant= array('id' => $datas[0]->idforeign);
                $dataTenant = DB::table('tenant')
                    ->where($kriteriaTenant)
                    ->get();

                // Tambahkan query ke pm_tenancy
                $dataTenancy = DB::table('pm_tenancy')
                    ->where('tenant_no', $dataTenant[0]->tenant_no_df)
                    ->first();
                // TAMBAHKAN INI
                Session::put('is_Tenant_logged', true);
                Session::put('Tuname', $dataTenant[0]->contact_name);
                Session::put('TCompany', $dataTenant[0]->name);
                Session::put('Tuser_id', $dataTenant[0]->id);
                Session::put('business_no', $dataTenant[0]->business_no);
                Session::put('tenant_df', $dataTenant[0]->tenant_no_df);
                Session::put('Tenemail', $dataTenant[0]->email);
                Session::put('Tflag', $dataTenant[0]->flag);
                Session::put('Ttenancy', $dataTenancy ? $dataTenancy->id : null);

                $ipA = $this->ip_address();
                $dtL = array(
                    'idforeign' => $datas[0]->idforeign,
                    'logintime' => date('Y-m-d H:i:s'),
                    'ipaddress' => $ipA);
                DB::table('log_login')->insert($dtL);
                return redirect('/dash');
            } else {
                return redirect('/');
            }
        } else {
            return redirect ('/')->with('alert', 'User not found');
        }
    }

    public function verify($mail=null, $err=null)
    {
        $pwd = md5("Capital123");
        // $criteria = array('email'=>$mail, 'password'=> $pwd);
        if ($mail != null ) {
            $criteria = array(
                'tableforeign' => 'tenant',
                'idforeign'    => $mail,
                'password'     => $pwd
            );
            $ret = DB::table('tenant')
                ->where($criteria)
                ->get();

            if ($ret)
            {
                $email = $ret[0]->email;
                $data = array(
                    'email' => $email,
                    'error' => $err,
                    'tid'   => $mail
                );
                return view('login/change', $data);
            } else {
                redirect('/');
            }
        }
    }

    function get_combo($selected_id = "", $crit=null)
    {
        if(!empty($crit)) {
            $query = DB::table('tenant')->where($crit)->get();
        }else{
            $query = DB::table('tenant')->get();
        }

        $email = $crit['email'];
        
        $wherein="";//ambil businessno by email
        foreach ($query as $result) {
            
            $wherein .= "'".$result->business_no."',";
        }

        $wherein=substr($wherein,0,-1);
        
        $sql = "SELECT * FROM pm_tenancy WHERE business_no in (".$wherein.") and status = 'A' and expiry_date >= now()";
        $query = DB::select($sql);

        if(!empty($query)){
            $wherein2="";
            foreach ($query as $result) {
                $wherein2 .= "'".$result->business_no."',";
            }
            $wherein2=substr($wherein2,0,-1);
        }else{
            $wherein2="''";
        }
        
        //select ulang berdasarkan business id
        // $query2 = "SELECT * FROM tenant WHERE email = $email and business_no in (".$wherein2.")";
        $query2 = "SELECT * FROM tenant WHERE email = '$email' AND business_no IN ($wherein2)";
        $results2 = DB::select($query2);
        //start making combo
        $combo[] = '<option value=0>-- Choose --</option>';
        $combo[] = "\n";
        if (count($results2) === 1) {
            $selected_id = $results2[0]->id;
        }
        foreach ($results2 as $result) {
            if ($result->id == $selected_id) {
                $selected = ' selected="1"';
            } else {
                $selected = '';
            }
            $combo[] = '<option value="' . $result->id . '"' . $selected . '>' . $result->name . '</option>';
            $combo[] = "\n";
        }
        return implode("", $combo);
    }

    public function logout()
    {
        Session::flush();
        return redirect('/');
    }
}
