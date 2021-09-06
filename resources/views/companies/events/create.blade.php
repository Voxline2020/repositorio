@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-12">
		<h2>
			Nuevo evento para {{ $company->name }}
		</h2>
	</div>
</div>
@include('flash::message')
{!! Form::open(['route' => ['companies.events.store', $company]]) !!}
<div class="row">
	@include('companies.events._fields')
</div>
{!! Form::close() !!}
@endsection
