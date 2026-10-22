<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    
    <link rel="shortcut icon" href="<?php echo e(url('img/logoweb/iconweb.ico')); ?>">
    <!-- Page Title  -->
    <title>IFCA</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="<?php echo e(url('assets/css/dashlite.css?ver=2.2.0')); ?>">
    <link id="skin-default" rel="stylesheet" href="<?php echo e(url('assets/css/theme.css?ver=2.2.0')); ?>">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css">

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="<?php echo e(url('assets/js/bundle.js?ver=2.2.0')); ?>"></script>
    <script src="<?php echo e(url('assets/js/scripts.js?ver=2.2.0')); ?>"></script>
    <script src="<?php echo e(url('assets/js/charts/gd-default.js?ver=2.2.0')); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
</head>

<body class="nk-body bg-lighter npc-general has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- sidebar @s -->
            <?php echo $__env->make('template.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <!-- main header @s -->
                <?php echo $__env->make('template.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <!-- main header @e -->
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <?php echo $__env->yieldContent('content'); ?>
                        </div>
                    </div>
                </div>
                <!-- content @e -->
                <!-- footer @s -->
                <?php echo $__env->make('template.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
</body>

</html>

<!-- Modal Content Code -->
<div class="modal fade" tabindex="-1" role="dialog" id="modalsm">
    <div id="modaldialogsm" class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-header" id="modalheadersm">
                <h5 class="modal-title" id="modaltitlesm">Modal Title</h5>
            </div>
            <div class="modal-body" id="modalbodysm">

            </div>
            <div class="modal-footer bg-light" id="modalfootersm">
                <!-- <button type="button" class="btn btn-primary" id="savefrm-sm">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Content Code -->
<div class="modal fade" tabindex="-1" role="dialog" id="modallg">
    <div id="modaldialoglg" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
            <div class="modal-header" id="modalheaderlg">
                <h5 class="modal-title" id="modaltitlelg"></h5>
            </div>
            <div class="modal-body" id="modalbodylg">

            </div>
            <div class="modal-footer bg-light" id="modalfooterlg">
                <!-- <button type="button" class="btn btn-primary" id="savefrm-sm">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
            </div>
        </div>
    </div>
</div><?php /**PATH D:\xampp\htdocs\twp_capital_new\resources\views\tenant\template\base.blade.php ENDPATH**/ ?>