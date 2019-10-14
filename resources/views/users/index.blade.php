@extends('layouts.principal')

@section('content')

<div class="row">
	<div class="col-md-9">
		<h2 class=font-weight-bold> Usuarios 👨👩 </h2>
	</div>
	<div class="col-md-3">
		<a class="btn btn-success w-100"  href="{!! route('users.create') !!}">Nuevo Usuario</a>
	</div>
</div>

<div class="content">
	<div class="clearfix"></div>

	@include('flash::message')

	<div class="clearfix"></div>
	<div class="box box-primary">
		<div class="box-body">
			@include('users.table')
		</div>
	</div>

</div>

@endsection
