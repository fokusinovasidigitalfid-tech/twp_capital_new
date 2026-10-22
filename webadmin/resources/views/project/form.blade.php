@extends('template.layout2.base')
@section('content')
<div class="nk-content-body">
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title"><?php echo $title1 ?></h4>
                </div>
            </div>
            <div class="card card-preview">
                <div class="card-inner">
                    <form id ="frmEditor" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="preview-block">
                      <input type="hidden" name="idproject" id="idproject" value="{{ $id }}">
                      <div class="row gy-4">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Database Profile <FONT COLOR="RED">*</FONT></label>
                                <div class="form-control-wrap">
                                    <select name="db_profile" id="db_profile" data-placeholder="Choose a Profile..." class="form-control select2" tabindex="2">
                                        <option value=""></option>
                                        <option value="{{ $dbprofile[0]->db_profile }}">{{ $dbprofile[0]->db_name }}</option>
                                      </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Database Name <FONT COLOR="RED">*</FONT></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control pull-right" id="db_name" name="db_name" readonly="true">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Entity Code <FONT COLOR="RED">*</FONT></label>
                                <div class="form-control-wrap">
                                    {{-- <input type="text" class="form-control pull-right" id="entity_cd" name="entity_cd" placeholder="Entity Code"> --}}

                                    <select name="entity_cd" id="entity_cd" data-placeholder="Choose an Entity..." class="form-control select2" tabindex="2">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Entity Name <FONT COLOR="RED">*</FONT></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control pull-right" id="entity_name" name="entity_name" placeholder="Entity Name" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Project No <FONT COLOR="RED">*</FONT></label>
                                <div class="form-control-wrap">
                                    {{-- <input type="text" class="form-control pull-right" id="project_no" name="project_no" placeholder="Project No"> --}}
                                    <select name="project_no" id="project_no" data-placeholder="Choose a Project..." class="form-control select2" tabindex="2">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Project Description <FONT COLOR="RED">*</FONT></label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control pull-right" id="descs" name="descs" placeholder="Project Description" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Picture Project Web</label>
                                <div class="form-control-wrap">
                                    <div id="logo" class="image" >
                                        <img class="img-responsive" src="<?php echo(empty('') ? url('images/PlProject/no_image.png'): url('images/PlProject/'.'') );?>" width="120px" id="picturebox1">
                                    </div>
                                    <br>

                                      <input type="file" id="userfile1" name="userfile" accept="image/x-png,image/gif,image/jpeg" onChange="saveImage(1,this)"/>

                                    <p>(* Only Jpg, Png allowed)</p>
                                    <input type="hidden" id="picturepath1" name="picturepath1" value="<?php echo ''?>" readonly="1">
                                    <input type="hidden" id="picturename1" name="picturename1" readonly="1">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Picture Project Mobile</label>
                                <div class="form-control-wrap">
                                    <div id="logo" class="image" >
                                        <img class="img-responsive" src="<?php echo(empty('') ? url('images/PlProject/no_image.png'): url('images/PlProject/'.'') );?>" width="120px" id="picturebox2">
                                    </div>
                                    <br>

                                      <input type="file" id="userfile2" name="userfile2" accept="image/x-png,image/gif,image/jpeg" onChange="saveImage(2,this)"/>

                                    <p>(* Only Jpg, Png allowed)</p>
                                    <input type="hidden" id="picturepath2" name="picturepath2" value="<?php echo ''?>" readonly="1">
                                    <input type="hidden" id="picturename2" name="picturename2" readonly="1">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Email Profile Name <FONT COLOR="RED">*</FONT></label>
                                <div class="form-control-wrap">
                                    <select name="sysmail" id="sysmail" data-placeholder="Choose a Email Profile Name..." class="form-control select2" tabindex="2">
                                        <option value=""></option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Project Address</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control pull-right" id="caption_address" name="caption_address" placeholder="Project Address">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Project Map Coordinat</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control pull-right" id="coordinat_project" name="coordinat_project" placeholder="Project Coordinat">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="default-01">Status</label>
                                <div class="form-control-wrap">
                                    <label class="radio-inline" style="padding-right: 10px"><input type="radio" name="status" id="1" value="1" checked style="margin-right: 10px">Active </label>
                                    <label class="radio-inline" ><input type="radio" name="status" id="0" value="0" style="margin-right: 10px">Inactive </label>
                                </div>
                            </div>
                        </div>

               

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label" for="default-01"> </label>
                                <div class="form-control-wrap">
                                    <button type="button" id="btnSave" class="btn btn-primary">Save</button>
                                    <button type="button" id="btnBack" class="btn btn-secondary">Back</button>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<script type="text/javascript">
  loaddata();
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $('.select2').select2({width:'100%'});
    var rowID = {{ $id }};
    $("#db_profile").change(function(e){
        e.preventDefault();
      if(rowID==0){
        var db = $(this).find(":selected").val();
        var dbname = $(this).find(":selected").text();
        $('#db_name').val(dbname);
         var site_url = "{{url('projects/zoom_entity')}}";
          $.post(site_url,
            {"_token": "{{ csrf_token() }}",cons:db},
            function(data,status) {
              $("#entity_cd").empty();
              $("#entity_cd").append(data);
              $("#entity_cd").trigger('change');
            }
          );
      }
    })

    function setentity(db,ent){
      console.log(db,ent);
      var site_url = "{{url('projects/zoom_entity')}}";
          $.post(site_url,
            {"_token": "{{ csrf_token() }}",cons:db,ent:ent},
            function(data,status) {
              $("#entity_cd").empty();
              $("#entity_cd").append(data);
              $("#entity_cd").trigger('change');
            }
          );
    }

    $("#db_profile").change(function(e){
        e.preventDefault();
      if(rowID==0){
        var dbs = $(this).find(":selected").val();
        var name = $(this).find(":selected").text();//WPR
         var site_url = "{{url('projects/email_profile')}}";
          $.post(site_url,
            {"_token": "{{ csrf_token() }}",cons:dbs},
            function(data,status) {
              $("#sysmail").empty();
              $("#sysmail").append(data);
              $("#sysmail").trigger('change');
            }
          );
      }
    })

    function setsysmail(dbs, name){
      console.log(dbs,name);
      var site_url = "{{url('projects/email_profile')}}";
          $.post(site_url,
            {"_token": "{{ csrf_token() }}",cons:dbs,name:name},
            function(data,status) {
              $("#sysmail").empty();
              $("#sysmail").append(data);
              $("#sysmail").trigger('change');
            }
          );
    }

    $("#entity_cd").change(function(){
      if(rowID==0){
      var db = $('#db_profile').find(":selected").val();
      var ent = $(this).find(":selected").val();
       var site_url = "{{url('projects/zoom_project')}}";
        $.post(site_url,
          {"_token": "{{ csrf_token() }}",cons:db,ent:ent},
          function(data,status) {
            $("#project_no").empty();
            $("#project_no").append(data);
            $("#project_no").trigger('change');
          }
        );
        var ent2 = $(this).find(":selected").data('entname');
        if(ent2!== 'undefined'){
          $('#entity_name').val(ent2);
        }
      }
    })

    function setproject(db,ent,pro){
        var db = $('#db_profile').find(":selected").val();
        var site_url = "{{url('projects/zoom_project')}}";
          $.post(site_url,
            {"_token": "{{ csrf_token() }}",cons:db,ent:ent,pro:pro},
            function(data,status) {
              $("#project_no").empty();
              $("#project_no").append(data);
              $("#project_no").trigger('change');
            }
          );
    }

    $("#project_no").change(function(){
      if(rowID==0){
      var prodescs = $(this).find(":selected").data('prodescs');
        if(prodescs!== 'undefined'){
          $('#descs').val(prodescs);
        }
      }
    })

    $("#frmEditor").validate({
        ignore:"",
        rules: {
          entity_cd: {
              required: true,
              maxlength:4
          },
          entity_name: {
              required: true
          },
          project_no: {
              required: true
          },
          descs: {
              required: true
          },
          db_profile:{
              required:true
          },
          db_name:{
              required:true
          },
          status:{
              required:true
          },
          // seq_no:{
          //     required:true
          // }
        },
        messages: {
                errorElement: "span",
            highlight: function (element, errorClass, validClass) {
              $(element).addClass(errorClass); //.removeClass(errorClass);
              $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
              $(element).removeClass(errorClass); //.addClass(validClass);
              $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')){
                    error.insertAfter(element.next('span'));
                } else {
                    error.insertAfter(element);
                }
            }
        }
    });

    $('#btnSave').click(function(){
        if($('#frmEditor').valid()){
              var id = {{$id}};
              var datafrm = $('#frmEditor').serializeArray();
              if(id>0){
                datafrm.push(
                  {name:"db_profile",value:$('#db_profile').find(':selected').val()},
                  {name:"entity_cd",value:$('#entity_cd').val()},
                  {name:"entity_name",value:$('#entity_name').val()},
                  {name:"project_no",value:$('#project_no').val()},
                  {name:"descs",value:$('#descs').val()}
                  );
              }
              // console.log(datafrm);return;
              $.ajax({
                    url : "{{url('projects/save_regist')}}",
                    type:"POST",
                    data: datafrm,
                    dataType:"json",
                    success:function(event, data){
                        if(event.status=='OK'){
                            Swal.fire({
                            title: "Information",
                            animation: true,
                            icon:"success",
                            text: event.pesan,
                            confirmButtonText: "OK"
                            }).then(function(){
                            window.history.back();
                            });
                        } else {
                          Swal.fire({
                            title: "Error",
                            animation: true,
                            icon:"error",
                            text: event.pesan,
                            confirmButtonText: "OK"
                          });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        Swal.fire({
                          title: "Error",
                          animation: false,
                          icon:"error",
                          text: textStatus+' Save : '+errorThrown,
                          confirmButtonText: "OK"
                        });
                    }
              });
        }
    });//end btnSave

    $("#btnBack").click(function(){
      window.history.back();
    })

    function saveImage(seq, el) {
        var a = el.files[0].size;
        var max = (1024 * 1024) * 7;
        if (a > max){
          if (max.toString().length > 6) {
            max = max / 1024 / 1024;
            max = max.toFixed(2);
            max = max + ' mb';
          } else {
            max = max / 1024;
            max = max.toFixed(2);
            max = max + ' kb';
          }
          Swal.fire('Please upload less than ' + max,'warning');
          return false;
        }

        $.ajax({
          url : "{{url('projects/savepic')}}",
          type:"POST",
          data: function () {
            var data = new FormData();
            data.append("seqno", seq);
            data.append("userfile", $("#userfile"+seq).get(0).files[0]);
            return data;
          }(),
          processData: false,
          contentType: false,
          dataType:"json",
          success:function(data, status){
            console.log(data);
            if(data.status == "OK"){
              // Swal.fire({
              //   title: "Information",
              //   text: data.pesan,
              //   icon: "success",
              //   confirmButtonText: "OK"
              // });
              console.log(data);
                $('#picturebox'+seq).attr('src', data.url);
                $('#picturepath'+seq).val(data.url)
                $('#picturename'+seq).val(data.picname)
            } else
            {
              Swal.fire({
                title: "Error",
                text: data.pesan,
                icon: "error",
                confirmButtonText: "OK"
              });
            }
          },
            error: function(jqXHR, textStatus, errorThrown){
            Swal.fire('Information',textStatus+' Save : '+errorThrown,'error');
          }
        });
    }

    function loaddata(){
        var rowID = {{$id}};
        console.log(rowID);
        if (rowID > 0) {
            $.getJSON("{{url('projects/getbyid')}}" + "/" + rowID, function (data) {
              $('#db_name').val(data[0].db_name);
              $("#db_profile").val(data[0].db_profile).trigger('change');
              setentity(data[0].db_profile,data[0].entity_cd);
              $('#entity_name').val(data[0].entity_name);
              setproject(data[0].db_profile,data[0].entity_cd,data[0].project_no);
              // $("#product_cd").val(data[0].product_cd).trigger('change');
              $('#descs').val(data[0].descs);
              $('#db_profile').attr('disabled',true);
              // $('#entity_cd').attr('disabled',true);
              // $('#entity_name').attr('disabled',true);
            //   $('#project_no').attr('disabled',true);
            //   $('#descs').attr('disabled',true);
              $('#coordinat_project').val(data[0].coordinat_project);
              $('#caption_address').val(data[0].caption_address);
              $('#hp').val(data[0].handphone);
              setsysmail(data[0].db_profile, data[0].entity_cd);
              // console.log(data);
              
              $('#picturepath1').val(data[0].picture_path)
              $('#picturepath2').val(data[0].picture_url)
              if(data[0].picture_path=='null'||data[0].picture_path==''||data[0].picture_path==null){
                pic_path = "{{url('images/PlProject/no_image.png')}}";
              }else{
                pic_path = data[0].picture_path;
              }
              $('#picturebox1').attr('src', pic_path);
              if(data[0].picture_url=='null'||data[0].picture_url==''||data[0].picture_url==null){
                pict_url = "{{url('images/PlProject/no_image.png')}}";
              }else{
                pict_url = data[0].picture_url;
              }
              $('#picturebox2').attr('src', pict_url);
              // $('#seq_no').val(data[0].seq_no);
              $('#sysmail').val(data[0].mailprofile_name).trigger('change');
              var status = data[0].status;
              document.getElementById(status).checked = true;
            });
        }else{}
    }

</script>
@endsection
