
<?php $__env->startSection('content'); ?>
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
                    <h4 class="nk-block-title">Survey Results</h4>
                </div>
            </div>
            <div class="card card-preview">
                <div class="card-inner">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tblpublished" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>          
                                    <th class="sorting_asc">Survey Title</th>
                                    <th>Subject</th>
                                    <th>Publish Date</th>
                                    <th>Expired Date</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div><!-- .card-preview -->
        </div> <!-- nk-block -->
    </div>
</div>
<script type="text/javascript">
    var tblsurvey;
    var tblpublishedd;
    $(function(){
         tblpublishedd = $('#tblpublished').DataTable( 
        {
             "language": {
                "decimal": ",",
                "thousands": ".",
            },
             "dom": '<"toolbar tblpublished">frtip',
            select: true,
            // order: [[ 3, 'desc' ]],
            "serverSide": true,
            "ajax":{
                "url":"<?php echo e(url('survey/result/all')); ?>",
                "data":{"sSearch": function(d){
                    var search = $('#txt_search').val();
                    var b="";
                    if(search == null || search==""){
                        return b;
                    }{
                        return search;
                    }
                 }},             
                "type":"POST"
            },
            "columns": [
                {data: "row_number",name:"row_number", searchable:false},
                {data: "title",name:"title", searchable:true},
                {data:"subjects",name:"subjects",searchable:true,
                     render: function (data, type, row) {
                    x=data;
                    // console.log(x);
                    var cc='<ul style="list-style-type:disc;margin-left: 20px">';
                    xArray = x.split(',');
                    $.each(xArray, function(index, value) { 
                        // console.log(value);
                        cc=cc+'<li>'+value+'</li>';
                    });
                    return cc+'</ul>';
                    }
                },
                {data: "publishdate",name:"publishdate",searchable:true,
                render: function (data, type, row) {
                    return FormatDateNew(data);
                }},
                {data: "expireddate",name:"expireddate",searchable:true,
                render: function (data, type, row) {
                    return FormatDateNew(data);
                }},
               
            ]
          
        });
        $("div.tblpublished").html(
            '<button id="btnresult" class="btn btn-primary pull-up">Result</button>&nbsp;'
        );
        tblpublishedd.on('click', 'tr', function() {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                tblpublishedd.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
        $('#btnresult').click(function(){
            // alert('weee');
            var rows = tblpublishedd.rows('.selected').indexes();
            if (rows.length < 1) {
                Swal.fire("Information",'Please select a row',"warning");
                return;
            } 
            var data = tblpublishedd.rows(rows).data();
            var publish_id = data[0].publish_id;
            // alert(publish_id);
            block(true,'#modalbodyxl');
            // $('#modalbodyxl').html("");
            // $('#modalxl').modal({backdrop: 'static', keyboard: false})  
         
            // $('#modaltitlexl').addClass('white');
            // $('#modaltitlexl').html('Survey Result');
            // $('.modal-footer').html("");
            // // $('.modal-footer').html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>');
            // $('#modalbodyxl').load("<?php echo e(url('/survey/result/see/')); ?>"+'/'+publish_id);
            // $('#modalxl').data('id', publish_id);
            // $('#modalxl').data('form', 'edit');
            // $('#modalxl').modal('show');
            window.location.href = "<?php echo e(url('/survey/result/see/')); ?>"+'/'+publish_id;
            
        });
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('template.layout2.base', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\twp_capital_new\resources\views\admin/survey/result/index.blade.php ENDPATH**/ ?>