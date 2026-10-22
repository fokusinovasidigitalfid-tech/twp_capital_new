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
                    <h4 class="nk-block-title">Overtime Posting</h4>
                </div>
            </div>
            <div class="card card-preview">
                <div class="card-inner" id="ot_body">
                    <form id="f_ot" enctype="multipart/form-data" method="post" action="">
                    <div class="col-lg-12 row" style="padding-bottom:20px">
                        <div class="form-group col-4">
                            <label class="form-label" style="padding-right: 0px;">Entity</label>
                            <div class="input-group">                            
                                <div class="col-11" style="padding:0px"><select name="entity" id="entity" data-placeholder="Choose Entity" class="form-control select2" tabindex="2" >
                                    <option value=""></option>
                                    {{-- <option value="all">All</option> --}}
                                    <?php if(!empty($dataent)) {
                                        foreach($dataent as $key){
                                            echo "<option value='".$key->entity_cd."'>".$key->entity_cd.' - '.$key->entity_name."</option>";
                                        }  
                                    } ?>  
                                </select></div>
                            </div>
                        </div>
                        <div class="form-group col-8">
                            <label class="form-label" style="padding-right: 0px;">Project</label>
                            <div class="input-group">                            
                                <div class="col-10" style="padding:0px"><select name="project" id="project" data-placeholder="Choose Project" class="form-control select2" tabindex="2" >
                                    <option value=""></option>
                                    {{-- <option value="all">All</option> --}}
                                    <?php if(!empty($datapro)) {
                                        foreach($datapro as $key){
                                            echo "<option value='".$key->project_no."'>".$key->descs."</option>";
                                        }  
                                    } ?>  
                                </select></div>
                            </div> 
                        </div>
                        <div class="form-group col-4">
                            <label class="form-label" style="padding-right: 0px;">Post Date</label>
                            <div class="col-xs-2">
                                <div class="form-control-wrap">
                                    <div class="form-icon form-icon-left">
                                        <em class="icon ni ni-calendar"></em>
                                    </div>                          
                                    <input id="post_date" name="post_date" class="form-control date-picker" data-date-format="dd-mm-yyyy"  type="text" value="<?php $mydate=date("d-m-Y");echo "$mydate";?>">   
                                 </div>
                            </div>
                        </div>
                        <div class="form-group col-8">
                            <label class="form-label" style="padding-right: 0px;"> Period Date</label>
                            <div class="row" style="padding-left: 14px">
                                <div class="form-control-wrap">
                                    <div class="form-icon form-icon-left">
                                        <em class="icon ni ni-calendar"></em>
                                    </div>
                                    <input type="text" id="start" name="start" autocomplete="off" class="form-control date-picker" data-date-format="dd-mm-yyyy" value="" >
                                </div>
                                <span class="badge-sm badge-gray badge-dim" style="font-size: 15px"> to </span>
                                <div class="form-control-wrap">
                                    <div class="form-icon form-icon-left">
                                        <em class="icon ni ni-calendar"></em>
                                    </div>
                                    <input type="text" id="end" name="end" autocomplete="off" class="form-control date-picker" data-date-format="dd-mm-yyyy" value="" >
                                </div>
                            </div>  
                        </div>
                        <div class="form-group col-11">
                            <label class="form-label" style="margin-top: 10px;padding-right: 0px;">Remarks</label>
                            <div class="col-12" style="padding-left:0px">
                                  <div class="input-group">                            
                                    <input id="remarks" name="remarks" class="form-control" type="text"> 
                                 </div>
                            </div>
                        </div>                 
                    </div>  
                    </form>
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="tblgroup" width="100%">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <!-- <th>Debtor Account</th> -->
                                    <th>Business ID</th>
                                    <th>Name </th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!-- .card-preview -->
        </div> <!-- nk-block -->
    </div>
</div>

