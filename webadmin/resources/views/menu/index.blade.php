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
                    <h4 class="nk-block-title">Menu Entry</h4>
                </div>
            </div>
            <div class="card card-preview">
                <div class="card-inner">
                    <div class="table-responsive">
                        <table id="tblmenu" class="table table-hover table-bordered" width="100%">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>MenuID</th>
                                <th>Title</th>
                                <th>URL</th>
                                <th>Parent Menu ID</th>
                                <th>Sequence No</th>
                                <th>Icon Class</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var tblmenu;
var tblmenu = $('#tblmenu').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ url('/menu/all') }}",
    columns: [
    {data: "row_number", width:'1px', searchable:false,
        render: function (data, type, row) {
            var row_number = row.row_number
            return row_number + '.';
            }
        },
    {data:"MenuID" },
    {data:"Title" },
    {data:"URL"},
    {data:"ParentMenuID"},
    {data:"OrderSeq"},
    {data:"IconClass"}
    ],
    language: {
    "decimal": ",",
    "thousands": ".",
    },
    dom: '<"toolbar menu">frtip',
});

$("div.menu").html(
    '<button id="addmenu" class="btn btn-primary pull-up" style="margin-top: 5px">Add</button>&nbsp;'+
    '<button id="editmenu" class="btn btn-info pull-up" style="margin-top: 5px">Edit</button>&nbsp;'
);

// SELECT ROW
tblmenu.on('click', 'tr', function()
{
    if ($(this).hasClass('selected'))
    {
    $(this).removeClass('selected');
    } else {
    tblmenu.$('tr.selected').removeClass('selected');
    $(this).addClass('selected');
    }
});

$('#addmenu').click(function()
{
    // $('#modalheader').removeClass('bg-info').addClass('bg-primary white');
    $('#modaltitle').addClass('white');
    $('#modaltitle').html('Menu Entry');
    $('#modalbody').load("{{url('/menu/form')}}");
    $('#modal').data('menuID', 0);
    $('#modal').modal('show');
});

$('#editmenu').click(function()
{
    var rows = tblmenu.rows('.selected').indexes();
    if (rows.length < 1) {
    Swal.fire("Information",'Please select a row',"warning");
    return;
    }
    var data = tblmenu.rows(rows).data();
    var menuID = data[0].MenuID;
    //console.log(menuID);
    var site_url = "{{url('/menu/form')}}";//+"/"+menuID;
    //console.log(site_url);
    // window.location.href= site_url;

    // $('#modalheader').removeClass('bg-primary').addClass('bg-info white');
    $('#modaltitle').addClass('white');
    $('#modaltitle').html('Menu Edit');
    $('#modalbody').load(site_url);

    $('#modal').data('menuID', menuID);
    $('#modal').modal('show');
});
</script>
@endsection
