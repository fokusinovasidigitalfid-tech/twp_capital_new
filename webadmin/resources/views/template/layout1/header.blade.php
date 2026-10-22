<div class="nk-header nk-header-fluid is-theme">
    <div class="container-xl wide-xl">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger mr-sm-2 d-lg-none">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="headerNav"><em class="icon ni ni-menu"></em></a>
            </div>
            {{-- <div class="nk-header-brand">
                <a href="" class="logo-link">
                    <img class="logo-light logo-img" src="{{ url('img/logoweb/favicon.ico') }}" alt="logo">
                </a>
            </div><!-- .nk-header-brand --> --}}
            <div class="nk-header-app-name">
                <div class="nk-header-app-logo">
                    {{-- <em class="icon ni ni-dashlite bg-purple-dim"></em> --}}
                    <img src="{{ url('images/logoweb/favicon.ico') }}" alt="">
                </div>
                <div class="nk-header-app-info">
                    <span class="sub-text">{{ Session::get('Tsproject_descs') }}</span>
                    <span class="lead-text">Web Admin</span>
                </div>
            </div>
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                   

                    @php
                        $data = '';
                        $userid = Session::get('Tsuser_id');
                        $email = Session::get('Tsemail');
                        $data = DB::connection('ifcaadm')->select("SELECT * FROM mgr.sysUser where email = '$email'");
                        $pict = $data[0]->pict;
                        $useremail = $data[0]->email;
                        $username = $data[0]->name;
                        // $data = DB::connection('ifcaadm')->select("SELECT*FROM mgr.sysUser where email = '$email'");
                        // $pict = Session::get('Tsprofil_pict');
                        // $useremail = Session::get('Tsemail');
                        // $username = Session::get('Tsuname');
                    @endphp
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <img src="{{ $pict }}" alt="">
                                </div>
                                <div class="user-info d-none d-xl-block">
                                    <div class="user-status">{{ $userid }}</div>
                                    <div class="user-name dropdown-indicator">{{ $username }}</div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1 is-light">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar" style="background:white;">
                                        <img src="{{ $pict }}" alt="">
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{ $username }}</span>
                                        <span class="sub-text">{{ $useremail }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="" id="profile"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li>
                                    <li><a href="{{ url('/logout') }}"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>
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
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-header" id="modalheader">
                <h5 class="modal-title" id="modaltitle">Modal Title</h5>
            </div>
            <div class="modal-body modal-body-md" id="modalbody">

            </div>
            <div class="modal-footer bg-light" id="modalfooter">
                <button type="button" class="btn btn-primary" id="savefrm">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="text/javascript">
    $('#profile').click(function(e){
        e.preventDefault();
        var data = "<?php echo Session::get('Tsemail');?>";
            console.log(data);
            // alert(data);
            $('#modaldialog').addClass('modal-md');
            // $('#modalheader').removeClass('bg-primary').addClass('bg-info white');
            $('#modaltitle').addClass('white');
            $('#modaltitle').html('Edit Profile');
            $('#modalbody').load("{{ url('account/profile') }}");
            $('#modal').data('Id', data);
            $('#modal').modal('show');
            $('.modal-footer').hide();
    });
    function FormatDateTimeNew(date) {
        if(date==''||date==null){
        return 'Not Set';
        }else{
        var dd = new Date(date.replace(/\s/, 'T'));
        var dt = dd.getDate();
        var Mn = dd.getMonth() + 1;
        var Yr = dd.getFullYear();

        var Hr = dd.getHours();
        var Mnt = dd.getMinutes();
        if(dt < 10){
            dt ='0'+dt;
        }
        if(Mn < 10){
            Mn ='0'+Mn;
        }
        if(Mnt < 10){
            Mnt ='0'+Mnt;
        }
        
        return dt +'/'+Mn+'/'+Yr+' '+Hr+':'+Mnt;
        }
        
    }
  
   
</script>

