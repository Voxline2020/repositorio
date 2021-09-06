@extends('layouts.principal')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			@include('flash::message')
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h1>
				Nuevo Usuario
			</h1>
		</div>
		<div class="col-sm-12">
			<hr>
				{!! Form::open(['route' => 'users.store']) !!}
				<div class="row">
						@include('users.fields')
				</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
