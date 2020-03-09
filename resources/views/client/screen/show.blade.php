@extends('layouts.principal')

@section('content')
    <section class="content-header">
        <h1>
            Pantalla: {!! $screen->name !!}
        </h1>

		</section>
		<hr>
    <div class="row">
        <div class="col-md-4">
            <p><b>Sucursal:</b> {!! $screen->computer->store->name !!}</p>
				</div>
				<div class="col-md-4">
					<p><b>Resolución: </b> {!! $screen->width !!}x{!! $screen->height !!}</p>
				</div>
				<div class="col-md-4">
					<p><b>Version: </b>@if($screen->version==null) N/A @else{!! $screen->version !!}@endif</p>
				</div>
				<div class="col-md-4">
					<p><b>Cant. de eventos: </b>{{ $eventAssigns->count() }}</p>
				</div>
				<div class="col-md-4">
					<p><b>Duración total: </b>{{$totalduration}}</p>
				</div>
        <div class="col-md-4">
            {!! Form::model($screen, ['route' => ['screens.changeStatus', $screen->id], 'method' => 'put']) !!}
                @if($screen->state==0)
                    {!! Form::hidden('state', 1) !!}
                    <b>Estado: </b>{!! Form::submit('Inactivo', ['class' => 'btn btn-danger']) !!}
                @endif
                @if($screen->state==1)
                    {!! Form::hidden('state', 0) !!}
                    <b>Estado: </b>{!! Form::submit('Activo', ['class' => 'btn btn-success']) !!}
                @endif
            {!! Form::close() !!}
				</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12">
				@include('flash::message')
			</div>
		</div>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('client.screen.show_fields')
                </div>
            </div>
        </div>
    </div>
@endsection

