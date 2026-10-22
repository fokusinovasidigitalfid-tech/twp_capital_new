@extends('template.base')
@section('content')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
	<div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">New Overtime Request</h3>
                </div><!-- .nk-block-head-content -->
            </div><!-- .nk-block-between -->
        </div><!-- .nk-block-head -->
        <div class="nk-block">
            <div class="card card-preview">
                <div class="card-inner">
                    <form class="form-horizontal" id="frm" enctype="multipart/form-data" method="post" action="">
                        @csrf
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-2 form-label">Tenant </label>
                                <div class="col-10">
                                    <select name="tenant_no" id="tenant_no" class="form-control select2" data-placeholder="Choose a Tenant">
                                        <option value=""></option>
                                        <?php echo $combo_tenant; ?>
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 form-label">Unit <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <div class="form-control-wrap">
                                        <select name="lot_no[]" id="lot_no" class="form-control select2" multiple="multiple" data-placeholder="Select Multiple Unit">
                                            
                                        </select>
                                        <button type="button" id="viewlayout" class="btn btn-primary btn-xs" style="margin-top:10px">View layout</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-4">
                                    <label class="col-xs-2 form-label">Overtime Date <span class="text-danger">*</span></label>
                                    <div class="col-xs-10">
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-left">
                                                <em class="icon ni ni-calendar"></em>
                                            </div>
                                            <input type="text" id="overtime_date" name="overtime_date" class="form-control datepickerr" data-date-format="dd/mm/yyyy" value="<?php $mydate=date("d/m/Y");echo "$mydate";?>" autocomplete="off">
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="col-xs-2 form-label">Start <span class="text-danger">*</span></label>
                                    <div class="col-xs-10">
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-left">
                                                <em class="icon ni ni-clock"></em>
                                            </div>
                                            <!-- <input type="text" id="start" name="start" class="form-control dstart" autocomplete="off"> -->
                                             <select id="start" name="start" class="form-control dstart"></select>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="col-xs-2 form-label">End <span class="text-danger">*</span></label>
                                    <div class="col-xs-10">
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-left">
                                                <em class="icon ni ni-clock"></em>
                                            </div>
                                            <!-- <input type="text" id="end" name="end" class="form-control dend" autocomplete="off"> -->
                                            <select id="end" name="end" class="form-control dend"></select>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 form-label">Description <span class="text-danger">*</span></label>
                                <div class="col-10">
                                    <textarea class="form-control" rows="3" maxlength="255" placeholder="Overtime Description" name="description">TWP (Tenant Web Portal)</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-2 form-label"></label>
                                <div class="col-10">
                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <strong>Disclaimer</strong>
                                            <br>
                                            <label>Overtime Hours:</label>
                                            <br>
                                            <ul>
                                                <li>Monday - Friday (18:00 - 07:00)</li>
                                                <li>Saturday (13:00 - 07:00)</li>
                                                <li>Sunday (08:00 - 23:00)</li>
                                                <li>Public Holiday Closed</li>
                                            </ul>
                                            <p>
                                            Request will be charge as overtime on which the rate will be determined from time to time by the lessor.</p>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
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
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            
            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
            console.log(time);
            if (time <= '23:59:59' && time >= '17:29:59'){
                $("#frm :input").prop("disabled", true);
            }

            // Generate dropdown jam
            function generateHourOptions(selector, start, end) {
                let el = document.querySelector(selector);
                el.innerHTML = "";

                for (let i = start; i <= end; i++) {
                    let hour = i.toString().padStart(2, '0') + ":00";
                    let opt = new Option(hour, hour);
                    el.add(opt);
                }
            }

            // Tambahkan +1 jam untuk end time minimal
            function addOneHour(time) {
                let h = parseInt(time.split(":")[0]) + 1;
                if (h > 23) h = 23;
                return `${String(h).padStart(2,'0')}:00`;
            }

            // Update end time when start changed
            function attachStartChangeListener(endMax) {
                $(".dstart").off("change").on("change", function(){
                    let startHour = parseInt(this.value.split(":")[0]);
                    let minEnd = addOneHour(this.value);
                    generateHourOptions(".dend", startHour + 1, endMax);
                    $(".dend").val(minEnd);
                });
            }

            // 🔥 Cek Hari & Set waktu dari DB
            function checkDay(date) {

                let day = date.getDay(); // 0=Sun, 6=Sat

                // Minggu: bebas 00:00 - 23:00
                if (day === 0) {
                    generateHourOptions(".dstart", 0, 23);
                    $(".dstart").val("00:00");

                    generateHourOptions(".dend", 1, 23);
                    $(".dend").val("01:00");

                    attachStartChangeListener(23);
                    return;
                }

                // Sabtu = E, Senin-Jumat = D
                let dayType = (day == 6) ? 'E' : 'D';

                $.ajax({
                    url: "{{ url('overtime/workhour') }}",
                    type: "GET",
                    data: { day_type: dayType },
                    success: function(res){

                        let end_time = res.end_time.substring(0,2);
                        let minStart = parseInt(end_time);

                        let maxAllowed = 23;
                        if (minStart > maxAllowed) minStart = maxAllowed;

                        generateHourOptions(".dstart", minStart, maxAllowed);
                        $(".dstart").val(end_time + ":00");

                        let minEnd = addOneHour(end_time + ":00");
                        generateHourOptions(".dend", minStart + 1, maxAllowed);
                        $(".dend").val(minEnd);

                        attachStartChangeListener(maxAllowed);
                    }
                });
            }

            // Datepicker: disable Sunday + min today
            $('.datepickerr').datepicker({
                startDate: new Date(),
                
                autoclose: true
            }).on("changeDate", function(e){
                checkDay(e.date);
            });

            // Trigger default hari ini
            let today = new Date();
            $('.datepickerr').datepicker('setDate', today);
            checkDay(today);

            $('.select2').select2();

            $('#tenant_no').change(function() {
                var tenant_no = $(this).val();

                if (tenant_no !== '') {
                    var site_url = "{{ url('overtime/getLotNo') }}";
                    $.post(site_url,
                        {
                            "_token": "{{ csrf_token() }}",
                            id_tenancy: tenant_no
                        },
                        function(data) {
                            $("#lot_no").empty().append(data).trigger('change');
                        }
                    );

                    // Update hidden field setiap ganti tenant
                    var ent = $("#tenant_no option:selected").data("entity");
                    var prj = $("#tenant_no option:selected").data("project");

                    $("#entity").val(ent);
                    $("#project").val(prj);
                } 
                else {
                    $("#lot_no").empty();
                }
            });

            $('#lot_no').change(function() {
                var ent = $("#tenant_no option:selected").data("entity");
                var prj = $("#tenant_no option:selected").data("project");

                $("#entity").val(ent);
                $("#project").val(prj);
            });

            $('#tenant_no').trigger('change');

            $('#viewlayout').click(function() {
                var tenant_no = $("#tenant_no").val();
                var lot_no = $("#lot_no").val(); // array
                var ent = $("#tenant_no option:selected").data("entity");
                var prj = $("#tenant_no option:selected").data("project");

                // 🔍 Check request values before AJAX
                console.log("=== REQUEST DATA ===");
                console.log("Tenant No :", tenant_no);
                console.log("Lot No :", lot_no);
                console.log("Entity :", ent);
                console.log("Project :", prj);

                if(!tenant_no || !lot_no || lot_no.length === 0){
                    alert("Please select Tenant & Unit first!");
                    return;
                }

                $.ajax({
                    url: "{{ url('overtime/getLayoutView') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        tenant_no: tenant_no,
                        lot_no: lot_no,
                        entity: ent,
                        project: prj
                    },
                    beforeSend: function(){
                        $("#modalbodylg").html('<p>Loading...</p>');
                    },
                    success: function(response){
                        console.log("=== RESPONSE SUCCESS ===");
                        console.log(response); // hasil HTML gambar yg diterima
                        $("#modalbodylg").html(response);
                    },
                    error: function(xhr, status, error){
                        $("#modalbodylg").html('<p class="text-danger">Failed to load layout.</p>');
                    }
                });

                $('#modaltitlelg').text('View Layout');
                $('#modallg').modal('show');
            });

            $.validator.addMethod("cek_jam", function (value, element,params) {
                // console.log(value);console.log(params);//console.log(element);
				var isSuccess = false;
				// var content = $('#news_descs').val();
				var date = $('#overtime_date').val();
                var dtArray = date.split("/");
                var ot_date = dtArray[2]+"-"+dtArray[1]+"-"+dtArray[0];
                var start = $('#start').val();
				var endd = $('#end').val();
                var start_ot = new Date(ot_date+' '+start);
                var end_ot = new Date(ot_date+' '+endd);
                // console.log('otdate:',ot_date);
                // console.log('startot:',start_ot);
                var diff =(end_ot.getTime() - start_ot.getTime()) / 1000;
                diff = diff / (60 * 60);
                diff = Math.abs(diff);
            
                // console.log('1: ', end_ot.getTime() - start_ot.getTime());
                // console.log('startot:',start_ot.getTime())
                // console.log('endot:',end_ot.getTime())
                console.log('diff:', diff);
				// // console.log('aa:',youtubelink,picture);
				if( diff>=1 ){
                    isSuccess=true;
				}
				return isSuccess;

			});
            // --- validasi tambahan baru ---
            $.validator.addMethod("check_time_order", function (value, element, params) {
                var start = $('#start').val();
                var endd = $('#end').val();
                var date = $('#overtime_date').val();

                if (!start || !endd || !date) return true; // skip jika kosong

                var dtArray = date.split("/");
                var ot_date = dtArray[2] + "-" + dtArray[1] + "-" + dtArray[0];
                var start_ot = new Date(ot_date + ' ' + start);
                var end_ot = new Date(ot_date + ' ' + endd);

                // End harus lebih besar dari Start minimal 1 jam
                var diff = (end_ot.getTime() - start_ot.getTime()) / (1000 * 60 * 60);

                return diff >= 1;
            }, "End time must be at least 1 hour after start time.");// --- validasi tambahan baru ---
            $.validator.addMethod("check_time_order", function (value, element, params) {
                var start = $('#start').val();
                var endd = $('#end').val();
                var date = $('#overtime_date').val();

                if (!start || !endd || !date) return true; // skip jika kosong

                var dtArray = date.split("/");
                var ot_date = dtArray[2] + "-" + dtArray[1] + "-" + dtArray[0];
                var start_ot = new Date(ot_date + ' ' + start);
                var end_ot = new Date(ot_date + ' ' + endd);

                // End harus lebih besar dari Start minimal 1 jam
                var diff = (end_ot.getTime() - start_ot.getTime()) / (1000 * 60 * 60);

                return diff >= 1;
            }, "End time must be at least 1 hour after start time.");
            $("#frm").validate({
                ignore:[],
                rules: {
                    tenant_no:{
                        required:true
                    },
                    lot_no:{
                        required:true
                    },
                    overtime_date:{
                        required:true
                    },
                    start:{
                        required:true
                    },
                    end:{
                        required:true,cek_jam:true,check_time_order:true
                    },
                    description:{
                        required:true
                    }
                },
                messages: {
                    start:{
						cek_jam:"Minimum overtime duration is one hour."
					},
                    end:{
						cek_jam:"Minimum overtime duration is one hour.",
                        check_time_order:"End time must be after start time (minimum 1 hour)."
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
                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'Overtime request will be charged, if you agree please continue.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Continue'
                        }).then(function(a){
                            if (a.value==true)
                            {
                                var overtime_date = $('#overtime_date').val();
                                var year =overtime_date.substr(6,4);
                                var month=overtime_date.substr(3,2);
                                var day =overtime_date.substr(0,2);
                                var format_overtime = month+"/"+day+"/"+year;
                                var datafrm = $('#frm').serializeArray();
                                    datafrm.push(
                                        {name:"f_overtime_date",value:format_overtime},
                                    );
                                console.log(datafrm);

                                $.ajax({
                                    url : "{{ url('/overtime/save') }}",
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
                                //block(false,'.content-body');
                            }
                        })
                    } 
                }
            });

            function loaddata(){
                
            }
        })
    </script>
@endsection