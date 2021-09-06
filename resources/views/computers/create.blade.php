@extends('layouts.principal')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			@include('adminlte-templates::common.errors')
		</div>
		<div class="col-sm-12">
			<h2>
				Nuevo Computador
			</h2>
		</div>
		<div class="col-sm-12">
				{!! Form::open(['route' => 'computers.store']) !!}
				<div class="row">
						@include('computers.fields')
				</div>
			 {!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
