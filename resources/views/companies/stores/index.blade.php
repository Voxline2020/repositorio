@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-9">
		<h1 class="font-weight-bold"> Sucursales {{ $company->name }} </h1>
	</div>
	@if(Auth::user()->hasRole('Administrador'))
	<div class="col-md-3">
		<a class="btn btn-success w-100" href="{!! route('companies.stores.create',$company->id) !!}">Nueva sucursal</a>
	</div>
	@endif
</div>
{{ Form::open(['route' =>['companies.stores.index',$company], 'method' => 'GET']) }}
<div class="row">
	<div class="col-md-4">
		{!! Form::text('nameFilter',null, ['class'=> 'form-control', 'placeholder' => 'Buscar por nombre']) !!}
	</div>
	<div class="col-md-4">
		{!! Form::text('addressFilter',null, ['class'=> 'form-control', 'placeholder' => 'Buscar por direccion']) !!}
	</div>

	<div class="col-md-4">
		<button type="submit" class="btn btn-primary w-100">Buscar </button>
	</div>
</div>
{!! Form::close() !!}
&nbsp;<br>
<div class="content">
	<div class="clearfix"></div>

	@include('flash::message')

	<div class="clearfix"></div>
	<div class="box box-primary">
		<div class="box-body">
			@include('companies.stores.table')
		</div>
	</div>





</div>
@endsection
