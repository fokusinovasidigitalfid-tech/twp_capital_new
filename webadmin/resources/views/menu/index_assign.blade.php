@extends('template.layout2.base')
@section('content')
<div class="nk-content-body">
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">Assign Menu Entry</h4>
                </div>
            </div>
            <div class="card card-preview">
                <div class="card-inner">
                    <div class="form-group">
                        <div class="row g-3 align-center">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label class="form-label" for="site-name">Group</label>
                                    <span class="form-note">Select the name of your group.</span>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <select data-placeholder="Choose a Group..." class="select2 form-control" id="group" name="group">
                                        <option value=""></option>
                                        @foreach ($cmbGroup as $key)
                                            <option value="{{$key->group_cd}}">{{$key->group_descs}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="tblassign" class="table table-hover table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Title</th>
                                <th>Path</th>
                                <th><input type="checkbox" id="cbHeader" onclick='cbAll(event)'/></th></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div>
                        <div class="" style="text-align:right;margin-right: 50px;">
                        <button type="button" id="btnSave" class="btn btn-primary">SAVE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  <script type="text/javascript">
    //dropdown select2
    $('.select2').select2({width:'100%'});

    var tblassign;
    var tblassign = $('#tblassign').DataTable({
      // processing: true,
      // serverSide: true,
      paging: false,
      ajax: "{{ url('/menu/assign/table') }}",
      order: [[ 2, "asc" ]],
      columns: [
        {data: "row_number", width:'1px', sortable: false, orderable:false,
          render: function (data, type, row){
            var row_number = row.row_number
            return row_number + '.';
          }},
        {data: 'Title', sortable: false, orderable:false,
          render: function(data, type, row){
            var str = data.replace(/\s/g, "   ");
            //console.log(str);
            return str;
          }},
        {data:"Path", visible:false},
        {data:"MenuID", searchable:false, orderable:false,
          render: function(data, type, row){
            switch(data) {
              case 2:
              default:
                return '<input type="checkbox" id="cb_' + data + '" name="cb_' + data + '" onclick="cbclick('+data+')"/>';
            }
          }
        }
      ],
      bFilter: false
    });

    //dropdown change grup
    $('#group').on("change",function(e){
      SetCheckBox(false);
      var groupId = $('#group').val();
      //console.log(groupId);
      if(groupId !== '')
      {
        $.ajax({
          url: '{{ url("/menu/assign/list")}}',
          data: {
            _token: "{{ csrf_token() }}",
            gid: groupId},
          type: 'post',
          dataType: 'json',
          success: function(dts, status)
          {
            var n = 0;
            $.each(dts, function(i, data){
              var ids = tblassign.rows().indexes();

              for (var i = 0; i < ids.length; i++) {
                var menuID = tblassign.rows(i).data()[0].MenuID;
                if(data.groupCd != '')
                {
                  if(menuID == data.MenuID) {
                    $('#tblassign input[name=cb_'+menuID+']').prop('checked', true);
                    n++;
                  }
                }
              };
              if(n == ids.length) {
                $('#cbHeader').prop('checked', true);
              }
            });
          },
          error: function(jqXHR, textStatus, errorThrown)
          {
            Swal.fire({
              title: "Error",
              animation: false,
              icon:"error",
              text: textStatus+' GetList : '+errorThrown,
              confirmButtonText: "OK"
            });
          }
        });
      }
    });

    $('#btnSave').click(function()
    {
      var groupId = $('#group').val();
      console.log(groupId);
      if(groupId !== '')
      {
        var ids = tblassign.rows().indexes();
        var ACCESS_CODE = '';
        var selData = [];
        for(var i = 0; i < ids.length; i++)
        {
          var menuID = tblassign.rows(i).data()[0].MenuID;
          var chx = $('#tblassign input[name=cb_'+menuID+']').prop('checked');

          ACCESS_CODE = '';
          if(chx) {
            ACCESS_CODE = 1;
          }

          if(ACCESS_CODE != '')
          {
            var today = new Date().toLocaleString();
            var sysMenuGroup = new Object()
            sysMenuGroup.GroupCd = groupId;
            sysMenuGroup.MenuID = menuID;
            selData.push(sysMenuGroup);
          }
        }

        $.ajax({
          url  : '{{ url("/menu/assign/save") }}',
          data : {
            _token: "{{ csrf_token() }}",
            models: selData,
            gid: groupId},
          type : 'POST',
          dataType: 'json',
          success:function(event, data)
          {
            if(event.status=='OK')
            {
              Swal.fire({
                  title: "Information",
                  animation: true,
                  icon:"success",
                  text: event.pesan,
                  confirmButtonText: "OK"
              }).then(function(){
                location.reload();
              });
            } else {
              Swal.fire({
                  title: "Error",
                  animation: true,
                  icon:"error",
                  text: event.pesan,
                  confirmButtonText: "OK"
              });
            }
          },
          error: function(jqXHR, textStatus, errorThrown)
          {
            Swal.fire({
              title: "Error",
              animation: true,
              icon:"error",
              text: textStatus+' Save : '+errorThrown,
              confirmButtonText: "OK"
            });
          }
        });
      } else {
        Swal.fire("Information",'Please select a group first !',"warning");
      }
    });

    //checkbox all
    function cbAll(e)
    {
      e = e || event; /* get IE event ( not passed ) */
      e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;

      var chkSelectAll = $('#cbHeader');

      if (chkSelectAll.length && chkSelectAll.is(':checked') && !status) {
        SetCheckBox(true);
      } else {
        SetCheckBox(false);
      }
    }

    function SetCheckBox(val)
    {
      var rows = tblassign.rows().indexes();
      for (var i = 0; i < rows.length ; i++)
      {
        var menuId = tblassign.rows(i).data()[0].MenuID;
        $('#tblassign' + ' input[name=cb' + '_' + menuId + ']').prop('checked', val);
      }
      $('#cbHeader').prop('checked', val);
    }

    function cbclick(data)
    {
      $('#cbHeader').prop('checked', false);
      var thisVal = $('#tblassign input[name=cb_'+data+']').is(':checked');
      if(thisVal == false)
      {
        uncheck(data);
        return;
      }

      var rows = tblassign.rows().indexes();
      var selMenu = '';
      $.each(rows, function()
      {
        var selMenu = tblassign.rows(this).data()[0].MenuID;
        if(data == selMenu)
        {
          var parentID = tblassign.rows(this).data()[0].ParentMenuID;
          if(!($('#tblassign input[name=cb_'+parentID+']').is(':checked')))
          {
            $('#tblassign input[name=cb_'+parentID+']').prop('checked', true);
          }

          var thisVal = $('#tblassign input[name=cb_'+parentID+']').is(':checked');
          if(thisVal == false)
            return;

          var selMenu = '';
          $.each(rows, function(){
            selMenu = tblassign.rows(this).data()[0].MenuID;

            if(parentID==selMenu)
            {
              var level1 = tblassign.rows(this).data()[0].ParentMenuID;
              console.log('level1 : '+level1);
              if(!($('#tblassign input[name=cb_'+level1+']').is(':checked')))
              {
                $('#tblassign input[name=cb_'+level1+']').prop('checked', true);
              }

              var thisVal = $('#tblassign input[name=cb_'+level1+']').is(':checked');
              if(thisVal==false)
                // console.log('end');
                return;

              var rows = tblassign.rows().indexes();
              var selMenu = '';

              $.each(rows, function(){
                selMenu = tblassign.rows(this).data()[0].MenuID;
                if(level1 == selMenu)
                {
                  var level2 = tblassign.rows(this).data()[0].ParentMenuID;
                  if(!$('#tblassign input[name=cb_'+level2+']').is(':checked'))
                  {
                    $('#tblassign input[name=cb_'+level2+']').prop('checked', true);
                  }
                }
              });
            }
          });
          return;
        }
      });
    }

    function uncheck(val)
    {
      $('#cbHeader').prop('checked', false);
      var thisVal = $('#tblassign input[name=cb_'+ val +']').is(':checked');
      var rows = tblassign.rows().indexes();
      var selMenu = '';

      $.each(rows, function(){
        selMenu = tblassign.rows(this).data()[0].ParentMenuID;
        if(val==selMenu)
        {
          var MenuID = tblassign.rows(this).data()[0].MenuID;
          $('#tblassign input[name=cb_'+MenuID+']').prop('checked', false);
          var rows = tblassign.rows().indexes();
          var selMenu  = '';
          $.each(rows, function(){
            selMenu = tblassign.rows(this).data()[0].ParentMenuID;
            if (MenuID == selMenu)
            {
              var level1 = tblassign.rows(this).data()[0].MenuID;
              $('#tblassign input[name=cb_'+level1+']').prop('checked', false);
              var rows = tblassign.rows().indexes();
              var selMenu = '';
              $.each(rows, function(){
                selMenu = tblassign.rows(this).data()[0].ParentMenuID;
                if(level1 == selMenu)
                {
                  var level2 = tblassign.rows(this).data()[0].MenuID;
                  $('#tblassign input[name=cb_'+level2+']').prop('checked', false);
                }
              });
            }
          });
          return;
        }
      });
    }
  </script>
@endsection
