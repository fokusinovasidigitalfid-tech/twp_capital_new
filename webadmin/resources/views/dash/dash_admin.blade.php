@extends('template.layout2.base')
@section('content')
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
                <div class="card-inner">
                    <ul class="nav nav-tabs mt-n3">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#t_overtime"><em class="icon ni ni-user"></em><span>Overtime  <span class="badge badge-pill badge-success"><?php echo $cntOT ?></span></span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#t_ticket"><em class="icon ni ni-lock-alt"></em><span>Ticket  <span class="badge badge-pill badge-success"><?php echo $cntTick ?></span></span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#t_application"><em class="icon ni ni-bell"></em><span>Application  <span class="badge badge-pill badge-success"><?php echo $cntAppl ?></span></span></a>
                        </li>
                       
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="t_overtime">
                            <div class="row" id="filter_OT">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label" for="default-01">Business</label>
                                        <div class="form-control-wrap">
                                            <select name="bussiness_id_OT" id="business_id_OT" data-placeholder="Choose Business ID" class="form-control select2" tabindex="2">
                                                <option value=""></option>
                                                <option value="all">All</option>
                                                <?php if(!empty($dtBusiness)) {
                                                    foreach($dtBusiness as $key){
                                                        echo "<option value='".$key->business_id."'>".$key->name."</option>";
                                                    }  
                                                } ?>  
                                            </select>
                                        </div>
                                    </div>
                                    <label for="pl_project" class="col-sm-2 form-label" style="padding-left:0px;"> Periode</label>
                                    <div class="form-group">
                                        <div class="row" style="margin-left: 0px">
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-left">
                                                    <em class="icon ni ni-calendar"></em>
                                                </div>
                                                <input type="text" id="start_OT" name="start_OT" class="form-control date-picker" data-date-format="dd/mm/yyyy" value="" width="50%">
                                            </div>
                                            <span class="badge-sm badge-gray badge-dim" style="font-size: 15px"> to </span>
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-left">
                                                    <em class="icon ni ni-calendar"></em>
                                                </div>
                                                <input type="text" id="end_OT" name="end_OT" class="form-control date-picker" data-date-format="dd/mm/yyyy" value="" width="50%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label" for="default-01">Debtor</label>
                                        <div class="form-control-wrap">
                                            <select name="debtor_OT" id="debtor_OT" data-placeholder="Choose Debtor" class="form-control select2" tabindex="2">
                                                <option value=""></option>
                                                <option value="all">All</option>
                                                <?php if(!empty($dtDebtor)) {
                                                    foreach($dtDebtor as $key){
                                                        echo "<option value='".$key->debtor_acct."'>".$key->name."</option>";
                                                    }  
                                                } ?>  
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="default-01">Lot No.</label>
                                        <div class="form-control-wrap">
                                            <select name="lot_OT" id="lot_OT" data-placeholder="Choose Lot No." class="form-control select2" tabindex="2">
                                                <option value=""></option>
                                                <option value="all">All</option>
                                                <?php if(!empty($dtLot)) {
                                                    foreach($dtLot as $key){
                                                        echo "<option value='".$key->lot_no."'>".$key->lot_no."</option>";
                                                    }  
                                                } ?>  
                                            </select>
                                        </div>
                                    </div>
                                    <div class="pull-right" style="text-align: right">
                                        <button class="btn btn-primary" onclick="fn_searchOT()"><em class="icon ni ni-search"></em> Search</button>&nbsp;
                                        <button class="btn btn-success" id="download_pdf"> <em class="icon ni ni-download"></em><span>Download PDF</span></button>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tblovertime" width="100%">
                                    <thead>
                                    <tr>
                                        <th class="sorting_asc">No.</th>
                                        <th>Lot No.</th>
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
                        <div class="tab-pane" id="t_ticket">
                            <div class="row" id="filter_ticket">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label" for="default-01">Business</label>
                                        <div class="form-control-wrap">
                                            <select name="business_id_ticket" id="business_id_ticket" data-placeholder="Choose Business ID" class="form-control select2" tabindex="2">
                                                <option value=""></option>
                                                <option value="all">All</option>
                                                <?php if(!empty($dtBusiness)) {
                                                    foreach($dtBusiness as $key){
                                                        echo "<option value='".$key->business_id."'>".$key->name."</option>";
                                                    }  
                                                } ?>  
                                            </select>
                                        </div>
                                    </div>
                                    <label for="pl_project" class="col-sm-2 form-label" style="padding-left:0px;"> Periode</label>
                                    <div class="form-group">
                                        <div class="row" style="margin-left: 0px">
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-left">
                                                    <em class="icon ni ni-calendar"></em>
                                                </div>
                                                <input type="text" id="start_ticket" name="start_ticket" class="form-control date-picker" data-date-format="dd/mm/yyyy" value="" width="50%">
                                            </div>
                                            <span class="badge-sm badge-gray badge-dim" style="font-size: 15px"> to </span>
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-left">
                                                    <em class="icon ni ni-calendar"></em>
                                                </div>
                                                <input type="text" id="end_ticket" name="end_ticket" class="form-control date-picker" data-date-format="dd/mm/yyyy" value="" width="50%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label" for="default-01">Debtor</label>
                                        <div class="form-control-wrap">
                                            <select name="debtor_ticket" id="debtor_ticket" data-placeholder="Choose Debtor" class="form-control select2" tabindex="2">
                                                <option value=""></option>
                                                <option value="all">All</option>
                                                <?php if(!empty($dtDebtor)) {
                                                    foreach($dtDebtor as $key){
                                                        echo "<option value='".$key->debtor_acct."'>".$key->name."</option>";
                                                    }  
                                                } ?>  
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="default-01">Category</label>
                                        <div class="form-control-wrap">
                                            <select name="category_ticket" id="category_ticket" data-placeholder="Choose Category" class="form-control select2" tabindex="2">
                                                <option value=""></option>
                                                <option value="all">All</option>
                                                <?php if(!empty($dtCategory)) {
                                                    foreach($dtCategory as $key){
                                                        echo "<option value='".$key->category_cd."'>".$key->descs."</option>";
                                                    }  
                                                } ?>   
                                            </select>
                                        </div>
                                    </div>
                                    <div class="pull-right" style="text-align: right">
                                        <button class="btn btn-primary" onclick="fn_searchTick()"><em class="icon ni ni-search"></em> Search</button>&nbsp;
                                        
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="tblticket" class="table table-striped table-bordered table-hover dataTables" cellspacing="0" width="100%">
                                    <thead>            
                                        <th class="sorting_asc">No.</th>
                                        <th>Ticket Number</th>
                                        <th>Category</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Reported Date</th>
                                        <th>Request By</th>
                                        <th>Lot Number</th>
                                        <th>Ticket Status</th>
                                        {{-- <th></th> --}}
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="t_application">
                            <div class="row" id="filter_app">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label" for="default-01">Business</label>
                                        <div class="form-control-wrap">
                                            <select name="business_id_app" id="business_id_app" data-placeholder="Choose Business ID" class="form-control select2" tabindex="2">
                                                <option value=""></option>
                                                <option value="all">All</option>
                                                <?php if(!empty($dtBusiness)) {
                                                    foreach($dtBusiness as $key){
                                                        echo "<option value='".$key->business_id."'>".$key->name."</option>";
                                                    }  
                                                } ?>  
                                            </select>
                                        </div>
                                    </div>
                                    <label for="pl_project" class="col-sm-2 form-label" style="padding-left:0px;"> Periode</label>
                                    <div class="form-group">
                                        <div class="row" style="margin-left: 0px">
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-left">
                                                    <em class="icon ni ni-calendar"></em>
                                                </div>
                                                <input type="text" id="start_app" name="start_app" class="form-control date-picker" data-date-format="dd/mm/yyyy" value="" width="50%">
                                            </div>
                                            <span class="badge-sm badge-gray badge-dim" style="font-size: 15px"> to </span>
                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-left">
                                                    <em class="icon ni ni-calendar"></em>
                                                </div>
                                                <input type="text" id="end_app" name="end_app" class="form-control date-picker" data-date-format="dd/mm/yyyy" value="" width="50%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label" for="default-01">Debtor</label>
                                        <div class="form-control-wrap">
                                            <select name="debtor_app" id="debtor_app" data-placeholder="Choose Debtor" class="form-control select2" tabindex="2">
                                                <option value=""></option>
                                                <option value="all">All</option>
                                                <?php if(!empty($dtDebtor)) {
                                                    foreach($dtDebtor as $key){
                                                        echo "<option value='".$key->debtor_acct."'>".$key->name."</option>";
                                                    }  
                                                } ?>  
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="default-01">Category</label>
                                        <div class="form-control-wrap">
                                            <select name="category_app" id="category_app" data-placeholder="Choose Category" class="form-control select2" tabindex="2">
                                                <option value=""></option>
                                                <option value="all">All</option>
                                                <?php if(!empty($dtCategory)) {
                                                    foreach($dtCategory as $key){
                                                        echo "<option value='".$key->category_cd."'>".$key->descs."</option>";
                                                    }  
                                                } ?>   
                                            </select>
                                        </div>
                                    </div>
                                    <div class="pull-right" style="text-align: right">
                                        <button class="btn btn-primary" onclick="fn_searchApp()"><em class="icon ni ni-search"></em> Search</button>&nbsp;
                                        
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="tblapplication" class="table table-striped table-bordered table-hover dataTables" cellspacing="0" width="100%">
                                    <thead>            
                                        <th class="sorting_asc">No.</th>
                                        <th>Application Number</th>
                                        <th>Category</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Reported Date</th>
                                        <th>Request By</th>
                                        <th>Lot Number</th>
                                        <th>Application Status</th>
                                        {{-- <th></th> --}}
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>      
                            </div>
                        </div>
                
                    </div>
                </div>
            </div>
          
        </div><!-- .row -->
    </div><!-- .nk-block -->
