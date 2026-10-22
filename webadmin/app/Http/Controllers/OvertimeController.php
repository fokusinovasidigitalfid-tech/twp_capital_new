<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use DataTables;
use PDF;

class OvertimeController extends Controller
{
    public function index_app(){
       
        // $sqlad = "SELECT entity_cd, entity_name from mgr.cf_entity (nolock)";        
        // $dtENT = DB::connection('ifcapb')->select($sqlad); 
        // $sql = "SELECT project_no, descs from mgr.pl_project (nolock)";        
        // $dtpro = DB::connection('ifcapb')->select($sql);    
        // $content = array(
        //     'dataent'=>$dtENT,
        //     'datapro'=>$dtpro
        // );
        
        return view('overtime.index_app');
    }

    public function getTableNewOT()
    {
         
        $query = DB::connection('ifcaadm')->select("SELECT @rownum := @rownum + 1 AS row_number, v_ot_tenancy.* FROM v_ot_tenancy, (SELECT @rownum := 0) r where status ='N' order by start_overtime desc");

        return DataTables::of($query)->make(true);
    }
    public function getTableAppOT()
    {
         
        $query = DB::connection('ifcaadm')->select("SELECT @rownum := @rownum + 1 AS row_number, v_ot_tenancy.* FROM v_ot_tenancy, (SELECT @rownum := 0) r where status = 'A' order by start_overtime desc");

        return DataTables::of($query)->make(true);
    }
    public function getTableCancelOT()
    {
         
        $query = DB::connection('ifcaadm')->select("SELECT @rownum := @rownum + 1 AS row_number, v_ot_tenancy.* FROM v_ot_tenancy, (SELECT @rownum := 0) r where status = 'X' order by start_overtime desc");

        return DataTables::of($query)->make(true);
    }
    public function approveOT(Request $request)
    {
        // var_dump($request->id);exit();
        $data= array('status' => 'A');
        $criteria = array('id' => $request->id);
        try { 
            DB::connection('ifcaadm')
            ->table('ot_trx')
            ->where($criteria)
            ->update($data);
            $msg = "Data has been approved successfully";
            $st  = 'OK';
        } catch(\Illuminate\Database\QueryException $ex){ 
            $msg = "Delete failed: " . $ex->getMessage();
            $st  = 'Fail';
        }
        return response()->json([
            'status' => $st,
            'pesan' => $msg
        ]);
    }
    public function cancelOT(Request $request)
    {
        // var_dump($request->id);exit();
        $data= array('status' => 'X');
        $criteria = array('id' => $request->id);
        try { 
            DB::connection('ifcaadm')
            ->table('ot_trx')
            ->where($criteria)
            ->update($data);
            $msg = "Data has been canceled successfully";
            $st  = 'OK';
        } catch(\Illuminate\Database\QueryException $ex){ 
            $msg = "Delete failed: " . $ex->getMessage();
            $st  = 'Fail';
        }
        return response()->json([
            'status' => $st,
            'pesan' => $msg
        ]);
    }
    public function index_post(){
       
        $sqlad = "SELECT entity_cd, entity_name from mgr.cf_entity (nolock)";        
        $dtENT = DB::connection('ifcapb')->select($sqlad); 
        $sql = "SELECT project_no, descs from mgr.pl_project (nolock)";        
        $dtpro = DB::connection('ifcapb')->select($sql);    
        $content = array(
            'dataent'=>$dtENT,
            'datapro'=>$dtpro
        );
        
        return view('overtime.index_post',$content);
    }
    public function getTable(Request $request)
    {
        $entity      = $request->entity;
        $project     = $request->project;
        $startperiod = $request->date_start;
        $endperiod   = $request->date_end;
        $filter_date = '';
        if(!empty($startperiod) and !empty($endperiod)){
            $startperiod = $startperiod." 00:00:00";
            $endperiod   = $endperiod." 23:59:59";
            $filter_date  = " and start_overtime >= '$startperiod' and start_overtime <= '$endperiod'";
        }
        if(empty($project)){
            $sql ="SELECT * from mgr.pl_project(nolock)";
            $query = DB::connection('ifcapb')->select($sql);
            $entity = $query[0]->entity_cd;
            $project = $query[0]->project_no;
        }
        $sqlad = "SELECT distinct tenant_no from v_ot_tenancy where entity_cd='$entity' and project_no='$project'  AND approved = 'N' and status='A' ".$filter_date;   
        // var_dump($sqlad);     
        $dt1 = DB::connection('ifcaadm')->select($sqlad);
        if(!empty($dt1)){
            $where_in = array();
            foreach ($dt1 as $key) {
                $where_in[]="'".$key->tenant_no."'";
            }
            $where_in = implode( ', ', $where_in );
            $where = "where entity_cd='$entity' and project_no='$project'  AND  bill_debtor_acct in ($where_in) and debtor_acct = bill_debtor_acct";
        }else{
             $where="where entity_cd='$entity' and project_no='$project'  AND  bill_debtor_acct='' and debtor_acct = bill_debtor_acct";
        }
        $sql ="SELECT ROW_NUMBER() OVER (ORDER BY bill_debtor_acct desc) AS [row_number], * from mgr.v_ot_debtor_tenancy ".$where;
        // var_dump($sql);//exit();
        $query = DB::connection('ifcapb')->select($sql);
        return DataTables::of($query)->make(true);
    }
    public function save(Request $request)
    {
        // var_dump($request->all());//exit;
        $bill_debtor='';$dtexec=null;
        $postdate='';
        $rowid_hd='';
        $project = $request->project;
        $entity = $request->entity;
        $bill_debtor_acct = $request->bill_debtor_acct;
        // $business_id = $request->business_id;
        $postdate = $request->post_date;
        $startperiod = $request->start;
        $endperiod = $request->end;
        $remarks = $request->remarks;
        $today = date('Y M d H:i:s');
        $time1 = strtotime($postdate);
        $postdate_new = date('Y M d H:i:s',$time1);

        // $cek_fji_billdate = explode('-', $postdate);
        // var_dump($cek_fji_billdate);exit();
        $time2 = strtotime($startperiod);
        $startperiod = date('Y M d H:i:s',$time2);

        $time3 = strtotime($endperiod." 23:59:59");
        $endperiod = date('Y M d H:i:s',$time3);
        $startmysql = date('Y-m-d H:i:s',$time2);
        $endmysql = date('Y-m-d H:i:s',$time3);
        try { 
            $dt_OTspec = DB::connection('ifcapb')->select('SELECT * from mgr.ot_spec');
            $trx_type = $dt_OTspec[0]->trx_type;
            $tax_cd = $dt_OTspec[0]->tax_cd;
            $dt_tax = DB::connection('ifcapb')->select("SELECT  * from mgr.cf_tax_sch_dt where scheme_cd = '$tax_cd' and deduct_flag = 'N'");
            $tax_rate = $dt_tax[0]->tax_rate;
            $sql = "SELECT distinct bill_debtor_acct,inv_group,currency_cd,min_hours_type,min_over_hours,credit_terms from mgr.v_ot_debtor_tenancy where entity_cd='$entity' and project_no='$project' and debtor_acct='$bill_debtor_acct' ";    
            $dthours = DB::connection('ifcapb')->select($sql);
            // exit();
            if(!empty($dthours)){
                $bill_debtor = $dthours[0]->bill_debtor_acct;
                $inv_group = $dthours[0]->inv_group;
                $currency_cd = $dthours[0]->currency_cd;
                $min_hours_type = $dthours[0]->min_hours_type;
                $min_over_hours = $dthours[0]->min_over_hours;
                $credit_terms = $dthours[0]->credit_terms;
            }else{
                $msg = "Can not get data from v_ot_debtor_tenancy.";
                $st = 'Fail';
                return response()->json([
                    'status' => $st,
                    'pesan' => $msg
                ]);
                exit();
            }
            
            
            $dt_fji = array(
                'entity_cd' => $entity,
                'project_no'=>$project,
                'debtor_acct'=>$bill_debtor_acct,
                'inv_group'=>$inv_group,
                'trx_type'=>$trx_type,
                'bill_date'=>$postdate_new,
                'currency_cd'=>$currency_cd,
                'remarks'=>$remarks,
                'status'=>'N',
                'audit_user'=>'TWP',
                'audit_date'=>$today,
                'min_hours_type'=>$min_hours_type,
                'min_over_hours'=>$min_over_hours,
                'bill_amt'=>0,
                'flag_round'=>'N',
                'credit_terms'=>$credit_terms,
                'start_period'=>$startperiod,
                'end_period'=>$endperiod
            );
            DB::connection('ifcapb')
                    ->table('mgr.ot_trx_fji')
                    ->insert($dt_fji);
            $sql = "SELECT * from mgr.ot_trx_fji where entity_cd='$entity' and project_no='$project' and debtor_acct='$bill_debtor_acct' and bill_date = '$postdate_new' and start_period='$startperiod' and end_period='$endperiod' and remarks='$remarks'";    
            $dt_id_fji = DB::connection('ifcapb')->select($sql);
            if(empty($dt_id_fji)){
                $msg = "Save failed: data header (mgr.ot_trx_fji) is not available.";
                $st = 'Fail';
                return response()->json([
                    'status' => $st,
                    'pesan' => $msg
                ]);
                exit();
            }
            $rowid_hd=$dt_id_fji[0]->rowID;
            if(empty($rowid_hd)){
                $msg = "Save failed: Can't get rowid from mgr.ot_trx_fji.";
                $st = 'Fail';
                return response()->json([
                    'status' => $st,
                    'pesan' => $msg
                ]);
                exit();
            }
            $postdate_new = $dt_id_fji[0]->bill_date;
            $postdate = date('d-M-Y',strtotime($postdate_new));
            $postdate_new = date('Y M d H:i:s',strtotime($postdate_new));
            $sql = "SELECT TIMESTAMPDIFF(HOUR,  start_overtime, end_overtime) as hour_diff, TIMESTAMPDIFF ( minute , start_overtime, end_overtime ) as min_diff,v_ot_tenancy.* from v_ot_tenancy where entity_cd='$entity' and project_no='$project' and tenant_no = '$bill_debtor' and approved='N'  and status ='A' and start_overtime >= '$startmysql' and start_overtime <= '$endmysql'";     
            $dt_OT = DB::connection('ifcaadm')->select($sql);
                if(!empty($dt_OT)){
                    // Ambil semua lot_no
                    $lotNos = [];
                    foreach ($dt_OT as $row) {
                        $lotNos[] = "'" . $row->lot_no . "'";
                    }

                    $lotList = implode(",", $lotNos);

                    // Hitung jumlah lot per level
                    $sqlLevel = "
                        SELECT level_no, COUNT(*) AS total_level
                        FROM mgr.pm_lot
                        WHERE entity_cd = '$entity'
                        AND project_no = '$project'
                        AND lot_no IN ($lotList)
                        GROUP BY level_no
                    ";

                    $dtLevel = DB::connection('ifcapb')->select($sqlLevel);

                    $levelCount = [];
                    foreach($dtLevel as $row){
                        $levelCount[$row->level_no] = $row->total_level;
                    }

                    $dt_fji_dtl = [];
                    $id_ot = [];
                    $no = 1;
                    $durasi = 0;
                    $dt_fji_zone = [];
                    $bill_amt = 0;
                    foreach ($dt_OT as $key) {

                        $interval = (strtotime($key->end_overtime) - strtotime($key->start_overtime))/(60*60);
                
                        // Ambil level dahulu
                        $sql = "
                            SELECT level_no
                            FROM mgr.pm_lot
                            WHERE entity_cd='$entity'
                              AND project_no='$project'
                              AND lot_no='$key->lot_no'
                        ";
                
                        $dtLevelLot = DB::connection('ifcapb')->select($sql);
                
                        if(empty($dtLevelLot)){
                            continue;
                        }
                
                        $levelNo = $dtLevelLot[0]->level_no;
                
                        // Tentukan field over_ot_cd berdasarkan jumlah lot pada level tersebut
                        $jumlahLevel = $levelCount[$levelNo] ?? 1;
                
                        $fieldOverCd = ($jumlahLevel == 1)
                            ? 'over_ot_cd'
                            : 'over_ot_cd' . $jumlahLevel;
                        // $interval2 = round($interval,2,PHP_ROUND_HALF_UP);
                        // var_dump($interval);var_dump($interval2);exit;
                        $sql = "SELECT mgr.pm_lot.level_no,mgr.pm_lot.$fieldOverCd AS over_ot_cd,ott.trx_type , mgr.pm_lot.zone_ot_cd 
                            from mgr.pm_lot
                            inner join mgr.ot_type ott (nolock) 
                            on mgr.pm_lot.entity_cd = ott.entity_cd and
                            mgr.pm_lot.over_ot_cd = ott.over_cd
                            where mgr.pm_lot.entity_cd = '$entity'
                            and mgr.pm_lot.project_no = '$project'
                            and mgr.pm_lot.lot_no = '$key->lot_no'";
                        $dt_lot = DB::connection('ifcapb')->select($sql);
                        if(empty($dt_lot)){
                            $msg = "Save failed: Can't get data from mgr.pm_lot or the data is empty/unavailable.";
                            $st = 'Fail';
                            return response()->json([
                                'status' => $st,
                                'pesan' => $msg
                            ]);
                            exit();
                        }
                        
                        $over_cd = $dt_lot[0]->over_ot_cd;
                        $zone_cd = $dt_lot[0]->zone_ot_cd;
                        $trx_type = $dt_lot[0]->trx_type; 
                        $sql="SELECT * from mgr.ot_rate_scl where over_cd = '$over_cd' and zone_cd = '$zone_cd' and entity_cd = '$entity' and project_no = '$project'";
                        $dt_otr = DB::connection('ifcapb')->select($sql);
                        $overtime_rate=$dt_otr[0]->rate;
                        // $level_no = $dt_lot[0]->level_no; 
                        $durasi = $durasi + $interval;
                        $base_amt = $interval * $overtime_rate;
                        $tax_amt = $base_amt * ($tax_rate/100);
                        // $trx_amt = $base_amt + $tax_amt;
                        $trx_amt = $base_amt;
                        $bill_amt=$bill_amt+$trx_amt;

                        $dt_fji_dtl[] = array(
                            'entity_cd' => $entity,
                            'project_no'=>$project,
                            'debtor_acct'=>$bill_debtor_acct,
                            'lot_no'=>$key->lot_no,
                            'inv_group'=>$inv_group,
                            'trx_type'=>$trx_type,
                            'over_cd'=>$over_cd,
                            'zone_cd'=>$zone_cd,
                            'bill_date'=>$postdate_new,
                            'begin_date'=>date('Y M d H:i:s',strtotime($key->start_overtime)),
                            'end_date'=>date('Y M d H:i:s',strtotime($key->end_overtime)),
                            'apport_percent'=>100,
                            'usage'=>(float)$interval,
                            'rate'=>$overtime_rate,
                            'trx_amt'=>$trx_amt,
                            'base_amt'=>$base_amt,
                            'tax_amt'=>$tax_amt,
                            'tax_cd'=>$tax_cd,
                            'audit_user'=>'TWP',
                            'audit_date'=>$today,
                            'descs'=>$key->description,
                            'usage_zone'=>$key->min_diff,
                            'area'=>0,
                            'rowid_hd'=>$rowid_hd
                        );
                        $scale = $interval*60;
                        $dt_fji_zone[] = array(
                            'entity_cd' => $entity,
                            'project_no'=>$project,
                            'debtor_acct'=>$bill_debtor_acct,
                            'lot_no'=>$key->lot_no,
                            'inv_group'=>$inv_group,
                            'trx_type'=>$trx_type,
                            'over_cd'=>$over_cd,
                            'zone_cd'=>$zone_cd,
                            'bill_date'=>$postdate_new,
                            'begin_date'=>date('Y M d H:i:s',strtotime($key->start_overtime)),
                            'start_date'=>date('Y M d H:i:s',strtotime($key->start_overtime)),
                            'end_date'=>date('Y M d H:i:s',strtotime($key->end_overtime)),
                            'scale'=>$scale,
                            'scale_use'=>$scale,
                            'rate'=>$overtime_rate,
                            'trx_amt'=>$trx_amt,
                            'base_amt'=>$base_amt,
                            'tax_amt'=>$tax_amt,
                            'rate_type'=>'O',
                            'audit_user'=>'TWP',
                            'audit_date'=>$today,
                            'descs'=>$key->description,
                            'rowid_hd'=>$rowid_hd
                        );
                        $id_ot[]=$key->id;
                        $no++;
                    }
                    DB::connection('ifcapb')
                        ->table('mgr.ot_trxdt_fji')
                        ->insert($dt_fji_dtl);
                    DB::connection('ifcapb')
                        ->table('mgr.ot_trxdt_zone_fji')
                        ->insert($dt_fji_zone);

                    $dtup = array('bill_amt' => $bill_amt);
                    DB::connection('ifcapb')
                    ->table('mgr.ot_trx_fji')
                    ->where('rowID',$rowid_hd)
                    ->update($dtup);
                    $dtup = array('approved' => 'Y', 'status'=>'Z');
                    DB::connection('ifcaadm')
                    ->table('ot_trx')
                    ->whereIn('id',$id_ot)
                    ->update($dtup);
                    // var_dump($up_stat);
                } else {
                    $msg = "There's no overtime available.";
                    $st = 'Fail';
                    return response()->json([
                        'status' => $st,
                        'pesan' => $msg
                    ]);
                } 
    
            $msg = "Data has been saved successfully";
            $st = 'OK';
            $dtexec = array('bill_debtor'=>$bill_debtor,'postdate'=> $postdate,'bill_date'=>$postdate_new,'startperiod'=>$startperiod,'endperiod'=>$endperiod,'rowid_hdfji'=>$rowid_hd,'entity'=>$entity,'project'=>$project,'audit_user'=>'TWP','id_ot'=>$id_ot);
            
        } catch(\Illuminate\Database\QueryException $ex){ 
            $msg = "Save failed: " . $ex->getMessage();
            $st  = 'Failed';
        }
        return response()->json([
            'status' => $st,
            'pesan' => $msg,
            'dtexec'=> $dtexec
        ]);
    }
    // public function save_old(Request $request)
    // {
    //     // var_dump($request->all());//exit;
    //     $bill_debtor='';$dtexec=null;
    //     $postdate='';
    //     $rowid_hd='';
    //     $project = $request->project;
    //     $entity = $request->entity;
    //     $bill_debtor_acct = $request->bill_debtor_acct;
    //     // $business_id = $request->business_id;
    //     $postdate = $request->post_date;
    //     $startperiod = $request->start;
    //     $endperiod = $request->end;
    //     $remarks = $request->remarks;
    //     $today = date('Y M d H:i:s');
    //     $time1 = strtotime($postdate);
    //     $postdate_new = date('Y M d H:i:s',$time1);

