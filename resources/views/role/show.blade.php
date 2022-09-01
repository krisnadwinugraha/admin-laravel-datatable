@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
  <h1>Role</h1>
  <a class="btn btn-danger mt-3" href="{{ route('role.index') }}"> Back</a>
@stop

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {{ $role->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Permissions:</strong>
            @if(!empty($rolePermissions))
                @foreach($rolePermissions as $v)
                    <label class="label label-success">{{ $v->name }},</label>
                @endforeach
            @endif
        </div>
    </div>
</div>
@stop

@section('css')

@stop

@section('js')

@stop
