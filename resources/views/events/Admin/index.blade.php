@extends('layouts.principal')

@section('content')
<div class="container">
	@include('flash::message')
	<div class="row">
		<div class="col-md-9">
			<h2 class=font-weight-bold>Eventos &#x1F4C6;</h2>
		</div>
	</div>
	<br>
	{{ Form::open(['route' =>['events.filter_by_name'], 'method' => 'GET']) }}
	<div class="row">
		<div class="col-md-3">
			<select name="state" id="state" class="form-control">
				<option null selected disabled>Estado</option>
				<option value="0">Inactivo</option>
				<option value="1">Activo</option>
			</select>
		</div>
		<div class="col-md-3">
			{!! Form::input('dateTime-local', 'initdate', null,['class' => 'form-control', 'placeholder' => 'Fecha inicio']) !!}
		</div>
	</div>
	<br>
	<div class="row">
		<br>
		<div class="col-md-9">
			{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'buscar evento']) !!}
		</div>
		<br>
		<div class="col-md-3">
			<button type="submit" class="btn btn-primary w-100">Buscar </button>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-12">
				@include('events.table')
		</div>
	</div>
	{!! Form::close() !!}
</div>
@endsection
