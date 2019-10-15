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
			{!! Form::open(['route' => 'eventsOld.store']) !!}
			<div class="row">
				@include('eventsOld.fields')
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
@endsection
