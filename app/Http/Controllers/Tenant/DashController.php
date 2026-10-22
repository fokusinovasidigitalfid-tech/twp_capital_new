<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class DashController extends Controller
{
    public function index()
    {
        
        $tenant_no = Session::get('tenant_df');
        $id_tenant = Session::get('Tuser_id');
        Session::put('debug_dataTenant', $id_tenant);
        $Ttenancy = Session::get('Ttenancy');
        $tenant_flag = Session::get('Tflag');
        // dd($tenant_no);
        $crit = array('tenant_no' => $tenant_no,'id_tenant' =>$id_tenant);

        //Combo Lot No
        $cbLot = $this->getLotNo($tenant_no);

        $cbMeterId = $this->getMeterId($tenant_no);

        //Billing Outstanding
        $business_no = Session::get('business_no');
        $criteria = array(
            'business_no'=>$business_no, 
            'tenant_no'=>$tenant_no
        );
        $dataTenancy = DB::table('mgr.pm_tenancy')
            ->where($criteria)
            ->get();
        $list_bill = '';
        $footer_bill = '';
        $i = 1;
        $totalOutstanding = number_format(0, 2, ",", ".");
        $statusPembayaran = true;

        if (!empty($dataTenancy)) {
            $entity = $dataTenancy[0]->entity_cd;
            $project = $dataTenancy[0]->project_no;
            $sumBilling = array(
                'RP'=>0,
                'USD'=>0
            );

            $dataBilling = $this->get_soa_by_tenant($entity,$project,$tenant_no);
            if (!empty($dataBilling)) {
                foreach ($dataBilling as $billing) {
                    $list_bill .= '<tr class="odd">';
                    $list_bill .= '<td>'. $i.'</td>';
                    $list_bill .= '<td>'. $billing->doc_no.'</td>';
                    $list_bill .= '<td>'. date("d M Y", strtotime($billing->doc_date)).'</td>';
                    $list_bill .= '<td>'. date("d M Y", strtotime($billing->due_date)).'</td>';
                    $list_bill .= '<td>'. $billing->ar_ldg_desc.'</td>';

                    if(empty($billing->start_date) || empty($billing->end_date)){
                        $dtPer = ' - ';
                    } else {
                        $dtPer = date("d M Y", strtotime($billing->start_date)).' - '.date("d M Y", strtotime($billing->end_date));
                    }

                    $list_bill .= '<td>'. $dtPer .'</td>';
                    $list_bill .= '<td>'. $billing->currency_cd.'</td>';
                    $list_bill .= '<td align="right">'. number_format($billing->fbal_amt,2,",",".").'</td>';
                    $list_bill .= '</tr>';

                    switch ($billing->currency_cd) {
                        case 'RP':
                            $sumBilling['RP'] += $billing->fbal_amt;
                            break;
                        case 'USD':
                            $sumBilling['USD'] += $billing->fbal_amt;
                            break;
                    }
                    $i++;
                }

                // TAMBAHKAN DI SINI
                $totalOutstanding = '';

                if($sumBilling['RP'] != 0){
                    $totalOutstanding .= 'RP '.number_format($sumBilling['RP'],2,",",".");
                }

                if($sumBilling['USD'] != 0){

                    if($totalOutstanding != ''){
                        $totalOutstanding .= ' | ';
                    }

                    $totalOutstanding .= 'USD '.number_format($sumBilling['USD'],2,",",".");
                }

                if($sumBilling['RP']!=0) {
                    $footer_bill .= '<tr><td colspan="6" align="center"><b>TOTAL</b></td><td><b><span>RP</span></b></td><td align="right"><b><span>'.number_format($sumBilling['RP'],2,",",".").'</span></b></td></tr>';
                }

                if($sumBilling['USD']!=0) {
                    $footer_bill .= '<tr><td colspan="6" align="right"></td><td><b><span>USD</span></b></td><td align="right"><b><span>'.number_format($sumBilling['USD'],2,",",".").'</span></b></td></tr>';
                }
                $statusPembayaran = false;
            }
        } else {
            $statusPembayaran = true;
            $totalOutstanding = number_format(0, 2, ",", ".");
        }

        //Our Latest Ticket
        $flagsurvey = 1;
        $list_hticket = "";
        $i = 1;

        $crit_t = array('tenant_no' => $tenant_no,'id_tenancy' =>$Ttenancy);
        // var_dump($crit_t);
        // exit;
        $htenants = DB::table('mgr.sv_entry_multi')->where($crit_t)->where('status','<>','C')->get();
        if (!empty($htenants)) {
            foreach ($htenants as $tenant)
            {
                // =========================
                // CEK CHARGEABLE / NON
                // =========================
                $billingType = 'Non-Chargeable';

                $report = DB::connection('MPP')
                    ->table('mgr.sv_entry_multi_dt')
                    ->where('complain_no', $tenant->complain_no)
                    ->where('debtor_acct', $tenant_no)
                    ->where('reported_by', 'TWP')
                    ->first();

                if ($report) {

                    $checkRecharge = DB::connection('MPP')
                        ->table('mgr.sv_entry_dt')
                        ->where('report_no', $report->report_no)
                        ->first();

                    if ($checkRecharge) {
                        $billingType = 'Chargeable';
                    }
                }
                // =========================
                // HTML TABLE
                // =========================
                $list_hticket .= '<tr class="odd">';
                $list_hticket .= '<td>'.$i.'</td>';
                $list_hticket .= '<td>'.$tenant->complain_no.'</td>';
                $crit = array('category_cd' => $tenant->category_cd);
                $data_category = DB::connection('MPP')
                    ->table('mgr.sv_category')
                    ->where($crit)
                    ->get();

                if(empty($data_category)) {
                    $list_hticket .= '<td>'.$tenant->category_cd.'</td>';
                } else {
                    foreach ($data_category as $datacate) {
                        if ($datacate->descs == null) {
                            $list_hticket .= '<td></td>';
                        } else {
                            $list_hticket .= '<td>'.$datacate->descs.'</td>';
                        }
                    }
                }

                $list_hticket .= '<td>'.$tenant->work_requested.'</td>';
                $list_hticket .= '<td>'.date("d M Y", strtotime($tenant->reported_date)).'</td>';
                $list_hticket .= '<td>'.$tenant->serv_req_by.'</td>';
                $list_hticket .= '<td>'.$tenant->lot_no.'</td>';
                

                $data_status = $this->get_statusIFCA($tenant->status);
                // Tambahan Chargeable / Non-Chargeable
                $list_hticket .= '<td>'.$billingType.'</td>';

                $list_hticket .= '<td><span class="badge badge-sm badge-dim '.$data_status["color"].' d-none d-md-inline-flex">'.$data_status["status"]. '</span></td>';

                if($tenant->status=='R') {
                    $list_hticket .= '<td><button class="btn btn-block btn-warning btn-sm" onclick="location.href=\''. url('ticket').'/'.$tenant->id.'/'.'edit'.'\'"> Edit</button></td>';
                } else {
                    $list_hticket .= '<td></td>'."\n";
                }
                $list_hticket .= '</tr>';
                $i++;
            }
        }

        //Our Latest Overtime
        $today = date('Y-m-d h:i:s');
        $i = 1;
        $list_hovertime = "";

        $hovertime = DB::select("SELECT * from mgr.ot_trx where  id_tenant = '$id_tenant' AND status NOT IN ('X','Y','Z')");
        if (!empty($hovertime)) {
            foreach ($hovertime as $overtime)
            {
                $list_hovertime .= '<tr class="odd">';
                $list_hovertime .= '<td>'.$i.'</td>';
                $list_hovertime .= '<td>'.$overtime->id.'</td>';
                $list_hovertime .= '<td>'.date("d M Y", strtotime($overtime->date_created)) . '</td>';
                $list_hovertime .= '<td>'.$overtime->lot_no.'</td>';
                $list_hovertime .= '<td>'.$overtime->start_overtime.'</td>';
                $list_hovertime .= '<td>'.$overtime->end_overtime . '</td>';

                $data_status = $this->get_statusOT(
                    $overtime->status,
                    $overtime->start_overtime,
                    $overtime->end_overtime
                );
                $list_hovertime .= '<td><span class="badge badge-sm badge-dim '.$data_status["color"].' d-none d-md-inline-flex">'.$data_status["status"]. '</span></td>';
                if($overtime->start_overtime > $today && $overtime->status=='N') {
                    $list_hovertime .= '<td><button class="btn btn-block btn-danger btn-sm" onclick="changeStatus('.$overtime->id.')" data-ot="'.$overtime->id.'">Cancel</button></td>'."\n";
                } else {
                    $list_hovertime .= '<td><button class="btn btn-block btn-danger btn-sm disabled">Cancel</button></td>'."\n";
                }
                $list_hovertime .= '</tr>' . "\n";
                $i++;
            }
        }
        $dtnews = DB::select("SELECT TOP 5 * from mgr.newsfeed where active='1' and attach_type='P' and status = '1'");
        $content = array(
            'combolot' => $cbLot,
            'combometerid' => $cbMeterId,
            'totalOutstanding' => $totalOutstanding,
            'statusPembayaran' => $statusPembayaran,
            'list_bill' => $list_bill,
            'footer_bill' => $footer_bill,
            'list_hticket' => $list_hticket,
            'list_hovertime' => $list_hovertime,
            'tenant_flag'=>$tenant_flag,
            'dtnews'=>$dtnews
        );
        return view('dash/index', $content);
    }

    public function getGraph(Request $request)
    {
        if($_POST)
        {
            $tenant_no = Session::get('tenant_df');

            
            $lot_no = $request->lot_no;
            $m = array(1=>'Jan',
                2=>'Feb',
                3=>'Mar',
                4=>'Apr',
                5=>'May',
                6=>'Jun',
                7=>'Jul',
                8=>'Aug',
                9=>'Sep',
                10=>'Oct',
                11=>'Nov',
                12=>'Dec');
            $idm = array();
            $lm = array();
            $lu = array();
            $lh = array();

            $business_no = Session::get('business_no');
            $criteria = array(
                'business_no' => $business_no, 
                'tenant_no'   => $tenant_no
            );

            $dataTenancy = DB::table('mgr.pm_tenancy')
                ->where($criteria)
                ->get();
            if (!empty($dataTenancy))
            {
                $entity = $dataTenancy[0]->entity_cd;
                $project = $dataTenancy[0]->project_no;

                $dataEUsage = $this->getEusage_by_lotno($entity,$project,$tenant_no,$lot_no);
                //var_dump($dataEUsage);
                if (!empty($dataEUsage))
                {
                    foreach ($dataEUsage as $graph) {
                        $idm[] = $graph->meter_id;
                        $lm[] = $m[$graph->Monthly].' '.$graph->Yearly ;
                        $lu[] = $graph->usages;
                        $lh[] = $graph->usage_highs;
                    }
                    
                }
                $aDs = array(
                    array(
                        'label'=>"Lewat Waktu Beban Puncak 22:00 - 18:00",
                        'backgroundColor'=>"#48A497",
                        'strokeColor'=>"#48A4D1",
                        'pointColor'=>"#3b8bba",
                        'pointStrokeColor'=>"rgba(60,141,188,1)",
                        'pointHighlightFill'=>"#fff",
                        'pointHighlightStroke'=>"rgba(60,141,188,1)",
                        'data'=>$lu
                    ),
                    array(
                        'label'=>"Waktu Beban Puncak 18:00 - 22:00",
                        'backgroundColor'=>"rgba(73,188,170,0.4)",
                        'strokeColor'=>"rgba(72,174,209,0.4)",
                        'pointColor'=>"rgba(210, 214, 222, 1)",
                        'pointStrokeColor'=>"#c1c7d1",
                        'pointHighlightFill'=>"#fff",
                        'pointHighlightStroke'=>"rgba(220,220,220,1)",
                        'data'=>$lh
                    )
                );
                $aRet = array('meterid'=>$idm, 'chartdt'=>array('labels'=>$lm,'datasets'=>$aDs));
            } else {
                $aRet = array();
            }
            echo json_encode($aRet);
        }
    }

    public function getGraphMeterId(Request $request)
    {
        $tenant_no = Session::get('tenant_df');
        // $tenant_no = '001-BENTOEL';
        $meteridcombo = $request->meteridcombo;
        
        $m = array(1=>'Jan',
            2=>'Feb',
            3=>'Mar',
            4=>'Apr',
            5=>'May',
            6=>'Jun',
            7=>'Jul',
            8=>'Aug',
            9=>'Sep',
            10=>'Oct',
            11=>'Nov',
            12=>'Dec');
        $idm = array();
        $lm = array();
        $lu = array();
        

        $business_no = Session::get('business_no');
        // $business_no = '00000001';
        $criteria = array(
            'business_no' => $business_no, 
            'tenant_no'   => $tenant_no
        );

        $dataTenancy = DB::table('mgr.pm_tenancy')
            ->where($criteria)
            ->get();
        if (!empty($dataTenancy))
        {
            $entity = $dataTenancy[0]->entity_cd;
            $project = $dataTenancy[0]->project_no;

            // $dataEUsage = $this->getEusage_by_lotno($entity,$project,$tenant_no,$lot_no);
            $dataEUsage = $this->getEusage_by_MeterId($entity,$tenant_no,$meteridcombo);
            if (!empty($dataEUsage))
            {
                foreach ($dataEUsage as $graph) {
                    $idm[] = $graph->meter_id;
                    $lm[] = $m[$graph->Monthly].' '.$graph->Yearly ;
                    $lu[] = $graph->usages;
                }
                
            }
            $aDs = array(
                array(
                    'label' => 'Monthly Usage (kWh)',
            
                    // warna utama area & bar
                    'backgroundColor' => '#D1BF8F',
            
                    // garis line chart
                    'borderColor' => '#BDA870',
            
                    // titik line chart
                    'pointBackgroundColor' => '#BDA870',
            
                    // border titik
                    'pointBorderColor' => '#FFFFFF',
            
                    // hover
                    'pointHoverBackgroundColor' => '#E5D6AB',
                    'pointHoverBorderColor' => '#C7B582',
            
                    // border bar chart
                    'borderWidth' => 2,
            
                    'pointRadius' => 5,
                    'pointHoverRadius' => 7,
            
                    'fill' => true,
            
                    'data' => $lu
                )
            );
            $aRet = array('meterid'=>$idm, 'chartdt'=>array('labels'=>$lm,'datasets'=>$aDs));
        } else {
            $aRet = array();
        }
        echo json_encode($aRet);
    }

    public function getLotNo($tenant_no=null)
    {
        if(!empty($tenant_no))
        {
            $crit = array('tenant_no' => $tenant_no);
            $tenancy = DB::table('mgr.pm_tenancy')
                ->where($crit)
                ->get();
            $list_lot = '';
            if(!empty($tenancy)) 
            {
                $business_no = $tenancy[0]->business_no;
                $crit1 = array(
                    'entity_cd'=>$tenancy[0]->entity_cd,
                    'project_no'=>$tenancy[0]->project_no,
                    'business_id'=>$tenancy[0]->business_no,
                    'tenant_no'=>$tenant_no
                );

                $tenant_lot = DB::connection('MPP')
                    ->table('mgr.tenant_lot')
                    ->where($crit1)
                    ->get();

                if(!empty($tenant_lot)) {
                    foreach ($tenant_lot as $datalot) {
                        $list_lot.='<option value="'.$datalot->lot_no.'" >'.$datalot->descs.'</option>';
                    }
                }
            }
            return $list_lot;
        }
    }

    public function getMeterId($tenant_no = null)
    {
        if (!empty($tenant_no))
        {
            $crit = array('tenant_no' => $tenant_no);

            $tenancy = DB::table('mgr.pm_tenancy')
                ->where($crit)
                ->get();

            $list_meter = '';

            if (!empty($tenancy))
            {
                $crit1 = array(
                    'entity_cd'  => $tenancy[0]->entity_cd,
                    'project_no' => $tenancy[0]->project_no,
                    'business_id'=> $tenancy[0]->business_no,
                    'tenant_no'  => $tenant_no
                );

                $tenant_lot = DB::connection('MPP')
                    ->table('mgr.tenant_lot')
                    ->where($crit1)
                    ->get();

                if (!empty($tenant_lot))
                {
                    foreach ($tenant_lot as $datalot)
                    {
                        $meters = DB::connection('MPP')
                            ->table('mgr.pm_lot_meter_new')
                            ->where('lot_no', $datalot->lot_no)
                            ->orderBy('ref_no')
                            ->get();

                        foreach ($meters as $meter)
                        {
                            // $list_meter .= '<option value="' . $meter->meter_id . '">' .
                            //     $meter->meter_id .
                            //     ' - ' . $datalot->descs .
                            //     '</option>';
                            $list_meter .= '<option value="' . $meter->meter_id . '">' .
                                $meter->ref_no .
                                '</option>';
                        }
                    }
                }
            }

            return $list_meter;
        }

        return '';
    }

    public function getEusage_by_lotno($entity="", $project="", $tenant_no="", $lotno="")
    {
        $sql = "SELECT
            a.meter_id,
            DAY(a.read_date) AS Daily,
            MONTH(a.read_date) AS Monthly,
            YEAR(a.read_date) AS Yearly,
            a.usage AS usages,
            a.usage_high AS usage_highs,
            b.lot_no 
            FROM
            mgr.pm_meter_dtl a 
            INNER JOIN
                mgr.pm_lot_meter_new b 
                ON a.entity_cd = b.entity_cd
                AND a.project_no = b.project_no 
                AND a.meter_id = b.meter_id 
            INNER JOIN
                mgr.pl_project d 
                ON a.entity_cd = d.entity_cd
                AND a.project_no = d.project_no 
            WHERE a.meter_type='E' AND a.entity_cd='$entity' and b.debtor_acct='$tenant_no' AND b.lot_no='$lotno' ORDER BY a.read_date";

        
        $query = DB::connection('MPP')->select($sql);
        return $query;
    }

    public function getEusage_by_MeterId($entity="", $tenant_no="", $meterId="")
    {
        $sql = "SELECT
            a.meter_id,
            DAY(a.read_date) AS Daily,
            MONTH(a.read_date) AS Monthly,
            YEAR(a.read_date) AS Yearly,
            a.usage AS usages 
            FROM
            mgr.pm_meter_dtl a 
            INNER JOIN
                mgr.pm_lot_meter_new b 
                ON a.entity_cd = b.entity_cd
                AND a.project_no = b.project_no 
                AND a.meter_id = b.meter_id 
            INNER JOIN
                mgr.pl_project d 
                ON a.entity_cd = d.entity_cd
                AND a.project_no = d.project_no 
            WHERE a.meter_type='E' AND a.entity_cd='$entity' 
            AND b.debtor_acct='$tenant_no' 
            AND b.meter_id='$meterId'
            AND YEAR(a.read_date) = YEAR(getdate()) 
            ORDER BY Yearly ASC, Monthly ASC";

        
        $query = DB::connection('MPP')->select($sql);
        return $query;
    }

    public function get_soa_by_tenant($entity="", $project="", $tenant_no="", $date_until=null)
    {
        if(is_null($date_until)) {
            $today = date('d M Y H:i:s');
        } else {
            $today = date('d M Y H:i:s', strtotime($date_until));
        }

        $sql = "SELECT DISTINCT pp.descs AS prj_desc, ad.name, ad.address1, ad.address2, ad.address3, ad.post_cd, al.doc_no, al.due_date, al.descs AS ar_ldg_desc, al.fdoc_amt, sum(ac.trx_amt) AS alloc_amt, al.trx_mode, al.trx_type, al.entity_cd, al.project_no, al.debtor_acct, al.mcurr_cd, al.currency_cd, al.currency_rate, ars.age1, ars.age2, ars.age3, ars.age4, ars.age5, ars.age6, al.start_date, al.end_date, al.fbal_amt, al.old_ref_no, al.doc_date 
            FROM mgr.ar_ledger al
            INNER JOIN mgr.ar_debtor ad ON  al.entity_cd = ad.entity_cd AND al.project_no = ad.project_no AND al.debtor_acct = ad.debtor_acct
            INNER JOIN mgr.cf_entity ce 
            ON  al.entity_cd = ce.entity_cd AND al.mcurr_cd = ce.base_currency
            INNER JOIN mgr.pl_project pp
            ON  al.project_no = pp.project_no AND al.entity_cd = pp.entity_cd
            LEFT OUTER JOIN mgr.ar_alloc ac 
            ON  al.entity_cd = ac.entity_cd AND al.project_no = ac.project_no AND al.debtor_acct = ac.debtor_acct AND al.doc_no = ac.debit_doc AND al.doc_date = ac.debit_date AND al.trx_type = ac.debit_trx AND al.currency_cd = ac.mcurr_cd AND ac.trx_date <= getdate(), mgr.ar_spec ars
            WHERE al.class='I' AND al.debtor_acct = '$tenant_no' AND al.doc_date <= getdate() AND fbal_amt > 0
            GROUP BY pp.descs, ad.name, ad.address1, ad.address2, ad.address3, ad.post_cd, al.doc_no, al.due_date, al.descs, al.fdoc_amt, al.trx_mode, al.trx_type, al.entity_cd, al.project_no, al.debtor_acct, al.mcurr_cd, al.currency_cd, al.currency_rate, ars.age1, ars.age2, ars.age3, ars.age4, ars.age5, ars.age6, al.fbal_amt, al.old_ref_no, al.start_date,  al.end_date, al.doc_date  
            HAVING al.fdoc_amt - isnull(sum(ac.trx_amt),0) > 0";
        $query = DB::connection('MPP')->select($sql);
        return $query;
    }

    function get_statusIFCA($statusid="")
    {
        $color = '';
        $status = '';
        switch ($statusid) {
            case 'R':
                $status = "Open";
                $color = "badge-outline-info";
                break;
            case 'A':
                $status = "Accepted";
                $color = "badge-outline-info";
                break;
            case 'S':
                $status = "Survey";
                $color = "badge-outline-info";
                break;
            case 'P':
                $status = "Process";
                $color = "badge-outline-info";
                break;
            case 'F':
                $status = "Confirm";
                $color = "badge-outline-info";
                break;
            case 'M':
                $status = "Modify";
                $color = "badge-outline-info";
                break;
            case 'Z':
                $status = "Charged Approved";
                $color = "badge-outline-warning";
                break;
            case 'Y':
                $status = "Approve";
                $color = "badge-outline-success";         
                break;      
            case 'C':
                $status = "Close";
                $color = "badge-outline-success";
                break;
            case 'X':
                $status = "Cancel";
                $color = "badge-outline-default";         
                break;
        }

        if(!is_null($color)) {
            $rst = array(
                'color'=>$color,
                'status'=>$status
            );
            return $rst;
        } else {
            return '';
        }
    }

    // function get_statusOT($statusid="")
    // {
    //     $color = null;
    //     $status = null;
    //     switch ($statusid) {
    //         case 'N':
    //             $color = "badge-outline-info";
    //             $status ="Process";
    //             break;
    //         case 'A':
    //             $color = "badge-outline-success";
    //             $status ="Scheduled";
    //             break;
    //         case 'X':
    //             $color = "badge-outline-warning";
    //             $status = "Canceled";
    //             break;
    //         case 'Z':
    //             $color = "badge-outline-danger";
    //             $status = "Closed";
    //             break;
    //     }
        
    //     if(!is_null($color)) {
    //         $rst = array(
    //             'color'=>$color,
    //             'status'=>$status
    //         );
    //         return $rst;
    //     } else {
    //         return null;
    //     }
    // }

    function get_statusOT($statusid = "", $startOvertime = null, $endOvertime = null)
    {
        $now = date('Y-m-d H:i:s');

        switch ($statusid) {

            case 'N':
                return [
                    'color'  => 'badge-outline-info',
                    'status' => 'Process'
                ];

            case 'A':

                if ($now >= $startOvertime && $now <= $endOvertime) {
                    return [
                        'color'  => 'badge-outline-primary',
                        'status' => 'Activated'
                    ];
                }

                if ($now > $endOvertime) {
                    return [
                        'color'  => 'badge-outline-dark',
                        'status' => 'Ended'
                    ];
                }

                return [
                    'color'  => 'badge-outline-success',
                    'status' => 'Scheduled'
                ];

            case 'X':
                return [
                    'color'  => 'badge-outline-warning',
                    'status' => 'Canceled'
                ];

            case 'Z':
                return [
                    'color'  => 'badge-outline-danger',
                    'status' => 'Closed'
                ];
        }

        return null;
    }

    function gen(Request $request)
    {
        $file = $request->chart;
        $lot_no = $request->lot_no;
        $up = 'data://'.substr($file, 5);
        $bin = file_get_contents($up);
        $target_dir = './tenant/storage/file_generate/chart/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir);
        }
        $target_file = $target_dir . 'eu_'. Session::get('Tuser_id').'.png';

        $na = 'eu_'.Session::get('Tuser_id').'.png';
        file_put_contents($target_file, $bin);
        echo url('dash/export/'.$na.'/'.$lot_no);
    }

    function export($nm = null, $lot_no=null)
    {
        if(!empty($nm) && !empty($lot_no))
        {
            $tenant_no = Session::get('tenant_df');
            $business_no = Session::get('business_no');
            $criteria = array(
                'business_no' => $business_no, 
                'tenant_no' => $tenant_no
            );
            $dtaTenancy = DB::table('mgr.pm_tenancy')->where($criteria)->get();
            if(!empty($dtaTenancy))
            {
                $entity = $dtaTenancy[0]->entity_cd;
                $project = $dtaTenancy[0]->project_no;
                $dtaGra = $this->getEusage_by_lotno($entity,$project,$tenant_no,$lot_no);
                if(!empty($dtaGra))
                {
                    $le = '';
                    foreach ($dtaGra as $Eusage) {
                        $mn = date('M', mktime(0,0,0,$Eusage->Monthly,10)). ' '. $Eusage->Yearly;
                        $le.='<tr class="odd">';
                        $le.='<td align="center">'.$mn.'</td>';
                        $le.='<td align="center">'.number_format($Eusage->usages,2).'</td>';
                        $le.='<td align="center">'.number_format($Eusage->usage_highs,2).'</td>';
                        $le.='</tr>';
                    }
                    $name_file = 'Electric_Usage_'.$lot_no;
                    $nama_gbr = $nm;

                    $content = array(
                        'image' => $nama_gbr,
                        'cl' => $le
                    );

                    //return view('export/elchart', $content); //nyalakan kalo mau cek tampilannya

                    $pdf = PDF::loadView('export/elchart', $content)
                        ->setOptions([
                            'defaultFont' => 'sans-serif',
                            'isPhpEnabled' => false,
                            'isJavascriptEnabled' => true,
                            'isRemoteEnabled' => true,
                            'defaultPaperSize' => 'A4',
                        ]);
                    return $pdf->download($name_file.'.pdf');
                }
            }
        }
    }

    public function cancelOT(Request $request)
    {
        $id = $request->id;
        $msg = "";

        $data_overtime = DB::table('mgr.ot_trx')
            ->where('id', $id)
            ->get();
        if ($data_overtime) {
            $crit = array('id' => $id);
            $data = array('status' => 'X');

            $query = DB::table('mgr.ot_trx')
                ->where($crit)
                ->update($data);
            if ($query != "1") {
                $msg = $query;
                $st  = 'Fail';
            } else {
                $msg = "Data has been updated successfully";
                $st  = 'OK';
            }
        }

        $callback = array(
            "pesan" => $msg,
            "status" => $st
        );
        echo json_encode($callback);
    }
}
