<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use DataTables;
use PDF;


class DashController extends Controller
{
    public function index($pdf = null)
    {
        $m = array(
            1=>"Jan", 2=>"Feb", 3=>"Mar", 4=>"Apr",
            5=>"May", 6=>"Jun", 7=>"Jul", 8=>"Aug",
            9=>"Sep", 10=>"Oct", 11=>"Nov", 12=>"Dec"
        );

        $year_list = DB::select("
            SELECT DISTINCT YEAR(read_date) AS year
            FROM mgr.pm_meter_dtl
            WHERE entity_cd = '0001'
            ORDER BY year DESC
        ");

        $years = [];

        foreach ($year_list as $row) {
            $years[] = $row->year;
        }
        $selectedYear = request('year', date('Y'));

        /*
        |--------------------------------------------------------------------------
        | GRAPH 1 : ELECTRIC USAGE
        |--------------------------------------------------------------------------
        */

        $dt_Gra = DB::connection('ifcapb')->select("
        SELECT
            MONTH(b.read_date) AS Monthly,
            YEAR(b.read_date) AS Yearly,
            SUM(b.usage) AS usages,
            SUM(b.usage_high) AS usage_highs
        FROM mgr.pm_meter_dtl b
        WHERE b.entity_cd = '0001'
        AND EXISTS (
            SELECT 1
            FROM mgr.pm_meter_hdr_debtor a
            WHERE a.entity_cd = b.entity_cd
            AND a.project_no = b.project_no
            AND a.read_date = b.read_date
            AND a.pos_flag = 'P'
            AND a.meter_type = 'E'
        )
        AND YEAR(b.read_date) = ?
        GROUP BY
            YEAR(b.read_date),
            MONTH(b.read_date)
        ORDER BY
            YEAR(b.read_date),
            MONTH(b.read_date)
        ", [$selectedYear]);

        $labels_usage = [];
        $usage = [];
        $usage_high = [];

        foreach ($dt_Gra as $row) {

            // $labels_usage[] = $m[$row->Monthly].' '.$row->Yearly;
            $labels_usage[] = date('M', mktime(0, 0, 0, $row->Monthly, 1, $row->Yearly));

            $usage[] = $row->usages ?? 0;
            $usage_high[] = $row->usage_highs ?? 0;
        }
        // $labels_usage = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        // $usage = array_fill(0, 12, 0);
        // $usage_high = array_fill(0, 12, 0);

        // foreach ($dt_Gra as $row) {

        //     $index = $row->Monthly - 1;

        //     $usage[$index] = (float)$row->usages;
        //     $usage_high[$index] = (float)$row->usage_highs;
        // }

        /*
        |--------------------------------------------------------------------------
        | GRAPH 2 : SERVICE STATUS
        |--------------------------------------------------------------------------
        */

        $dt_Status = DB::connection('ifcapb')->select("
        SELECT 
            MONTH(reported_date) AS Monthly,
            YEAR(reported_date) AS Yearly,
        
            SUM(CASE WHEN status = 'A' THEN 1 ELSE 0 END) AS assigned,
        
            SUM(CASE WHEN status IN ('P','S','M') THEN 1 ELSE 0 END) AS process,
        
            SUM(CASE WHEN status = 'F' THEN 1 ELSE 0 END) AS confirmm,
        
            SUM(CASE WHEN status = 'C' THEN 1 ELSE 0 END) AS closedd,
        
            COUNT(*) AS total_all
        
        FROM mgr.sv_entry_hd
        
        WHERE reported_date >= DATEFROMPARTS(
                YEAR(DATEADD(MONTH,-3,GETDATE())),
                MONTH(DATEADD(MONTH,-3,GETDATE())),
                1
        )
        
        AND entity_cd = '0001'
        
        GROUP BY
            MONTH(reported_date),
            YEAR(reported_date)
        
        ORDER BY
            YEAR(reported_date),
            MONTH(reported_date)
        ");

        $labels_status = [];
        $assigned = [];
        $process = [];
        $confirm = [];
        $closed = [];
        $total = [];

        foreach ($dt_Status as $row) {

            $labels_status[] = $m[$row->Monthly].' '.$row->Yearly;

            $assigned[] = $row->assigned ?? 0;
            $process[] = $row->process ?? 0;
            $confirm[] = $row->confirmm ?? 0;
            $closed[] = $row->closedd ?? 0;
            $total[] = $row->total_all ?? 0;
        }
        

        return view('dash.dashboard', [

            // GRAPH 1
            'labels_usage' => json_encode($labels_usage),
            'usage' => json_encode($usage),
            'usage_high' => json_encode($usage_high),

            // GRAPH 2
            'labels_status' => json_encode($labels_status),
            'assigned' => json_encode($assigned),
            'process' => json_encode($process),
            'confirm' => json_encode($confirm),
            'closed' => json_encode($closed),
            'total' => json_encode($total),
            'years' => $years,
            'selectedYear' => $selectedYear
        ]);
    }
    function generatepdf(Request $request)
    {
        $file = $request->file;
        // var_dump($file);exit();

        $up = 'data://'.substr($file, 5);
        // getcontent
        $bin = file_get_contents($up);
        if (!is_dir("./admin/images/meterchart")) {
            mkdir("./admin/images/meterchart");
        }
        $nm = './admin/images/meterchart/eu_'.Session::get('Tsuser_id').'.png';
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
         
        $query = DB::connection('ifcaadm')->select("SELECT ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS [row_number], a.id,b.tenant_no,a.lot_no,a.start_overtime,a.end_overtime,a.description,a.status,a.approved FROM mgr.ot_trx a join mgr.pm_tenancy b on a.id_tenancy = b.id where a.status not in ('X') order by start_overtime desc");

        return DataTables::of($query)->make(true);
    }
    // public function getTableTicket()
    // {
    //     //  var_dump(DB::connection('ifcapb')->getDatabaseName());exit();
    //     $query = DB::connection('ifcapb')->select("
    //         SELECT
    //             ROW_NUMBER() OVER (
    //                 ORDER BY reported_date DESC, complain_no DESC
    //             ) AS [row_number],
    //             *
    //         FROM mgr.v_sv_entry_multi_dash
    //         ORDER BY reported_date DESC, complain_no DESC
    //     ");
    //     // var_dump(DB::connection('ifcapb')->getDatabaseName());exit();
    //     return DataTables::of($query)->make(true);
    // }

    public function getTableTicket()
    {
        $query = DB::connection('ifcapb')->select("
            SELECT *
            FROM mgr.v_sv_entry_multi_dash
            ORDER BY reported_date DESC, complain_no DESC
        ");

        return DataTables::of($query)->make(true);
    }

    public function getElectricUsage(Request $request)
    {
        $selectedYear = $request->year;

        // query seperti yang sekarang
        /*
        |--------------------------------------------------------------------------
        | GRAPH 1 : ELECTRIC USAGE
        |--------------------------------------------------------------------------
        */

        $dt_Gra = DB::connection('ifcapb')->select("
        SELECT
            MONTH(b.read_date) AS Monthly,
            YEAR(b.read_date) AS Yearly,
            SUM(b.usage) AS usages,
            SUM(b.usage_high) AS usage_highs
        FROM mgr.pm_meter_dtl b
        WHERE b.entity_cd = '0001'
        AND EXISTS (
            SELECT 1
            FROM mgr.pm_meter_hdr_debtor a
            WHERE a.entity_cd = b.entity_cd
            AND a.project_no = b.project_no
            AND a.read_date = b.read_date
            AND a.pos_flag = 'P'
            AND a.meter_type = 'E'
        )
        AND YEAR(b.read_date) = ?
        GROUP BY
            YEAR(b.read_date),
            MONTH(b.read_date)
        ORDER BY
            YEAR(b.read_date),
            MONTH(b.read_date)
        ", [$selectedYear]);

        $labels_usage = [];
        $usage = [];
        $usage_high = [];

        foreach ($dt_Gra as $row) {

            // $labels_usage[] = $m[$row->Monthly].' '.$row->Yearly;
            $labels_usage[] = date('M', mktime(0, 0, 0, $row->Monthly, 1, $row->Yearly));

            $usage[] = $row->usages ?? 0;
            $usage_high[] = $row->usage_highs ?? 0;
        }
        

        return response()->json([
            'labels' => $labels_usage,
            'usage' => $usage,
            'usage_high' => $usage_high
        ]);
        // $labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        // $usage = array_fill(0, 12, 0);
        // $usageHigh = array_fill(0, 12, 0);

        // foreach ($dt_Gra as $row) {
        //     $index = $row->Monthly - 1;

        //     $usage[$index] = (float) $row->usages;
        //     $usageHigh[$index] = (float) $row->usage_highs;
        // }

        // return response()->json([
        //     'labels' => $labels,
        //     'usage' => $usage,
        //     'usage_high' => $usageHigh
        // ]);
    }
    
}
