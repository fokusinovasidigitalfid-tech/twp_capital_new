@extends('template.layout2.base')
@section('content')
<style type="text/css">
    .toolbar {
        float: left;
        margin-bottom: 1em;
    }
</style>
<div class="nk-content-body">
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">Overtime Approval</h4>
                </div>
            </div>
            <div class="card card-preview">
                <div class="card-inner">
                    <ul class="nav nav-tabs mt-n3">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#t_new"><em class="icon ni ni-edit"></em> &nbsp;New Overtime</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#t_published"><em class="icon ni ni-list-check"></em> &nbsp; Approved Overtime</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#t_canceled"><em class="icon ni ni-cross-circle"></em> &nbsp; Cancelled Overtime</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="t_new">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tblOTn" width="100%">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        {{-- <th>Entity</th>
                                        <th>Project</th> --}}
                                        <th>Lot No</th>
                                        <th>Tenant</th>
                                        <th>Start Overtime</th>
                                        <th>End Overtime</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="t_published">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tblOTa" width="100%">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        {{-- <th>Entity</th>
                                        <th>Project</th> --}}
                                        <th>Lot No</th>
                                        <th>Tenant</th>
                                        <th>Start Overtime</th>
                                        <th>End Overtime</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="t_canceled">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" id="tblOTc" width="100%">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        {{-- <th>Entity</th>
                                        <th>Project</th> --}}
                                        <th>Lot No</th>
                                        <th>Tenant</th>
                                        <th>Start Overtime</th>
                                        <th>End Overtime</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div><!-- .card-preview -->
        </div> <!-- nk-block -->
    </div>
</div>