    //     $cek_fji_billdate = explode('-', $postdate);
    //     // var_dump($cek_fji_billdate);exit();
    //     $time2 = strtotime($startperiod);
    //     $startperiod = date('Y M d H:i:s',$time2);

    //     $time3 = strtotime($endperiod." 23:59:59");
    //     $endperiod = date('Y M d H:i:s',$time3);
    //     $startmysql = date('Y-m-d H:i:s',$time2);
    //     $endmysql = date('Y-m-d H:i:s',$time3);
    //     try { 
    //         // $sql = "SELECT distinct bill_debtor_acct,inv_group,currency_cd,min_hours_type,min_over_hours,credit_terms,trx_type from mgr.v_ot_debtor_tenancy where entity_cd='$entity' and project_no='$project' and debtor_acct='$bill_debtor_acct' ";     
    //         $dt_OTspec = DB::connection('ifcaadm')->select('SELECT * from mgr.ot_spec');
    //         $trx_type = $dt_OTspec[0]->trx_type;
    //         $sql = "SELECT distinct bill_debtor_acct,inv_group,currency_cd,min_hours_type,min_over_hours,credit_terms from mgr.v_ot_debtor_tenancy where entity_cd='$entity' and project_no='$project' and debtor_acct='$bill_debtor_acct' ";    
    //         $dthours = DB::connection('ifcapb')->select($sql);
    //         // exit();
    //         if(!empty($dthours)){
    //             $bill_debtor = $dthours[0]->bill_debtor_acct;
    //             $inv_group = $dthours[0]->inv_group;
    //             $currency_cd = $dthours[0]->currency_cd;
    //             $min_hours_type = $dthours[0]->min_hours_type;
    //             $min_over_hours = $dthours[0]->min_over_hours;
    //             $credit_terms = $dthours[0]->credit_terms;
               
