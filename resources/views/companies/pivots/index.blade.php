@extends('layouts.principal')
@php
$company = App\Models\Company::find($id);
@endphp
@section('content')
<div class="row">
	<div class="col-md-6 text-left">
		<h2 class="font-weight-bold">Computadores Pivote <i class="fas fa-server"></i></h2>
	</div>
	<div class="col-md-2">
		<a class="btn btn-secondary w-100" href="{{route('companies.pivots.index',[$company]) }}">Limpiar</a>
	</div>
	<div class="col-md-2 text-right">
	<a class="btn btn-success w-100" href="{{route('companies.pivots.create',[$company]) }}">Nuevo Pivote</a>
	</div>
	<div class="col-md-2 text-right">
		<a class="btn btn-primary w-100" href="{{route('companies.index') }}">Atras</a>
		</div>
</div>
<hr>
{{ Form::open(['route' =>['companies.pivots.filter_by',$company], 'method' => 'GET']) }}
<div class="row">
	<div class="col-md-3">
		<select name="store" id="store" class="form-control">
		<option null selected disabled>Ubicación</option>
			@foreach($stores AS $store)
			<option value="{{$store->name}}">{{$store->name}}</option>
			@endforeach
		</select>
	</div>
	<div class="col-md-3">
		{!! Form::text('codeFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'Codigo']) !!}
	</div>
	<div class="col-md-3">
		{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'Nombre']) !!}
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
				@include('companies.pivots.table')
			</div>
		</div>

	</div>
</div>
@endsection




