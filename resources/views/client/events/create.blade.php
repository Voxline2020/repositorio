@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h2>
			Nuevo evento
		</h2>
	</div>
</div>
@include('flash::message')
{!! Form::open(['route' => ['clients.events.store']]) !!}
<div class="row">
	@include('client.events._fields')
</div>
{!! Form::close() !!}
@endsection
