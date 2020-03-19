@extends('layouts.principal')

@section('content')
    <section class="content-header">
        <h1>
            Dispositivo: {!! $device->name !!}
        </h1>

    </section>
    <div class="row my-lg-4 my-md-4 my-sm-1">
        <div class="col-md-3">
            <p><b>Tipo:</b> {!! $device->type->name !!}</p>
        </div>
        <div class="col-md-3">
            <p><b>Resolución: </b> {!! $device->width !!}x{!! $device->height !!}</p>
        </div>
        <div class="col-md-3">
            <p><b>Version: </b>@if($device->version==null) N/A @else{!! $device->version !!}@endif</p>
		</div>
        <div class="col-md-3">
        {!! Form::model($device, ['route' => ['companies.computers.changeStatusDevice', 'company'=>$company,'computer'=>$computer,'device'=>$device], 'method' => 'put']) !!}
            @if($device->state==0)
                {!! Form::hidden('state', 1) !!}
                <b>Estado: </b>{!! Form::submit('Inactivo', ['class' => 'btn btn-danger']) !!}
            @endif
            @if($device->state==1)
                {!! Form::hidden('state', 0) !!}
                <b>Estado: </b>{!! Form::submit('Activo', ['class' => 'btn btn-success']) !!}
            @endif
        {!! Form::close() !!}
		</div>
	</div>
		<div class="row">
			<div class="col-md-12">
				@include('flash::message')
			</div>
		</div>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
									<div class="col-md-12">
										@include('companies.computers.showDevicefields')
									</div>
                </div>
            </div>
        </div>
    </div>
@endsection

