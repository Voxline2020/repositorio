@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h2> Nuevo Computador</h2>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-12">
		@include('flash::message')
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
			{{ Form::open(['route' =>['companies.storeComputer',$company]]) }}
			<div class="row">
					@include('companies.computers.fields')
			</div>
		{!! Form::close() !!}
	</div>
</div>
@endsection
