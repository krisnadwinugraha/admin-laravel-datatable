
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="pl-3 pt-3">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Edit Role</h2>
                </div>
                <div class="pull-right">
                    
                </div>
                {!! Form::model($role, ['method' => 'PATCH','route' => ['role.update', $role->id]]) !!}
                <a class="btn btn-danger" href="{{ route('role.index') }}"> Back</a>
                <button type="submit" class="btn btn-danger">Submit</button>
            </div>
        </div>
    </div>
@stop

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <div class="row p-3">
        <div class="col-12">
            <div class="form-group">
                <strong>Name:</strong>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                <strong>Permission:</strong>
                <br/>
                @foreach($permission as $value)
                    <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                    {{ $value->name }}</label>
                <br/>
                @endforeach
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('js')

@stop










