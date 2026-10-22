{{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> --}}
<form id ="frmEditor" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
      <div class="form-group">
        <div class="form-control-wrap">
            <input type="hidden" name="txtGroupID" id="txtGroupID" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <label for="GroupCD" class="form-label">Group Code</label>
        <div class="form-control-wrap">
          <input type="text" class="form-control" name="txtGroupCD" id="txtGroupCD" placeholder="Group Code">
        </div>
      </div>
      <div class="form-group">
        <label for="GroupDescs" class="form-label control-label">Group Description</label>
        <div class="form-control-wrap">
          <input type="text" class="form-control" name="txtGroupDescs" id="txtGroupDescs" placeholder="Group Description">
        </div>
      </div>
      <div class="form-group" hidden='true'>
        <label for="dashboard" class="form-label control-label">Group Dashboard</label>
        <div class="form-control-wrap">
          <input type="text" class="form-control" name="dashboard" id="dashboard" placeholder="Group Dashboard">
        </div>
      </div>
</form>
<script type="text/javascript">
	$(document).ready(function () {
	    //menampilkan data
	    loaddata();
      $("#frmEditor").validate({
        rules: {

          txtGroupCD: {
            required: true,
            maxlength:10
          },
          txtGroupDescs:{
            required:true
          },

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
          } else if (element.hasClass('select2_demo_1') || element.hasClass('checkbox-inline') || element.hasClass('radio-inline')){
            error.insertAfter(element.next('span'));
          } else {
            error.insertAfter(element);
          }
        }
      });
	    // simpan&edit data
	    $('#savefrm-sm').click(function(event)
	    {
	    	event.preventDefault();
	    	if (event.handled !== true) {
	    		event.handled = true;
	    		if ($('#frmEditor').valid())
	    		{
	    			//block(true);
	    			var GroupID = $('#modalsm').data('GroupID');
	    			var datafrm = $('#frmEditor').serializeArray();
                    datafrm.push({name:"_token",value:"{{ csrf_token() }}"});
	    			console.log(datafrm);//return;
	    			$.ajax({
	    				url : "{{ url('/group/save') }}",
	    				type:"POST",
	    				data: datafrm,
	    				dataType:"json",
	    				success:function(event, data)
	    				{
	    					//console.log(event);
	    					if (event.status == 'OK')
	    					{
	    						Swal.fire({
	    							title: "Information",
	    							icon:"success",
	    							text: event.pesan
	    						});
	    						$('#modalsm').modal('hide');
                                console.log(tblgroupp.ajax);
	    						tblgroupp.ajax.reload(null,true);
	    					} else {
	    						if (event.status == "Not Valid")
	    						{
	    							printErrorMsg(event.error);
	    						} else {
	    							Swal.fire({
		    							title: "Information",
		    							icon:"error",
		    							text: event.pesan
		    						});
	    						}
	    					}
	    				},error: function(jqXHR, textStatus, errorThrown){
	    					Swal.fire({
	    						title: "Error",
	    						icon:"error",
	    						text: textStatus+' Save : '+errorThrown
	    					});
	    					// block(false);
	    				}
	    			});
	    		} else {
	    			// block(false);
	    		}
	    	}
	    });
	});

	function loaddata(){
		var GroupID = $('#modalsm').data('GroupID');
        $('#GroupID').val(GroupID);
        if (GroupID > 0)
        {
        	$.getJSON("{{url('/group/id/')}}" + "/" + GroupID, function (data)
        	{
                $('#txtGroupID').val(data[0].GroupID);
                $('#txtGroupCD').val(data[0].group_cd);
                $('#txtGroupDescs').val(data[0].group_descs);
                $('#dashboard').val(data[0].dashboard_url);
            });
        }
    }


</script>
