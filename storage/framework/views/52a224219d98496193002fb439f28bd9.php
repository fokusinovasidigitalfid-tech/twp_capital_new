<?php
    $hour = now()->hour;

    $hour = now()->hour;

    if ($hour >= 3 && $hour < 11) {
        $greeting = 'Good morning,';
    } elseif ($hour >= 11 && $hour < 18) {
        $greeting = 'Good afternoon,';
    } elseif ($hour >= 18 || $hour < 3) {
        $greeting = 'Good evening,';
    } else {
        $greeting = 'Good evening,';
    }

    $email = Session::get('Tenemail');

    $data = DB::table('mgr.tenant')
        ->where('email', $email)
        ->first();

    $portalType = '';

    if ($data) {
        if ($data->flag == 'F') {
            $portalType = 'Finance';
        }
    }
?>
<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ml-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand d-xl-none">
                <div class="nk-header-app-info">
                    
                    <span class="sub-text"><?php echo e($greeting); ?></span>
                    <span class="lead-text">Welcome To Tenant Web Portal<?php echo e($portalType ? " ({$portalType})" : ''); ?></span>
                </div>
            </div><!-- .nk-header-brand -->
            <div class="nk-header-news d-none d-xl-block">
                <div class="nk-news-list">
                    <div class="nk-header-app-info">
                        <span class="sub-text"><?php echo e($greeting); ?></span>
                        <span class="lead-text">
                            Welcome to Tenant Web Portal<?php echo e($portalType ? " ({$portalType})" : ''); ?>

                        </span>
                    </div>
                </div>
            </div>
            <?php
                $email = Session::get('Tenemail');
                $tenant_df = Session::get('tenant_df');

                // Data login
                $login = DB::table('mgr.all_login')
                    ->where('email', $email)
                    ->first();

                // Data tenant
                $tenant = DB::table('mgr.tenant')
                    ->where('tenant_no_df', $tenant_df)
                    ->first();

                $pict = !empty($login->pict) ? $login->pict : url('/img/user/defaultuser.png');
                $useremail = $login->email;
                $username = $tenant->name ?? '';
            ?>
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar" style="background:white;">
                                    <img src="<?php echo e($pict); ?>" alt="">
                                </div>
                                <div class="user-info">
                                    <span class="lead-text"><?php echo e($username); ?></span>
                                    <span class="sub-text"><?php echo e($useremail); ?></span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar" style="background:white;">
                                        <img src="<?php echo e($pict); ?>" alt="">
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text"><?php echo e($username); ?></span>
                                        <span class="sub-text"><?php echo e($useremail); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    
                                    <li><a id="profile" style="cursor: pointer;"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li>
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="<?php echo e(url ('logout')); ?>"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </li><!-- .dropdown -->
                </ul><!-- .nk-quick-nav -->
            </div><!-- .nk-header-tools -->
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>
<!-- Modal Content Code -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-header" id="modalheader">
                <h5 class="modal-title" id="modaltitle">Modal Title</h5>
            </div>
            <div class="modal-body" id="modalbody">

            </div>
            <div class="modal-footer bg-light" id="modalfooter">
                <button type="button" class="btn btn-primary" id="savefrm">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#profile').click(function(){
        var data = "<?php echo Session::get('Tenemail');?>";
        // console.log(data);
        $('#modaldialog').addClass('modal-md');
        // $('#modalheader').removeClass('bg-primary').addClass('bg-info white');
        $('#modaltitle').addClass('white');
        $('#modaltitle').html('Edit Profile');
        $('#modalbody').load("<?php echo e(url('account/profile')); ?>");
        $('#modal').data('Id', data);
        $('#modal').modal('show');
        $('.modal-footer').hide();
    });
</script><?php /**PATH D:\xampp\htdocs\twp_capital_new\resources\views\tenant/template/header.blade.php ENDPATH**/ ?>