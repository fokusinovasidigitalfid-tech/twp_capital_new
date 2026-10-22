<div class="nk-sidebar nk-sidebar-fixed is-dark " data-content="sidebarMenu" style="width: 250px;">
    <div class="nk-sidebar-element nk-sidebar-head" style="width: 250px;height: 165px;">
        <div class="nk-sidebar-brand">
            <a href="" class="logo-link nk-sidebar-logo">
                {{-- <img class="logo-light logo-img" src="{{ url('/img/logo-bw.png') }}" alt="logo"> --}}
                <img class="logo-light logo-img" src="{{ url('/img/logoweb/logoweb.png') }}" alt="logo" style="max-height: 136px;margin-left: 10%;">
            </a>
        </div>
        <div class="nk-menu-trigger mr-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
        </div>
    </div><!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Dashboard</h6>
                    </li><!-- .nk-menu-heading -->
                    <li class="nk-menu-item">
                        <a href="{{ url('/dash') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                            <span class="nk-menu-text"> Dashboard</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Menu</h6>
                    </li><!-- .nk-menu-heading -->
                    <li class="nk-menu-item">
                        <a href="{{ url('/ticket') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-ticket"></em></span>
                            <span class="nk-menu-text"> Ticket</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @unless(session('Tflag') == 'O')
                    <li class="nk-menu-item">
                        <a href="{{ url('/oustanding') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-report-profit"></em></span>
                            <span class="nk-menu-text"> Outstanding</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @endunless
                    <li class="nk-menu-item">
                        <a href="{{ url('/overtime') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-clock"></em></span>
                            <span class="nk-menu-text"> Overtime</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-histroy"></em></span>
                            <span class="nk-menu-text">History</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="{{ url('/history/ticket') }}" id="ht" class="nk-menu-link"><span class="nk-menu-text">Ticket</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ url('/history/overtime') }}" id="ho" class="nk-menu-link"><span class="nk-menu-text">Overtime</span></a>
                            </li>
                            @unless(session('Tflag') == 'O')
                            <li class="nk-menu-item">
                                <a href="{{ url('/history/billing') }}" id="hb" class="nk-menu-link"><span class="nk-menu-text">Billing</span></a>
                            </li>
                            @endunless
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="http://capitalplace.co.id/news/" class="nk-menu-link" target="_blank" rel="noopener noreferrer">
                            <span class="nk-menu-icon"><em class="icon ni ni-template-fill"></em></span>
                            <span class="nk-menu-text"> News </span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <!-- <li class="nk-menu-item">
                        <a href="{{ url('/online_survey') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-edit"></em></span>
                            <span class="nk-menu-text"> Online Survey</span>
                        </a>
                    </li> -->
                    <!-- .nk-menu-item -->
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>