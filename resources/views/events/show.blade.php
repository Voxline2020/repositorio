@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-9">
		<h2> Evento {{ $event->name }} </h2>
	</div>
	<div class="col-md-3">
		<a class="btn btn-primary w-100"  href="{!!  url()->previous() !!}">Atras</a>
	</div>

</div>

<div class="row my-lg-4 my-md-4 my-sm-1">
	@include('companies.events._show_fields')
</div>
@endsection