    //         }else{
    //             $msg = "Can not get data from v_ot_debtor_tenancy.";
    //             $st = 'Fail';
    //             return response()->json([
    //                 'status' => $st,
    //                 'pesan' => $msg
    //             ]);
    //             exit();
    //         }
    //         $dt_fji = array(
    //             'entity_cd' => $entity,
    //             'project_no'=>$project,
    //             'debtor_acct'=>$bill_debtor_acct,
    //             'inv_group'=>$inv_group,
    //             'trx_type'=>$trx_type,
    //             'bill_date'=>$postdate_new,
    //             'currency_cd'=>$currency_cd,
    //             'remarks'=>$remarks,
    //             'status'=>'N',
    //             'audit_user'=>'TWP',
    //             'audit_date'=>$today,
    //             'min_hours_type'=>$min_hours_type,
    //             'min_over_hours'=>$min_over_hours,
    //             'bill_amt'=>0,
    //             'flag_round'=>'N',
    //             'credit_terms'=>$credit_terms,
    //             'start_period'=>$startperiod,
    //             'end_period'=>$endperiod
    //         );
    //         DB::connection('ifcapb')
    //                 ->table('mgr.ot_trx_fji')
    //                 ->insert($dt_fji);
    //             $sql = "SELECT * from mgr.ot_trx_fji where entity_cd='$entity' and project_no='$project' and debtor_acct='$bill_debtor_acct' and month(bill_date) = '$cek_fji_billdate[1]' and year(bill_date) ='$cek_fji_billdate[2]'";     
    //             $dt_id_fji = DB::connection('ifcapb')->select($sql);

