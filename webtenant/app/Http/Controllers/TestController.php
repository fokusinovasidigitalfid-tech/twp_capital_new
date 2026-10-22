<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TestController extends Controller {

    public function index($id=null, $form=null){
        $entity = '0001';
        $pre = 'SR';

        $dataopenArray = array (
            'entity_cd' => $entity,
            'prefix' => $pre
        );


        $dataopen = DB::connection('MPP')
            ->table('mgr.cf_document_ctl')
            ->where($dataopenArray)
            ->get();

        $next_doc_noSave = $dataopen[0]->next_doc_no;

        $Type_format1 = $dataopen[0]->type_format;

        $dataopen2Array = array (
            'rowId' => $next_doc_noSave,
            'type_format' => $Type_format1
        );


        $dataopen2 = DB::connection('MPP')
            ->table('mgr.cf_document_format')
            ->where($dataopen2Array)
            ->get();

        $typeformat2 = $dataopen2[0]->format;
        $ticketFormat = 'PPYYMMNNNN';    
        $pre = 'SR';
        $number = '18';
        $year = date('y');
        $month = date('m');
        if (strpos($typeformat2, 'PP') !== false){
            $typeformat2 = str_replace('PP', $pre, $typeformat2);
        }
        if (strpos($typeformat2, 'YY') !== false){
            $typeformat2 = str_replace('YY', $year, $typeformat2);
        }
        if (strpos($typeformat2, 'MM') !== false){
            $typeformat2 = str_replace('MM', $month, $typeformat2);
        }
        if (strpos($typeformat2, 'N') !== false){
            $countN = substr_count($typeformat2,"N");
            $del = trim($typeformat2,"N");
            $change = str_pad($number, $countN, '0', STR_PAD_LEFT);
            $typeformat2 = $del.$change;
        }

        var_dump($typeformat2);
    }

}