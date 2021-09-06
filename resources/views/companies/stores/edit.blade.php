@extends('layouts.principal')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<h2>
				Editar sucursal {{ $store->name }}
			</h2>
		</div>
	</div>

	{!! Form::model($store, ['route' => ['companies.stores.update', $company,$store], 'method' => 'put']) !!}
	<div class="row">
		@include('companies.stores.fields')
	</div>
	{!! Form::close() !!}
@endsection
