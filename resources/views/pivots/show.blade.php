@extends('layouts.principal')

@section('content')
<div class="container">
	@include('flash::message')
	<div class="row">
		<div class="col-md-8">
			<h2 class=font-weight-bold>{{$pivot->name}} <i class="fas fa-server"></i></h2>
		</div>
		<div class="col-md-2">
			<a  type="button" class="btn btn-secondary w-100" href="{!! route('clients.events.show', [ $pivot->id]) !!}">Limpiar</a>
		</div>
		<div class="col-md-2">
			<a  type="button" class="btn btn-primary w-100" href="{{ URL::previous() }}">Volver</a>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-4">
			<h4><span class="badge badge-light">Codigo: {!! $pivot->code !!} </span></h4>
		</div>
		<div class="col-md-4">
			<h4><span class="badge badge-light">IP: {!! $pivot->ip !!}</span></h4>
		</div>
		<div class="col-md-4">
			<h4><span class="badge badge-light">Ubicacion: {!! $pivot->location !!}</span></h4>
		</div>
		<div class="col-md-4">
			<h4><span class="badge badge-light">Codigo TeamViewer: {!! $pivot->teamviewer_code !!} </span></h4>
		</div>
		<div class="col-md-4">
			<h4><span class="badge badge-light">Pass TeamViewer: {!! $pivot->teamviewer_pass !!}</span></h4>
		</div>
		<div class="col-md-4">
			<h4><span class="badge badge-light">Empresa: {!! $pivot->company->name !!}</span></h4>
		</div>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-md-12">
		<h2>Computadores</h2>
	</div>
	<div class="col-md-12">
		@include('pivots.showfields')
	</div>
</div>
@endsection
