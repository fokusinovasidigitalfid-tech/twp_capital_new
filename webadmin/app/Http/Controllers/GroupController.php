<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use DataTables;
use Validator;

class GroupController extends Controller
{
    public function getTable()
    {
        $query = DB::connection('ifcaadm')->select('SELECT ROW_NUMBER() OVER (ORDER BY groupID ASC) AS [row_number], * from mgr.sysgroup');
        return DataTables::of($query)->make(true);
    }

    public function getByID($groupid = '')
    {
        $where = array('GroupID' => $groupid);
        $data = DB::connection('ifcaadm')
            ->table('mgr.sysgroup')
            ->where($where)
            ->get();
        echo json_encode($data);
    }

    public function save(Request $request)
    {
        $messages = [
            'required' => 'This field is required.',
        ];
        $validator = Validator::make($request->all(), [
            'txtGroupCD' => 'required',
            'txtGroupDescs' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'status' => 'Not Valid'
            ]);
        }

        if (empty($request->parent)) {
            $request->parent = 0;
        }

        if (empty($request->dashboard)) {
            $url = 'dash/tenant/';
        } else {
            $url = $request->dashboard;
        }
        $data = array(
            'group_cd' => $request->txtGroupCD,
            'group_descs' => $request->txtGroupDescs,
            'audit_user' => Session::get('Tsuname'),
            'audit_date' => date('d M Y H:i:s'),
            'dashboard_url' => $url
        );
        $criteria = array('GroupID' => $request->txtGroupID);
        try { 
            if ($request->txtGroupID > 0) { //update
                DB::connection('ifcaadm')
                    ->table('mgr.sysgroup')
                    ->where($criteria)
                    ->update($data);
                
                $msg = "Data has been updated successfully";
                $st  = 'OK';
                
            } else {//create
                DB::connection('ifcaadm')
                    ->table('mgr.sysgroup')
                    ->insert($data);
                $msg = "Data has been saved successfully";
                $st = 'OK';
                
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

    public function delete(Request $request)
    {
        // var_dump($request->id);exit();
       
        $criteria = array('groupID' => $request->id);
        try { 
            DB::connection('ifcaadm')
            ->table('mgr.sysgroup')
            ->where($criteria)
            ->delete();
            $msg = "Data has been deleted successfully";
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
}
