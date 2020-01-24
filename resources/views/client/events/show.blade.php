@extends('layouts.principal')

@section('content')
<div class="container">
	@include('flash::message')
	<div class="row">
		<div class="col-md-6">
			<h2 class=font-weight-bold>{{$event->name}} &#x1F4C6;</h2>
		</div>
		<div class="col-md-3">
			<a  type="button" class="btn btn-warning w-100" href="{!! route('events.show', [ $event->id]) !!}">Editar</a>
		</div>
		<div class="col-md-3">

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
<br>
{{ Form::open(['route' =>['clients.events.show', $event], 'method' => 'GET']) }}

<div class="row">
	<div class="col-md-3">
		<select name="sector" id="sector" class="form-control">
			<option null selected disabled>Sector</option>
			<option value="ropa hombre">Ropa hombre</option>
			<option value="ropa mujer">Ropa mujer</option>
			<option value="joyeria">joyeria</option>
		</select>
	</div>

	<div class="col-md-3">
		<select name="floor" id="floor" class="form-control">
			<option null selected disabled>Piso</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
		</select>
	</div>

	<div class="col-md-3">
		<select name="type" id="type" class="form-control">
			<option null selected disabled>Tipo</option>
			<option value="colgante">Colgante</option>
			<option value="espejo">Espejo</option>
			<option value="madera">Madera</option>
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
	<div class="col-md-12">
		{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'buscar pantalla']) !!}
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-3">
		<button type="submit" class="btn btn-info w-100">Buscar </button>
	</div>
</div>
<br>
{!! Form::close() !!}
<div class="row">
	<div class="col-md-9">
		<h2>Pantallas</h2>
	</div>
	<div class="col-md-12">
		{{-- @include('client.events._screenFields') --}}
	</div>
</div>
@endsection
