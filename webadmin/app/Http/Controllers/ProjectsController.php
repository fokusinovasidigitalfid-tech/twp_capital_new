<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use DataTables;
use Validator;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::connection('ifcaadm')->select('SELECT ROW_NUMBER() OVER (ORDER BY rowID ASC) AS [row_number], * from mgr.v_project');
            return DataTables::of($query)->make(true);
        }
        return view('project/index');
    }
    public function addproject($typeform = '', $id = '')
    {
       
        $dbprofile = DB::connection('ifcaadm')
            ->select('SELECT db_profile,db_name from mgr.pl_project ');
        if($typeform=='edit'){
            $tform = 'Edit Projects';
        }else{
            $tform = 'Registration New Project';
        }
        $data = array(
            'typeform'=>$typeform,
            'title1'=>$tform,
            'dbprofile'=>$dbprofile,
            'id'=>$id
        );
        return view('project/form', $data);
       
    }
    public function email_profile(Request $request)
    {
        $cons = $request->cons;
        $name = $request->name;
        // var_dump($cons, $name);
        // die;
        $list = '';
        $pilih = '';

        $data = DB::connection($cons)
            ->select('SELECT * from msdb.dbo.sysmail_profile');
        if (!empty($data)) {
            $list = '<option></option>';
            foreach ($data as $key) {
                if ($name === $key->name) {
                    $pilih = ' selected="1" ';
                } else {
                    $pilih = '';
                }
                $list .= '<option value="' . $key->name . '"' . $pilih . '>' . $key->name . '</option>';
            }
        }
        echo ($list);
    }
    public function zoom_entity(Request $request)
    {
        $cons = $request->cons;
        $ent = $request->ent;
        $list = '';
        $pilih = '';

        $data = DB::connection($cons)
            ->select('SELECT * from mgr.cf_entity');
        if (!empty($data)) {
            $list = '<option></option>';
            foreach ($data as $key) {
                if ($ent === $key->entity_cd) {
                    $pilih = ' selected="1" ';
                } else {
                    $pilih = '';
                }
                $list .= '<option value="' . $key->entity_cd . '" data-entname="' . $key->entity_name. '"' . $pilih . '>' . $key->entity_cd. '-' . $key->entity_name. '</option>';
            }
        }
        echo ($list);
    }
    public function zoom_project(Request $request)
    {
        $ent    = $request->ent;
        $cons   = $request->cons;
        $pro    = $request->pro;
        $comboProject[] = '';
        $pilih = '';
        $data = DB::connection($cons)
            ->select("SELECT * from mgr.pl_project where entity_cd='$ent'");
        if (!empty($data)) {
            $comboProject[] = '<option></option>';
            foreach ($data as $dtProject) {
                if ($pro == $dtProject->project_no) {
                    $pilih = ' selected="1" ';
                } else {
                    $pilih = '';
                }
                $comboProject[] = '<option value="' . $dtProject->project_no . '" data-prodescs="' . $dtProject->descs . '"' . $pilih . '>' . $dtProject->descs . '(' . $dtProject->project_no . ')' . '</option>';
            }
        }
        $comboProject = implode("", $comboProject);
        echo $comboProject;
    }
    public function getByID($rowID = '')
    {
        $where = array('rowID' => $rowID);
        $data = DB::connection('ifcaadm')
            ->table('mgr.pl_project')
            ->where($where)
            ->get();
        echo json_encode($data);
    }
    public function savePic()
    {
        $picture = !empty($_FILES) ? $picture = $_FILES["userfile"] : '';
        if (!empty($picture["name"])) {
            $picname = str_replace(' ', '_', $picture["name"]);
            $picture = $_FILES["userfile"];
            // var_dump($picture);exit();
            // $tmpName = $_FILES['userfile']['tmp_name'];
            // $imgString = file_get_contents($tmpName);
            // $imgData = bin2hex($imgString);
            // $imgbin = "0x" . $imgData;
            $psn = '';
            $msg = '';
            $picture = array_filter($picture);

            $target_dir = "./images/PlProject/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir);
            }
            // $target_file = $target_dir . basename($_FILES["userfile"]["name"]);
            $target_file = $target_dir . str_replace(' ', '_', basename($_FILES["userfile"]["name"]));
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            if ($_FILES["userfile"]["size"] > 5000000) {
                $msg = "Maximum file size is 5MB";
                $uploadOk = 0;
                $psn = 'failed';
                // return;
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
                // return;
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
                // $file->move($tujuan_upload,$file->getClientOriginalName());
                if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target_file)) {
                    $msg = "The file " . basename($_FILES["userfile"]["name"]) . " has been uploaded.";
                    $psn = "OK";
                    $descs = "/images/PlProject/" . $picname;
                    $url = url('/') . $descs;
                    // var_dump($url);exit();
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
        );
        echo json_encode($res);
    }
    public function save_regist(Request $request)
    {
        $msg            = '';
        $id             = $request->idproject;
        $entity_cd      = $request->entity_cd;
        $entity_name    = $request->entity_name;
        $project_no     = $request->project_no;
        $descs          = $request->descs;
        $caption_address = $request->caption_address;
        $coordinat_project = $request->coordinat_project;
        // $hp             = $request->hp;
        $db_profile     = $request->db_profile;
        $sysmail        = $request->sysmail;
        $db_name        = $request->db_name;
        $status         = $request->status;
        $pict_namee     = $request->picturepath1;
        $pict_proo      = $request->picturepath2;
        // $product_cd     = $request->product_cd;
        // $seq_no         = $request->seq_no;
        $audit_date     = date('d M Y H:i:s');
        $audit_user     = Session::get('Tsuser_id');
        $usergroup      = Session::get('Tsusergroup');
        // $email_user     = $request->email;
        // var_dump($pict_namee);var_dump($pict_proo);exit();
        $data = array(
            'entity_cd'     => $entity_cd,
            'entity_name'   => $entity_name,
            'project_no'    => $project_no,
            'descs'         => $descs,
            'picture_path'  => $pict_namee,
            'picture_url'   => $pict_proo,
            'coordinat_project' => $coordinat_project,
            'caption_address' => $caption_address,
            // 'handphone'     => $hp,
            'db_profile'    => $db_profile,
            'db_name'       => $db_name,
            // 'product_cd'    => $product_cd,
            'status'        => $status,
            'mailprofile_name'  => $sysmail,
            'audit_user'    => $audit_user,
            'audit_date'    => $audit_date//,
            // 'seq_no'        => $seq_no
        );

        $criteria = array('RowID' => $id);
        try { 
            if ($id > 0) { //update
                DB::connection('ifcaadm')
                    ->table('mgr.pl_project')
                    ->where($criteria)
                    ->update($data);
                
                $msg = "Data has been updated successfully";
                $st  = 'OK';
                
            } else {//create
                $sql = "SELECT count(*) as cnt FROM mgr.pl_project WHERE entity_cd='$entity_cd' and project_no='$project_no'";
                $cekproject = DB::connection('ifcaadm')->select($sql);
                $cnt = $cekproject[0]->cnt;
                if ($cnt > 0) {
                    $msg = "There's already a project with the same code (" . $project_no . ") in this entity (" . $entity_cd . "). Please use another code.";
                    $st = "Failed";
                    return response()->json([
                        'status' => $st,
                        'pesan' => $msg
                    ]);
                } else {
                    DB::connection('ifcaadm')
                        ->table('mgr.pl_project')
                        ->insert($data);
                    if ($usergroup == "ADMINWEB" || $usergroup == "ADMIN" ) {
                        $datacfs = array(
                            'entity_cd' => $entity_cd,
                            'project_no' => $project_no,
                            'userid' => $audit_user,
                            'audit_user' => $audit_user,
                            'audit_date' => $audit_date,
                            // 'email' => $email_user,
                            // 'db_profile' => $db_profile,
                        );

                        DB::connection('ifcaadm')->table('mgr.cfs_user_project')->insert($datacfs);
                    }
                    $msg = "Data has been saved successfully";
                    $st = 'OK';
                }
                
            }
        } catch(\Illuminate\Database\QueryException $ex){ 
            $msg = "Save failed: " . $ex->getMessage();
            $st  = 'Failed';
        }
        return response()->json([
            'status' => $st,
            'pesan' => $msg
        ]);
    }

}
