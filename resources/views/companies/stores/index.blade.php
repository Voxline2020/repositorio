@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-6">
		<h1 class="font-weight-bold"> Sucursales {{ $company->name }} </h1>
	</div>
	<div class="col-md-2">
		<a class="btn btn-secondary w-100" href="{!! route('companies.stores.index',$company->id) !!}">Limpiar</a>
	</div>
	<div class="col-md-2">
		<a class="btn btn-success w-100" href="{!! route('companies.stores.create',$company->id) !!}">Nueva sucursal</a>
	</div>
	<div class="col-md-2">
		<a class="btn btn-outline-primary w-100" href="{!! route('companies.index') !!}">Atras</a>
	</div>
</div>
{{ Form::open(['route' =>['companies.filterStore',$company], 'method' => 'GET']) }}
<div class="row">
	<div class="col-md-5">
		{!! Form::text('nameFilter',null, ['class'=> 'form-control', 'placeholder' => 'Buscar por nombre']) !!}
	</div>
	<div class="col-md-5">
		{!! Form::text('addressFilter',null, ['class'=> 'form-control', 'placeholder' => 'Buscar por direccion']) !!}
	</div>
	<div class="col-md-2">
		<button type="submit" class="btn btn-primary w-100">Buscar </button>
	</div>
</div>
{!! Form::close() !!}
<hr>
<div class="row">
	<div class="col-md-12">
		@include('flash::message')
	</div>
</div>
<div class="content">
	<div class="clearfix"></div>
	<div class="box box-primary">
		<div class="box-body">
			@include('companies.stores.table')
		</div>
	</div>
</div>
@endsection
