<div class="nk-sidebar is-capital" data-content="sidebarMenu">
    <div class="nk-sidebar-inner" data-simplebar>
        <ul class="nk-menu nk-menu-md">
            <li class="nk-menu-heading">
                <h6 class="overline-title text-primary-alt">Dashboards</h6>
            </li><!-- .nk-menu-heading -->
            <li class="nk-menu-item">
                <a href="<?php echo e(url('/dash')); ?>" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                    <span class="nk-menu-text">Dashboard</span>
                </a>
            </li><!-- .nk-menu-item -->

            <li class="nk-menu-heading">
                <h6 class="overline-title text-primary-alt">MENU</h6>
            </li><!-- .nk-menu-heading -->
            <li class="nk-menu-item has-sub">
                <a href="#" class="nk-menu-link nk-menu-toggle" data-original-title="" title="">
                    <span class="nk-menu-icon"><em class="icon ni ni-list-thumb"></em></span><span class="nk-menu-text">News Feed</span>
                </a>
                <ul class="nk-menu-sub">
                    <div class="arrow_box">
                        <li class="nk-menu-item"><a href="<?php echo e(url('/news/form/A')); ?>" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text"> Create News</span></a></li>
                        <li class="nk-menu-item"><a href="<?php echo e(url('/news')); ?>" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text">List News </span></a></li>
                    </div>
                </ul>					
              
            </li>
            <li class="nk-menu-item has-sub">
                <a href="#" class="nk-menu-link nk-menu-toggle" data-original-title="" title="">
                    <span class="nk-menu-icon"><em class="icon ni ni-edit"></em></span><span class="nk-menu-text">Online Survey</span>
                </a>
                <ul class="nk-menu-sub">
                    <div class="arrow_box">
                        <li class="nk-menu-item"><a href="<?php echo e(url('/survey/questions')); ?>" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text"> Template Survey Questions</span></a></li>
                        <li class="nk-menu-item"><a href="<?php echo e(url('/survey/publish')); ?>" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text"> Publish Survey </span></a></li>
                        <li class="nk-menu-item"><a href="<?php echo e(url('/survey/result')); ?>" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text"> Survey Results </span></a></li>
                    </div>
                </ul>	
            </li>
            <li class="nk-menu-item has-sub">
                <a href="#" class="nk-menu-link nk-menu-toggle" data-original-title="" title="">
                    <span class="nk-menu-icon"><em class="icon ni ni-briefcase"></em></span><span class="nk-menu-text">Overtime</span>
                </a>
                <ul class="nk-menu-sub">
                    <div class="arrow_box">
                        <li class="nk-menu-item"><a href="<?php echo e(url('/overtime/approval')); ?>" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text"> Overtime Approval</span></a></li>
                        <li class="nk-menu-item"><a href="<?php echo e(url('/overtime/posting')); ?>" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text"> Overtime Posting</span></a></li>
                        
                    </div>
                </ul>	
            </li>
            <?php if(Session::get('Tsuname') == 'Admin Management'): ?>
            <li class="nk-menu-item">
                <a href="<?php echo e(url('/management')); ?>" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-bar-chart"></em></span>
                    <span class="nk-menu-text">Business Overview</span>
                </a>
            </li>
            <?php endif; ?>
            <li class="nk-menu-item has-sub">
                <a href="#" class="nk-menu-link nk-menu-toggle" data-original-title="" title="">
                    <span class="nk-menu-icon"><em class="icon ni ni-histroy"></em></span><span class="nk-menu-text">History</span>
                </a>
                <ul class="nk-menu-sub">
                    <div class="arrow_box">
                        <li class="nk-menu-item"><a href="<?php echo e(url('/history/ticket')); ?>" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text"> Ticket</span></a></li>
                        <li class="nk-menu-item"><a href="<?php echo e(url('/history/overtime')); ?>" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text"> Overtime </span></a></li>
                        <li class="nk-menu-item"><a href="<?php echo e(url('/history/users')); ?>" class="nk-menu-link" data-original-title="" title=""><span class="nk-menu-text"> User login </span></a></li>
                    </div>
                </ul>	
            </li>
            <li class="nk-menu-item">
                <a href="<?php echo e(url('/systemspec')); ?>" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-setting"></em></span>
                    <span class="nk-menu-text">System Spec</span>
                </a>
            </li>
            <li class="nk-menu-item">
                <a href="<?php echo e(url('/account/reset')); ?>" class="nk-menu-link">
                    <span class="nk-menu-icon"><em class="icon ni ni-account-setting"></em></span>
                    <span class="nk-menu-text">Password Reset</span>
                </a>
            </li>
        </ul><!-- .nk-menu -->
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\twp_capital_new\resources\views\admin\template\layout2\sidebar.blade.php ENDPATH**/ ?>