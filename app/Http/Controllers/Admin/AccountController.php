<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use DataTables;

class AccountController extends Controller
{
    public function getTable()
    {
        $query = DB::connection('ifcaadm')->select("
            SELECT
                ROW_NUMBER() OVER (ORDER BY (SELECT NULL)) AS [row_number],
                a.*,
                t.*
            FROM mgr.all_login a
            INNER JOIN mgr.tenant t
                ON a.email = t.email
            WHERE t.email <> 'ahmad.ariffandy@ifca.co.id'
        ");

        return DataTables::of($query)->make(true);
    }

    public function getbyemail($email)
    {
        $data = DB::connection('ifcaadm')
            ->select("SELECT * from mgr.all_login where email=?", [$email]);
        echo json_encode($data);
    }
    public function savepic()
    {
        // $files = $_FILES;
        $picture = !empty($_FILES) ? $picture = $_FILES["userfile"] : '';
        if (!empty($picture["name"])) {
            $picname = str_replace(' ', '_', $picture["name"]);
            $picture = $_FILES["userfile"];
            // $tmpName = $_FILES['userfile']['tmp_name'];
            // $imgString = file_get_contents($tmpName);
            // $imgData = bin2hex($imgString);
            // $imgbin = "0x" . $imgData;
            $psn = '';
            $msg = '';
            $picture = array_filter($picture);

            $target_dir = "./admin/images/user/";
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
                    $descs = "/images/user/" . $picname;
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
        // dd($request->all());
        
        // die;
        if (strpos($images, url('images/user/')) !== false) {
            $image = $images;
        } else {
            $image = url('images/user') . "/" . $images;
        }

        // var_dump($image);
        // die;
        $data = array(
            'name' => $name,
            'handphone' => $telp,
            'pict' => $image
        );
        $criteria = array('email' => $email);

        
        try { 
            
                DB::connection('ifcaadm')
                    ->table('mgr.all_login')
                    ->where($criteria)
                    ->update($data);
                
                $msg = "Data has been updated successfully";
                $st  = 'OK';
             
        } catch(\Illuminate\Database\QueryException $ex){ 
            report($ex); // detail error -> storage/logs/laravel-{tanggal}.log
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
        // $email2 = strtoupper(md5(strtolower($request->email)));
        //     $password2 = strtoupper(md5($request->password));
        //     $emailpassword = strtoupper(md5($email2.'P@ssw0rd'.$password2));
        $password = md5($request->password);
        $data = array(
            'password' => $password
        );
        $criteria = array('email' => $request->email);

        // var_dump($criteria);exit();
        try { 
            
                DB::connection('ifcaadm')
                    ->table('mgr.all_login')
                    ->where($criteria)
                    ->update($data);
                
                $msg = "Data has been updated successfully";
                $st  = 'OK';
             
        } catch(\Illuminate\Database\QueryException $ex){ 
            report($ex); // detail error -> storage/logs/laravel-{tanggal}.log
            $msg = "Save failed: " . $ex->getMessage();
            $st  = 'Failed';
        }
        return response()->json([
            'status' => $st,
            'pesan' => $msg
        ]);
    }
    public function resetpass(Request $request)
    {
        $password_default = 'Capital123';
        $password = md5($password_default);
        
        // var_dump($request->all());exit;
        // $emailsend = 'deskaruwanda@gmail.com';
        $emailsend = $request->email;
        $subj = "Replacement login information for ". $request->name;
        $body ="";
        $body.='<h3>Dear '.$request->name.', '."</h3>";
        $body.='A request to reset the password for your account has been made at TWP.'."<br>";
        $body.='Your new password is '.$password_default.". <br><br>";
        $body.='TWP System,<br>';
        $body.='Administrator';
        try { 
            $criteria = array('email' => $request->email);
            $data = array(
                'password' => $password
            );
                DB::connection('ifcaadm')
                    ->table('mgr.all_login')
                    ->where($criteria)
                    ->update($data);
                if (!config('app.send_mail_twp')) {
                    \Log::info('[SEND_MAIL_TWP=false] x_send_mail_twp skipped', ['to' => $emailsend, 'subject' => $subj]);
                } else {
                    DB::connection('ifcapb')->statement("exec mgr.x_send_mail_twp ?,?,?", [$emailsend, $subj, $body]);
                }
                $msg = "Data has been updated successfully";
                $st  = 'OK';
             
        } catch(\Illuminate\Database\QueryException $ex){ 
            report($ex); // detail error -> storage/logs/laravel-{tanggal}.log
            $msg = "Save failed: " . $ex->getMessage();
            $st  = 'Failed';
        }
        return response()->json([
            'status' => $st,
            'pesan' => $msg
        ]);
    }
}