</div>
<script text="javascript">

    $("#filter_ticket").hide()
    $("#filter_app").hide()
    $("#filter_OT").hide()
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    $('.select2').select2({width:'100%'});
    tblovertimee = $('#tblovertime').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            "url" : "{{ url('/dash/data/admin/ot') }}",
            "type": "POST",
            data: {
              "_token": "{{ csrf_token() }}",
                        "date_end": function(d){
                            var a = $('#end_OT').val();

                            var date = new Date(parseInt(a.substr(0,10)));
                            var year =a.substr(6,4);
                            var month=a.substr(3,2);
                            var day =a.substr(0,2);
                                       
                            var aa1 = year+"/"+month+"/"+day;
                           // console.log(aa);
                            var b ="";
                            if(aa1 == "//"){
                                return b;
                            }{
                                return aa1;
                            }
                       
                        },
                        "date_start": function(d){
                            var a = $('#start_OT').val();
                            var date = new Date(parseInt(a.substr(0,10)));
                            var year =a.substr(6,4);
                            var month=a.substr(3,2);
                            var day =a.substr(0,2);
                                       
                            var aa1 = year+"/"+month+"/"+day;
                            // console.log(aa);
                            var b ="";
                            if(aa1 == "//"){
                                return b;
                            }{
                                return aa1;
                            }
                            // console.log(a);
                        },
                        "debtor_acct": function (d) {
                            var search = $('#debtor_OT').val();
                            var b = "";
                            if (search == null || search == "" || search == "all") {
                                return b;
                            } {
                                return search;
                            }
                        },
                        "business_id": function (d) {
                            var search = $('#business_id_OT').val();
                            var b = "";
                            if (search == null || search == "" || search == "all") {
                                return b;
                            } {
                                return search;
                            }
                        },
                        "lot_no": function (d) {
                            var search = $('#lot_OT').val();
                            var b = "";
                            if (search == null || search == "" || search == "all") {
                                return b;
                            } {
                                return search;
                            }
                        }
              }
            },
          columns: [
            {data: "row_number",name: "row_number",searchable: false},
                    {data: "lot_no",name: "lot_no",sortable: false},
                    {data: "debtor_acct",name: "debtor_acct",sortable: false},
                    {data: "start_overtime",name: "start_overtime",
                        render: function (data, type, row) {
                      
                            return FormatDateTimeNew(data);
                        }
                    },
                    {data: "end_overtime",name: "end_overtime",
                        render: function (data, type, row) {
                            return FormatDateTimeNew(data); 
                        }
                    },
                    {data: "status",name: "status",
                        render: function (data, type, row) {
                            if (data == 'N') {
                                return '<span class="badge badge-pill badge-primary"> Open </span>';
                            } else if (data == 'R') {
                                return '<span class="badge badge-pill badge-info"> Progress </span>';
                            } else {
                                return '<span class="badge badge-pill badge-danger"> Close </span>';
                            }
                        }
                    },
                    {data: "description",name: "description",sortable: false}
          ],
          dom: '<"toolbar overtimee">frtip',
          "responsive": {
            details: {
                type: 'column',
                target: 7
            }
          }
      });
      $("div.overtimee").html(
        '<button  class="btn btn-primary pull-up" style="margin-top: 5px" onclick="fn_filter1()">Specific search</button>'
      );
      tbltickett = $('#tblticket').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            "url" : "{{ url('/dash/data/admin/ticket') }}",
            "type": "POST",
            data: {
              "_token": "{{ csrf_token() }}",
              "date_end": function(d){
                            var a = $('#end_ticket').val();

                            var date = new Date(parseInt(a.substr(0,10)));
                            var year =a.substr(6,4);
                            var month=a.substr(3,2);
                            var day =a.substr(0,2);
                                       
                            var aa1 = year+"/"+month+"/"+day;
                           // console.log(aa);
                            var b ="";
                            if(aa1 == "//"){
                                return b;
                            }{
                                return aa1;
                            }
                       
                        },
                        "date_start": function(d){
                            var a = $('#start_ticket').val();
                            var date = new Date(parseInt(a.substr(0,10)));
                            var year =a.substr(6,4);
                            var month=a.substr(3,2);
                            var day =a.substr(0,2);
                                       
                            var aa1 = year+"/"+month+"/"+day;
                            // console.log(aa);
                            var b ="";
                            if(aa1 == "//"){
                                return b;
                            }{
                                return aa1;
                            }
                            // console.log(a);
                        },
                        "debtor_acct": function (d) {
                            var search = $('#debtor_ticket').val();
                            var b = "";
                            if (search == null || search == "" || search == "all") {
                                return b;
                            } {
                                return search;
                            }
                        },
                        "business_id": function (d) {
                            var search = $('#business_id_ticket').val();
                            var b = "";
                            if (search == null || search == "" || search == "all") {
                                return b;
                            } {
                                return search;
                            }
                        },
                        "category": function (d) {
                            var search = $('#category_ticket').val();
                            var b = "";
                            if (search == null || search == "" || search == "all") {
                                return b;
                            } {
                                return search;
                            }
                        }
              }
            },
          columns: [
                    {data: "row_number",name: "row_number",searchable: false},
                    {data: "complain_no",name: "complain_no",sortable: true},
                    {data: "category_descs",name: "category_descs"},
                    {data: "name",name: "name"},
                    {data: "work_requested",name: "work_requested"},
                    {
                        data: "reported_date",
                        name: "reported_date",
                        render: function (data, type, row) {
                            return FormatDateNew(data); 
                        }
                    },
                    {data: "serv_req_by",name: "serv_req_by",sortable: true},
                    {data: "lot_no",name: "lot_no",sortable: true},
                    {data: "status",name: "status",
                        render: function (data, type, row) {
                            if (data == 'R') {
                                return '<span class="badge badge-pill badge-primary"> Open </span>';
                            } else if (data == 'C') {
                                return '<span class="badge badge-pill badge-info"> Progress </span>';
                            } else {
                                return '<span class="badge badge-pill badge-danger"> Close </span>';
                            }
                        }
                    }
          ],
          dom: '<"toolbar tickett">frtip',
          "responsive": {
            details: {
                type: 'column',
                target: 9
            }
          }
      });
      $("div.tickett").html(
        '<button  class="btn btn-primary pull-up" style="margin-top: 5px" onclick="fn_filter2()">Specific search</button>'
      );
      tblapplicationn = $('#tblapplication').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            "url" : "{{ url('/dash/data/admin/application') }}",
            "type": "POST",
            data: {
              "_token": "{{ csrf_token() }}",
              "date_end": function(d){
                            var a = $('#end_app').val();

                            var date = new Date(parseInt(a.substr(0,10)));
                            var year =a.substr(6,4);
                            var month=a.substr(3,2);
                            var day =a.substr(0,2);
                                       
                            var aa1 = year+"/"+month+"/"+day;
                           // console.log(aa);
                            var b ="";
                            if(aa1 == "//"){
                                return b;
                            }{
                                return aa1;
                            }
                       
                        },
                        "date_start": function(d){
                            var a = $('#start_app').val();
                            var date = new Date(parseInt(a.substr(0,10)));
                            var year =a.substr(6,4);
                            var month=a.substr(3,2);
                            var day =a.substr(0,2);
                                       
                            var aa1 = year+"/"+month+"/"+day;
                            // console.log(aa);
                            var b ="";
                            if(aa1 == "//"){
                                return b;
                            }{
                                return aa1;
                            }
                            // console.log(a);
                        },
                        "debtor_acct": function (d) {
                            var search = $('#debtor_app').val();
                            var b = "";
                            if (search == null || search == "" || search == "all") {
                                return b;
                            } {
                                return search;
                            }
                        },
                        "business_id": function (d) {
                            var search = $('#business_id_app').val();
                            var b = "";
                            if (search == null || search == "" || search == "all") {
                                return b;
                            } {
                                return search;
                            }
                        },
                        "category": function (d) {
                            var search = $('#category_app').val();
                            var b = "";
                            if (search == null || search == "" || search == "all") {
                                return b;
                            } {
                                return search;
                            }
                        }
              }
            },
          columns: [
                    {data: "row_number",name: "row_number",searchable: false},
                    {data: "complain_no",name: "complain_no",sortable: true},
                    {data: "category_descs",name: "category_descs"},
                    {data: "name",name: "name"},
                    {data: "work_requested",name: "work_requested"},
                    {
                        data: "reported_date",
                        name: "reported_date",
                        render: function (data, type, row) {
                            return FormatDateNew(data); 
                        }
                    },
                    {data: "serv_req_by",name: "serv_req_by",sortable: true},
                    {data: "lot_no",name: "lot_no",sortable: true},
                    {data: "status",name: "status",
                        render: function (data, type, row) {
                            if (data == 'R') {
                                return '<span class="badge badge-pill badge-primary"> Open </span>';
                            } else if (data == 'C') {
                                return '<span class="badge badge-pill badge-info"> Progress </span>';
                            } else {
                                return '<span class="badge badge-pill badge-danger"> Close </span>';
                            }
                        }
                    }
          ],
          dom: '<"toolbar applicationn">frtip',
          "responsive": {
            details: {
                type: 'column',
                target: 9
            }
          }
      });
      $("div.applicationn").html(
        '<button  class="btn btn-primary pull-up" style="margin-top: 5px" onclick="fn_filter3()">Specific search</button>'
      );
        function fn_filter1(){
            $('#filter_OT').toggle('slow');
            $('#start_OT').val('');
            $('#end_OT').val('');
            $('#business_id_OT').select2('val', '');
            $('#debtor_OT').select2('val', '');
            $('#lot_OT').select2('val', '');
        }
        function fn_filter2(){
            $('#filter_ticket').toggle('slow');
            $('#start_ticket').val('');
            $('#end_ticket').val('');
            $('#business_id_ticket').select2('val', '');
            $('#debtor_ticket').select2('val', '');
            $('#category_ticket').select2('val', '');
        }
        function fn_filter3(){
            $('#filter_app').toggle('slow');
            $('#start_app').val('');
            $('#end_app').val('');
            $('#business_id_app').select2('val', '');
            $('#debtor_app').select2('val', '');
            $('#category_app').select2('val', '');
        }

        function fn_searchTick() {
            tbltickett.ajax.reload(null, true);   
        }
        function fn_searchApp() {
            tblapplicationn.ajax.reload(null, true);
        }
        function fn_searchOT() {
            tblovertimee.ajax.reload(null, true);
        }
        $('#business_id_OT').change(function(){
            var prod = $(this).find(':selected').val();
            
            // alert(prod);
            if(prod!==''){
            var site_url = '{{ url("dash/zoom/debtor")}}';
                $.post(site_url,
                {prod:prod},
                function(data,status) {
                    $("#debtor_OT").empty();
                    $("#debtor_OT").append(data);
                    $("#debtor_OT").trigger('change');
                }
                );

            }
            
        });
        $('#business_id_ticket').change(function(){
            var prod = $(this).find(':selected').val();
            
            // alert(prod);
            if(prod!==''){
            var site_url = '{{ url("dash/zoom/debtor")}}';
                $.post(site_url,
                {prod:prod},
                function(data,status) {
                    $("#debtor_ticket").empty();
                    $("#debtor_ticket").append(data);
                    $("#debtor_ticket").trigger('change');
                }
                );

            }
            
        });
        $('#business_id_app').change(function(){
            var prod = $(this).find(':selected').val();
            
            // alert(prod);
            if(prod!==''){
            var site_url = '{{ url("dash/zoom/debtor")}}';
                $.post(site_url,
                {prod:prod},
                function(data,status) {
                    $("#debtor_app").empty();
                    $("#debtor_app").append(data);
                    $("#debtor_app").trigger('change');
                }
                );

            }
            
        });
        $('#download_pdf').click(function(){
       // alert('aha');
            var start_date = $('#start_OT').val();
            var end_date = $('#end_OT').val();
            var business_id = $('#business_id_OT').find(':selected').val();
            var debtor = $('#debtor_OT').find(':selected').val();
            var lot_no = $('#lot_OT').find(':selected').val();

                // console.log(business_id +'-'+ debtor +'-'+ lot_no);

            window.location.href="{{ url('dash/admin/show_pdf') }}/"+btoa(start_date+'=%='+end_date+'=%='+business_id+'=%='+debtor+'=%='+lot_no);
                
        });
</script>
@endsection
