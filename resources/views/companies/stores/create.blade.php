@extends('layouts.principal')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<h2>
				Nueva Sucursal para {{ $company->name }}
			</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			@include('flash::message')
		</div>
	</div>
	{!! Form::open(['route' => ['companies.stores.store', $company]]) !!}
	<div class="row">
		@include('companies.stores.fields')
	</div>
	{!! Form::close() !!}
@endsection
