@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="pl-3 pt-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="pull-left">
                    <h2>Create New Role</h2>
                </div>
                <div class="pull-right">
                
                </div>
                {!! Form::open(array('route' => 'role.store','method'=>'POST')) !!}
                <a class="btn btn-primary" href="{{ route('role.index') }}"> Back</a>
                <button type="submit" class="btn btn-primary">Submit</button>
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
                    <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
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



