
<?php $__env->startSection('content'); ?>
	<div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Take Survey</h3>
                </div><!-- .nk-block-head-content -->
            </div><!-- .nk-block-between -->
        </div><!-- .nk-block-head -->
        <div class="nk-block">
            <div class="card card-preview">
                <div class="card-inner">
                	<?php
                        if (!empty($dP))
                        {
                            echo $dP;
                        }
                        else {
                        	echo "<p class='card-text badge badge-gray'>No Survey Available</p>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('input[type="radio"]').click(function() 
            {
                if ($(this).data("ada") == true) 
                {
                    $("#remarks").html("");
                    $("#remarks").prop("disabled", false);
                    $("#remarks").focus();
                } else
                {
                    $("#remarks").html("");
                    $("#remarks").prop("disabled", true);
                }
            });
        });

        function generateButton(publishId, questionId) {
            var buttonId = 'btnSave' + publishId; // Generate button ID
            var button = '<button type="button" id="' + buttonId + '" data-p="' + publishId + '" data-q="' + questionId + '" class="btn btn-primary">Submit</button>';
            return button;
        }

        $(document).on('click', '[id^="btnSave"]', function() {
            // var buttonId = $(this).attr('id');
            // console.log(buttonId);
            var button = $(this);
            var publishId = button.data('p');
            event.preventDefault();
            if (event.handled !== true) {
                event.handled = true;
                if ($('#frm' + publishId).valid())
                {
                    var datafrm = $('#frm' + publishId).serializeArray();
                        datafrm.push(
                            {name:"_token",value:"<?php echo e(csrf_token()); ?>"}
                        );
                    console.log(datafrm);
                    $.ajax({
                        url : "<?php echo e(url('/online_survey/save')); ?>",
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
                                    window.location.href="<?php echo e(url('/online_survey')); ?>";
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

  </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template.base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\twp_capital_new\resources\views\tenant/online_survey/index.blade.php ENDPATH**/ ?>