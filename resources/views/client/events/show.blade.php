@extends('layouts.principal')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h2 class=font-weight-bold>{{$event->name}} &#x1F4C6;</h2>
		</div>
		<div class="col-md-2">
			<a  type="button" class="btn btn-secondary w-100" href="{!! route('clients.events.show', [ $event->id]) !!}">Limpiar</a>
		</div>
		<div class="col-md-2">
			<a  type="button" class="btn btn-warning w-100" href="{!! route('clients.events.edit', [ $event->id]) !!}">Editar</a>
		</div>
		<div class="col-md-2">
			<a  type="button" class="btn btn-outline-primary w-100" href="{{ URL::previous() }}">Atras</a>
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
<div class="row">
		<div class="col-md-12">
			@include('flash::message')
		</div>
	</div>
<hr>
{{ Form::open(['route' =>['clients.filter_device'], 'method' => 'GET']) }}
<div class="row">
	<div class="col-md-3">
		{!! Form::hidden('event_id',$event->id)!!}
		{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'Nombre']) !!}
	</div>
	<div class="col-md-3">
		<select name="store_id" id="store_id" class="form-control">
			<option null selected disabled>Tienda/Sucursal</option>
			@foreach ($stores as $store)
				<option value="{{$store->id}}">{{$store->name}}</option>
			@endforeach
		</select>
	</div>
	<div class="col-md-2">
		<select name="type_id" id="type_id" class="form-control">
			<option null selected disabled>Tipo</option>
			@foreach ($types as $type)
				<option value="{{$type->id}}">{{$type->name}}</option>
			@endforeach
		</select>
	</div>
	<div class="col-md-2">
		<select name="state" id="state" class="form-control">
			<option null selected disabled>Estado</option>
			<option value="0">Inactivo</option>
			<option value="1">Activo</option>
		</select>
	</div>
	<div class="col-md-2">
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
