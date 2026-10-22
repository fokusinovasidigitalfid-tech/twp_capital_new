<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fav Icon  -->
    {{-- <link rel="shortcut icon" href="{{ url('/images/logoweb/favicon.ico') }}"> --}}
    <link rel="shortcut icon" href="{{ url('/images/logoweb/iconweb.ico') }}">
    <!-- Page Title  -->
    <title>Login</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ url('assets/css/dashlite.css') }}">
    <link id="skin-default" rel="stylesheet" href="{{ url('assets/css/theme.css') }}">
    <script src="{{ url('assets/js/ShowPCx.js')}}" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body class="nk-body npc-default pg-auth">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-split nk-split-page nk-split-md">
                        <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white">
                            <div class="absolute-top-right d-lg-none p-3 p-sm-5">
                                <a href="#" class="toggle btn-white btn btn-icon btn-light" data-target="athPromo"><em class="icon ni ni-info"></em></a>
                            </div>
                            <div class="nk-block nk-block-middle nk-auth-body">
                                <div class="brand-logo pb-5" style="padding-bottom: 20px!important;">
                                    <a href="" class="logo-link">
                                        {{-- <img class="logo-img logo-img-lg" src="{{ url('/images/logoweb/favicon.ico') }}" alt="logo">  --}}
                                        <img class="logo-img logo-img-lg" src="{{ url('/images/logoweb/iconweb.ico') }}" alt="logo"> 
                                    </a>
                                    <br><p style="font-size:20px;padding-top:20px">Admin<b>TWP</b>
                                    <span style="font-size:15px;"> <br>Tenant Web Portal - Administrator.</span></p>
                                </div>
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title">Sign-In</h5>
                                        <div class="nk-block-des">
                                            {{-- <p>Tenan Web Portal - Administrator.</p> --}}
                                        </div>
                                    </div>
                                </div><!-- .nk-block-head -->
                                <form action="{{ url('/login') }}" method="POST" id="formlogin" class="needs-validation" novalidate="">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="default-01">Email</label>
                                        </div>
                                        <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" id="email" placeholder="Enter your email address" required = "true">
                                    </div>
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="password">Password</label>
                                        </div>
                                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" id="password" placeholder="Enter your password" required = "true">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block">Sign in</button>
                                    </div>
                                </form><!-- form -->
                                <!-- <div class="form-note-s2 pt-4"> Forgot Password? <a href="{{ url('/account/forgot_password') }}">Click to reset password</a>
                                </div> -->
                            </div><!-- .nk-block -->
                        </div><!-- .nk-split-content -->
                        <div class="nk-split-content nk-split-stretch bg-lighter d-flex toggle-break-lg toggle-slide toggle-slide-right" data-content="athPromo" data-toggle-screen="lg" data-toggle-overlay="true">
                          
                            <div class="slider-wrap w-100 w-max-1000px">
                                <div id="carouselExCap" class="carousel slide carousel-fade" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                        <li data-target="#carouselExCap" data-slide-to="0" class="active"></li>
                                        <li data-target="#carouselExCap" data-slide-to="1"></li>
                                        <li data-target="#carouselExCap" data-slide-to="2"></li>
                                    </ol>
                                    <div class="carousel-inner text-light">
                                    <?php 
                                        if(!empty($dataimages)){
                                            foreach ($dataimages as $key) {
                                                if($key->seq_no=="1"){
                                                    $active=' active';
                                                }else {
                                                    $active='';
                                                }
                                                echo '<div class="carousel-item'.$active.'">
                                                    <img src="'.$key->image_url.'" height="635" class="d-block w-100" alt="...">                                    
                                                </div>';
                                            }
                                        } else{
                                            echo '<div class="carousel-item active">
                                                    <img src="./images/slides/pb-1.jpg" height="635" class="d-block w-100" alt="...">                                    
                                                </div>';
                                        }
                                    ?>
                                    {{-- <div class="carousel-item active">
                                        <img src="./images/slides/pb-1.jpg" height="635" class="d-block w-100" alt="...">                                    
                                    </div>
                                    <div class="carousel-item">
                                        <img src="./images/slides/pb-2.jpg" height="635" class="d-block w-100" alt="...">
                                    </div>
                                    <div class="carousel-item">
                                        <img src="./images/slides/pb-3.png" height="635" class="d-block w-100" alt="...">
                                    </div> --}}
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExCap" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExCap" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div><!-- .nk-split-content -->
                    </div><!-- .nk-split -->
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="{{ url('assets/js/bundle.js') }}"></script>
    <script src="{{ url('assets/js/scripts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script type="text/javascript">
        new ShowPCx(document.getElementById("password"));
            var frm = document.forms['formlogin'];
            if(!frm){
              frm = document.formlogin;
            }
      </script>

      <script type="text/javascript">
        @if(Session::has('alert'))
            toastr.error("{{Session::get('alert')}}");
            toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
            }
        @endif
      </script>

</html>
