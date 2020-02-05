@extends('layouts.principal')

@section('content')

<div class="row">
	<div class="col-md-8">
		<h2 class=font-weight-bold> Usuarios 👨👩 </h2>
	</div>
	<div class="col-md-2">
		<a class="btn btn-secondary w-100"  href="{!! route('users.index') !!}">Limpiar</a>
	</div>
	<div class="col-md-2">
		<a class="btn btn-success w-100"  href="{!! route('users.create') !!}">Nuevo Usuario</a>
	</div>
</div>
<hr>
{{ Form::open(['route' =>['users.filter_by'], 'method' => 'GET']) }}
<div class="row">
	<div class="col-md-5">
		{!! Form::text('nameFilter',null, ['class'=> 'form-control', 'placeholder' => 'Nombre']) !!}
	</div>
	<div class="col-md-5">
		{!! Form::text('emailFilter',null, ['class'=> 'form-control', 'placeholder' => 'Email']) !!}
	</div>
	<div class="col-md-2">
		<button type="submit" class="btn btn-primary w-100">Buscar </button>
	</div>
</div>
{!! Form::close() !!}
<hr>
<div class="row">
	<div class="col-md-12">
		@include('flash::message')
	</div>
</div>
<div class="content">
	<div class="clearfix"></div>
	<div class="box box-primary">
		<div class="box-body">
			@include('users.table')
		</div>
	</div>

</div>

@endsection
