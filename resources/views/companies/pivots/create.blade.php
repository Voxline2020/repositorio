@extends('layouts.principal')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			@include('adminlte-templates::common.errors')
		</div>
		<div class="col-sm-12">
			<h2>
				Nuevo Pivote
			</h2>
		</div>
		<div class="col-md-12">
			@include('flash::message')
		</div>
		<div class="col-sm-12">
				{!! Form::open(['route' =>['companies.storePivot',$id]]) !!}
				<div class="row">
						@include('companies.pivots.fields')
				</div>
			 {!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
