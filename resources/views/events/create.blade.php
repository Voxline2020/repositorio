@extends('layouts.principal')

@section('content')
@section('script')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			@include('adminlte-templates::common.errors')
		</div>
		<div class="col-sm-12">
			<h1>
				Nuevo Evento
			</h1>
		</div>
		<div class="col-sm-12">
				@include('flash::message')
			{!! Form::open(['route' => 'events.store']) !!}
			<div class="row">
				@include('events.fields')
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
@endsection
