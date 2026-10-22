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
                    
                    <h5 class="card-title" style="border-bottom: solid 2px #dbdfea;padding-bottom:25px;margin-bottom: 20px;">Chart </h5><br>
                    <div style='height: 240px!important;'>
                        <canvas class="col-sm-12" id="barChart" style="width: 571px; "></canvas>
                    </div>
                    
                    <div class="card-footer">
                        <div id="legendDiv"></div>
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
    var labelss = "{{ $labels }}";
    labelss=labelss.replace(/&#039;/g,"'");labelss=labelss.split(",");
    var data1 = "{{ $data1 }}";data1=data1.split(",");
    var data2 = "{{ $data2 }}";data2=data2.split(",");
    var barChartData = {
        labels:labelss, 
        datasets:[
            {
                label:"LWBP (Lewat Waktu Beban Puncak) 22:00 - 17:00",
                fillColor: "rgba(60,141,188,0.9)",
                strokeColor: "rgba(60,141,188,0.8)",
                pointColor: "#3b8bba",
                pointStrokeColor: "rgba(60,141,188,1)",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(60,141,188,1)",
                data:data1
            },
            {
                label:"WBP (Waktu Beban Puncak) 17:00 - 22:00",
                fillColor: "rgba(210, 214, 222, 1)",
                strokeColor: "rgba(210, 214, 222, 1)",
                pointColor: "rgba(210, 214, 222, 1)",
                pointStrokeColor: "#c1c7d1",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data:data2} ]
            };
var barChartCanvas = $("#barChart").get(0).getContext("2d"); 
    barChartData.datasets[1].fillColor = "#00a65a"; 
    barChartData.datasets[1].strokeColor = "#00a65a"; 
    barChartData.datasets[1].pointColor = "#00a65a";
var barChartOptions = {
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
    scaleLabel: "<%= value%> kWh",
    legendTemplate: "<table><% for(var i=0;i<datasets.length; i++) {%><tr><td><div class=\"boxx\" style=\"background-color:<%=datasets[i].fillColor %>\"></div></td><% if(datasets[i].label) { %><td>&nbsp<%= datasets[i].label %></td></tr><% } %><% } %><tr height=\"5\"></tr></table>",
    responsive: true,
    maintainAspectRatio: false
  };
    barChartOptions.datasetFill = false;
    var barChart = new Chart(barChartCanvas).Bar(barChartData,barChartOptions);
    document.getElementById("legendDiv").innerHTML = barChart.generateLegend()
}); 
$('#generate').click(function(){
   
        var site_url = '{{ url("dash/dlpdf")}}';
            $.post(site_url,
                {file:document.getElementById("barChart").toDataURL(),"_token": "{{ csrf_token() }}" },
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
            });
    
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
              { data: 'row_number', name: 'row_number' },
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
                            }else if(data=='A'||data=='S'||data=='P'||data=='M'||data=='Z'){
                                status = "Process";
                                label = "warning";
                            }else if(data=="Y"){
                                status = "Approve";
                                label = "success";
                            }else if(data=='C'||data=='F'){
                                status = "Close";
                                label = "danger";
                            }else if(data=="X"){
                                status = "Cancel";
                                label = "default";  
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
