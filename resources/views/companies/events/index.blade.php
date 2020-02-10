@extends('layouts.principal')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h2 class=font-weight-bold>Eventos &#x1F4C6;</h2>
		</div>
		<div class="col-md-2">
			<a class="btn btn-secondary w-100" href="{!! route('companies.events.index',$company) !!}">Limpiar</a>
		</div>
		<div class="col-md-2">
			<a class="btn btn-success w-100" href="{!! route('companies.events.create',$company) !!}">Nuevo Evento</a>
		</div>
		<div class="col-md-2">
			<a class="btn btn-outline-primary w-100" href="{!! route('companies.index') !!}">Atras</a>
		</div>
	</div>
	<hr>
	{{ Form::open(['route' =>['companies.events.filterEvent_by',$company], 'method' => 'GET']) }}
	<div class="row">

		<div class="col-md-1">Fecha Inicio:</div>
		<div class="col-md-3">
			{!! Form::input('date', 'initdate', null,['class' => 'form-control','placeholder' => 'Fecha inicio'])!!}
		</div>
		<div class="col-md-1">Fecha Termino:</div>
		<div class="col-md-3">
			{!! Form::input('date', 'enddate', 'Fecha',['class' => 'form-control','placeholder' => 'Fecha termino'])!!}
		</div>
		<div class="col-md-4">
			<select name="state" id="state" class="form-control">
				<option null selected disabled>Estado</option>
				<option value="0">Inactivo</option>
				<option value="1">Activo</option>
			</select>
		</div>
	</div>
	<div class="row">
	<div class="col-md-10">
		{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'Nombre']) !!}
	</div>
	<div class="col-md-2">
		<button type="submit" class="btn btn-primary w-100">Buscar</button>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-md-12">
		@include('flash::message')
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		@include('companies.events.table')
	</div>
</div>
{!! Form::close() !!}
</div>
@endsection
