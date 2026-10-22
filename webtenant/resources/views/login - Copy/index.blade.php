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
    <link rel="shortcut icon" href="{{ url('/img/logoweb/iconweb.ico') }}">
    <!-- Page Title  -->
    <title>Login</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ url('assets/css/dashlite.css') }}">
    <link id="skin-default" rel="stylesheet" href="{{ url('assets/css/theme.css') }}">
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
                                <div class="brand-logo pb-5">
                                    <a href="" class="logo-link">
                                        {{-- <img class="logo-img logo-img-lg" src="{{ url('/img/logo-col.png') }}" alt="logo"> --}}
                                        <img class="logo-img logo-img-lg" src="{{ url('/img/logoweb/logoweb.png') }}" alt="logo">
                                    </a>
                                </div>
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h5 class="nk-block-title">Sign-In</h5>
                                        <!-- <div class="nk-block-des">
                                            <p>Access the Web Admin using your email and password.</p> 
                                        </div> -->
                                    </div>
                                </div><!-- .nk-block-head -->
                                <form action="{{ url('/based') }}" method="POST" id="formlogin" class="needs-validation" novalidate="">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="default-01">Email</label>
                                        </div>
                                        <input type="text" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" id="email" placeholder="Enter your email address" required = "true">
                                    </div><!-- .foem-group -->
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block">Sign In</button>
                                    </div>
                                </form><!-- form -->
                            </div><!-- .nk-block -->
                        </div><!-- .nk-split-content -->
                        <div class="nk-split-content nk-split-stretch bg-lighter d-flex toggle-break-lg toggle-slide toggle-slide-right" data-content="athPromo" data-toggle-screen="lg" data-toggle-overlay="true">
                            <div class="slider-wrap w-100 w-max-1000px">
                                <div id="carouselExCap" class="carousel slide carousel-fade" data-ride="carousel">
                                    <?php 
                                        if(!empty($dataimages)){
                                            echo '<ol class="carousel-indicators">
                                                <li data-target="#carouselExCap" data-slide-to="0" class="active"></li>
                                                <li data-target="#carouselExCap" data-slide-to="1"></li>
                                                <li data-target="#carouselExCap" data-slide-to="2"></li>
                                            </ol>';
                                            echo '<div class="carousel-inner text-light">';
                                                foreach ($dataimages as $key) {
                                                    if($key->seq_no=="4"){
                                                        $active=' active';
                                                    }else {
                                                        $active='';
                                                    }
                                                    echo '<div class="carousel-item'.$active.'">
                                                        <img src="'.$key->image_url.'" height="635" class="d-block w-100" alt="...">                                    
                                                    </div>';
                                                }
                                            echo '<a class="carousel-control-prev" href="#carouselExCap" role="button" data-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span></a>';
                                            echo '<a class="carousel-control-next" href="#carouselExCap" role="button" data-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span></a>';
                                        } else {
                                                echo '<div class="carousel-item active">
                                                        <img src="./img/Background2.png" height="635" class="d-block w-100" alt="...">
                                                        <div class="carousel-caption d-none d-md-block">
                                                        </div>
                                                    </div>';
                                            }
                                        ?>
                                    </div>
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

    <script type="text/javascript">
        @if(Session::has('alert'))
            toastr.error("{{Session::get('alert')}}");
        @endif
    </script>

</html>