    //             if(empty($dt_id_fji)){
    //                 $msg = "Save failed: Can't get rowid from mgr.ot_trx_fji.";
    //                 $st = 'Fail';
    //                 return response()->json([
    //                     'status' => $st,
    //                     'pesan' => $msg
    //                 ]);
    //                 exit();
    //             }
    //             $rowid_hd=$dt_id_fji[0]->rowID;
    //             $postdate_new = $dt_id_fji[0]->bill_date;
    //             $postdate = date('d-M-Y',strtotime($postdate_new));
    //             $postdate_new = date('Y M d H:i:s',strtotime($postdate_new));
                
    //             $sql = "SELECT TIMESTAMPDIFF(HOUR,  start_overtime, end_overtime) as hour_diff, TIMESTAMPDIFF ( minute , start_overtime, end_overtime ) as min_diff,v_ot_tenancy.* from v_ot_tenancy where entity_cd='$entity' and project_no='$project' and tenant_no = '$bill_debtor' and approved='N'  and status ='A' and start_overtime >= '$startmysql' and start_overtime <= '$endmysql'";   
    //             // var_dump($sql);exit();     
    //             $dt_OT = DB::connection('ifcaadm')->select($sql);
    //             if(!empty($dt_OT)){
    //                 $dt_fji_dtl = [];$id_ot = [];$no=1;
    //                 foreach ($dt_OT as $key) {
    //                     $sql = "SELECT mgr.pm_lot.level_no,mgr.pm_lot.over_ot_cd,ott.trx_type , mgr.pm_lot.zone_ot_cd 
    //                         from mgr.pm_lot
    //                         inner join mgr.ot_type ott (nolock) 
    //                         on mgr.pm_lot.entity_cd = ott.entity_cd and
    //                         mgr.pm_lot.over_ot_cd = ott.over_cd
    //                         where mgr.pm_lot.entity_cd = '$entity'
    //                         and mgr.pm_lot.project_no = '$project'
    //                         and mgr.pm_lot.lot_no = '$key->lot_no'";
    //                     $dt_lot = DB::connection('ifcapb')->select($sql);
    //                     if(empty($dt_lot)){
    //                         $msg = "Save failed: Can't get data from mgr.pm_lot or the data is empty/unavailable.";
    //                         $st = 'Fail';
    //                         return response()->json([
    //                             'status' => $st,
    //                             'pesan' => $msg
    //                         ]);
    //                         exit();
    //                     }
    //                     $over_cd = $dt_lot[0]->over_ot_cd;
    //                     $zone_cd = $dt_lot[0]->zone_ot_cd;
    //                     $trx_type = $dt_lot[0]->trx_type; 
    //                     // $level_no = $dt_lot[0]->level_no; 
                        
