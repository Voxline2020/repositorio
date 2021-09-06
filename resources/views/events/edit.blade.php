@extends('layouts.principal')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			@include('adminlte-templates::common.errors')
		</div>
		<div class="col-sm-12">
			<h1>
				Evento
			</h1>
		</div>
		<div class="col-sm-12">
			{!! Form::model($event, ['route' => ['events.update', $event->id], 'method' => 'patch']) !!}
			<div class="row">
				@include('events.fields')
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
