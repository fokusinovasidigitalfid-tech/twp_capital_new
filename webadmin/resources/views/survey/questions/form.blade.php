
<style >
    #signupForm label.error {
    margin-left: 10px;
    width: auto;
    display: inline;
  }
  td {
      height: 40px;
    }
  
  #label_form label {
      text-align: right;
    }
  
  .marginSelect{
    padding-left: 12px !important;
    padding-bottom: 6px !important;
    border-bottom-width: 1px !important;
    padding-top: 3px !important;
  
  }
  label {
    text-align: right;
  }
  .has-error .select2-selection {
    border: 1px solid #a94442;
    border-radius: 4px;
  }
  
  </style>
  <style>
  /* width */
  ::-webkit-scrollbar {
    width: 10px;
  }
  
  /* Track */
  ::-webkit-scrollbar-track {
    background: #f1f1f1; 
  }
   
  /* Handle */
  ::-webkit-scrollbar-thumb {
    background: #888; 
  }
  
  /* Handle on hover */
  ::-webkit-scrollbar-thumb:hover {
    background: #555; 
  }
  </style>
  
  
  <div class="ibox-content">
    <form role="form" class="form-horizontal" enctype="multipart/form-data" id="form_nup" method="POST" >
  
              <div class="form-group">
                <label >Subject</label>
                <div class="col-12">
                  <input type="text" class="form-control" name="txtsubject" id="txtsubject" placeholder="Input Subject">
                </div>
              </div>
              <div class="form-group">
                <label >Question</label>
                <div class="col-12">
                  <input type="text" class="form-control" name="txtquestion" id="txtquestion" placeholder="Input Question">
                </div>
              </div>      
              <h6 class="form-section"> Add Option(s) <button class="btn btn-outline-success round btn-sm" style="padding: 0px;margin-left:5px" id="btnAdd" type="button"><em class="icon ni ni-plus" style="padding-left: 5px;padding-right: 5px;"></em></button></h6>
            
              <div  style="overflow-y: auto; overflow-x: hidden; height: 150px; ">
                <div id="options" >
                  
                </div>
            
              </div>
               <input type="hidden" id="batas" name="batas"/>
  
          </form>
  </div>
      
  