<script type="text/javascript">

  var tblgroupp;
  $(function() {
    $('.select2').select2();
    tblgroupp = $('#tblgroup').DataTable({
          processing: true,
          serverSide: true,
          // ajax: "{{ url('/group/all') }}",
          ajax: {
            "url" : "{{ url('/overtime/posting/all') }}",
            "type": "POST",
            data: {
              "_token": "{{ csrf_token() }}",
              "date_end": function(d){
                            var a = $('#end').val();
                            // console.log('dateend',a);
                            var date = new Date(parseInt(a.substr(0,10)));
                            var year =a.substr(6,4);
                            var month=a.substr(3,2);
                            var day =a.substr(0,2);
                                       
                            var aa1 = year+"-"+month+"-"+day;
                        //    console.log('dateend',aa1);
                            var b ="";
                            if(aa1 == "--"){
                                return b;
                            }{
                                return aa1;
                            }
                       
                        },
             "date_start": function(d){
                            var a = $('#start').val();
                            //console.log('datestart',a);
                            var date = new Date(parseInt(a.substr(0,10)));
                            var year =a.substr(6,4);
                            var month=a.substr(3,2);
                            var day =a.substr(0,2);
                                       
                            var aa1 = year+"-"+month+"-"+day;
                            // console.log('datestart',aa1);
                            var b ="";
                            if(aa1 == "--"){
                                return b;
                            }{
                                return aa1;
                            }
                            // console.log(a);
                        },
                "entity": function (d) {
                            var search = $('#entity').val();
                            var b = "";
                            if (search == null || search == "" || search == "all") {
                                return b;
                            } {
                                return search;
                            }
                        },
                "project": function (d) {
                            var search = $('#project').val();
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
            {data:"row_number",name:"row_number"},
            {data:"business_id",name:"business_id", sortable: false},
            {data:"debtor_name",name:"debtor_name"},
            {data: "bill_debtor_acct",name:"bill_debtor_acct", searchable:false,
                render: function (data, type, row) {
                    return  '<button class="btn btn-primary" onclick="cekifpost(\''+row.business_id+'\',\''+row.debtor_name+'\',\''+data+'\')"><i class="icon ni ni-edit"></i>&nbsp;Post</button>';

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
      $('#end').change(function(){
        var date_end = $('#end').val();
        var date_start = $('#start').val();

        if (date_start!='' && date_end=='')
        {
            Swal.fire('Warning','Please choose end date','warning');
            return;
        }
        tblgroupp.ajax.reload(null,true);
    });
      $('#end').change(function(){
        var date_end = $('#end').val();
        var date_start = $('#start').val();

        if (date_start!='' && date_end=='')
        {
            Swal.fire('Warning','Please choose end date','warning');
            return;
        }
        tblgroupp.ajax.reload(null,true);
    });
    $('#project').change(function(){
        tblgroupp.ajax.reload(null,true);
    });
    

});
function cekifpost(businessid,debtorname,bill_debtor_acct){
        var remarks = $('#remarks').val();
        var end = $('#end').val();
        var start = $('#start').val();
        var entity = $('#entity').val();
        var project = $('#project').val();
        if(entity==''){
            Swal.fire('Information','Please choose entity','warning');
            return;
        }
        if(project==''){
            Swal.fire('Information','Please choose project','warning');
            return;
        }
        if(start==''){
            Swal.fire('Information','Please input start period','warning');
            return;
        }
        if(end==''){
            Swal.fire('Information','Please input end period','warning');
            return;
        }
        if(remarks==''){
            Swal.fire('Information','Please input remaks','warning');
            return;
        }
        Swal.fire({
                title: 'Post Overtime',
                text: 'Are you sure you want to commit '+debtorname+' ('+businessid+')\'s data?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                allowOutsideClick: false,
                confirmButtonText: 'Yes'
            })
            .then(function(a){
                if (a.value==true) {
                    posting(bill_debtor_acct)
                }else{
                    // block(false,'.content-body');
                }
            })

    }
    function posting(bill_debtor_acct){
    // posting berdasar bisnis id
    block('#ot_body',true);
    var datafrm = $('#f_ot').serializeArray();
    datafrm.push({name:"bill_debtor_acct",value:bill_debtor_acct});
    
    $.ajax({
        url : "{{ url('overtime/posting/save') }}",
        type:"POST",
        data: datafrm,
        dataType:"json",
        success:function(event, data){
                $('#modal').modal('hide');
                if(event.status !='OK'){
                    Swal.fire("Error",event.pesan,"error");
                    block('#ot_body',false);
                }else{
                    // swal("Success",event.pesan,"success");
                    // console.log(event.dtexec);
                    Swal.fire({
                              title: "Success",
                              animation: false,
                              text: event.pesan+'. Do you want to post the data?',
                              icon: "success",
                              showCancelButton: true,
                              allowOutsideClick: false,
                            //   confirmButtonColor: "#DD6B55",
                              confirmButtonText: "Post",
                              reverseButtons: true
                            })
                            .then(function(a){
                                
                                if(a.value==true){
                                    $.ajax({
                                        url : "{{ url('overtime/posting/approve');}}",
                                        type:"POST",
                                        data: {"dtexec":event.dtexec},
                                        dataType:"json",
                                        success:function(event, data){
                                            if(event.status !='OK'){
                                                Swal.fire("Error",event.pesan,"error");
                                                block('#ot_body',false);

                                            }else{
                                                Swal.fire("Success",event.pesan,"success");
                                                block('#ot_body',false);
                                            }
                                            tblgroupp.ajax.reload(null,true); 
                                        },                    
                                        error: function(jqXHR, textStatus, errorThrown){        
                                            Swal.fire("Information",textStatus+' Save : '+errorThrown,"warning");
                                            block('#ot_body',false);
                                        }
                                    });
                                }else{
                                    $.ajax({
                                        url : "{{ url('overtime/posting/cancel'); }}",
                                        type:"POST",
                                        data: {"dtexec":event.dtexec},
                                        dataType:"json",
                                        success:function(event, data){
                                            if(event.status !='OK'){
                                                Swal.fire("Error",event.pesan,"error");
                                                block('#ot_body',false);
                                            }else{
                                                Swal.fire("Success",event.pesan,"success");
                                                block('#ot_body',false);
                                            }
                                            tblgroupp.ajax.reload(null,true); 
                                        },                    
                                        error: function(jqXHR, textStatus, errorThrown){        
                                                Swal.fire("Information",textStatus+' Save : '+errorThrown,"warning");
                                                    block('#ot_body',false);
                                                  // $('#modal').modal('hide');
                                            }
                                        });
                                }
                                $('#remarks').val('');
                                $('#start').val('').datepicker("update");
                                $('#end').val('').datepicker("update");
                                tblgroupp.ajax.reload(null,true); 
                                block('#ot_body',false);
                            });
                    
                    
                }
                
        },                    
        error: function(jqXHR, textStatus, errorThrown){        
            Swal.fire("Information",textStatus+' Save : '+errorThrown,"warning");
            block('#ot_body',false);
        }
    });
}

</script>

@endsection

