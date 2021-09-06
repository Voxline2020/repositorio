@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-9">
		<h2 class=font-weight-bold> {{ $user->fullName }}  </h2>
		<h3>{{ $user->NamesOfRoles}}</h3>
		<h4 class="{{ $user->state == 0 ? "red-text" : "green-text" }}">
			({{ $user->stateName }})
		</h4>
	</div>
	<div class="col-md-3">
		<a class="btn btn-outline-primary w-100"  href="{!! route('users.index') !!}">Atras</a>
	</div>
</div>

<div class="row">
	@include('users.show_fields')
</div>


@endsection

