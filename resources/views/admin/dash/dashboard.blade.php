@extends('template.layout2.base')
@section('content')
<style type="text/css">
    .dataTables_filter{
        padding-bottom: 10px!important;
    }
    .table-responsive{
        overflow-x:none!important;
    }
</style>

<div class="nk-content-body">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Dashboard Administrator</h3>
                <div class="nk-block-des text-soft">
                    <p>Welcome to Tenant Web Portal.</p>
                </div>
            </div><!-- .nk-block-head-content -->
        </div><!-- .nk-block-between -->
    </div><!-- .nk-block-head -->
    <div class="nk-block">
        <div class="row g-gs">
            <div class="card card-preview col-12">
                <div class="card-inner" style="padding-top:10px">
                    <h5 class="card-title" style="border-bottom: solid 2px #dbdfea;padding-bottom:25px;margin-bottom: 20px;">
                        Work Order Graphic
                    </h5>

                    <!-- CHART -->
                    <div style="height: 480px; position: relative;">
                        <canvas
                            class="col-sm-12"
                            id="barChartStatus">
                        </canvas>
                    </div>

                    <br>

                    <!-- TABLE -->
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" style="border-collapse: collapse; width:100%;">
                            <thead>
                                <tr>
                                    <th width="220" style=" border:1px solid #d3d3d3; background:#f8f9fc;">
                                        Status
                                    </th>

                                    @foreach(json_decode($labels_status) as $lbl)

                                        <th style="border:1px solid #d3d3d3; background:#f8f9fc;">
                                            {{ $lbl }}
                                        </th>

                                    @endforeach

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align:left;border:1px solid #d3d3d3;padding-left:20px;">
                                        <span style="display:inline-block;width:14px;height:14px;background:#c0504d;margin-right:8px;vertical-align:middle;"></span>
                                        Assigned
                                    </td>

                                    @foreach(json_decode($assigned) as $v)

                                        <td style="border:1px solid #d3d3d3;">
                                            {{ $v }}
                                        </td>

                                    @endforeach

                                </tr>
                                <tr>
                                    <td style="text-align:left;border:1px solid #d3d3d3;padding-left:20px;">
                                        <span style="display:inline-block;width:14px;height:14px;background:#d9964b;margin-right:8px;vertical-align:middle;"></span>
                                        Process
                                    </td>

                                    @foreach(json_decode($process) as $v)

                                        <td style="border:1px solid #d3d3d3;">
                                            {{ $v }}
                                        </td>

                                    @endforeach

                                </tr>
                                <tr>
                                    <td style="text-align:left;border:1px solid #d3d3d3;padding-left:20px;">
                                        <span style="display:inline-block;width:14px;height:14px;background:#7f7f7f;margin-right:8px;vertical-align:middle;"></span>
                                        Confirm
                                    </td>

                                    @foreach(json_decode($confirm) as $v)

                                        <td style="border:1px solid #d3d3d3;">
                                            {{ $v }}
                                        </td>

                                    @endforeach

                                </tr>

                                <tr>

                                    <td style="text-align:left;border:1px solid #d3d3d3;padding-left:20px;">
                                        <span style="display:inline-block;width:14px;height:14px;background:#4f81bd;margin-right:8px;vertical-align:middle;"></span>
                                        Closed
                                    </td>

                                    @foreach(json_decode($closed) as $v)

                                        <td style="border:1px solid #d3d3d3;">
                                            {{ $v }}
                                        </td>

                                    @endforeach

                                </tr>

                                <tr>

                                    <td style="text-align:left;border:1px solid #d3d3d3;padding-left:20px;font-weight:bold;">
                                        <span style="display:inline-block;width:14px;height:14px;background:#f2b233;margin-right:8px;vertical-align:middle;"></span>
                                        Total
                                    </td>

                                    @foreach(json_decode($total) as $v)

                                        <td style="border:1px solid #d3d3d3;font-weight:bold;">
                                            {{ $v }}
                                        </td>
                                    @endforeach

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- .card-preview -->
        </div><!-- .row -->
    </div><!-- .nk-block -->
    <br><br>
    <div class="nk-block">
        <div class="row g-gs">
            <div class="card card-preview col-12">
                <div class="card-inner" style="padding-top:10px">

                    <div style="display:flex;justify-content:space-between;align-items:center;
                                border-bottom:solid 2px #dbdfea;padding-bottom:15px;margin-bottom:20px;">

                        <h5 class="card-title mb-0">
                            Tenant Electric Usage
                        </h5>

                        <div style="width:150px;">
                            <select class="form-control" id="year_usage">
                                @foreach($years as $year)
                                    <option value="{{ $year }}"
                                        {{ $selectedYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div style="height:480px!important;">
                        <canvas class="col-sm-12" id="barChartUsage"></canvas>
                    </div>

                    <div class="card-footer">
                        <div id="legendUsage"></div>
                    </div>

                </div>
            </div><!-- .card-preview -->
        </div><!-- .row -->
    </div><!-- .nk-block -->
    <br><br>
    <div class="nk-block">
        <div class="row g-gs">
            <div class="card card-preview col-12">
                <div class="card-inner" style="padding-top:10px">
                    <h5 class="card-title" style="border-bottom: solid 2px #dbdfea;padding-bottom:12px;margin-bottom: 20px;">Overtime</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tblovertimee" width="100%" data-auto-responsive="false">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Lot No</th>
                                    <th>Tenant</th>
                                    <th>Start Overtime</th>
                                    <th>End Overtime</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div><!-- .card-preview -->
        </div><!-- .row -->
    </div><!-- .nk-block -->
    <br><br>
    <div class="nk-block">
        <div class="row g-gs">
            <div class="card card-preview col-12">
                <div class="card-inner" style="padding-top:10px">
                    <h5 class="card-title" style="border-bottom: solid 2px #dbdfea;padding-bottom:12px;margin-bottom: 20px;">Ticket</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tbltickett" width="100%" data-auto-responsive="false">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Ticket Number</th>
                                    <th>Category</th>
                                    <th>Tenant Name</th>
                                    <th>Description</th>
                                    <th>Reported Date</th>
                                    <th>Request By</th>
                                    <th>Lot No</th>
                                    <th>Ticket Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div><!-- .card-preview -->
        </div><!-- .row -->
    </div><!-- .nk-block -->
</div>
<script src="{{ url('assets/js/Chart.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">

$(function() {

    /*
    |--------------------------------------------------------------------------
    | GRAPH 1 : ELECTRIC USAGE
    |--------------------------------------------------------------------------
    */

    var labelsUsage = {!! $labels_usage !!};

    var usage = {!! $usage !!};
    var usageHigh = {!! $usage_high !!};

    // Hitung nilai maksimum data
    var maxData = Math.max.apply(null, usage);

    // Tambahkan ruang 30% lalu bulatkan ke atas ke kelipatan 100.000
    var maxYAxis = Math.ceil((maxData * 1.3) / 100000) * 100000;

    // Jumlah garis sumbu Y
    var steps = 10;
    var stepWidth = Math.ceil(maxYAxis / steps);

    var usageData = {
        labels: labelsUsage,
        datasets: [
            {
                label:"LWBP (Lewat Waktu Beban Puncak) 22:00 - 17:00",
                fillColor: "rgba(60,141,188,0.9)",
                strokeColor: "rgba(60,141,188,0.8)",
                pointColor: "#3b8bba",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(60,141,188,1)",
                data: usage
            }
        ]
    };

    var usageCanvas = $("#barChartUsage").get(0).getContext("2d");

    var usageOptions = {
        scaleOverride: true,
        scaleSteps: steps,
        scaleStepWidth: stepWidth,
        scaleStartValue: 0,
        scaleBeginAtZero: true,
        scaleShowGridLines: true,
        scaleGridLineColor: "rgba(0,0,0,.05)",
        scaleGridLineWidth: 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        barShowStroke: true,
        barStrokeWidth: 2,
        barValueSpacing: 60,
        barDatasetSpacing: 0,
        scaleLabel: "<%= value%> kWh",

        legendTemplate:
            '<table class="table table-borderless">' +
            '<% for(var i=0; i<datasets.length; i++) { %>' +
            '<tr>' +
            '<td width="20">' +
            '<div style="width:15px;height:15px;background:<%=datasets[i].fillColor%>"></div>' +
            '</td>' +
            '<td><%= datasets[i].label %></td>' +
            '</tr>' +
            '<% } %>' +
            '</table>',

        responsive: true,
        maintainAspectRatio: false
    };

    usageOptions.datasetFill = false;

    var usageChart = new Chart(usageCanvas).Bar(
        usageData,
        usageOptions
    );

    document.getElementById("legendUsage").innerHTML =
        usageChart.generateLegend();

    $('#year_usage').change(function () {

        $.get(
            "{{ url('dash/electric-usage') }}",
            {
                year: $(this).val()
            },
            function (res) {

                // Hapus chart lama
                usageChart.destroy();

                // Update data chart
                usageData.labels = res.labels;
                usageData.datasets[0].data = res.usage;

                // Hitung ulang skala Y
                var maxData = Math.max.apply(null, res.usage);

                // Tambah ruang 30% lalu bulatkan ke atas ke kelipatan 100.000
                var maxYAxis = Math.ceil((maxData * 1.3) / 100000) * 100000;

                // Update option chart
                usageOptions.scaleOverride = true;
                usageOptions.scaleStartValue = 0;
                usageOptions.scaleSteps = 10;
                usageOptions.scaleStepWidth = Math.ceil(maxYAxis / 10);

                // Buat chart baru
                usageChart = new Chart(usageCanvas).Bar(
                    usageData,
                    usageOptions
                );

                // Refresh legend
                document.getElementById("legendUsage").innerHTML =
                    usageChart.generateLegend();

            }
        );

    });

    /*
    |--------------------------------------------------------------------------
    | GRAPH 2 : SERVICE STATUS
    |--------------------------------------------------------------------------
    */

    var labelsStatus = {!! $labels_status !!};

    var assigned = {!! $assigned !!};
    var process = {!! $process !!};
    var confirmm = {!! $confirm !!};
    var closedd = {!! $closed !!};
    var total = {!! $total !!};

    var statusData = {
        labels: labelsStatus,
        datasets: [

            {
                label: "Assigned",
                fillColor: "#c0504d",
                strokeColor: "#c0504d",
                pointColor: "#c0504d",
                data: assigned
            },

            {
                label: "Process",
                fillColor: "#d9964b",
                strokeColor: "#d9964b",
                pointColor: "#d9964b",
                data: process
            },

            {
                label: "Confirm",
                fillColor: "#7f7f7f",
                strokeColor: "#7f7f7f",
                pointColor: "#7f7f7f",
                data: confirmm
            },

            {
                label: "Closed",
                fillColor: "#4f81bd",
                strokeColor: "#4f81bd",
                pointColor: "#4f81bd",
                data: closedd
            },

            {
                label: "Total",
                fillColor: "#f2b233",
                strokeColor: "#f2b233",
                pointColor: "#f2b233",
                data: total
            }

        ]
    };

    var statusCanvas = $("#barChartStatus").get(0).getContext("2d");

    var statusOptions = {

        scaleBeginAtZero: true,
        scaleShowGridLines: true,
        scaleGridLineColor: "rgba(0,0,0,.05)",
        scaleGridLineWidth: 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        barShowStroke: true,
        barStrokeWidth: 2,
        barValueSpacing: 5,
        barDatasetSpacing: 1,

        responsive: true,
        maintainAspectRatio: false,

        showTooltips: false,

        animation: false,

        onAnimationComplete: function () {

            var ctx = this.chart.ctx;

            ctx.font = "bold 11px Arial";
            ctx.fillStyle = "#000";
            ctx.textAlign = "center";
            ctx.textBaseline = "bottom";

            var datasets = this.datasets;

            for (var i = 0; i < datasets.length; i++) {

                for (var j = 0; j < datasets[i].bars.length; j++) {

                    var bar = datasets[i].bars[j];

                    ctx.fillText(
                        bar.value,
                        bar.x,
                        bar.y - 5
                    );
                }
            }
        }
    };

    statusOptions.datasetFill = false;

    var statusChart = new Chart(statusCanvas).Bar(
        statusData,
        statusOptions
    );
});



$('#generate').click(function(){

    var site_url = '{{ url("dash/dlpdf")}}';

    $.post(
        site_url,
        {
            file: document.getElementById("barChartUsage").toDataURL(),
            "_token": "{{ csrf_token() }}"
        },
        function(data,status) {

            if(status=='success'){

                window.open(data);

            }else{

                Swal.fire({
                    title: "Information",
                    icon:"error",
                    text: "Failed generating pdf file."
                });

            }
        }
    );

});

</script>

<script text="javascript">
var tblovertime,tblticket;
  $(function() {
    $('.select2').select2();
    tblovertime = $('#tblovertimee').DataTable({
          processing: true,
          serverSide: true,
          // ajax: "{{ url('/group/all') }}",
          ajax: {
            "url" : "{{ url('/dash/data/overtime') }}",
            "type": "POST",
            data: {
              "_token": "{{ csrf_token() }}"
              }
            },
            columns: [
              { data: 'row_number', name: 'row_number' },
              { data: 'lot_no', name: 'lot_no' },
              { data: 'tenant_no', name: 'tenant_no' },
              { data:"start_overtime",name:"start_overtime", sortable: true,
                    render:function (data,type,row) {
                            var a = data.substr(0, 4);
                            var b = data.substr(5, 2);
                            var c = data.substr(8, 2);
                            return c+"-"+b+"-"+a;
                        }
              },
              { data:"end_overtime",name:"end_overtime", sortable: true,
                    render:function (data,type,row) {
                            var a = data.substr(0, 4);
                            var b = data.substr(5, 2);
                            var c = data.substr(8, 2);
                            return c+"-"+b+"-"+a;
                        }
              },
              { data: 'status', name: 'status',
              render:function (data,type,row) {
                            var status='',label='';
                            console=data
                            if(data=='N'){
                                status = "Waiting to be activated";
                                label = "info";
                            }else if(data=='A'){
                                status = "Activated";
                                label = "success";
                            }else if(data=="X"){
                                status = "Canceled";
                                label = "warning";
                            }else if(data=="Z"){
                                status = "Posted";
                                label = "danger";	
                            }
                            return '<span class="badge badge-pill badge-'+label+'">'+status+'</span>';
                        } 
                },
              { data: 'description', name: 'description' },
          ],
          dom: '<"toolbar group">frtip',
          "responsive": {
            details: {
                type: 'column',
                target: 8
            }
          }
      });
        tblticket = $('#tbltickett').DataTable({
            processing: true,
            serverSide: true,
            // ajax: "{{ url('/group/all') }}",
            ajax: {
                "url" : "{{ url('/dash/data/ticket') }}",
                "type": "POST",
                data: {
                "_token": "{{ csrf_token() }}"
                }
                },
            
            columns: [
                {
                    data: null,
                    name: 'row_number',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.settings._iDisplayStart + meta.row + 1;
                    }
                },
                { data: 'complain_no', name: 'complain_no' },
                { data: 'categoryname', name: 'categoryname' },
                { data: 'name', name: 'name' },
                { data: 'work_requested', name: 'work_requested' },
                { data:"reported_date",name:"reported_date", sortable: true,
                        render:function (data,type,row) {
                                var a = data.substr(0, 4);
                                var b = data.substr(5, 2);
                                var c = data.substr(8, 2);
                                return c+"-"+b+"-"+a;
                            }
                },
                { data: 'serv_req_by', name: 'serv_req_by' },
                { data: 'lot_no', name: 'lot_no' },
                { data: 'status', name: 'status',
                render:function (data,type,row) {
                                var status='',label='';
                                if(data=='R'){
                                    status = "Open";
                                    label = "info";
                                }else if(data=='A'){
                                    status = "Accepted";
                                    label = "warning";
                                }else if(data=="Y"){
                                    status = "Approve";
                                    label = "success";
                                }else if(data=='C'){
                                    status = "Close";
                                    label = "danger";
                                }else if(data=="X"){
                                    status = "Cancel";
                                    label = "default";  
                                } else if (data == "S") {
                                    status = "Survey";
                                    label = "info";
                                } else if (data == "P") {
                                    status = "Process";
                                    label = "info";
                                }else if (data == "M") {
                                    status = "Modify";
                                    label = "info";
                                } else if (data == "Z") {
                                    status = "Charged Approved";
                                    label = "info";
                                } else if (data == "F") {
                                    status = "Confirm";
                                    label = "info";
                                }
                                return '<span class="badge badge-pill badge-'+label+'">'+status+'</span>';
                            } 
                    },
            ],
            dom: '<"toolbar group">frtip',
            "responsive": {
                details: {
                    type: 'column',
                    target: 8
                }
            }
        });
  });
</script>
@endsection