    //                     $dt_fji_dtl[] = array(
    //                         'entity_cd' => $entity,
    //                         'project_no'=>$project,
    //                         'debtor_acct'=>$bill_debtor_acct,
    //                         'lot_no'=>$key->lot_no,
    //                         'inv_group'=>$inv_group,
    //                         'trx_type'=>$trx_type,
    //                         'over_cd'=>$over_cd,
    //                         'zone_cd'=>$zone_cd,
    //                         'bill_date'=>$postdate_new,
    //                         'begin_date'=>date('Y M d H:i:s',strtotime($key->start_overtime)),
    //                         'end_date'=>date('Y M d H:i:s',strtotime($key->end_overtime)),
    //                         'apport_percent'=>100,
    //                         'usage'=>$key->hour_diff,
    //                         'rate'=>0,
    //                         'trx_amt'=>0,
    //                         'base_amt'=>0,
    //                         'tax_amt'=>0,
    //                         'audit_user'=>'TWP',
    //                         'audit_date'=>$today,
    //                         'descs'=>$key->description,
    //                         'usage_zone'=>$key->min_diff,
    //                         'area'=>0,
    //                         // 'level_no'=>$level_no,
    //                         // 'hours'=>$key->hour_diff,
    //                         // 'trx_no'=>$no,
    //                         'rowid_hd'=>$rowid_hd
    //                     );
    //                     $id_ot[]=$key->id;
    //                     $no++;
    //                 }
    //                 DB::connection('ifcapb')
    //                 ->table('mgr.ot_trxdt_fji')
    //                 ->insert($dt_fji_dtl);
    //                 $dtup = array('approved' => 'Y', 'status'=>'Z');
    //                 // $criteria = array(
    //                 //     'entity_cd'=>$entity,
    //                 //     'project_no'=>$project,
    //                 //     'debtor_acct' => $bill_debtor,
    //                 //     'status' =>'A',
    //                 //     'start_overtime >' =>$startmysql,
    //                 //     'start_overtime <' =>$endmysql
    //                 //     );
    //                 DB::connection('ifcaadm')
    //                 ->table('ot_trx')
    //                 ->whereIn('id',$id_ot)
    //                 ->update($dtup);
    //                 // var_dump($up_stat);
    //             } else {
    //                 $msg = "There's no overtime available.";
    //                 $st = 'Fail';
    //                 return response()->json([
    //                     'status' => $st,
    //                     'pesan' => $msg
    //                 ]);
    //             } 
    
