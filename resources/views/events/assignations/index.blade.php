@extends('layouts.principal')

@section('content')

<div class="row">
	<div class="col-md-9">
		<h3 class="font-weight-bold">Asignacion de contenido a pantallas</h3>
	</div>
	<br>
</div>
<br>
<div class="row">
	<div class="col-md-3">
		<h4><span class="badge badge-light"> {!! $content->name !!}</span></h4>
	</div>
	<div class="col-md-3">
		<h4><span class="badge badge-light">Tamaño: {!! $content->SizeMB !!}</span></h4>
	</div>
	<div class="col-md-6">
		<h4><span class="badge badge-light">Medidas: {!! $content->Resolution !!}</span></h4>
	</div>
</div>
<br>
{{ Form::open(['route' =>['screens.filter_by_name', $content->id], 'method' => 'GET']) }}

<div class="row">
	<div class="col-md-9">
		{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'buscar pantalla']) !!}
	</div>
	<div class="col-md-3">
		<button type="submit" class="btn btn-primary w-100">Buscar </button>
	</div>
</div>
{!! Form::close() !!}

{{ Form::open(['route' =>['events.assignations.store',$event, $content], 'method' => 'post']) }}
<div class="row my-2">
		@include('events.assignations._table')
</div>
{!! Form::close() !!}

@endsection
