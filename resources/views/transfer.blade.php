@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
  <h1>Transfer</h1>
  <a href="javascript:void(0)" class="btn btn-danger mt-3" id="create-new-transfer" onclick="addTransfer()">Add Transfer</a>
  <a class="btn btn-success mt-3" href="{{ route('export') }}">Export Excel</a>  
  <a class="btn btn-warning mt-3" href="{{ route('pdf') }}">Export PDF</a>  
@stop

@section('content') 
  <div class="row" style="clear: both;margin-top: 18px;">
    <div class="col-12">
        <div class="table-responsive">
          <table id="laravel_crud" class="table table-striped table-bordered">
            <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Action</th>
                </tr>
            </thead>
          </table>
        </div>   
    </div>
  </div>
@stop

<div class="modal fade" id="transfer-modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Transfer</h4>
        </div>
        <div class="modal-body">
            <form name="userForm" class="form-horizontal">
               <input type="hidden" name="transfer_id" id="transfer_id">
                <div class="form-group">
                    <label for="name" class="col-sm-2">Name</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                        <span id="transferError" class="alert-message"></span>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="createTransfer()">Save</button>
        </div>
    </div>
  </div>
</div>

@section('css')

@stop

@section('js')
  <script>
    $(function() {
      $('#laravel_crud').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('transferData')}}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            {
              data: 'action',
              name: 'action' ,
              orderable: true, 
              searchable: true
            }
        ]
      });
    });

    function addTransfer() {
      $("#transfer_id").val('');
      $('#transfer-modal').modal('show');
    }

    function editTransfer(id) {
      var id  = id;
      let _url = `/transfer/${id}`;
      $('#transferError').text('');

      $.ajax({
        url: _url,
        type: "GET",
        success: function(response) {
            if(response) {
              $("#transfer_id").val(response.id);
              $("#name").val(response.name);
              $('#transfer-modal').modal('show');
            }
        }
      });
    }

    function createTransfer() {
      var name = $('#name').val();
      var id = $('#transfer_id').val();
      let _url     = `/transfer`;
      let _token   = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
          url: _url,
          type: "POST",
          data: {
            id: id,
            name: name,
            _token: _token
          },
          success: function(response) {
            if(response.code == 200) {
              if(id != ""){
                var oTable = $('#laravel_crud').dataTable();
                oTable.fnDraw(false)
                swal("Updated!", "Data Has Been Updated", "success");
              } else {
                var oTable = $('#laravel_crud').dataTable();
                oTable.fnDraw(false)
                swal("Created!", "Data Has Been Created", "success");
              }
              $('#name').val('');

              $('#transfer-modal').modal('hide');
            }
          },
          error: function(response) {
            $('#transferError').text(response.responseJSON.errors.name);
            swal("Error!", "Check your Data", "error");
          }
        });
    }

    function deleteTransfer(id) {
      var id = id;
      let _url = `/transfer/${id}`;
      let _token   = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
          url: _url,
          type: 'DELETE',
          data: {
            _token: _token
          },
          success: function(response) {
            var oTable = $('#laravel_crud').dataTable();
                  oTable.fnDraw(false);
            swal("Deleted!", "Data Has Been Deleted", "success");
          }
        });
    }
  </script>
@stop
