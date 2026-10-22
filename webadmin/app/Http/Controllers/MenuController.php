<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use DataTables;
use Validator;

class MenuController extends Controller
{
    public function form(){
        
        $where = array('ParentMenuID' => 0 );
        $MenuData = DB::connection('ifcaadm')
        ->table('mgr.sysmenu')
        ->where($where)
        ->get();
        $content = array('menuData'=>$MenuData);
        return view('menu.form',$content);

    }
    public function getTable()
    {
        $query = DB::connection('ifcaadm')->select('SELECT ROW_NUMBER() OVER (ORDER BY menuID ASC) AS [row_number], * from mgr.v_sysMenu');
        return DataTables::of($query)->make(true);
    }
    public function getByID($id = '')
    {
        $where = array('menuID' => $id);
        $data = DB::connection('ifcaadm')
            ->table('mgr.sysmenu')
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
            'title' => 'required',
            'OrderSeq' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'status' => 'Not Valid'
            ]);
        }

        if (empty($request->ParentMenuID)) {
            $request->ParentMenuID = 0;
        }

        $data = array(
            'Title' => $request->title,
            'URL' => $request->url,
            'ParentMenuID' =>$request->ParentMenuID,
            'IconClass' =>$request->IconClass,                        
            'OrderSeq'=>$request->OrderSeq,
            'audit_user' => Session::get('Tsuname'),
            'audit_date' => date('d M Y H:i:s')
        );
        $criteria = array('menuID' => $request->MenuID);
        try { 
            if ($request->MenuID > 0) { //update
                DB::connection('ifcaadm')
                    ->table('mgr.sysmenu')
                    ->where($criteria)
                    ->update($data);
                
                $msg = "Data has been updated successfully";
                $st  = 'OK';
                
            } else {//create
                DB::connection('ifcaadm')
                    ->table('mgr.sysmenu')
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
    public function assign()
    {
        $query = DB::connection('ifcaadm')
            ->select("SELECT DISTINCT group_cd,group_descs FROM mgr.sysGroup(nolock)");
        $content = array('cmbGroup' => $query);
        return view('menu.index_assign', $content);
    }
    public function getTableAssign(Request $request)
    {
        if ($request->ajax()) {
            // $query = DB::connection('IFCAADM')
            // 	->select('SELECT ROW_NUMBER() OVER (ORDER BY MenuID) AS [row_number], * FROM mgr.sysmenu');
            $query = DB::connection('ifcaadm')
                ->select("WITH cteMenu(MenuID, Title, URL, ParentMenuID, IconClass, OrderSeq, [Level], Path)
                    AS (SELECT MenuID, Title, URL, ParentMenuID, IconClass, OrderSeq, 0 as [Level],
                    CAST(ROW_NUMBER() OVER(PARTITION BY ParentMenuID ORDER BY OrderSeq) AS varchar(max))
                    FROM mgr.sysMenu with(nolock)  WHERE ParentMenuID = 0  UNION ALL
                    SELECT mn.MenuID, mn.Title, mn.URL, mn.ParentMenuID, mn.IconClass, mn.OrderSeq, [Level] + 1 as [Level],
                    CONVERT(varchar(max), cteMenu.Path + '.' +
                    CAST(ROW_NUMBER() OVER(PARTITION BY mn.ParentMenuID ORDER BY mn.OrderSeq) AS VARCHAR))
                    FROM mgr.sysMenu mn with(nolock) INNER JOIN cteMenu ON (mn.ParentMenuID = cteMenu.MenuID)),
                    result_set AS (SELECT  ROW_NUMBER() OVER (ORDER BY MenuID ) AS [row_number],  MenuID, Title, URL, ParentMenuID, [Level] as MenuLevel, IconClass, OrderSeq, [Path] FROM cteMenu with(nolock))

                    SELECT [row_number], MenuID, Case
                        When MenuLevel = 0 Then Title
                        When MenuLevel = 1 Then '   ' + Title
                        When MenuLevel = 2 Then '      ' + Title
                        When MenuLevel = 3 Then '         ' + Title
                        Else Title End Title, URL, ParentMenuID, MenuLevel, IconClass, OrderSeq, [Path] FROM result_set  order by CAST([Path] AS DECIMAL(10, 2)) ");
            //var_dump($query);
            return Datatables::of($query)
                ->make(true);
        }

        //return view('users');
        return view('menu.index_assign');
    }
    public function getListAssign(Request $request)
    {
        if ($_POST) {
            $criteria = array('groupCd' => $request->gid);
            $query = DB::connection('ifcaadm')
                ->table('mgr.v_SysMenuGroup')
                ->where($criteria)
                ->get();
            //dd($query);
        }
        echo json_encode($query);
        return;
    }
    public function saveAssign(Request $request)
    {

        if ($_POST) {
            if (!empty($request->models)) {
                // dd($request->models);
                // dd($request->gid);
                //$mID = $request->models[0]['MenuID'];
                $criteria = array('GroupCd' => $request->gid);
                try { 
                    DB::connection('ifcaadm')
                    ->table('mgr.sysMenuGroup')
                    ->where($criteria)
                    ->delete();
                    $datains=array();
                    foreach ($request->models as $dt) {
                        //dd($dt);
                        $data = array(
                            // 'GroupCd' => $request->gid,
                            // 'MenuID' => $dt,
                            'audit_user' => Session::get('Tsuname'),
                            'audit_date' => date('d M Y H:i:s')
                        );
                        $datains[] = array_merge($dt, $data);
                        //dd($dt);
                        
                    }
                    // var_dump($datains);exit;
                    DB::connection('ifcaadm')
                            ->table('mgr.sysMenuGroup')
                            ->insert($datains);
                    $msg = "Data has been saved successfully";
                    $st = 'OK';
                } catch(\Illuminate\Database\QueryException $ex){ 
                    $msg = "Save failed: " . $ex->getMessage();
                    $st  = 'Failed';
                    return response()->json([
                        'status' => $st,
                        'pesan' => $msg
                    ]);
                }
                
            } //end if models empty
            $msg = "Data has been saved successfully";
            $st = 'OK';
        } else {
            $msg = 'No menu Assigned';
            $st  = 'Failed';
        }
        return response()->json([
            'status' => $st,
            'pesan' => $msg
        ]);
    }
}
