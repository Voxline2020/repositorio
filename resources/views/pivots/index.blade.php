@extends('layouts.principal')
@section('content')
<div class="row">
	<div class="col-md-7 text-left">
		<h2 class="font-weight-bold">Computadores Pivote  <i class="fas fa-server"></i></h2>
	</div>
	<div class="col-md-2">
		<button type="button" class="btn btn-secondary w-100" onclick="location.href='/computers'">Limpiar </button>
	</div>
	<div class="col-md-3 text-right">
		<a class="btn btn-success w-100" href="{!! route('pivots.create') !!}">Nuevo Pivote</a>
	</div>
</div>
<hr>
{{ Form::open(['route' =>'computers.filter_computers', 'method' => 'GET']) }}
<div class="row">
	<div class="col-md-4">
		<select name="company" id="company" class="form-control">
			<option null selected disabled>Empresa</option>
		</select>
	</div>
	<div class="col-md-4">
		{!! Form::select('store',[''=>'Tienda/Sucursal'],null,['id'=>'store', 'class'=>'form-control'])!!}
	</div>
	<div class="col-md-4">
		<select name="type" id="type" class="form-control">
			<option null selected disabled>Tipo de acceso</option>
		</select>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-3">
		{!! Form::text('codeFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'Codigo']) !!}
	</div>
	<div class="col-md-3">
		{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'Nombre']) !!}
	</div>
	<div class="col-md-3">
		{!! Form::text('passFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'Password']) !!}
	</div>
	<div class="col-md-3">
		<button type="submit" class="btn btn-primary w-100">Buscar </button>
	</div>
</div>
{!! Form::close() !!}
</td>
</section>
<hr>
<div class="content">
	<div class="clearfix"></div>

	@include('flash::message')

	<div class="clearfix"></div>
	<div class="box box-primary">
		<div class="box-body">
			<div class="col-sm-12">
				@include('pivots.table')
			</div>
		</div>

	</div>
</div>
@endsection

