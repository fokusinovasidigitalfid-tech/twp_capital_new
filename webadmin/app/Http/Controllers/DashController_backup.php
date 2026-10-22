<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use DataTables;
use PDF;


class DashController extends Controller
{
    public function index($pdf=null)
    {       
        $jscript = '';
        $m = array(1=>"Jan",
            2=>"Feb",
            3=>"Mar",
            4=>"Apr",
            5=>"May",
            6=>"Jun",
            7=>"Jul",
            8=>"Aug",
            9=>"Sep",
            10=>"Oct",
            11=>"Nov",
            12=>"Dec");
        // $dt_Gra = $this->m_wsbangun->get_Eusage_summary();
        $dt_Gra = DB::connection('ifcapb')
        ->select("SELECT MONTH(a.read_date) AS Monthly, YEAR(a.read_date) AS Yearly, SUM(b.usage) AS usages, SUM(b.usage_high) AS usage_highs
            FROM mgr.pm_meter_hdr_debtor a
            INNER JOIN mgr.pm_meter_dtl b ON
            a.entity_cd = b.entity_cd AND
            a.project_no = b.project_no AND
            a.read_date = b.read_date
            INNER JOIN mgr.cf_entity c ON
            a.entity_cd = c.entity_cd
            INNER JOIN mgr.pl_project d ON
            a.entity_cd = d.entity_cd AND
            a.project_no = d.project_no
        WHERE a.pos_flag='P' AND a.meter_type='E' AND a.read_date>DATEADD(m,-4,GETDATE()) AND a.read_date <= GETDATE() 
        GROUP BY MONTH(a.read_date),YEAR(a.read_date)");
        $lm = array();
        $lu = array();
        $lh = array();
        if(!empty($dt_Gra))
        {
            foreach ($dt_Gra as $graph) {
                $lm[] =  $m[$graph->Monthly].' '.$graph->Yearly;
                if(!empty($graph->usages)){
                    $lu[] = $graph->usages;
                }else{
                    $lu[] = 0;
                }
                if(!empty($graph->usage_highs)){
                    $lh[] = $graph->usage_highs;
                }else{
                    $lh[] = 0;
                }

            }
        }
      
        $content = array(
                            'labels' => implode(",",$lm),
                            'data1'=> implode(",",$lu),
                            'data2'=> implode(",",$lh),
                        );
        return view('dash.dashboard',$content);
    }
    function generatepdf(Request $request)
    {
        $file = $request->file;
        // var_dump($file);exit();

        $up = 'data://'.substr($file, 5);
        // getcontent
        $bin = file_get_contents($up);
        if (!is_dir("./images/meterchart")) {
            mkdir("./images/meterchart");
        }
        $nm = './images/meterchart/eu_'.Session::get('Tsuser_id').'.png';
        $na = 'eu_'.Session::get('Tsuser_id').'.png';
        file_put_contents($nm, $bin);
        echo url('dash/export/'.$na);
 
    }
    function export($nm = null)
    {
        $dt_Gra = DB::connection('ifcapb')
        ->select("SELECT MONTH(a.read_date) AS Monthly, YEAR(a.read_date) AS Yearly, SUM(b.usage) AS usages, SUM(b.usage_high) AS usage_highs
            FROM mgr.pm_meter_hdr_debtor a
            INNER JOIN mgr.pm_meter_dtl b ON
            a.entity_cd = b.entity_cd AND
            a.project_no = b.project_no AND
            a.read_date = b.read_date
            INNER JOIN mgr.cf_entity c ON
            a.entity_cd = c.entity_cd
            INNER JOIN mgr.pl_project d ON
            a.entity_cd = d.entity_cd AND
            a.project_no = d.project_no
        WHERE a.pos_flag='P' AND a.meter_type='E' AND a.read_date>DATEADD(m,-4,GETDATE()) AND a.read_date <= GETDATE() 
        GROUP BY MONTH(a.read_date),YEAR(a.read_date)");
        
        if(!empty($dt_Gra)&& !empty($nm))
        {
            $le = '';
            foreach ($dt_Gra as $Eusage) {
                //$mn = date('F',mktime(0,0,0,$Eusage->Monthly,10));
                $mn = date('M',mktime(0,0,0,$Eusage->Monthly,10)) .' '.$Eusage->Yearly;
                $le.='<tr class="odd">';
                $le.='<td>'.$mn.'</td>';
                $le.='<td>'.number_format($Eusage->usages,2).'</td>';
                $le.='<td>'.number_format($Eusage->usage_highs,2).'</td>';
                $le.='</tr>';
            }
            $nf = 'summary_electric_usage';
       
            $na = url('images/meterchart/'.$nm);
            // var_dump($na);exit();
            $content = array('im'=>$na, 'cl'=>$le);
            // $html = $this->load->view('export/sumEusage',$content);
            // $html = $this->load->view('export/sumEusage',$content,true);
            $pdf = PDF::loadView('dash/exportpdf', $content);
            // pdfGen($html,$nf,"A4","portrait");
            // return $pdf->stream($nf.'.pdf');
            return $pdf->download($nf.'.pdf');
        } 
        // else {
        //     show_404();
        //     exit();
        // }        
    }
    public function getTableOT()
    {
         
        $query = DB::connection('ifcaadm')->select("SELECT @rownum := @rownum + 1 AS row_number, a.id,b.tenant_no,a.lot_no,a.start_overtime,a.end_overtime,a.description,a.status,a.approved FROM ot_trx a join pm_tenancy b on a.id_tenancy = b.id, (SELECT @rownum := 0) r where a.status not in ('X') order by start_overtime desc");

        return DataTables::of($query)->make(true);
    }
    public function getTableTicket()
    {
        //  var_dump(DB::connection('ifcapb')->getDatabaseName());exit();
        $query = DB::connection('ifcapb')->select("SELECT  ROW_NUMBER() OVER (ORDER BY complain_no ASC) AS [row_number], * FROM mgr.v_sv_entry_multi_dash order by reported_date desc");
        // var_dump(DB::connection('ifcapb')->getDatabaseName());exit();
        return DataTables::of($query)->make(true);
    }
    
}
