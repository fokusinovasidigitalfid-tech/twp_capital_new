<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function getbyemail($email)
    {
        $tenant_no = Session::get('tenant_df');
        
        $data = DB::select(
            "SELECT a.*, t.name
             FROM all_login a
             INNER JOIN tenant t ON a.idforeign = t.id
             WHERE a.email = ?
               AND t.tenant_no_df = ?",
            [$email, $tenant_no]
        );
        
        echo json_encode($data);
    }
    public function savepic()
    {
        // $files = $_FILES;
        $picture = !empty($_FILES) ? $picture = $_FILES["userfile"] : '';
        if (!empty($picture["name"])) {
            $picname = str_replace(' ', '_', $picture["name"]);
            $picture = $_FILES["userfile"];
            $psn = '';
            $msg = '';
            $picture = array_filter($picture);

            $target_dir = "./img/user/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir);
            }
            $target_file = $target_dir . str_replace(' ', '_', basename($_FILES["userfile"]["name"]));
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            if ($_FILES["userfile"]["size"] > 5000000) {
                $msg = "Maximum file size is 5MB";
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
                if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $target_file)) {
                    $msg = "The file " . basename($_FILES["userfile"]["name"]) . " has been uploaded.";
                    $psn = "OK";
                    $descs = "/img/user/" . $picname;
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
        );
        echo json_encode($res);
    }
    public function updateprofile(Request $request)
    {
        $name       = $request->name;
        $telp       = $request->handphone;
        $images      = $request->labelimage;
        $email      = $request->email;
        $id_tenant = Session::get('Tuser_id');
        // dd($request->all());
        
        // die;
        if (filter_var($images, FILTER_VALIDATE_URL)||strpos($images, url('img/user/')) !== false) {
            $image = $images;
        } else {
            $image = url('img/user') . "/" . $images;
        }

        // var_dump($image);
        // die;
        $data = array(
            'name' => $name,
            'handphone' => $telp,
            'pict' => $image
        );
        $criteria = array('email' => $email);
        // $criteria = array('email' => $email, 'id' => $id_tenant );

        
        try { 
            
                DB::table('all_login')
                    ->where($criteria)
                    ->update($data);
                
                $msg = "Data has been updated successfully";
                $st  = 'OK';
             
        } catch(\Illuminate\Database\QueryException $ex){ 
            $msg = "Save failed: " . $ex->getMessage();
            $st  = 'Failed';
        }
        return response()->json([
            'status' => $st,
            'pesan' => $msg
        ]);
    }
    public function changepass(Request $request)
    {
        $password = md5($request->password);
        $data = array(
            'password' => $password
        );
        $criteria = array('email' => $request->email);

        // var_dump($criteria);exit();
        try { 
            
                DB::table('all_login')
                    ->where($criteria)
                    ->update($data);
                
                $msg = "Data has been updated successfully";
                $st  = 'OK';
             
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