    //             $msg = "Data has been saved successfully";
    //             $st = 'OK';
    //             $dtexec = array('bill_debtor'=>$bill_debtor,'postdate'=> $postdate,'bill_date'=>$postdate_new,'startperiod'=>$startperiod,'endperiod'=>$endperiod,'rowid_hdfji'=>$rowid_hd,'entity'=>$entity,'project'=>$project,'audit_user'=>'TWP','id_ot'=>$id_ot);
                
    //     } catch(\Illuminate\Database\QueryException $ex){ 
    //         $msg = "Save failed: " . $ex->getMessage();
    //         $st  = 'Failed';
    //     }
    //     return response()->json([
    //         'status' => $st,
    //         'pesan' => $msg,
    //         'dtexec'=> $dtexec
    //     ]);
    // }
    public function approve_ot(Request $request){
        $dtexec = $request->dtexec;
        $rowid_hd = $dtexec['rowid_hdfji'];
        $bill_debtor = $dtexec['bill_debtor'];
        $bill_date = $dtexec['bill_date'];
        $entity = $dtexec['entity'];
        $project = $dtexec['project'];
        // $audit_user = $dtexec['audit_user'];
        // $startperiod = $dtexec['startperiod'];
        // $endperiod = $dtexec['endperiod'];
        // $postdate = $dtexec['postdate'];
        // $postmonth = date('n',strtotime($postdate));
        // $postyear = date('Y',strtotime($postdate));
        try{
            $dt_post = array(
                        'entity_cd' => $entity,
                        'project_no'=>$project,
                        'debtor_acct'=>$bill_debtor,
                        'post_date'=>$bill_date,
                        'rowid_hd'=>(int)$rowid_hd,
                        'status'=>'Y'
                    );
            DB::connection('ifcapb')
                ->table('mgr.ot_post_web')
                ->insert($dt_post);
                $msg="Data has been posted succesfully.";
                $st = 'OK';
            } catch(\Illuminate\Database\QueryException $ex){ 
                $msg = "Posting data failed: " . $ex->getMessage();
                $st  = 'Failed';
            }
            return response()->json([
                'status' => $st,
                'pesan' => $msg
            ]);

    }
    public function cancel_ot(Request $request){
        $dtexec = $request->dtexec;
        // var_dump($dtexec);
        $rowid_hd = $dtexec['rowid_hdfji'];
        $bill_debtor = $dtexec['bill_debtor'];
        $entity = $dtexec['entity'];
        $audit_user = $dtexec['audit_user'];
        $startperiod = $dtexec['startperiod'];
        $endperiod = $dtexec['endperiod'];
        $postdate = $dtexec['postdate'];
        $id_ot = $dtexec['id_ot'];
        $postmonth = date('n',strtotime($postdate));
        $postyear = date('Y',strtotime($postdate));
        try{
            // var_dump($rowid_hd);var_dump($postmonth);var_dump($postyear);exit();
            // $sql="DELETE mgr.ot_trx_fji  where month (bill_date) = '$postmonth' and YEAR (bill_date) = '$postyear' AND audit_user ='$audit_user' and entity_cd ='$entity' and debtor_acct  in ('$bill_debtor')";
            // DB::connection('ifcapb')->statement($sql);
       
            // $sql2="DELETE mgr.ot_trxdt_fji  where month (bill_date) = '$postmonth' and YEAR (bill_date) = '$postyear' AND audit_user ='$audit_user' and entity_cd ='$entity' and debtor_acct  in ('$bill_debtor')";
            // DB::connection('ifcapb')->statement($sql2);
         
            // $sql3="UPDATE ot_trx set approved ='N' where entity_cd ='$entity'  and debtor_acct = '$bill_debtor' and start_overtime  >= '$startperiod' and start_overtime  <= '$endperiod'";
            // DB::connection('ifcaadm')->statement($sql3);
            DB::connection('ifcapb')
                    ->table('mgr.ot_trx_fji')
                    ->where('rowID',$rowid_hd)
                    ->delete();
            DB::connection('ifcapb')
                    ->table('mgr.ot_trxdt_fji')
                    ->where('rowid_hd',$rowid_hd)
                    ->delete();
            DB::connection('ifcapb')
                    ->table('mgr.ot_trxdt_zone_fji')
                    ->where('rowid_hd',$rowid_hd)
                    ->delete();
            $dtup = array('approved' =>'N', 
            'status' => 'A');
            DB::connection('ifcaadm')
                    ->table('ot_trx')
                    ->whereIn('id',$id_ot)
                    ->update($dtup);
            $msg="Data has been cancelled successfully.";
            $st = 'OK';
  
          
        }catch(\Illuminate\Database\QueryException $ex){ 
            $msg = "Cancel posting data failed: " . $ex->getMessage();
            $st  = 'Failed';
        }
        return response()->json([
            'status' => $st,
            'pesan' => $msg
        ]);
    }
}
