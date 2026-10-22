<link rel="stylesheet" type="text/css" href="{{url('assets/css/forms/icheck/custom.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('assets/css/forms/icheck/icheck.css')}}">

<script src="{{url('assets/js/validate/jquery.validate.min.js')}}"></script>
<script src="{{url('assets/js/forms/icheck/icheck.min.js')}}"></script>

<form id ="frmEditor" class="form-validate is-alter"  method="post" action="">
    @csrf
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="Title">Title</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="title" id="title" placeholder="Title">
                    <span class="text-danger" id="titleError"></span>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="URL">URL</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="url" id="url" placeholder="URL">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="parent">Parent Menu</label>
                <div class="form-control-wrap">
                    <select data-placeholder="Choose a Parent Menu" class="select2 form-control" id="ParentMenuID" name="ParentMenuID">
                        <option value=""></option>
                        @foreach ($menuData as $key)
                            <option value="{{$key->MenuID}}">{{$key->Title}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="IconClass">Icon Class</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="IconClass" id="IconClass" placeholder="Icon Class">
                    <span class="text-danger" id="iconclassError"></span>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label" for="OrderSequence">Order Sequence</label>
                <div class="form-control-wrap">
                    <input type="text" class="form-control" name="OrderSeq" id="OrderSeq" placeholder="Order Sequence">
                    <span class="text-danger" id="orderseqError"></span>
                </div>
            </div>
        </div>
    </div>
        <div class="form-group">
            <input type="hidden" name="MenuID" id="MenuID" class="form-control">
        </div>
</form>

<script type="text/javascript">
	$(document).ready(function () {
	    //dropdown select2
	    $('.select2').select2({width:'100%'});

	    //menampilkan data
	    loaddata();

        $("#frmEditor").validate({
            ignore:"",
            rules: {
                title: {
                    required: true
                },
                OrderSeq: {
                    required: true
                },
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

	    //simpan&edit data
	    $('#savefrm').click(function(event)
	    {
	    	event.preventDefault();
	    	if (event.handled !== true) {
	    		event.handled = true;
	    		if ($('#frmEditor').valid())
	    		{
	    			//block(true);
	    			var menuID = $('#modal').data('menuID');
	    			var datafrm = $('#frmEditor').serializeArray();
	    			console.log(datafrm);//return;
	    			$.ajax({
	    				url : "{{ url('/menu/save') }}",
	    				type:"POST",
	    				data: datafrm,
	    				dataType:"json",
	    				success:function(event, data)
	    				{
	    					if (event.status == 'OK')
	    					{
	    						Swal.fire({
	    							title: "Information",
	    							animation: true,
	    							icon:"success",
	    							text: event.pesan,
	    							confirmButtonText: "OK"
	    						}).then(function(){
                                    $('#modal').modal('hide');
                                    tblmenu.ajax.reload(null,true);
                                });
	    					} else {
	    						if (event.status == "Not Valid")
	    						{
	    							printErrorMsg(event.error);
	    						}
	    						else
	    						{
	    							Swal.fire({
		    							title: "Information",
		    							animation: true,
		    							icon:"error",
		    							text: event.pesan,
		    							confirmButtonText: "OK"
		    						});
	    						}
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
	    			//block(false);
	    		}
	    	}
	    });

	    function printErrorMsg (msg) {
            $.each(msg, function( key, value ) {
            console.log(key, value);
            //console.log($('.'+key+'_err').text(value));
              $('#'+key+'Error').text(value);
            });
        }
	});

	function loaddata(){
		var MenuID = $('#modal').data('menuID');
        console.log(MenuID);
        $('#MenuID').val(MenuID);

        if (MenuID > 0)
        {
        	$.getJSON("{{url('/menu/id')}}" + "/" + MenuID, function (data)
        	{
        		//console.log(data.length)
	        	console.log(data);
	            $('#title').val(data[0].Title);
	            $("#ParentMenuID").val(data[0].ParentMenuID).append('<option value="0">Parent Menu</option> ');
	            $('#ParentMenuID').val(data[0].ParentMenuID).trigger('change');
	            $('#url').val(data[0].URL);
	            $('#IconClass').val(data[0].IconClass);
	            $('#OrderSeq').val(data[0].OrderSeq);
            });
        }
    }

    function block(boelan)
    {
    	var block_ele = $('#frmEditor')
      	if (boelan==true)
      	{
      		$(block_ele).block({
      			message: '<div class="semibold"><span class="ft-refresh-cw icon-spin text-left"></span>&nbsp; Loading ...</div>',
      			fadeIn: 1000,
      			fadeOut: 1000,
      			overlayCSS: {
      				backgroundColor: '#fff',
      				opacity: 0.8,
      				cursor: 'wait'
      			},
      			css: {
      				border: 0,
      				padding: '10px 15px',
      				color: '#fff',
      				width: 'auto',
      				backgroundColor: '#333',
      				marginLeft : 'auto'
      			}
      		});
      	}
      	else
      	{
      		$(block_ele).unblock()
        }
    }
</script>
