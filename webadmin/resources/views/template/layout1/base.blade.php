<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ url('img/logoweb/favicon.ico') }}">
    <!-- Page Title  -->
    <title>Projects</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ url('assets/css/dashlite.css') }}">
    <link id="skin-default" rel="stylesheet" href="{{ url('assets/css/theme.css') }}">

    <!-- JavaScript -->
    <script src="{{ url('assets/js/bundle.js') }}"></script>
    <script src="{{ url('assets/js/scripts.js') }}"></script>
    <script src="{{ url('assets/js/charts/gd-invest.js') }}"></script>

    {{-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> --}}
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/forms/toggle/switchery.min.css') }}">
    <link href="{{ url('assets/css/plugins/fileupload/css/jquery.fileupload.css') }}" rel="stylesheet" />
    <script src="{{ url('assets/js/forms/icheck/icheck.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/forms/toggle/switchery.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/forms/toggle/switchery.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/forms/switch/switch.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/plugins/fileupload/js/jquery.ui.widget.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/plugins/fileupload/js/jquery.fileupload.js') }}" type="text/javascript"></script>
</head>

<body class="nk-body npc-invest bg-lighter ">
    <div class="nk-app-root">
        <!-- wrap @s -->
        <div class="nk-wrap ">
            <!-- main header @s -->
            @include('template.layout1.header')
            <!-- main header @e -->
            <!-- content @s -->
            <div class="nk-content nk-content-fluid">
                <div class="container-xl wide-xl">
                    <div class="nk-content-inner">
                        @yield('body')
                    </div>
                </div>
            </div>
            <!-- content @e -->
            <!-- footer @s -->
            @include('template.layout1.footer')
            <!-- footer @e -->
        </div>
        <!-- wrap @e -->
    </div>
    <!-- app-root @e -->

</body>

</html>
