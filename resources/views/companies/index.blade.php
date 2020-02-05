@extends('layouts.principal')


@section('content')

<div class="row">
	<div class="col-md-12">
		@include('flash::message')
	</div>
	<div class="col-md-10">
		<h2 class=font-weight-bold> Compañias &#127970; </h2>
	</div>
	<div class="col-md-2">
		<a class="btn btn-success w-100" href="{!! route('companies.create') !!}">Nueva Empresa</a>
	</div>
</div>
<hr>
<div class="row">
	@include('companies.table')
</div>
@endsection

