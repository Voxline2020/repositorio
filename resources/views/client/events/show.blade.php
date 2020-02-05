@extends('layouts.principal')

@section('content')
<div class="container">
	@include('flash::message')
	<div class="row">
		<div class="col-md-6">
			<h2 class=font-weight-bold>{{$event->name}} &#x1F4C6;</h2>
		</div>
		<div class="col-md-2">
			<a  type="button" class="btn btn-secondary w-100" href="{!! route('clients.events.show', [ $event->id]) !!}">Limpiar</a>
		</div>
		<div class="col-md-2">
			<a  type="button" class="btn btn-warning w-100" href="{!! route('events.show', [ $event->id]) !!}">Editar</a>
		</div>
		<div class="col-md-2">
			<a  type="button" class="btn btn-primary w-100" href="{{ URL::previous() }}">Volver</a>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-4">
			<h4><span class="badge badge-light">Duracion:
					{!!number_format((int)(strtotime($event->enddate)-strtotime($event->initdate))/60/60/24)!!} dia(s)</span></h4>
		</div>
		<div class="col-md-4">
			<h4><span class="badge badge-light">Fecha inicio: {!! $event->initdate !!}</span></h4>
		</div>
		<div class="col-md-4">
			<h4><span class="badge badge-light">Fecha termino: {!! $event->enddate !!}</span></h4>
		</div>
	</div>
</div>
<hr>
{{ Form::open(['route' =>['clients.filter_screen'], 'method' => 'GET']) }}
@php
$totalscreen = App\Models\Screen::all();
$listsectors=[];
$listfloors=[];
$listtypes=[];
foreach($totalscreen AS $screen){
	array_push($listsectors,$screen->sector);
}
foreach($totalscreen AS $screen){
	array_push($listfloors,$screen->floor);
}
foreach($totalscreen AS $screen){
	array_push($listtypes,$screen->type);
}
$sectors=array_unique($listsectors);
$floors=array_unique($listfloors);
$types=array_unique($listtypes);
@endphp
<div class="row">
	<div class="col-md-3">
		<select name="sector" id="sector" class="form-control">
			<option null selected disabled>Sector</option>
			@foreach ($sectors as $sector)
				@if($sector!=null)
				<option value="{{$sector}}">{{$sector}}</option>
				@endif
			@endforeach
		</select>
	</div>
	<div class="col-md-3">
		<select name="floor" id="floor" class="form-control">
			<option null selected disabled>Piso</option>
			@foreach ($floors as $floor)
				@if($floor!=null)
				<option value="{{$floor}}">{{$floor}}</option>
				@endif
			@endforeach
		</select>
	</div>
	<div class="col-md-3">
		<select name="type" id="type" class="form-control">
			<option null selected disabled>Tipo</option>
			@foreach ($types as $type)
				@if($type!=null)
				<option value="{{$type}}">{{$type}}</option>
				@endif
			@endforeach
		</select>
	</div>
	<div class="col-md-3">
		<select name="state" id="state" class="form-control">
			<option null selected disabled>Estado</option>
			<option value="0">Inactivo</option>
			<option value="1">Activo</option>
		</select>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-9">
		{!! Form::hidden('event_id',$event->id)!!}
		{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'buscar pantalla']) !!}
	</div>
	<div class="col-md-3">
		<button type="submit" class="btn btn-primary w-100">Buscar </button>
	</div>
</div>
{!! Form::close() !!}
<hr>
<div class="row">
	<div class="col-md-12">
		<h2>Pantallas</h2>
	</div>
	<div class="col-md-12">
		@include('client.events._screenFields')
	</div>
</div>
@endsection
