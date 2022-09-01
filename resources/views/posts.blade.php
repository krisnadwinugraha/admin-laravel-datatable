@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
  <h1>Post</h1>
  <a href="javascript:void(0)" class="btn btn-danger mt-3" id="create-new-post" onclick="addPost()">Add Post</a>
      
@stop

@section('content')

    <div class="row" style="clear: both;margin-top: 18px;">
        <div class="col-12">
          
          <table id="laravel_crud" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
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
<div class="modal fade" id="post-modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Post</h4>
        </div>
        <div class="modal-body">
            <form name="userForm" class="form-horizontal">
               <input type="hidden" name="post_id" id="post_id">
                <div class="form-group">
                    <label for="name" class="col-sm-2">Title</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
                        <span id="titleError" class="alert-message"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2">Description</label>
                    <div class="col-sm-12">
                        <textarea class="form-control" id="description" name="description" placeholder="Enter description" rows="4" cols="50">
                        </textarea>
                        <span id="descriptionError" class="alert-message"></span>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="createPost()">Save</button>
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
        ajax: "{{ route('postData')}}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'description', name: 'description' },
            {
              data: 'action',
              name: 'action' ,
              orderable: true, 
              searchable: true
            }
        ]
    });
  });

  function addPost() {
    $("#post_id").val('');
    $('#post-modal').modal('show');
  }

  function editPost(id) {
    var id  = id;
    let _url = `/posts/${id}`;
    $('#titleError').text('');
    $('#descriptionError').text('');

    $.ajax({
      url: _url,
      type: "GET",
      success: function(response) {
          if(response) {
            $("#post_id").val(response.id);
            $("#title").val(response.title);
            $("#description").val(response.description);
            $('#post-modal').modal('show');
          }
      }
    });
  }

  function createPost() {
    var title = $('#title').val();
    var description = $('#description').val();
    var id = $('#post_id').val();
    let _url     = `/posts`;
    let _token   = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
        url: _url,
        type: "POST",
        data: {
          id: id,
          title: title,
          description: description,
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
              $('#title').val('');
              $('#description').val('');

              $('#post-modal').modal('hide');
            }
        },
        error: function(response) {
          $('#titleError').text(response.responseJSON.errors.title);
          $('#descriptionError').text(response.responseJSON.errors.description);
          swal("Error!", "Check your Data", "error");
        }
      });
  }

  function deletePost(id) {
    var id = id;
    let _url = `/posts/${id}`;
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
