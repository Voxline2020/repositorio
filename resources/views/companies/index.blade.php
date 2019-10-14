@extends('layouts.principal')


@section('content')

<div class="row">
	<div class="col-md-9">
		<h2 class=font-weight-bold> Compañias &#127970; </h2>
	</div>
	<div class="col-md-12">
		@include('flash::message')
	</div>
</div>

<div class="row">
	@include('companies.table')
</div>
@endsection

