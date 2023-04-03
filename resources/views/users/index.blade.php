@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
  <div class="pl-3 pt-3">
    <h1>User</h1>
    <a href="javascript:void(0)" class="btn btn-danger mt-3" id="create-new-user" onclick="addUser()">Create User</a>                            
  </div>
@stop

@section('content')
  <div class="row p-3">
    <div class="col-12">
      <div class="table-responsive">
        <table id="laravel_crud" class="table table-stripped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Alamat</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>  
    </div>
  </div>
@stop

<div class="modal fade" id="user_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">User</h4>
        </div>
        <div class="modal-body">
            <form name="userForm" class="form-horizontal">
               <input type="hidden" name="user_id" id="user_id">
                <div class="form-group">
                    <label for="name" class="col-sm-2">Name</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                        <span id="nameError" class="alert-message"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label form="email" class="col-sm-2">Email</label>
                    <div class="col-sm-12">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email">
                        <span id="emailError" class="alert-message"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="col-sm-2">Password</label>
                    <div class="col-sm-12">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                        <span id="passwordError" class="alert-message"></span>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                  <div class="form-group">
                      <strong>Role:</strong>
                      {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple' , 'id' => 'role')) !!}
                  </div>
                </div>

                <div class="form-group">
                    <label for="phone" class="col-sm-2">Phone</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone">
                        <span id="phoneError" class="alert-message"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat" class="col-sm-2">Alamat</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Enter Alamat">
                        <span id="alamatError" class="alert-message"></span>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="createUser()">Save</button>
        </div>
    </div>
  </div>
</div>

@section('js')
<script>
$(function() {
    $('#laravel_crud').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('userData')}}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'alamat', name: 'alamat' },
            { data: 'status', name: 'status' },
            {
              data: 'action',
              name: 'action' ,
              orderable: true, 
              searchable: true
            }
        ]
    });
  });
  function addUser() {
    $("#user_id").val('');
    $('#user_modal').modal('show');
  }

  function editUser(id) {
    var id  = id;
    let _url = `/users/${id}`;
    $('#nameError').text('');
    $('#emailError').text('');
    $('#phoneError').text('');
    $('#passwordError').text('');
    $('#alamatError').text('');

    $.ajax({
      url: _url,
      type: "GET",
      success: function(response) {
          if(response) {
            $("#user_id").val(response.id);
            $("#name").val(response.name);
            $("#email").val(response.email);
            $("#password").val(response.password);
            $("#phone").val(response.phone);
            $("#alamat").val(response.alamat);
            $('#user_modal').modal('show');
          }
      }
    });
  }

  function createUser() {
    var name = $("#name").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var phone = $("#phone").val();
    var alamat = $("#alamat").val();
    var role = $('#role option:selected').val();
    var id = $("#user_id").val();
    let _url     = `/users`;
    let _token   = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
        url: _url,
        type: "POST",
        data: {
          id: id,
          name: name,
          email: email,
          password: password,
          phone: phone,
          alamat: alamat,
          role: role,
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
              $("#id").val('');
              $("#name").val('');
              $("#email").val('');
              $("#password").val('');
              $("#phone").val('');
              $("#alamat").val('');

              $('#user_modal').modal('hide');
            }
        },
        error: function(response) {
          swal("Error!", "Check your Data", "error");
        }
      });
  }

  function deleteUser(id) {
    var id = id;
    let _url = `/users/${id}`;
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