<script type="text/javascript">
  $(document).ready(function(){
    // $('#savefrm').attr("disabled", false); 
    //   $("#modal").on("hidden.bs.modal", function(){
    //       $("#modalbody").html("");
    //   });
      $("#txtoptType").select2();
      var xx = $("#batas").val();
      loaddata();

  $("#btnAdd").click(function(){
        var xx = $("#batas").val();    
        // console.log(xx);return;
        var li = 1;
        for (var i = xx; i <= xx; i++) 
        {
          $("#options").append('<div class="form-group" id="option_div'+i+'" style="margin-bottom: 10px;"><div class="col-12"><label><button class="btn btn-outline-danger round btn-sm" style="padding: 2px;" onclick="remove('+i+')"><em class="icon ni ni-minus-sm"></em></button></label> Option Value(s) <FONT COLOR="RED">*</FONT><br><div style="margin-left: 40px"><input type="text" class="form-control" name="txtopt_value[]" id="txtopt_value'+i+'" placeholder="Input Option"><label class="checkbox-inline"><input type="checkbox" name="remark[]"  id="remark'+i+'" onclick="checkremark('+i+')">  Need Remark </label></div><input type="hidden" name="remark_val[]" id="remark_val'+i+'" value="0"><input type="hidden" name="line_no[]" id="line_no'+i+'" ></div></div>');
        }
        xx = i;
        $('#batas').val(i);
        // $("#line").val(i);
      });
  
    
  
    $('#form_nup').validate({
        ignore: "",
        rules: {
          txtsubject: {required: true},
          txtquestion: {required: true},
          txtopt_value1: {required: true},
        },
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
            } else if (element.hasClass('select2_demo_1') || element.hasClass('select2_demo_2')) {
              error.insertAfter(element.next('span'));
            } else {
              error.insertAfter(element);
            }
          }
  
      });
  
  $('#savefrmxl').unbind().click(function(){
      
      block(true,'#form_nup');
      var form = $('#modalxl').data('form');
      var survey_id = $('#modalxl').data('id');      
      var a = $("#batas").val();  
      var datafrm = $('#form_nup').serializeArray();
          datafrm.push(
                       {name:"survey_id",value:survey_id},
                       {name:"form",value:form},
                       {name:"_token",value:"{{ csrf_token() }}"}
                       );
                       console.log(datafrm);
         if($('#form_nup').valid()){
              $.ajax({
                  url : "{{ url('survey/questions/save') }}",
                  type:"POST",
                  data: datafrm,
                  dataType:"json",
                  success:function(data, status){
                   // console.log(data);
                   // console.log(status);
                  if(data.status =='OK'){
                        Swal.fire({
                          title: "Information",
                          animation: true,
                          icon:"success",
                          text: data.pesan,
                          confirmButtonText: "OK"
                        }).then(function(){
                          $('#modalxl').modal('hide');
                          tblgroupp.ajax.reload(null,true);  
                          block(false,'#form_nup');
		  	    					});
                      
                  } else {
                      Swal.fire({
                          title: "Information",
                          animation: true,
                          icon: "error",
                          text: data.pesan,
                          confirmButtonText: "OK"
                      });
                      block(false,'#form_nup');
                  }
                },                    
                  error: function(jqXHR, textStatus, errorThrown){
                      Swal.fire("Information",textStatus+' Save : '+errorThrown,"error");
                  }
              });
        }else{
          block(false,'#form_nup');
        }
    });
});
  function loaddata(){
    block(true,'#form_nup');
    var survey_id = $('#modalxl').data('id');
    var form = $('#modalxl').data('form');
    var line_no = $('#modalxl').data('line_no');
    console.log(form);
    if (form != 'add') {
      $.getJSON("{{ url('survey/questions/id') }}" + "/" + survey_id, function (data) {
          $('#txtsubject').val(data[0].subject);
          $('#txtquestion').val(data[0].content);
          var a = data.length;
          $('#batas').val(a);
            console.log(data);
            for(var i = 0; i < a ;i++ ){
                var urut=i;
                var txtFlag = data[i].flag;
                var flagvalue =" ";var flagval='';
                if(txtFlag == '1'){
                  flagvalue = 'checked';
                  flagval = '1';
                }else{
                  flagvalue = ' ';
                  flagval = '0';
                }
                $("#options").append('<div class="form-group" id="option_div'+urut+'" style="margin-bottom: 10px;"><div class="col-12"><label><button class="btn btn-outline-danger round btn-sm" style="padding: 2px;"  onclick="remove('+urut+')"><em class="icon ni ni-minus-sm"></em></button></label> Option Value(s) <FONT COLOR="RED">*</FONT><br><div style="margin-left: 40px"><input type="text" class="form-control" name="txtopt_value[]" id="txtopt_value'+urut+'" placeholder="Input Option" value="'+data[i].options+'"><label class="checkbox-inline"><input type="checkbox" name="remark[]"  id="remark'+i+'" onclick="checkremark('+i+')" '+flagvalue+'> Need Remark </label></div><input type="hidden" name="remark_val[]" id="remark_val'+i+'" value="'+flagval+'"><input type="hidden" name="line_no[]" id="line_no'+i+'" value="'+data[i].line_no+'"></div></div>');
            }
            block(false,'#form_nup');
      });
    } else {
      // $('#batas').val(3);
      block(false,'#form_nup');
    }
  }
      
  $('#modal').on('hidden.bs.modal', function (e) {
      $('div.modal-body').html("");
      $(this).removeData();
  });
  
  
  function remove(no){
    var survey_idDel = $("#modalxl").data('survey_id');
    $("#options #option_div"+no).remove();
    if(xx>1) {
        xx--;
    }
  }
  
  function checkremark(no){
      if($('#remark'+no).is(':checked')){
        $('#remark_val'+no).val(1);
      } else {
        $('#remark_val'+no).val(0);
      }
  }
</script> 
  