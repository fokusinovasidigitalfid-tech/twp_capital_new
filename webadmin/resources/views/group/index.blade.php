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
                    <h4 class="nk-block-title">Group Menu</h4>
                </div>
            </div>
            <div class="card card-preview">
                <div class="card-inner">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tblgroup" width="100%">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Group Code</th>
                                <th>Group Description</th>
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
  var tblgroupp;
  $(function() {
    $('.select2').select2();
    tblgroupp = $('#tblgroup').DataTable({
          processing: true,
          serverSide: true,
          // ajax: "{{ url('/group/all') }}",
          ajax: {
            "url" : "{{ url('/group/all') }}",
            "type": "POST",
            data: {
              "_token": "{{ csrf_token() }}"
              }
            },
          columns: [
              { data: 'row_number', name: 'row_number' },
              { data: 'group_cd', name: 'group_cd' },
              { data: 'group_descs', name: 'group_descs' }
          ],
          dom: '<"toolbar group">frtip',
          "responsive": {
            details: {
                type: 'column',
                target: 8
            }
          }
      });
      $("div.group").html(
        '<button id="addgroup" class="btn btn-primary pull-up" style="margin-top: 5px">Add</button>&nbsp;'+
        '<button id="editgroup" class="btn btn-info pull-up" style="margin-top: 5px">Edit</button>&nbsp;'+
        '<button id="deletegroup" class="btn btn-danger pull-up" style="margin-top: 5px">Delete</button>&nbsp;'

      );
      tblgroupp.on('click', 'tr', function() {
          if ($(this).hasClass('selected')) {
              $(this).removeClass('selected');
          } else {

            tblgroupp.$('tr.selected').removeClass('selected');
              $(this).addClass('selected');
          }
      });

      $('#addgroup').click(function(){
        $('#modaltitlesm').addClass('white');
        $('#modaltitlesm').html('Group Entry');
        $('#modalbodysm').load("{{ url('/group/form') }}");
        $('#modalsm').data('GroupID', 0);
        $('#modalsm').modal('show');

      })

      $('#editgroup').click(function(){
        var rows = tblgroupp.rows('.selected').indexes();
        if (rows.length < 1) {
            Swal.fire.fire("Information",'Please select a row',"warning");
            return;
        }
        var data = tblgroupp.rows(rows).data();
        var GroupID = data[0].GroupID;

        // $('#modalheader').removeClass('bg-primary').addClass('bg-info white');
        $('#modaltitlesm').addClass('white');
        $('#modaltitlesm').html('Menu Edit');
        $('#modalbodysm').load("{{ url('/group/form') }}");
        $('#modalsm').data('GroupID', GroupID);
        $('#modalsm').modal('show');
    })


        $('#deletegroup').click(function(){
            var rows = tblgroupp.rows('.selected').indexes();
            if (rows.length < 1) {
                Swal.fire("Information",'Please select a row',"warning");
                return;
            }
            var data = tblgroupp.rows(rows).data();
            var id = data[0].GroupID;

            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            })
            .then(function(a){
                if (a.value==true) {
                    Delete(id);
                }else{
                    block(false,'.content-body');
                }
            })
        })
    });

    function Delete(id) {
        $.ajax({
            url : "{{ url('/group/delete') }}",
            type:"POST",
            data: { id: id,"_token": "{{ csrf_token() }}" },
            dataType:"json",
            success:function(event, data){
                Swal.fire("Information",event.pesan,"success");
                tblgroupp.ajax.reload(null,true);
            },
            error: function(jqXHR, textStatus, errorThrown){
                Swal.fire("Information",textStatus+' Save : '+errorThrown,"warning");
            }
        });
    }

</script>

@endsection

