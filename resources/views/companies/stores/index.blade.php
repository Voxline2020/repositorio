@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-9">
		<h1 class="font-weight-bold"> {{ $company->name }}
	</div>

</div>
<div class="row">
	<div class="col-md-9">
		<h3 class="font-weight-normal">Sucursales</h3>
	</div>
	<div class="col-md-3">
		<a class="btn btn-success w-100" href="{!! route('companies.stores.create',$company->id) !!}">Nueva sucursal</a>
	</div>
</div>
{{ Form::open(['route' =>['stores.filter_by_name',$company->id], 'method' => 'GET']) }}
<div class="row">
	<div class="col-md-9">
		{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'Buscar sucursales']) !!}
	</div>

	<div class="col-md-3">
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
