<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>IFCA Software</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ url('assets/css/exportpdf/bootstrap.min.css');}}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    {{-- <link href="{{ url('assets/css/exportpdf/font-awesome.min.css'); }}" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="{{ asset('public/lainnya/plugins/ionicons-2.0.1/css/ionicons.min.css'); }}" rel="stylesheet" type="text/css" /> --}}
    <!-- Theme -->
    <link href="{{ url('assets/css/exportpdf/skin-yellow.min.css'); }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/css/exportpdf/AdminLTE.min.css'); }}" rel="stylesheet" type="text/css" />
    <style type="text/css">.boxx{position: relative;width: 16px;height: 16px;border-radius: 3px;}thead:before, thead:after { display: none; }
tbody:before, tbody:after { display: none; }.footer{position: fixed; bottom: 0px; text-align: center;}</style>
  </head>
  <!-- <body onload="window.print();"> -->
  <body>
    <section class="invoice">
      <h4 style="font-family: Tahoma!important;">Electricity Summary Usage</h4><hr/>
      <div class="col-sm-12">
        <!-- <canvas id="barChart" style="width: 320px; height: 240px;"></canvas> -->
        <img id="chart" style="width: 600px;" src="{{ $im }}" />
        <div id="legendDiv"></div>
      </div>        
      <hr>
      <table class="table table-bordered table-striped dataTable" role="grid">
        <thead style="background:#ffa500;">
          <tr>
            <th>Period</th>
            <th>LWBP usage (kWh)</th>
            <th>WBP usage (kWh)</th>
          </tr>            
        </thead>
        <tbody>
            <?php echo $cl;?>
        </tbody>
      </table>
    </section>
    <div class="footer">
        <p style="font-size:8px">Capital Place Tenant Web Portal may contain information that is created and managed by various sources, both internal and external. At no time shall Capital Place Building Management be responsible or liable, directly or indirectly, for any damage or loss resulting from or alleged to result from the use of or reliance on any such content in Capital Place Tenant Web Portal</p>
      </div> 
  </body>
</html>