<script type="text/javascript">
  var tblOTApp,tblOTNew;
  $(function() {
    tblOTNew = $('#tblOTn').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            "url" : "{{ url('/overtime/data/new') }}",
            "type": "POST",
            data: {
              "_token": "{{ csrf_token() }}"
              }
            },
          columns: [
            { data: 'row_number', name: 'row_number' },
            // { data: 'entity_cd', name: 'entity_cd',
            //     render:function (data,type,row) {
            //             return data+" - "+row.entity_desc;
            //         } 
            // },
            // { data: 'project_no', name: 'project_no' ,
            //     render:function (data,type,row) {
            //             return data+" - "+row.project_desc;
            //         } },
              { data: 'lot_no', name: 'lot_no' },
              { data: 'tenant_no', name: 'tenant_no' },
              { data:"start_overtime",name:"start_overtime", sortable: true,
                    render:function (data,type,row) {
                            // var a = data.substr(0, 4);
                            // var b = data.substr(5, 2);
                            // var c = data.substr(8, 2);
                            // return c+"-"+b+"-"+a;
                            return FormatDateTimeNew(data);
                        }
              },
              { data:"end_overtime",name:"end_overtime", sortable: true,
                    render:function (data,type,row) {
                            // var a = data.substr(0, 4);
                            // var b = data.substr(5, 2);
                            // var c = data.substr(8, 2);
                            // return c+"-"+b+"-"+a;
                            return FormatDateTimeNew(data);
                        }
              },
              { data: 'description', name: 'description' },
              { data: 'status', name: 'status',
              render:function (data,type,row) {
                            var status='',label='';
                            console=data
                            if(data=='N'){
                                status = "Waiting to be approved";
                                label = "info";
                            }else if(data=='A'){
                                status = "Approved";
                                label = "success";
                            }else if(data=="X"){
                                status = "Canceled";
                                label = "warning";
                            }else if(data=="Z"){
                                status = "Closed";
                                label = "danger";   
                            }
                            return '<span class="badge badge-pill badge-'+label+'">'+status+'</span>';
                        } 
                },
                { data: 'id', name: 'id' ,
                render:function (data,type,row) {
                        return '<button onclick="update(\''+data+'\')" class="btn btn-sm btn-primary">Approve</button>&nbsp;&nbsp;<button onclick="cancel(\''+data+'\')" class="btn btn-sm btn-danger">Cancel</button>';
                    } },
          ],
          dom: '<"toolbar group">frtip',
          "responsive": {
            details: {
                type: 'column',
                target: 8
            }
          }
    });
    tblOTApp = $('#tblOTa').DataTable({
          processing: true,
          serverSide: true,
          // ajax: "{{ url('/group/all') }}",
          ajax: {
            "url" : "{{ url('/overtime/data/app') }}",
            "type": "POST",
            data: {
              "_token": "{{ csrf_token() }}"
              }
            },
          columns: [
            { data: 'row_number', name: 'row_number' },
            // { data: 'entity_cd', name: 'entity_cd',
            //     render:function (data,type,row) {
            //             return data+" - "+row.entity_desc;
            //         } 
            // },
            // { data: 'project_no', name: 'project_no' ,
            //     render:function (data,type,row) {
            //             return data+" - "+row.project_desc;
            //         } },
              { data: 'lot_no', name: 'lot_no' },
              { data: 'tenant_no', name: 'tenant_no' },
              { data:"start_overtime",name:"start_overtime", sortable: true,
                    render:function (data,type,row) {
                            // var a = data.substr(0, 4);
                            // var b = data.substr(5, 2);
                            // var c = data.substr(8, 2);
                            // return c+"-"+b+"-"+a;
                            return FormatDateTimeNew(data);
                        }
              },
              { data:"end_overtime",name:"end_overtime", sortable: true,
                    render:function (data,type,row) {
                            // var a = data.substr(0, 4);
                            // var b = data.substr(5, 2);
                            // var c = data.substr(8, 2);
                            // return c+"-"+b+"-"+a;
                            return FormatDateTimeNew(data);
                        }
              },
              { data: 'description', name: 'description' },
              { data: 'status', name: 'status',
              render:function (data,type,row) {
                            var status='',label='';
                            console=data
                            if(data=='N'){
                                status = "Waiting to be approved";
                                label = "info";
                            }else if(data=='A'){
                                status = "Approved";
                                label = "success";
                            }else if(data=="X"){
                                status = "Canceled";
                                label = "warning";
                            }else if(data=="Z"){
                                status = "Closed";
                                label = "danger";   
                            }
                            return '<span class="badge badge-pill badge-'+label+'">'+status+'</span>';
                        } 
                }
          ],
          dom: '<"toolbar group">frtip',
          "responsive": {
            details: {
                type: 'column',
                target: 8
            }
          }
    });
     
    tblOTCancel = $('#tblOTc').DataTable({
          processing: true,
          serverSide: true,
          // ajax: "{{ url('/group/all') }}",
          ajax: {
            "url" : "{{ url('/overtime/data/cancel') }}",
            "type": "POST",
            data: {
              "_token": "{{ csrf_token() }}"
              }
            },
          columns: [
            { data: 'row_number', name: 'row_number' },
            // { data: 'entity_cd', name: 'entity_cd',
            //     render:function (data,type,row) {
            //             return data+" - "+row.entity_desc;
            //         } 
            // },
            // { data: 'project_no', name: 'project_no' ,
            //     render:function (data,type,row) {
            //             return data+" - "+row.project_desc;
            //         } },
              { data: 'lot_no', name: 'lot_no' },
              { data: 'tenant_no', name: 'tenant_no' },
              { data:"start_overtime",name:"start_overtime", sortable: true,
                    render:function (data,type,row) {
                            // var a = data.substr(0, 4);
                            // var b = data.substr(5, 2);
                            // var c = data.substr(8, 2);
                            // return c+"-"+b+"-"+a;
                            return FormatDateTimeNew(data);
                        }
              },
              { data:"end_overtime",name:"end_overtime", sortable: true,
                    render:function (data,type,row) {
                            // var a = data.substr(0, 4);
                            // var b = data.substr(5, 2);
                            // var c = data.substr(8, 2);
                            // return c+"-"+b+"-"+a;
                            return FormatDateTimeNew(data);
                        }
              },
              { data: 'description', name: 'description' },
              { data: 'status', name: 'status',
              render:function (data,type,row) {
                            var status='',label='';
                            console=data
                            if(data=='N'){
                                status = "Waiting to be approved";
                                label = "info";
                            }else if(data=='A'){
                                status = "Approved";
                                label = "success";
                            }else if(data=="X"){
                                status = "Canceled";
                                label = "warning";
                            }else if(data=="Z"){
                                status = "Closed";
                                label = "danger";   
                            }
                            return '<span class="badge badge-pill badge-'+label+'">'+status+'</span>';
                        } 
                }
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
    function update(id) {
        block('div.card-inner',true);
        Swal.fire({
                title: 'Approve Overtime',
                text: 'Are you sure you want to approve this overtime?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            })
            .then(function(a){
                if (a.value==true) {
                    $.ajax({
                        url : "{{ url('/overtime/approve') }}",
                        type:"POST",
                        data: { id: id,"_token": "{{ csrf_token() }}" },
                        dataType:"json",
                        success:function(event, data){
                            if(event.status=='OK'){
                                Swal.fire("Information",event.pesan,"success");
                                tblOTNew.ajax.reload(null,true);
                                tblOTApp.ajax.reload(null,true);
                                tblOTCancel.ajax.reload(null,true);
                            }else{
                                Swal.fire("Information",event.pesan,"error");
                                tblOTNew.ajax.reload(null,true);
                                tblOTApp.ajax.reload(null,true);
                                tblOTCancel.ajax.reload(null,true);
                            }
                            block('div.card-inner',false);
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            Swal.fire("Information",textStatus+' Save : '+errorThrown,"warning");
                            block('div.card-inner',false);
                        }
                    });
                }else{
                    block('div.card-inner',false);
                }
            })
  
    }
    function cancel(id) {
        block('div.card-inner',true);
        Swal.fire({
                title: 'Cancel Overtime',
                text: 'Are you sure you want to cancel this overtime?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            })
            .then(function(a){
                if (a.value==true) {
                    $.ajax({
                        url : "{{ url('/overtime/cancel') }}",
                        type:"POST",
                        data: { id: id,"_token": "{{ csrf_token() }}" },
                        dataType:"json",
                        success:function(event, data){
                            if(event.status=='OK'){
                                Swal.fire("Information",event.pesan,"success");
                                tblOTNew.ajax.reload(null,true);
                                tblOTApp.ajax.reload(null,true);
                                tblOTCancel.ajax.reload(null,true);
                            }else{
                                Swal.fire("Information",event.pesan,"error");
                                tblOTNew.ajax.reload(null,true);
                                tblOTApp.ajax.reload(null,true);
                                tblOTCancel.ajax.reload(null,true);
                            }
                            block('div.card-inner',false);
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            Swal.fire("Information",textStatus+' Save : '+errorThrown,"warning");
                            block('div.card-inner',false);
                        }
                    });
                }else{
                    block('div.card-inner',false);
                }
            })
  
    }
</script>

@endsection

