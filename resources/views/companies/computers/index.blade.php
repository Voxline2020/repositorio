@extends('layouts.principal')
@section('content')
<div class="row">
	<div class="col-md-6 text-left">
		<h2 class="font-weight-bold">Computadores &#128187;</h2>
	</div>
	<div class="col-md-2">
		<a class="btn btn-secondary w-100" href="{!! route('companies.computers.index',['company' => $company]) !!}">Limpiar </a>
	</div>
	<div class="col-md-2 text-right">
		<a class="btn btn-success w-100" href="{!! route('companies.computers.create',['company' => $company]) !!}">Crear Nuevo</a>
	</div>
	<div class="col-md-2">
		<a class="btn btn-outline-primary w-100" href="{!! route('companies.index') !!}">Atras </a>
	</div>
</div>
<hr>
{{ Form::open(['route' =>['companies.computers.filter_computers',$company], 'method' => 'GET']) }}
<div class="row">
	<div class="col-md-4">
		{!! Form::text('codeFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'Codigo']) !!}
	</div>
	<div class="col-md-3">
		<select name="type" id="type" class="form-control">
			<option null selected disabled>Tipo de acceso</option>
			@foreach ($types as $type)
			<option value="{{$type->id}}">{{$type->name}}</option>
			@endforeach
		</select>
	</div>
	<div class="col-md-3">
		<select name="store" id="store" class="form-control">
			<option null selected disabled>Tienda/Sucursal</option>
			@foreach ($stores as $store)
				@if($store->company_id==$company->id)
					<option value="{{$store->id}}">{{$store->name}}</option>
				@endif
			@endforeach
		</select>
	</div>
	<div class="col-md-2">
		<button type="submit" class="btn btn-primary w-100">Buscar </button>
	</div>
</div>
{!! Form::close() !!}
</td>
</section>
<hr>
<div class="row">
	<div class="col-md-12">
		@include('flash::message')
	</div>
</div>
<div class="content">
	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-sm-12">
					@include('companies.computers.table')
				</div>
		</div>
		</div>
	</div>
</div>
@endsection
