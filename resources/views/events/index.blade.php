@extends('layouts.principal')

@section('content')

	<div class="row">
		<div class="col-md-9">
			<h2 class=font-weight-bold>Eventos &#x1F4C6;</h2>
		</div>
		
		<div class="col-md-3">
			<a class="btn btn-success w-100" href="{!! route('companies.events.create',$company->id) !!}">Nuevo Evento</a>
		</div>
	</div>
	<br>
	{{ Form::open(['route' =>['events.filter_by_name'], 'method' => 'GET']) }}
	<div class="row">

		<div class="col-md-1">
			Fecha Inicio:
		</div>
		<div class="col-md-3">
			{!! Form::input('date', 'initdate', null,['class' => 'form-control','placeholder' => 'Fecha inicio']) !!}
		</div>
		<div class="col-md-1">
			Fecha Termino:
		</div>
		<div class="col-md-4">
			{!! Form::input('date', 'enddate', null,['class' => 'form-control','placeholder' => 'Fecha termino']) !!}
		</div>
		<div class="col-md-3">
			<select name="state" id="state" class="form-control">
				<option null selected disabled>Estado</option>
				<option value="0">Inactivo</option>
				<option value="1">Activo</option>
			</select>
		</div>
	</div>
<div class="row">
	<br>
	<div class="col-md-12">
		{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'buscar evento']) !!}
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-3">
		<button type="submit" class="btn btn-info w-100">Filtrar</button>
	</div>
</div>
<br>
<div class="row">
@include('flash::message')
	<div class="col-md-12">
		@include('companies.events.table')
	</div>
</div>
{!! Form::close() !!}
@endsection
