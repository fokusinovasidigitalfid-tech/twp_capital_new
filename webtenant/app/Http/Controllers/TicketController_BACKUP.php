<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TicketController extends Controller
{
    public function index($id=null, $form=null)
    {
        $buss_id = Session::get('business_no');
        $tenant_no = Session::get('tenant_df');
        $crit = array(
            'business_no' => $buss_id,
            'tenant_no'   => $tenant_no
        );

        $data_tenancy = DB::table('pm_tenancy')->where($crit)->get();
        $combo_tenant='';
        if($data_tenancy){
            $combo_tenant = $this->get_combo($buss_id, $data_tenancy[0]->id);
        }

        $crit_spec = array(
            'entity_cd' => $data_tenancy[0]->entity_cd,
            'project_no' => $data_tenancy[0]->project_no
        );
        $dataspec = DB::connection('MPP')
            ->table('mgr.sv_spec')
            ->where($crit_spec)
            ->get();

        $complain_no = $dataspec[0]->complain_seq_no;

        if(empty($id) || empty($form))
        {
            $id   = '0';
            $jdl  = 'New Ticket';
            $form = 'add';
        } else {
            $id   = $id;
            $jdl  = 'Edit Ticket';
            $form = 'edit';
        }

        $content = array(
            'id' => $id,
            'jdl'=> $jdl,
            'form'=> $form,
            'combo_tenant' => $combo_tenant,
            //'link_cal' => $link_cal,
            'number' => $complain_no,
            //'error' => $data
        );
        //var_dump($content);
        return view('ticket/index', $content);
    }

    public function getByID($id = '')
    {
        $where = array('id' => $id);
        $data = DB::table('sv_entry_multi')->where($where)->get();
        echo json_encode($data);
    }

    public function getTicket($ent = '', $prj = '')
    {
        $where = array(
            'entity_cd' => $ent,
            'project_no' => $prj
        );
        $data = DB::connection('MPP')
                    ->table('mgr.sv_spec')
                    ->where($where)
                    ->get();
        echo json_encode($data);
    }

    public function getTicketNew(Request $request)
    {
        $entity_cd = $request->ent;
        $project_no = $request->prj;
        $where = array(
            'entity_cd' => $entity_cd,
            'project_no' => $project_no
        );
        $data = DB::connection('MPP')
                    ->table('mgr.sv_spec')
                    ->where($where)
                    ->get();
        if(!empty($data)) {
            foreach ($data as $key) {                    
                $list = $key->doc_prefix;
            }
            echo($list);
        }
    }

    public function getTicketPrefix($ent="", $prefix="")
    {
        // echo date("Y");
        $crit_cat = array(
            'entity_cd' => $ent,
            'prefix' => $prefix,
            'year'  => date("Y")
        );
        $data_cat = DB::connection('MPP')
            ->table('mgr.cf_document_ctl_dtl')
            ->where($crit_cat)
            ->get();
        $next_doc_no = $data_cat[0]->next_doc_no;
        echo ($next_doc_no);
    }

    function get_combo($business_no = "", $selected_id = "")
    {
        $where = array('business_no'=> $business_no,
                'project_no'=>'0002');

        $query = DB::table('pm_tenancy')->where($where)->get();
        $combo[] = '<option></option>';
        $combo[] = "\n";
        foreach ($query as $result) {
            $value = $result->tenant_no;
            // $value = $result->tenant_no.' ('.$result->project_desc.')';
            $combo[] = '<option value="' . $result->id . '" data-entity="'.$result->entity_cd.'" data-project="'.$result->project_no.'" '. '>' . $value . '</option>';
            $combo[] = "\n";
        }
        return implode("", $combo);
    }

    public function getCat(Request $request)
    {
        if($_POST)
        {
            $ticket_type = $request->ticket_type;
            if (is_null($ticket_type) || !isset($ticket_type))
            {
                echo('<option></option>');
            } else {
                $crit_cat = array('complain_type' => $ticket_type);
                $data_cat = DB::connection('MPP')
                    ->table('mgr.sv_category')
                    ->where($crit_cat)
                    ->get();

                if(!empty($data_cat)) {
                    $list = '<option></option>';
                    foreach ($data_cat as $key) {                    
                        $list .= '<option value="' . $key->category_cd . '" data-ticketname="' . $key->descs . '"' . '>' . $key->descs . '</option>';
                    }
                    echo($list);
                }
            }
        }
    }

    public function getCatEdit($complain_type, $category_cd)
    {
        $crit_cat = array('complain_type' => $complain_type);
        $data_cat = DB::connection('MPP')
            ->table('mgr.sv_category')
            ->where($crit_cat)
            ->get();

        if(!empty($data_cat)) {
            $list = '<option></option>';
            foreach ($data_cat as $key) {
                if ($category_cd == $key->category_cd) {
                    $pilih = ' selected = "1"';
                } else {
                    $pilih = '';
                }                  
                $list .= '<option ' . $pilih . ' value="' . $key->category_cd . '" data-ticketname="' . $key->descs . '"' . '>' . $key->descs . '</option>';
            }
            echo json_encode($list);
        }
    }

    public function getLotNo(Request $request)
    {
        if($_POST)
        {
            $id_tenancy = $request->id_tenancy;
            if (is_null($id_tenancy) || !isset($id_tenancy)) {
                echo('<option></option>');
            } else {
                $data_tenancy = DB::table('pm_tenancy')
                    ->where('id', $id_tenancy)
                    ->get();

                $entity = $data_tenancy[0]->entity_cd;
                $project = $data_tenancy[0]->project_no;
                $tenant_no = $data_tenancy[0]->tenant_no;

                if ($tenant_no == 'BM') {
                    // Ambil semua lot untuk BM (tidak perlu filter tenant)
                    $tenant_lot = DB::connection('MPP')
                        ->table('mgr.pm_lot')
                        ->where([
                            ['entity_cd', '=', $entity],
                            ['project_no', '=', $project],
                        ])
                        ->orderBy('lot_no', 'ASC')
                        ->get();
                } else {
                    // Join ke pm_tenant_lot untuk dapat lot yang terkait tenant_no tertentu
                    $tenant_lot = DB::connection('MPP')
                        ->table('mgr.pm_lot AS l')
                        ->join('mgr.pm_tenant_lot AS tl', function ($join) {
                            $join->on('l.entity_cd', '=', 'tl.entity_cd')
                                ->on('l.project_no', '=', 'tl.project_no')
                                ->on('l.lot_no', '=', 'tl.lot_no');
                        })
                        ->where([
                            ['l.entity_cd', '=', $entity],
                            ['l.project_no', '=', $project],
                            ['tl.tenant_no', '=', $tenant_no],
                        ])
                        ->select(
                            'l.lot_no',
                            'l.descs',
                            'l.level_no',
                            'tl.tenant_no'
                        )
                        ->orderBy('l.lot_no', 'ASC')
                        ->get();
                }

                // Generate list <option>
                if (!$tenant_lot->isEmpty()) {
                    $list_lot = '<option></option>';
                    foreach ($tenant_lot as $datalot) {
                        $list_lot .= '<option data-level="' . $datalot->level_no . '" value="' . $datalot->lot_no . '">' . $datalot->descs . '</option>';
                    }
                    echo $list_lot;
                } else {
                    echo '<option value="">No lot available</option>';
                }
            }
        }      
    }

    public function getLotnoEdit($tenant_no, $lot_no)
    {
        $data_tenancy = DB::table('pm_tenancy')
            ->where('id', $tenant_no)
            ->get();

        $entity = $data_tenancy[0]->entity_cd;
        $project = $data_tenancy[0]->project_no;
        $tenancy = $data_tenancy[0]->tenant_no;
        
        if ($tenant_no=='BM') {
            $like = array(
                'entity_cd'  => $entity,
                'project_no' => $project,
            );
            $tenant_lot = DB::connection('MPP')
            ->table('mgr.pm_lot')
            ->where($like)
            ->orderBy('lot_no', 'ASC')
            ->get();
        } else {
            $like = array(
                'tenant_no' => $tenancy,
                'entity_cd'  => $entity,
                'project_no' => $project,
            );
            $tenant_lot = DB::connection('MPP')
                        ->table('mgr.pm_lot AS l')
                        ->join('mgr.pm_tenant_lot AS tl', function ($join) {
                            $join->on('l.entity_cd', '=', 'tl.entity_cd')
                                ->on('l.project_no', '=', 'tl.project_no')
                                ->on('l.lot_no', '=', 'tl.lot_no');
                        })
                        ->where([
                            ['l.entity_cd', '=', $entity],
                            ['l.project_no', '=', $project],
                            ['tl.tenant_no', '=', $tenancy],
                        ])
                        ->select(
                            'l.lot_no',
                            'l.descs',
                            'l.level_no',
                            'tl.tenant_no'
                        )
                        ->orderBy('l.lot_no', 'ASC')
                        ->get();
        }
            // var_dump($tenant_lot);

        if(!empty($tenant_lot)) {
            $list_lot = '<option></option>';
            foreach ($tenant_lot as $datalot) {
                
                if ($lot_no == $datalot->lot_no) {
                    $pilih = ' selected = "1"';
                } else {
                    $pilih = '';
                }
                $list_lot.='<option data-level="'.$datalot->level_no.'" ' . $pilih . ' value="'.$datalot->lot_no.'" >'.$datalot->descs.'</option>';
            }
            // echo json_encode($list_lot);
            echo json_encode($list_lot, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        }
    }

    public function savepic(Request $request)
    {
        $files = $_FILES;
        $picture = !empty($_FILES) ? $picture = $_FILES["ticket_image"] : '';
        if (!empty($picture["name"])) {
            $picname = str_replace(' ', '_', $picture["name"]);
            $picture = $_FILES["ticket_image"];
            $tmpName = $_FILES['ticket_image']['tmp_name'];
            $imgString = file_get_contents($tmpName);
            $imgData = bin2hex($imgString);
            $imgbin = "0x" . $imgData;
            $psn = '';
            $msg = '';
            $picture = array_filter($picture);

            $target_dir = "./storage/file_ticket/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir);
            }
            $target_file = $target_dir . str_replace(' ', '_', basename($_FILES["ticket_image"]["name"]));
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            if ($_FILES["ticket_image"]["size"] > 2000000) {
                $msg = "Maximum file size is 2MB";
                $uploadOk = 0;
                $psn = 'failed';
                $res = array("pesan" => $msg, "status" => $psn);

                echo json_encode($res);
                exit();
            }

            $imageFileType = strtolower($imageFileType);
            // Allow certain file formats
            if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" && $imageFileType != "JPG"
            ) {
                $msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
                $psn = 'failed';
                $res = array("pesan" => $msg, "status" => $psn);

                echo json_encode($res);
                exit();
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $msg = "Sorry, your file was not uploaded.";
                $psn = "Failed";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["ticket_image"]["tmp_name"], $target_file)) {
                    $msg = "The file " . basename($_FILES["ticket_image"]["name"]) . " has been uploaded.";
                    $psn = "OK";
                    $descs = "/storage/file_ticket/" . $picname;
                    $url = url('/') . $descs;
                } else {
                    $msg = "Sorry, there was an error uploading your file.";
                    $psn = "Failed";
                }
            }
        } else {
            $msg = "Sorry, there was an error uploading your file.";
            $psn = "Failed";
        }
        $res = array(
            'pesan' => $msg,
            'status' => $psn,
            'url' => $url,
            'picname' => $picname,
            'pic_attached' => $imgbin
        );
        echo json_encode($res);
    }

    public function save(Request $request)
    {
        // Gunakan 2 koneksi
        $mainConn = DB::connection(); // default
        $liveConn = DB::connection('MPP');
        try {
            // === semua kode kamu dimasukkan di dalam try ===
            $mainConn->beginTransaction();
            $liveConn->beginTransaction();

            $msg = "";
            $id = $request->id;
            $number = $request->angka;
            $pre = $request->pre;
            $ticket_type = $request->ticket_type;
            $tenant_no = $request->tenant_no;
            $lot_no = $request->lot_no;
            $floor = $request->floor;
            $location = $request->location;
            $req_by = $request->req_by;
            $contact_no = $request->contact_no;
            $category = $request->category;
            $description = $request->description;
            $picture = $request->picturepath;
            $pic_attached = $request->pictureattach;
            $entity = $request->entity;
            $project = $request->project;
            $webuser = 'TWP';

            // --- tambahkan log agar tahu posisi ---
            \Log::info('SAVE START', ['id' => $id, 'tenant_no' => $tenant_no, 'entity' => $entity]);

            $crit_spec = ['category_cd' => $category];
            $dataspec = DB::connection('MPP')
                ->table('mgr.sv_category')
                ->where($crit_spec)
                ->get();

            $assign_to = $dataspec[0]->user_spv ?? null;

            
            $data_tenant = DB::table('pm_tenancy')->where('id', $tenant_no)->get();
            if ($data_tenant->isEmpty()) {
                throw new \Exception("Tenant tidak ditemukan: $tenant_no");
            }

            $dataopen = DB::connection('MPP')
                ->table('mgr.cf_document_ctl')
                ->where(['entity_cd' => $entity, 'prefix' => $pre])
                ->get();
            if ($dataopen->isEmpty()) {
                throw new \Exception("Document control tidak ditemukan untuk $entity / $pre");
            }

            $next_doc_noSave = $dataopen[0]->next_doc_no;
            $Type_format1 = $dataopen[0]->type_format;

            $dataopen2 = DB::connection('MPP')
                ->table('mgr.cf_document_format')
                ->where(['rowId' => $next_doc_noSave, 'type_format' => $Type_format1])
                ->get();

            if ($dataopen2->isEmpty()) {
                throw new \Exception("Document format tidak ditemukan (rowId=$next_doc_noSave, type_format=$Type_format1)");
            }

            $typeformat2 = $dataopen2[0]->format;
            $year = date('y');
            $month = date('m');

            if (strpos($typeformat2, 'PP') !== false) $typeformat2 = str_replace('PP', $pre, $typeformat2);
            if (strpos($typeformat2, 'YY') !== false) $typeformat2 = str_replace('YY', $year, $typeformat2);
            if (strpos($typeformat2, 'MM') !== false) $typeformat2 = str_replace('MM', $month, $typeformat2);
            if (strpos($typeformat2, 'N') !== false) {
                $countN = substr_count($typeformat2, "N");
                $del = trim($typeformat2, "N");
                $change = str_pad($number, $countN, '0', STR_PAD_LEFT);
                $typeformat2 = $del . $change;
            }

            // --- log nomor tiket ---
            \Log::info('Generated complain_no', ['complain_no' => $typeformat2]);

            // (lanjutkan semua kode insert/update kamu di sini tanpa ubahan)
            // ...
            $data = array(
                'complain_no'     => $typeformat2,
                'id_tenant'       => Session::get('Tuser_id'),
                'reported_by'     => $webuser,
                'reported_date'   => date('Y-m-d'),
                'serv_req_by'     => $req_by,
                'location'        => $location,
                'floor'           => $floor,
                'contact_no'      => $contact_no,
                'billing_type'    => 'T',
                'complain_type'   => $ticket_type,
                'complain_source' => '01',
                'category_cd'     => $category,
                'lot_no'          => $lot_no,
                'status'          => 'R',
                'work_requested'  => $description,
                'id_tenancy'      => $tenant_no,
                'tenant_no'       => $data_tenant[0]->tenant_no,
                'picture'         => $picture,
                'entity_cd'       => $entity,
                'project_no'      => $project
            );

            $critedit = array('id' => $id);
            $crit = array('complain_no' => $typeformat2,);
            $complain = DB::table('sv_entry_multi')
                ->where($crit)
                ->get();

            if (count($complain) > 0)
            {
                DB::table('sv_entry_multi')
                    ->where($critedit)
                    ->update($data);
            } else {
                DB::table('sv_entry_multi')
                    ->insert($data);
            }             

            $dataServ1 = array(
                'entity_cd'       => $entity,
                'project_no'      => $project,
                'debtor_acct'     => $data_tenant[0]->tenant_no,
                'complain_no'     => $typeformat2,
                'reported_by'     => $webuser,
                'reported_date'   => date('d M Y H:i:s'),
                'serv_req_by'     => $req_by,
                'work_requested'  => $description,
                'location'        => $location,
                'floor'           => $floor,
                'contact_no'      => $contact_no,
                'billing_type'    => 'T',
                'status'          => 'R',
                'complain_source' => '01',
                'lot_no'          => $lot_no,
                'complain_type'   => $ticket_type,
                'category_cd'     => $category,
                'post_status'     => 'N',
                'audit_user'      => 'MGR',
                'audit_date'      => date('d M Y H:i:s'),
                // 'file_attachment' => $picture,
                // 'file_attached'   => $pic_attached
            );

            $critedit2 = array(
                'entity_cd'   => $entity,
                'project_no'  => $project,
                'complain_no' => $typeformat2
            );
            if (!$id)
            {
                DB::connection('MPP')
                    ->table('mgr.sv_entry_multi')
                    ->insert($dataServ1);
            } else {
                DB::connection('MPP')
                    ->table('mgr.sv_entry_multi')
                    ->where($critedit2)
                    ->update($dataServ1);
            }

            $dataServ2 = array(
                'entity_cd'      => $entity,
                'project_no'     => $project,
                'debtor_acct'    => $data_tenant[0]->tenant_no,
                'complain_no'    => $typeformat2,
                'seq_no'         => 1,
                'reported_by'    => $webuser,
                'reported_date'  => date('d M Y H:i:s'),
                'work_requested' => $description,
                'serv_req_by'    => $req_by,
                'location'       => $location,
                'floor'          => $floor,
                'contact_no'     => $contact_no,
                'billing_type'   => 'T',
                'status'         => 'A',
                'complain_source'=> '01',
                'assign_to'      => $assign_to,
                'audit_user'     => 'MGR',
                'audit_date'     => date('d M Y H:i:s')
            );

            if (!$id) {
                $query = DB::connection('MPP')
                    ->table('mgr.sv_entry_multi_dt')
                    ->insert($dataServ2);

                if ($query != "OK") {
                    $msg = $query;
                    $st = 'Fail';
                } else {
                    $msg = "Data has been saved successfully";
                    $st = 'OK';
                }

            } else {
                $query = DB::connection('MPP')
                    ->table('mgr.sv_entry_multi_dt')
                    ->where($critedit2)
                    ->update($dataServ2);

                if ($query != "1") {
                    $msg = $query;
                    $st = 'Fail';
                } else {
                    $msg = "Data has been updated successfully";
                    $st = 'OK';
                }
            }

            if (!$id) {
                $autonum = $number + 1;
                $dataCompl = array('complain_seq_no' => $autonum);
                $crit = array(
                    'entity_cd'=>$entity,
                    'project_no'=>$project
                );


                DB::connection('MPP')
                    ->table('mgr.sv_spec')
                    ->where($crit)
                    ->update($dataCompl);

                $dataCompl2 = array('next_doc_no' => $autonum);
                $crit2 = array(
                    'entity_cd'=>$entity,
                    'prefix'=>$pre,
                    'year'  => date("Y")
                );

                DB::connection('MPP')
                    ->table('mgr.cf_document_ctl_dtl')
                    ->where($crit2)
                    ->update($dataCompl2);
            }

        
            // bagian send email
            $crit_spec = array(
                'entity_cd'=>$entity,
                'project_no'=>$project
            );

            $dataspec = DB::connection('MPP')
                ->table('mgr.sv_spec')
                ->where($crit_spec)
                ->get();

            if (!empty($dataspec)){
                $email = $dataspec[0]->email_helpdesk;
            } else {
                $email = 'deska.priyanti@ifca.co.id';
            }
            $body = "";
            $body.= '<h3>Hi Helpdesk, </h3>';
            $body.= 'Ticket '.$number. ' has been submit in queue, please assign ticket to PIC :'."<br><br>";
            $body.= Session::get('TCompany').' wrote : '."<br>";
            $body.= $description.' '."<br><br>";
            $body.='TWP System<br>';
            
            $subj = 'Ticket number '.$number.' opened';
            DB::connection('MPP')->statement("exec mgr.x_send_mail_twp '$email','$subj','$body'");

            $callback = array(
                "pesan" => $msg,
                "status" => $st
            );
            
            // Di akhir tetap balikan JSON:

            return response()->json([
                "pesan" => $msg ?: "Data berhasil disimpan",
                "status" => $st ?? 'OK'
            ]);

        } catch (\Throwable $e) {
            \Log::error('Error in save(): ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Fail',
                'pesan' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
