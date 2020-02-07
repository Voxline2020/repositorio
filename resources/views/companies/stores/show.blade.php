@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-9">
		<h2 class=font-weight-bold> Sucursal {{ $store->name }}  </h2>
	</div>
	<div class="col-md-3">
		<a class="btn btn-outline-primary w-100"  href="{!! route('companies.stores.index', $company) !!}">Atras</a>
	</div>
</div>

<div class="row">
	@include('companies.stores._show_fields')
</div>

@endsection
