<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// build_menu()/get_childs() lama dihapus: tidak pernah dipanggil dan memakai mgr.v_sysMenuGroup
// yang tidak ada di database capital (salinan aslinya ada di webadmin/).
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
