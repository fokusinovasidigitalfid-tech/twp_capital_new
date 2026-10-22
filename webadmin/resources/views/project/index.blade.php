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
                    <h4 class="nk-block-title">Project Entry</h4>
                </div>
            </div>
            <div class="card card-preview">
                <div class="card-inner">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tblprojects" width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Entity Code</th>
                                <th>Project No.</th>
                                <th>Project Name</th>
                                <th>Database Profile</th>
                                <th>Database Name</th>
                                <th>Status</th>
                                {{-- <th>Priority No</th> --}}
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                </div>
            </div><!-- .card-preview -->
        </div> <!-- nk-block -->
    </div>
</div>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var tblproject;
    var tblproject = $('#tblprojects').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ url('/projects') }}",
      columns: [
        {data: "row_number", width:'1px', searchable:false,
            render: function (data, type, row) {
                var row_number = row.row_number
                return row_number + '.';
                }
            },
        {data: 'entity_cd', name: 'entity_cd'},
        {data: 'project_no', name: 'project_no'},
        {data: 'project_descs', name: 'project_descs'},
        {data: 'db_profile', name: 'db_profile'},
        {data: 'db_name', name: 'db_name'},
        {data: 'project_status_descs', name: 'project_status_descs'},
        // {data: 'seq_no', name: 'seq_no'},
      ],
      dom: '<"toolbar project">frtip',
    });

    $("div.project").html(
      '<button id="addproject" class="btn btn-primary pull-up" style="margin-top: 5px">Add</button>&nbsp;'+
      '<button id="editproject" class="btn btn-info pull-up" style="margin-top: 5px">Edit</button>&nbsp;'
    );

    tblproject.on('click', 'tr', function() {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            tblproject.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    $('#addproject').click(function(){
        window.location.href = "{{ URL::to('projects/form/add/0') }}";
    });

    $('#editproject').click(function(){
        var rows = tblproject.rows('.selected').indexes();
        if (rows.length < 1) {
            Swal.fire("Information",'Please select a row',"warning");
            return;
        }
        var data = tblproject.rows(rows).data();
        var rowID = data[0].RowID;

        var site_url = "{{ URL::to('projects/form/edit') }}"+ "/" +rowID;
        window.location.href= site_url;
    })
</script>
@endsection
