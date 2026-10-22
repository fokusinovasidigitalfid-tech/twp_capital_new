<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private $id_menu        = 'id="menu"';
    private $class_menu     = 'class="nk-sidebar"';
    private $class_parent   = 'class="nk-sidebar-inner"';
    private $class_last     = 'class="last"';
    private $li_class       = 'class="nk-menu-item"';
    private $ul_class       = 'class="nk-menu nk-menu-md"';
    function build_menu($active = null, $usergroup = 'null')
    {
        $menu = array();
        $query = DB::connection('ifcaadm')
            ->select("SELECT ROW_NUMBER() OVER (ORDER BY parent_seq, child_seq, mgr.v_sysMenuGroup.MenuID ASC) AS [row_number], * from mgr.v_sysMenuGroup where GroupCd ='$usergroup' order by parent_seq, child_seq");
        
        // $query = $response->json();
        if (count($query) > 0) {
            foreach ($query as $row) {
                $menu[$row->row_number]['MenuID']       = $row->MenuID;
                $menu[$row->row_number]['Title']        = $row->Title;
                $menu[$row->row_number]['URL']          = $row->URL;
                $menu[$row->row_number]['ParentMenuID'] = $row->ParentMenuID;
                $menu[$row->row_number]['IconClass']    = $row->IconClass;
                $menu[$row->row_number]['Is_parent']    = $row->Is_parent;
                $menu[$row->row_number]['PARENT_SEQ']   = $row->PARENT_SEQ;
            }
        }

        $flag = false;
        $parentId = 0;
        $parentId2 = 0;
        $parentId3 = 0;
        for ($j = 1; $j <= count($menu); $j++) {
            if ($menu[$j]['URL'] == $active) {
                $parentId = $menu[$j]['ParentMenuID'];
            }
        }

        if ($parentId != 0) {
            for ($j = 1; $j <= count($menu); $j++) {
                if ($parentId == $menu[$j]['MenuID']) {
                    $parentId2 = $menu[$j]['ParentMenuID'];
                }
            }

            if ($parentId2 != 0) {
                for ($j = 1; $j <= count($menu); $j++) {
                    if ($parentId2 == $menu[$j]['MenuID']) {
                        $parentId3 = $menu[$j]['ParentMenuID'];
                    }
                }
            }
        }

        $html_out  = '';
        // var_dump(url($menu[1]['URL']));
        // loop through the $menu array() and build the parent menus.
        for ($i = 1; $i <= count($menu); $i++) {
            // var_dump($parentId);
            // var_dump(url());
            if (is_array($menu[$i]))    // must be by construction but let's keep the errors home
            {
                if ($menu[$i]['ParentMenuID'] == 0)    // are we allowed to see this menu?
                {

                    if ($menu[$i]['Is_parent'] == TRUE) {

                        if ($menu[$i]['MenuID'] == $parentId || $menu[$i]['MenuID'] == $parentId2 || $menu[$i]['MenuID'] == $parentId3) {
                            $html_out .= "\t\t\t" . '<li class="active nk-menu-item has-sub"><a href="#" class="nk-menu-link nk-menu-toggle"><span class="nk-menu-icon"><em class="' . $menu[$i]['IconClass'] . '"></em></span><span class="nk-menu-text">' . $menu[$i]['Title'] . '</span></a>';
                        } else {
                            $html_out .= "\t\t\t" . '<li class="nk-menu-item has-sub"><a href="#" class="nk-menu-link nk-menu-toggle"><span class="nk-menu-icon"><em class="' . $menu[$i]['IconClass'] . '"></em></span><span class="nk-menu-text">' . $menu[$i]['Title'] . '</span></a>';
                        }
                    } else {

                        if ($menu[$i]['URL'] == $active || $menu[$i]['MenuID'] == $parentId2) {
                            // $html_out .= "\t\t\t\t" . '<li><a class="nav-link" href="' . $menu[$i]['URL'] . '"><i class="far fa-user"></i> <span>User Profile</span></a></li>';
                            $html_out .= "\t\t\t\t" . '<li class="nk-menu-item"><a href="' . url($menu[$i]['URL']) . '" class="nk-menu-link"><span class="nk-menu-icon"><em class="' . $menu[$i]['IconClass'] . '"></em></span><span class="nk-menu-text">' . $menu[$i]['Title'] . '</span></a></li>';
                        } else {
                            $html_out .= "\t\t\t\t" . '<li class="nk-menu-item"><a href="' . url($menu[$i]['URL']) . '" class="nk-menu-link"><span class="nk-menu-icon"><em class="' . $menu[$i]['IconClass'] . '"></em></span><span class="nk-menu-text">' . $menu[$i]['Title'] . '</span></a>';
                        }
                    }

                    // loop through and build all the child submenus.
                    $MenuID = $menu[$i]['MenuID'];
                    $html_out .= $this->get_childs($menu, $MenuID, $active);


                    $html_out .= '</li>' . "\n";
                }
            } else {
                exit(sprintf('menu nr %s must be an array', $i));
            }
        }

        return $html_out;
    }

    function get_childs($menu, $ParentMenuID, $active)
    {
        $has_subcats = FALSE;

        $html_out  = '';
        // $html_out .= "\n\t\t\t\t".'<div>'."\n";
        $html_out .= "\t\t\t\t\t" . '<ul class="nk-menu-sub">' . "\n" . '<div class="arrow_box">' . "\n";

        $parentId = 0;
        $parentId2 = 0;
        $parentId3 = 0;
        for ($j = 1; $j <= count($menu); $j++) {
            // var_dump($menu[$j]['URL']);
            if ($menu[$j]['URL'] == $active) {
                $parentId = $menu[$j]['ParentMenuID'];
            }
        }
        // var_dump($parentId);
        if ($parentId != 0) {
            for ($j = 1; $j <= count($menu); $j++) {
                if ($parentId == $menu[$j]['MenuID']) {
                    $parentId2 = $menu[$j]['ParentMenuID'];
                }
            }


            if ($parentId2 != 0) {
                for ($j = 1; $j <= count($menu); $j++) {
                    if ($parentId2 == $menu[$j]['MenuID']) {
                        $parentId3 = $menu[$j]['ParentMenuID'];
                    }
                }
            }
        }

        for ($i = 1; $i <= count($menu); $i++) {
            $uri = $menu[$i]['URL'];
            $site_url = is_array($uri)
                ? url($uri)
                : (preg_match('#^(\w+:)?//#i', $uri) ? $uri : url($uri));
            // var_dump(url());
            // var_dump($site_url);
            if ($menu[$i]['ParentMenuID'] == $ParentMenuID)    // are we allowed to see this menu?
            {
                $has_subcats = TRUE;


                if ($menu[$i]['Is_parent'] == TRUE) {
                    if ($menu[$i]['URL'] == $active || ($menu[$i]['Is_parent'] == TRUE && $menu[$i]['PARENT_SEQ'] == 1000 && $menu[$i]['MenuID'] == $parentId) || ($menu[$i]['Is_parent'] == TRUE && $menu[$i]['PARENT_SEQ'] == 1000 && $menu[$i]['MenuID'] == $parentId)) {

                        $html_out .= "\t\t\t\t\t\t" . '<li class="nk-menu-item"><a href="' . '#"  class="nk-menu-link nk-menu-toggle"><span class="nk-menu-text">' . $menu[$i]['Title'] . '</span></a>';
                    } else {
                        $html_out .= "\t\t\t\t\t\t" . '<li class="nk-menu-item"><a href="' . '#"  class="nk-menu-link nk-menu-toggle"><span class="nk-menu-text">' . $menu[$i]['Title'] . ' </span></a>';
                    }
                } else {

                    if ($menu[$i]['URL'] == $active) {
                        $subparentid = $menu[$i]['ParentMenuID'];
                        $html_out .= "\t\t\t\t\t\t" . '<li class="active nk-menu-item"><a href="' . $site_url . '" class="nk-menu-link"><span class="nk-menu-text">' . $menu[$i]['Title'] . '</span></a>';
                    } else {
                        $html_out .= "\t\t\t\t\t\t" . '<li class="nk-menu-item"><a href="' . $site_url . '" class="nk-menu-link"><span class="nk-menu-text">' . $menu[$i]['Title'] . ' </span></a>';
                    }
                }

                // Recurse call to get more child submenus.
                $html_out .= $this->get_childs($menu, $menu[$i]['MenuID'], $active);

                $html_out .= '</li>' . "\n";
            }
        }
        $html_out .= "\t\t\t\t\t" . '</div></ul>' . "\n";
        return ($has_subcats) ? $html_out : FALSE;
    }
}
