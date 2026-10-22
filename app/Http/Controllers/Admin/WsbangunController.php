<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WsbangunController extends Controller
{
    public function update_ticket(Request $request){
        $par = $request->params;
        $par = explode(':',$par);
        $entity = $par[0]; 
        $project = $par[1]; 
        $debtor = $par[2]; 
        $complain_no = $par[3]; 
        $status = $par[4]; 
        $data = array(
            'status'=>$status
        );
        $crit1 = array(
            'entity_cd'=>$entity,
            'project_no'=>$project,
            'tenant_no'=>$debtor,
            'complain_no'=>$complain_no
        );
        try{
            DB::connection('ifcaadm')
                ->table('mgr.sv_entry_multi')
                ->where($crit1)
                ->update($data);
            $feedback = "Data has been updated successfully";
        } catch(\Illuminate\Database\QueryException $ex){ 
            report($ex); // detail error -> storage/logs/laravel-{tanggal}.log
         
            $feedback = "Insert failed: " . $ex->getMessage();
        }

        echo $feedback;
        
    }
    public function update_OT(Request $request){
      
        try{
            $sql = "SELECT * from mgr.ot_trx where status='N' and start_overtime<=GETDATE() ";     
            $dtot = DB::connection('ifcaadm')->select($sql);
            if(!empty($dtot)){
                foreach ($dtot as $key) {
                    $data = array(
                        'status'=>'A'
                    );
                    $crit1 = array(
                        'id'=>$key->id
                    );
                    DB::connection('ifcaadm')
                    ->table('mgr.ot_trx')
                    ->where($crit1)
                    ->update($data);
                }
            }
            $feedback = "Data has been updated successfully";
        } catch(\Illuminate\Database\QueryException $ex){ 
            report($ex); // detail error -> storage/logs/laravel-{tanggal}.log
         
            $feedback = "Update failed: " . $ex->getMessage();
        }

        echo $feedback;
        
    }
    public function business($method='', $value='')
    {
        // var_dump($method);
        // var_dump($value);exit();
        if(!empty($method)&&!empty($value))
        {
            $method = strtolower($method);
            $list_value = explode(":",$value);
            // $cnt_value = count($list_value);
            switch ($method) {
                case 'insert':
                    $content = array(
                        'entity_cd'=>$list_value[0],
                        'project_no'=>$list_value[1],
                        'tenant_no'=>$list_value[2]
                    );
                    $this->new_business($content);
                    break;
                case 'delete':
                    $content = array(
                        'entity_cd'=>$list_value[0],
                        'project_no'=>$list_value[1],
                        'tenant_no'=>$list_value[2]
                    );
                    $this->del_business($content);
                    break;
                case 'edit':
                    $content = array(
                        'entity_cd'=>$list_value[0],
                        'project_no'=>$list_value[1],
                        'business_id'=>$list_value[2]
                    );
                    $this->edit_business($content);
                    break;
            }
        }
    }
    public function new_business($value = "")
    {
        $entity = $value["entity_cd"];
        $project = $value["project_no"];
        $tenant_no = $value["tenant_no"];

        try {
            $result = DB::connection('ifcapb')->select("SELECT * FROM mgr.tenant_login WHERE entity_cd=? AND RTRIM(project_no)=? AND debtor_acct=?", [$entity, $project, $tenant_no]);

            if (!empty($result)) {

                $id_business = $result[0]->business_id;
                $crit1 = array('business_no' => $id_business);

                $check_tenant = DB::connection('ifcaadm')
                    ->table('mgr.tenant')
                    ->where($crit1)
                    ->get();

                if (empty($check_tenant) || count($check_tenant) == 0) {

                    // Insert Owner
                    $data1 = array(
                        'name'            => $result[0]->name,
                        'phone'           => $result[0]->tel_no,
                        'contact_name'    => $result[0]->contact_person,
                        'contact_mobile'  => $result[0]->hand_phone,
                        'address'         => $result[0]->address1 . ' ' . $result[0]->address2 . ' ' . $result[0]->address3 . ' ' . $result[0]->post_cd,
                        'email'           => trim($result[0]->email_addr),
                        'status'          => 1,
                        'business_no'     => $result[0]->business_id,
                        'tenant_no_df'    => $tenant_no,
                        'flag'            => 'O'
                    );

                    DB::connection('ifcaadm')
                        ->table('mgr.tenant')
                        ->insert($data1);

                    // Insert Finance (jika ada email finance)
                    if (!is_null($result[0]->email_addr_fin) && trim($result[0]->email_addr_fin) != '') {

                        $dataFinance = $data1;
                        $dataFinance['email'] = trim($result[0]->email_addr_fin);
                        $dataFinance['flag'] = 'F';

                        DB::connection('ifcaadm')
                            ->table('mgr.tenant')
                            ->insert($dataFinance);
                    }

                    // Ambil seluruh tenant (Owner & Finance)
                    $data_tenant = DB::connection('ifcaadm')
                        ->table('mgr.tenant')
                        ->where($crit1)
                        ->orderBy('id')
                        ->get();

                    // ID Owner untuk pm_tenancy
                    $id_tenant = $data_tenant[0]->id;

                    $password = md5('Capital123');

                    // Insert seluruh tenant ke all_login
                    foreach ($data_tenant as $tenant) {

                        $exist = DB::connection('ifcaadm')
                            ->table('mgr.all_login')
                            ->where('tableforeign', 'tenant')
                            ->where('idforeign', $tenant->id)
                            ->exists();

                        if (!$exist) {
                            DB::connection('ifcaadm')
                                ->table('mgr.all_login')
                                ->insert([
                                    'name'         => $tenant->name,
                                    'email'        => $tenant->email,
                                    'password'     => $password,
                                    'tableforeign' => 'tenant',
                                    'idforeign'    => $tenant->id
                                ]);
                        }
                    }

                    $crit2 = array('tenant_no' => $tenant_no);

                    $cnt_tenancy = DB::connection('ifcaadm')
                        ->table('mgr.pm_tenancy')
                        ->where($crit2)
                        ->get();

                    if (count($cnt_tenancy) < 1) {

                        if (
                            is_null($result[0]->contract_date) ||
                            is_null($result[0]->commence_date) ||
                            is_null($result[0]->expiry_date)
                        ) {
                            $contract_date = $result[0]->contract_date;
                            $commence_date = $result[0]->commence_date;
                            $expiry_date = $result[0]->expiry_date;
                        } else {
                            $contract_date = date('Y-m-d', strtotime($result[0]->contract_date));
                            $commence_date = date('Y-m-d', strtotime($result[0]->commence_date));
                            $expiry_date = date('Y-m-d', strtotime($result[0]->expiry_date));
                        }

                        $entity_desc = $result[0]->entity_desc;
                        $project_desc = $result[0]->project_desc;

                        $data3 = array(
                            'id'             => $id_tenant,
                            'business_no'    => $id_business,
                            'tenant_no'      => $tenant_no,
                            'entity_cd'      => $entity,
                            'project_no'     => $project,
                            'contract_date'  => $contract_date,
                            'commence_date'  => $commence_date,
                            'expiry_date'    => $expiry_date,
                            'entity_desc'    => $entity_desc,
                            'project_desc'   => $project_desc,
                            'status'         => 'A'
                        );

                        DB::connection('ifcaadm')
                            ->table('mgr.pm_tenancy')
                            ->insert($data3);
                    }
                }

                $feedback = json_encode($result);

            } else {
                $feedback = 'Bad request';
            }

        } catch (\Illuminate\Database\QueryException $ex) {
            report($ex); // detail error -> storage/logs/laravel-{tanggal}.log

            $feedback = "Insert failed: " . $ex->getMessage();
        }

        echo $feedback;
    }
    public function edit_business($value="")
    {
        $entity = $value["entity_cd"];
        $project = $value["project_no"];
        $business_no = $value["business_id"];
        try{
            // $result = $this->m_wsbangun->get_tenant_by_id($tenant_no);
            // var_dump($business_no);exit;
            $result = DB::connection('ifcapb')->select("SELECT * FROM mgr.tenant_login WHERE business_id=?", [$business_no]);
            if(!empty($result)||count($result)>0) {
                // var_dump('a');
                $tenant_no = $result[0]->debtor_acct;
                $crit1 = array('business_no'=>$business_no);
                // $check_tenant = $this->m_tenant->get_by_criteria($crit1);
                $check_tenant = DB::connection('ifcaadm')->table('mgr.tenant')->where($crit1)->get();
                if(!empty($check_tenant)) {
                    // var_dump('b');
                    $data = array(
                        'name'=>$result[0]->name,
                        'email'=>$result[0]->email_addr,
                        'phone'=>$result[0]->tel_no,
                        'contact_name'=>$result[0]->contact_person,
                        'contact_mobile'=>$result[0]->hand_phone
                    );
                    $id_tenant = $check_tenant[0]->id;
                    // $this->m_tenant->update($data,$crit1);
                    DB::connection('ifcaadm')
                    ->table('mgr.tenant')
                    ->where($crit1)
                    ->update($data);

                    $crit2 = array(
                        'idforeign'=>$id_tenant,
                        'tableforeign'=>'tenant'
                    );
                    // $cnt_login = $this->m_login->count_by_criteria($crit2);
                    $cnt_login = DB::connection('ifcaadm')->table('mgr.all_login')->where($crit2)->get();
                    if(count($cnt_login)<1) {
                        // var_dump("KOSONG");
                        $password = 'Capital123';
                        $data2 = array(
                            'name'=>$result[0]->name,
                            'email'=>$result[0]->email_addr,
                            'password'=>md5($password),
                            'tableforeign'=>'tenant',
                            'idforeign'=>$id_tenant
                        );
                        // $this->m_login->insert($data2);
                        DB::connection('ifcaadm')
                        ->table('mgr.all_login')
                        ->insert($data2);
                    } else {
                        var_dump("ISI");
                        $data2 = array('email'=>$result[0]->email_addr);
                        // $this->m_login->update($data2, $crit2);
                        DB::connection('ifcaadm')
                            ->table('mgr.all_login')
                            ->where($crit2)
                            ->update($data2);
                    }
                    $feedback = 'Tenant id: '. $id_tenant.' data updated!';
                }
            } else {
                $feedback = 'Bad request';
            }
        } catch(\Illuminate\Database\QueryException $ex){ 
            report($ex); // detail error -> storage/logs/laravel-{tanggal}.log
            $feedback = "Update failed: " . $ex->getMessage();
        }
        echo $feedback;
        
    }
    public function del_business($value="")
    {
        try{
            // $check_tenancy = $this->m_tenancy->get_by_criteria($value);
            $check_tenancy = DB::connection('ifcaadm')->table('mgr.pm_tenancy')->where($value)->get();
            if(!empty($check_tenancy)) {
                $business_no = $check_tenancy[0]->business_no;
                $tenant_no = $check_tenancy[0]->tenant_no;
                $crit1 = array('business_no'=>$business_no);
                // $cnt_tenancy = $this->m_tenancy->count_by_criteria($crit1);
                $cnt_tenancy = DB::connection('ifcaadm')->table('mgr.pm_tenancy')->where($crit1)->get();
                if(count($cnt_tenancy)==1) {
                    $data = array('status'=>0);
                    // $data_tenant = $this->m_tenant->get_by_criteria($crit1);
                    $data_tenant = DB::connection('ifcaadm')->table('mgr.tenant')->where($crit1)->get();
                    $id_tenant = $data_tenant[0]->id;
                    $crit2 = array(
                        'idforeign'=>$id_tenant,
                        'tableforeign'=>'tenant'
                    );
                    // $this->m_tenant->update($data,$crit2);
                    // $this->m_tenant->delete($crit1);
                    // $this->m_login->delete($crit2);
                    DB::connection('ifcaadm')
                        ->table('mgr.tenant')
                        ->where($crit1)
                        ->delete();
                    DB::connection('ifcaadm')
                        ->table('mgr.all_login')
                        ->where($crit2)
                        ->delete();
                }
                // $this->m_tenancy->delete($value);
                DB::connection('ifcaadm')
                        ->table('mgr.pm_tenancy')
                        ->where($value)
                        ->delete();
                $feedback='Tenant no : '. $tenant_no . ' deleted!';
            }
        } catch(\Illuminate\Database\QueryException $ex){ 
            report($ex); // detail error -> storage/logs/laravel-{tanggal}.log
            $feedback = "Delete failed: " . $ex->getMessage();
        }
        echo $feedback;
    }
}
