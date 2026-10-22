@extends('template.base')
@section('content')
	<div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title"><?php echo $jdl?></h3>
                </div><!-- .nk-block-head-content -->
            </div><!-- .nk-block-between -->
        </div><!-- .nk-block-head -->
    	<div class="card card-preview">
    		<div class="card-inner">
    			<form class="form-horizontal" id="frm" enctype="multipart/form-data" method="POST" action="">
    				@csrf
					<div class="col-md-12">
	                    <!-- <div class="form-group row">
	                    	<label class="col-2 form-label">Ticket Number </label>
		                    <div class="col-10">
		                        <input type="text" class="form-control" value='<?php echo $number?>' name="angka" id="angka" readonly="readonly" />
		                    </div>
	                    </div> -->
	                    <div class="form-group row">
	                    	<div class="col-6">
	                    		<label class="col-xs-2 form-label">Ticket Type <span class="text-danger">*</span></label>
			                    <div class="col-xs-10">
			                        <select name="ticket_type" id="ticket_type" class="form-control select2" data-placeholder="Choose a Ticket Type">
			                        	<option value=""></option>
			                        	<option value="R">Request</option>
			                        	<option value="C">Complain</option>
			                        </select>
			                    </div>
	                    	</div>
	                    	<div class="col-6">
	                    		<label class="col-xs-2 form-label">Tenant <span class="text-danger">*</span></label>
			                    <div class="col-xs-10">
			                    	<select name="tenant_no" id="tenant_no" class="form-control select2" data-placeholder="Choose a Tenant">
			                    		<?php echo $combo_tenant; ?>
			                    	</select>  
			                    </div>
	                    	</div>
	                    </div>
	                    <div class="form-group row">
	                    	<div class="col-6">
	                    		<label class="col-xs-2 form-label">Unit <span class="text-danger">*</span></label>
		                    	<div class="col-xs-10">
		                        	<select name="lot_no" id="lot_no" class="form-control select2" data-placeholder="Choose a Unit">
		                        		<option value=""></option>
		                        	</select>  
		                    	</div>
	                    	</div>
	                    	<div class="col-6">
	                    		<label class="col-xs-2 form-label">Floor <span class="text-danger">*</span></label>
		                    	<div class="col-xs-10">
		                        	<input type="text" class="form-control" value='<?php //echo $floor; ?>' name="floor" id="floor" readonly="readonly" />  
		                    	</div>
	                    	</div>
	                    </div>
	                    <div class="form-group row">
	                    	<label class="col-2 form-label">Ticket Number </label>
		                    <div class="col-10">
		                        <input type="text" class="form-control" name="angka" id="angka" readonly="readonly" />
		                    </div>
		                    <div class="col-10">
		                        <input type="text" class="form-control" name="pre" id="pre" readonly="readonly" hidden/>
		                    </div>
	                    </div>
	                    <div class="form-group row">
	                    	<label class="col-2 form-label">Location <span class="text-danger">*</span></label>
	                    	<div class="col-10">
	                        	<input type="text" class="form-control" maxlength="20" id="location" name="location" value="" />  
	                    	</div>
	                    </div>
	                    <div class="form-group row">
	                    	<label class="col-2 form-label">Requested By <span class="text-danger">*</span></label>
	                    	<div class="col-10">
	                        	<input type="text" class="form-control" id="req_by" name="req_by" value="" />
	                    	</div>
	                    </div>
	                    <div class="form-group row">
	                    	<label class="col-2 form-label">Contact No <span class="text-danger">*</span></label>
	                    	<div class="col-10">
	                        	<input type="text" class="form-control" maxlength="20" id="contact_no" name="contact_no" value="" />
	                    	</div>
	                    </div>
	                    <div class="form-group row">
	                    	<label class="col-2 form-label">Category <span class="text-danger">*</span> </label>
	                    	<div class="col-10">
	                        	<select name="category" id="category" class="form-control select2" data-placeholder="Choose a Category" disabled>
	                        		<option value=""></option>
	                        	</select> 
	                    	</div>
	                    </div>
	                    <div class="form-group row">
	                    	<label class="col-2 form-label">Description <span class="text-danger">*</span></label>
	                    	<div class="col-10">
	                    		<textarea class="form-control" rows="3" maxlength="255" placeholder="Complain Description" id="description" name="description"></textarea>
	                    	</div>
	                    </div>
	                    <div class="form-group row">
	                    	<label class="col-2 form-label">Picture </label>
	                    	<div class="col-10">
	                    		<div class="form-control-wrap">
        							<div class="custom-file">
        								<input type="file" id="ticket_image" name="ticket_image" class="custom-file-input" accept="image/*">
        								<label class="custom-file-label" for="ticket_image" id="pictname">Choose File</label>
        								<p style="color: red">(* Max Upload Size 2MB. Only JPG, JPEG, PNG, GIF allowed)</p>
        							</div>
        						</div>
	                    	</div>
	                    </div>
	                    <div class="form-group">
        					<div class="col-xs-4">
        						<img src="" id="picturebox" class="img-responsive">
        						<input type="hidden" class="form-control" name="picturepath" id="picturepath" value="https://i0.wp.com/www.winhelponline.com/blog/wp-content/uploads/2017/12/user.png?resize=256%2C256&quality=100&ssl=1" readonly><input type="hidden" class="form-control" name="picturename" id="picturename" readonly><input type="hidden" class="form-control" name="pictureattach" id="pictureattach" readonly>
        					</div>
			            </div>
	                    <!-- <div class="form-group row">
	                    	<label class="col-2 form-label"></label>
	                    	<div class="col-10"><span class="text-danger">* = must be filled</span></div>
	                    </div> -->
		            </div>
		            <div style="text-align:right;margin-right: 50px;margin-top: 20px">
        				<button type="button" id="btnSave" class="btn btn-primary">Submit</button>
	                </div>
		            <input type="hidden" name="entity" id="entity" />
		            <input type="hidden" name="project" id="project" />
		        </form>
		    </div>
		</div>
    </div>

    <script type="text/javascript">
    	$(document).ready(function(){
    		loaddata();

    		$('.select2').select2();

    		$('#ticket_type').change(function() {
    			var ticket_type = $(this).find(':selected').val();
    			//console.log('ready ' + ticket_type);
    			var site_url = "{{ url('ticket/getCat') }}";
				$.post(site_url,
					{
						"_token": "{{ csrf_token() }}",
						ticket_type:ticket_type
					},
    				function(data, status) {
		                $("#category").empty();
		                $("#category").attr('disabled', false);
		                $("#category").append(data);
		            }
	            );
	        });

	        $('#tenant_no').change(function() {
	        	var tenant_no = $(this).find(':selected').val();
	        	var ent = $(this).find(':selected').data("entity");
	        	var prj = $(this).find(':selected').data("project");
	        	// console.log(tenant_no);
	        	if(tenant_no!=='') {
	        		var site_url = "{{ url('ticket/getLotNo') }}";
	        		$.post(site_url,
						{
							"_token": "{{ csrf_token() }}",
							id_tenancy: tenant_no
						},
						function(data, status) {
							$("#lot_no").empty();
							$("#lot_no").append(data);
							// $.getJSON("{{url('/ticket/getTicket')}}" + "/" + ent + "/" + prj, function (datas)
			    // 			{
			    // 				$('#angka').val(datas[0].complain_seq_no);
			    // 			})
						}
					);
	        	} else {
		            $("#lot_no").empty();
		            $("#floor").empty();
		            $('#floor').val(null);
		        }
		    });

		    $('#lot_no').change(function() {
				var lvl_no = $("#lot_no option:selected").data("level");
				var ent = $("#tenant_no option:selected").data("entity");
				var prj = $("#tenant_no option:selected").data("project");

				$("#floor").empty();
				$("#floor").val(lvl_no);
				$("#entity").val(ent);
				$("#project").val(prj);
			});

			$("#ticket_image").on('change', function ()
		    {
			    $.ajax({
	            	url : "{{url('ticket/savepic')}}",
	            	type:"POST",
	            	data: function () {
	            		var data = new FormData();
	            		data.append("_token", "{{ csrf_token() }}");
			            data.append("ticket_image", $("#ticket_image").get(0).files[0]);
			            data.append("req_by", $('#req_by').val());
			            data.append("ticket_type", $('#ticket_type').val());
			            return data;
			        }(),
			        processData: false,
			        contentType: false,
			        dataType:"json",
			        success:function(data, status){
			            console.log(data.status);
			            if(data.status == "OK"){
							Swal.fire({
								title: "Information",
								text: data.pesan,
								icon: "success",
								confirmButtonText: "OK"
							});
							$('#picturebox').attr('src', data.url);
							$('#picturepath').val(data.url)
							$('#picturename').val(data.picname)
							$('#pictureattach').val(data.pic_attached)
			            } else {
							Swal.fire({
								title: "Error",
								text: data.pesan,
								icon: "error",
								confirmButtonText: "OK"
							});
			            }
			        },
			            error: function(jqXHR, textStatus, errorThrown){
			            Swal.fire(textStatus+' Save : '+errorThrown);
			        }
			    });
	        });

			$("#frm").validate({
    			ignore:[],
    			rules: {
					ticket_type:{
						required:true
					},
					tenant_no:{
						required:true
					},
					lot_no:{
						required:true
					},
					location:{
						required:true
					},
					req_by:{
						required:true
					},
					contact_no:{
						required:true
					},
					category:{
						required:true
					},
					description:{
						required:true
					}
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
		    $('#btnSave').click(function(event)
		    {
		    	
		    	event.preventDefault();
		    	if (event.handled !== true) {
		    		event.handled = true;
		    		if ($('#frm').valid())
		    		{
		    			//block(true);
		    			var id = '<?php echo $id?>';
		    			console.log('aa');
		    			var action ='<?php echo $form?>';
		    			console.log('aa');
		    			var tenant_no = $('#tenant_no').val();
		    			var datafrm = $('#frm').serializeArray();
			    			datafrm.push(
			    				{name:"action",value:action},
			    				{name:"id",value:id},
			    				{name:"tenant_no",value:tenant_no},
			    			);
		    			

		    			$.ajax({
		    				url : "{{ url('/ticket/save') }}",
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
		    							animation: true,
		    							icon:"success",
		    							text: event.pesan,
		    							confirmButtonText: "OK"
		    						}).then(function(){
		    							window.location.href="{{url('/dash')}}";
		  	    					});
		    					} else {
		  							Swal.fire({
		    							title: "Information",
		    							animation: true,
		    							icon:"error",
		    							text: event.pesan,
		    							confirmButtonText: "OK"
		    						});
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

		    function loaddata(){
				var id = '<?php echo $id ?>';
				console.log("ID:", id);

				if (id > 0)
				{
					// 🔹 Load data lama
					$.getJSON("{{url('/ticket')}}" + "/" + id, function (data)
					{
						console.log("Loaded data:", data);
						$('#angka').val(data[0].complain_no);
						$('#ticket_type').val(data[0].complain_type).trigger('change');
						$('#tenant_no').val(data[0].id_tenancy).trigger('change');
						$('#tenant_no').attr('disabled', true);

						getlotno(data[0].id_tenancy, data[0].lot_no);
						$('#floor').val(data[0].floor);
						$('#location').val(data[0].location);
						$('#req_by').val(data[0].serv_req_by);
						$('#contact_no').val(data[0].contact_no);

						getcategory(data[0].complain_type, data[0].category_cd);
						$('#description').val(data[0].work_requested);

						if (data[0].picture != "") {
							$('#picturebox').attr("src", data[0].picture);
							$('#picturepath').val(data[0].picture);
						}
					});
				} 
				else 
				{
					// 🔹 Buat data baru
					$('#tenant_no').change(function() {
						var tenant_no = $(this).find(':selected').val();
						var ent = $(this).find(':selected').data("entity");
						var prj = $(this).find(':selected').data("project");

						if (tenant_no !== '') {
							var site_url = "{{ url('ticket/getLotNo') }}";
							$.post(site_url, {
								"_token": "{{ csrf_token() }}",
								tenant_no: tenant_no
							}, function(data, status) {
								$("#lot_no").empty().append(data);
								var site_url2 = "{{ url('ticket/getTicketNew') }}" + "/" + ent + "/" + prj;
								$.get(site_url2, {
									"_token": "{{ csrf_token() }}",
									ent: ent,
									prj: prj
								}, function(datas, status){
									var prefix = datas;
									console.log("Prefix:", prefix);
									$.getJSON("{{url('/ticket/getTicketPrefix')}}" + "/" + ent + "/" + datas, function (datas2){
										$('#angka').val(datas2);
										$('#pre').val(prefix);
									});
								});
							});
						} else {
							$("#lot_no").empty();
							$("#floor").val(null);
						}
					});

					// 🔹 AUTO SELECT jika hanya ada satu tenant
					setTimeout(function() {
						var $tenantSelect = $('#tenant_no');
						var options = $tenantSelect.find('option');

						// cek jika hanya 1 opsi valid (bukan placeholder kosong)
						if (options.length === 1 || 
							(options.length === 2 && options.first().val() === '')) {

							// pilih opsi yang valid
							var onlyOption = (options.first().val() === '') ? options.eq(1).val() : options.first().val();
							$tenantSelect.val(onlyOption).trigger('change');
							console.log("Auto-selected tenant:", onlyOption);
						}
					}, 500); // kasih delay sedikit biar dropdown sempat di-render
				}
			}

			function getlotno(tenant_no, lot_no) {
		        var site_url = "{{ url('ticket/getLotNoEdit') }}" + "/" + tenant_no + "/" + lot_no;
        		$.getJSON(site_url, function(data) {
					$("#lot_no").empty();
					$("#lot_no").append(data);
					$("#lot_no").trigger("change");
				});
		    }

		    function getcategory(complain_type, category_cd) {
		        var site_url = "{{ url('ticket/getCatEdit') }}" + "/" + complain_type + "/" + category_cd;
        		$.getJSON(site_url, function(data) {
					$("#category").empty();
		            $("#category").append(data);
		            $("#category").trigger("change");
				});
		    }
    	})
    </script>
@endsection