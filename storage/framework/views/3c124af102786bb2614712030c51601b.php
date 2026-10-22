
<?php $__env->startSection('content'); ?>
<style type="text/css">
    .transbox {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 35%;
        background: rgba(0,0,0,0.5);
        padding-top: 50px;
        padding-left: 50px;
        padding-right:30px;
    }
    </style>
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Dashboard</h3>
                </div><!-- .nk-block-head-content -->
            </div><!-- .nk-block-between -->
        </div><!-- .nk-block-head -->
        <div class="nk-block">
            <?php if(!empty($dtnews)){ 
                $no=1;?>
            <div class="row">
                <div class="col-12">
                    <div id="carouselExCap" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner text-light">
                            <?php foreach ($dtnews as $key) {
                                if($no==1){
                                    $active = 'active';
                                }else{
                                    $active = '';
                                }
                                $string = strip_tags($key->content);
                                if (strlen($string) > 150) {

                                    // truncate string
                                    $stringCut = substr($string, 0, 150);
                                    $endPoint = strrpos($stringCut, ' ');

                                    //if the string doesn't contain any space then it will cut without word basis.
                                    $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                    $string .= '... <h6><a href="'.url('/news').'" style="float:right;color:white;text-decoration: underline;">Read More</a></h6>';
                                }
                                
                                echo '<div class="carousel-item '.$active.'">
                                        <img src="'.$key->picture.'" class="d-block w-100" style="object-fit: cover;max-height:300px" alt="...">
                                        <div class="transbox">
                                        <h5 style="color: white!important">'.$key->subject.'</h5><br>
                                        <p>'.$string.'</p>
                                        </div>
                                    </div>';
                                    $no++;
                            }?>
                          
                        </div>
                        <a class="carousel-control-prev" href="#carouselExCap" role="button" data-slide="prev" style="justify-content: left!important;padding-left:10px">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExCap" role="button" data-slide="next" style="justify-content: right!important;padding-right:10px">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
            <?php } ?><br>
            <div class="row">
                <?php
                    $colSize = session('Tflag') == 'O' ? 12 : 8;
                ?>

                <div class="col-<?php echo e($colSize); ?>">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <div class="card-title-group">
                                <div class="card-title">
                                    <h6 class="title">
                                        <span class="mr-2">Monthly Electricity Usage</span>
                                    </h6>
                                </div>
                                <!-- <div class="card-tools">
                                    <select class="select2 form-control" name="meteridcombo" id="meteridcombo">
                                        <?php echo $combometerid; ?>
                                    </select>
                                </div> -->
                                <div class="card-tools" style="width:300px;">
                                    <select class="select2 form-control" name="meteridcombo" id="meteridcombo" style="width:100%;">
                                        <?php echo $combometerid; ?>
                                        <!-- <option value="ADD-kWh-L3P-R2-STARBUCKS-Retail">
                                            ADD-kWh-L3P-R2-STARBUCKS-Retail
                                        </option> -->
                                    </select>
                                </div>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabItem2">
                                    <canvas id="barChart"></canvas>
                                </div>
                                <div class="mt-3" id="legendDiv"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (! (session('Tflag') == 'O')): ?>
                <div class="col-4">
                    <?php 
                        if(!$statusPembayaran)
                        {
                    ?>
                    <div class="card card-bordered border-danger">
                        <div class="card-inner">
                            <div class="card-title-group">
                                <div class="card-title">
                                    <h6 class="title">
                                        <span class="mr-2">IMPORTANT NOTIFICATION</span>
                                    </h6>
                                </div>
                            </div>

                            <a href="<?php echo e(url('/oustanding')); ?>" class="badge badge-danger mt-3">
                                You have outstanding balance
                            </a>

                            <div class="mt-2">
                                <strong>Total :</strong><br>
                                <?= $totalOutstanding ?>
                            </div>
                        </div>
                    </div>

                    <?php } else { ?>

                    <div class="card card-bordered border-primary">
                        <div class="card-inner">
                            <div class="card-title-group">
                                <div class="card-title">
                                    <h6 class="title">
                                        <span class="mr-2">IMPORTANT NOTIFICATION</span>
                                    </h6>
                                </div>
                            </div>

                            <p class="card-text badge badge-primary mt-3">
                                Thank you for your payment
                            </p>
                        </div>
                    </div>

                    <?php } ?>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="card card-bordered mt-3">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">
                                <span class="mr-2">Our Latest Ticket</span>
                            </h6>
                        </div>
                    </div>
                    <div class="table-responsive mt-3">
                        <?php
                            if(!empty($list_hticket)) {
                        ?>
                        <table id="tblTicket" class="table table-bordered table-striped" role="grid" aria-describedby="tblTicket_info">
                            <thead style="background:#101924; color: #ffffff;">
                                <tr role="row">
                                    <th class="sorting text-center" style="width: 7px; vertical-align: middle;">No.</th>
                                    <th class="sorting text-center" style="width: 24px;">Ticket Number</th>
                                    <th class="sorting text-center" style="width: 24px; vertical-align: middle;">Category</th>
                                    <th class="sorting text-center" style="vertical-align: middle;">Description</th>
                                    <th class="sorting text-center" style="width: 100px;">Reported Date</th>
                                    <th class="sorting text-center" style="width: 24px;">Request By</th>
                                    <th class="sorting text-center" style="width: 80px;">Lot No</th>
                                    <th class="sorting text-center" style="width: 10px;">Ticket Status</th>
                                    <th class="sorting text-center" style="width: 10px;">Keterangan</th>
                                    <th class="text-center" style="width: 80px; vertical-align: middle;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if (!empty($list_hticket))
                                    { 
                                        echo $list_hticket;
                                    }  
                                ?>
                            </tbody>
                        </table>
                        <?php  
                            } else {
                                echo "<p class='card-text badge badge-gray'>Data Not Available</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>

            <div class="card card-bordered mt-3">
                <div class="card-inner">
                    <div class="card-title-group">
                        <div class="card-title">
                            <h6 class="title">
                                <span class="mr-2">Our Latest Overtime</span>
                            </h6>
                        </div>
                    </div>
                    <div class="table-responsive mt-3">
                        <?php
                            if(!empty($list_hovertime)) {
                        ?>
                        <table id="tblOvertime" class="table table-bordered table-striped" role="grid" aria-describedby="tblOvertime_info">
                            <thead style="background:#101924; color: #ffffff;">
                                <tr role="row">
                                    <th class="sorting_asc text-center" style="width: 40px; vertical-align: middle;">No.</th>
                                    <th class="sorting text-center" style="width: 80px; vertical-align: middle;">ID</th>
                                    <th class="sorting text-center" style="width: 152px; vertical-align: middle;">Request Date</th>
                                    <th class="sorting text-center">Lot No</th>
                                    <th class="sorting text-center" style="width: 177px; vertical-align: middle;">Start Overtime</th>
                                    <th class="sorting text-center" style="width: 177px; vertical-align: middle;">End Overtime</th>
                                    <th class="sorting text-center" style="width: 10px; vertical-align: middle;">Status</th>
                                    <th class="sorting text-center" style="width: 80px; vertical-align: middle;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if (!empty($list_hovertime))
                                    { 
                                        echo $list_hovertime;
                                    }  
                                ?>
                            </tbody>
                        </table>
                        <?php  
                            } else {
                                echo "<p class='card-text badge badge-gray'>Data Not Available</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div><!-- .nk-block -->
    </div>

    <script type="text/javascript">
        console.log(<?php echo json_encode(session('debug_dataTenant'), 15, 512) ?>);
        function genPDF()
        {
            var meteridcombo = $("#meteridcombo").find(':selected').val();
            console.log(meteridcombo);
            // console.log($('.nav-tabs .active').text());

            var chart ='';
            chart = document.getElementById("barChart").toDataURL();

            var site_url = "<?php echo e(url('dash/gen')); ?>";
            //console.log(chart);
            $.post(site_url,
            {
                "_token": "<?php echo e(csrf_token()); ?>",
                meteridcombo,
                chart
            },
            function (data, status)
            {
                console.log(data);
                console.log(status);
                if (status=='success'){
                    window.open(data);
                } else {
                    Swal.fire({
                        title: "Information",
                        icon:"error",
                        text: "Failed generating pdf file."
                    });
                }
            })
        };

        function changeStatus(id)
        {
            console.log(id);
            Swal.fire({
                title: 'Cancel this Request Overtime?',
                // text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then(function(a){
                if (a.value==true)
                {
                    $.ajax({
                        url : "<?php echo e(url('/dash/cancelOT')); ?>",
                        type:"POST",
                        //data: datafrm,
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>",
                            id: id,
                        },
                        dataType:"json",
                        success:function(event, data)
                        {
                            //console.log(event);
                            if (event.status == 'OK')
                            {
                                Swal.fire({
                                    title: "Information",
                                    animation: true,
                                    icon:"success",
                                    text: event.pesan,
                                    confirmButtonText: "OK"
                                }).then(function(){
                                    window.location.reload(true);
                                });
                            } else {
                                Swal.fire({
                                    title: "Information",
                                    animation: true,
                                    icon:"error",
                                    text: event.pesan,
                                    confirmButtonText: "OK"
                                });
                            }
                        },error: function(jqXHR, textStatus, errorThrown){
                            Swal.fire({
                                title: "Error",
                                animation: true,
                                icon:"error",
                                text: textStatus+' Save : '+errorThrown,
                                confirmButtonText: "OK",
                            });
                            //block(false);
                        }
                    });
                } else {
                    //block(false,'.content-body');
                }
            })
        }

        $(document).ready(function(){
            $('#tblBilling').DataTable({
                paging: false,
                dom : "Bfrtip",
                buttons: [
                    {
                        extend: 'pdf',
                        title: 'Billing Outstanding',
                        className: 'btn btn-primary mb-2',
                        text: '<em class="icon ni ni-download"></em>&nbsp;Generate PDF',
                        init: function(api, node, config) {
                            $(node).removeClass('dt-button')
                        },
                    },
                ]
            });

            $('#tblTicket').DataTable({
                paging: false,
                dom : "Bfrtip",
                buttons: [
                    {
                        extend: 'pdf',
                        title: 'Our Latest Ticket',
                        className: 'btn btn-primary mb-2',
                        text: '<em class="icon ni ni-download"></em>&nbsp;Generate PDF',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7]
                        },
                        init: function(api, node, config) {
                            $(node).removeClass('dt-button')
                        },
                    },
                ]
            });

            $('#tblOvertime').DataTable({
                paging: false,
                dom : "Bfrtip",
                buttons: [
                    {
                        extend: 'pdf',
                        title: 'Our Latest Overtime',
                        className: 'btn btn-primary mb-2',
                        text: '<em class="icon ni ni-download"></em>&nbsp;Generate PDF',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6]
                        },
                        init: function(api, node, config) {
                            $(node).removeClass('dt-button')
                        },
                    },
                ]
            });

            $('.select2').select2();

            var meteridcombo = $(this).find(':selected').val();
            console.log(meteridcombo);

            $.ajax({
                type: 'POST',
                datatType: 'json',
                url: "<?php echo e(url('dash/getGraphMeterId')); ?>",
                data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    meteridcombo: meteridcombo
                },
                success:function(data){
                    // console.log(data);
                    console.log('Version A: ');
                    console.log(Chart);
                    var datas = JSON.parse(data);
                    console.log(Chart.version);
                    var aop = {
                        showScale: true,
                        scaleShowGridLines: true,
                        scaleGridLineColor: "rgba(0,0,0,.05)",
                        scaleGridLineWidth: 1,
                        scaleShowHorizontalLines: true,
                        scaleLabel: "<%= value%> kWh",
                        scaleShowVerticalLines: true,
                        bezierCurve: true,
                        bezierCurveTension: 0.3,
                        pointDot: false,
                        pointDotRadius: 4,
                        pointDotStrokeWidth:2,
                        pointHitDetectionRadius: 20,
                        datasetStroke: true,
                        datasetStrokeWidth: 2,
                        datasetFill: false,
                        maintainAspectRatio: false,
                        responsive: true
                    };

                    var bop = {
                        scaleBeginAtZero: true,
                        scaleShowGridLines: true,
                        scaleGridLineColor: "rgba(0,0,0,.05)",
                        scaleGridLineWidth: 1,
                        scaleShowHorizontalLines: true,
                        scaleShowVerticalLines: true,
                        scaleLabel: "<%= value%> kWh",
                        barShowStroke: true,
                        barStrokeWidth: 2,
                        barValueSpacing: 5,
                        barDatasetSpacing: 1,
                        responsive: true,
                        maintainAspectRatio: false
                    };
                    
                    // BAR CHART
                    $("#barChart").remove();
                    $("#tabItem2").append('<canvas id="barChart"></canvas>');
                    var ctb = document.getElementById("barChart").getContext("2d");
                    var maxData = Math.max.apply(null, datas.chartdt.datasets[0].data.map(Number));
                    var maxYAxis = Math.ceil((maxData + 1000) / 1000) * 1000;

                    console.log(maxData);   // 7967.07
                    console.log(maxYAxis);  // 10000
                    var bch = new Chart(ctb, {
                        type: 'bar',
                        data: datas.chartdt,
                        options: {
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Usage : ' +
                                                Number(context.raw).toLocaleString(
                                                    'en-US',
                                                    {
                                                        minimumFractionDigits: 2,
                                                        maximumFractionDigits: 2
                                                    }
                                                ) + ' kWh';
                                        },
                                        afterLabel: function(context) {
                                            return '(' + datas.meterid[context.dataIndex] + ')';
                                        }
                                    }
                                }
                            },
                            
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                        min: 0,
                                        max: maxYAxis,
                                        suggestedMin: 0,
                                        callback: function(value) {
                                            return Number(value).toLocaleString('en-US');
                                        }
                                    }
                                }]
                            }
                        }
                    });

                    // BUTTON
                    $("#legendDiv").empty();
                    $("#legendDiv").append('<button name="genPDF" type="button" class="btn btn-primary" onclick="genPDF()"><em class="icon ni ni-download"></em><span>Generate PDF</span></button>');
                }
            });
            

            $('#meteridcombo').change(function() {
                var meteridcombo = $(this).find(':selected').val();
                console.log(meteridcombo);

                $.ajax({
                    type: 'POST',
                    datatType: 'json',
                    url: "<?php echo e(url('dash/getGraphMeterId')); ?>",
                    data: {
                        "_token": "<?php echo e(csrf_token()); ?>",
                        meteridcombo: meteridcombo
                    },
                    success:function(data){
                        //console.log(data);
                        console.log('Version B: ');
                        console.log(Chart);
                        var datas = JSON.parse(data);

                        var aop = {showScale: true, scaleShowGridLines: true, scaleGridLineColor: "rgba(0,0,0,.05)", scaleGridLineWidth: 1, scaleShowHorizontalLines: true, scaleLabel: "<%= value%> kWh", scaleShowVerticalLines: true, bezierCurve: true, bezierCurveTension: 0.3, pointDot: false, pointDotRadius: 4, pointDotStrokeWidth:2, pointHitDetectionRadius: 20, datasetStroke: true, datasetStrokeWidth: 2, datasetFill: false, maintainAspectRatio: false, responsive: true};

                        var bop = {scaleBeginAtZero: true, scaleShowGridLines: true, scaleGridLineColor: "rgba(0,0,0,.05)", scaleGridLineWidth: 1, scaleShowHorizontalLines: true, scaleShowVerticalLines: true, scaleLabel: "<%= value%> kWh", barShowStroke: true, barStrokeWidth: 2, barValueSpacing: 5, barDatasetSpacing: 1, responsive: true, maintainAspectRatio: false };

                        // BAR CHART
                        $("#barChart").remove();
                        $("#tabItem2").append('<canvas id="barChart"></canvas>');
                        var ctb = document.getElementById("barChart").getContext("2d");
                        var maxData = Math.max.apply(null, datas.chartdt.datasets[0].data.map(Number));
                        var maxYAxis = Math.ceil((maxData + 2000) / 1000) * 1000;

                        console.log(maxData);   // 7967.07
                        console.log(maxYAxis);  // 10000
                        var bch = new Chart(ctb, {
                            type: 'bar',
                            data: datas.chartdt,
                            options: {
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return 'Usage : ' +
                                                    Number(context.raw).toLocaleString(
                                                        'en-US',
                                                        {
                                                            minimumFractionDigits: 2,
                                                            maximumFractionDigits: 2
                                                        }
                                                    ) + ' kWh';
                                            },
                                            afterLabel: function(context) {
                                                return '(' + datas.meterid[context.dataIndex] + ')';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            min: 0,
                                            max: maxYAxis,
                                            suggestedMin: 0,
                                            callback: function(value) {
                                                return Number(value).toLocaleString('en-US');
                                            }
                                        }
                                    }]
                                }
                            }
                        });

                        $("#legendDiv").empty();
                        $("#legendDiv").append('<button name="genPDF" id="genPDF" type="button" class="btn btn-primary" onclick="genPDF()"><em class="icon ni ni-download"></em><span>Generate PDF</span></button>');
                    }
                });
            });
        })
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\twp_capital_new\resources\views\tenant\dash\index.blade.php ENDPATH**/ ?>