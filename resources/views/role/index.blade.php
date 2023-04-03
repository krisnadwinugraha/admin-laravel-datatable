@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
  <div class="pl-3 pt-3">
    <div class="row">
      <div class="col-lg-12 margin-tb">
        <div class="pull-left">
          <h2>Role Management</h2>
        </div>
        <div class="pull-right">
          @can('role-create')
              <a class="btn btn-primary mt-3" href="{{ route('role.create') }}"> Create New Role</a>
          @endcan
        </div>
      </div>
    </div>
  </div>
@stop

@section('content')
  <div class="row p-3">
    <div class="col-12">
      <div class="table-responsive">
        <table id="myTable" class="table table-stripped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Guard Name</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
@stop

@section('js')
<script>
  $(function() {
      $('#myTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('roleData')}}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'guard_name', name: 'guard_name' },
            {
              data: 'action',
              name: 'action' ,
              orderable: true, 
              searchable: true
            }
        ]
      });
  });
</script>
@stop
