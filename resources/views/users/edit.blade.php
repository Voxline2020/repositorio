@extends('layouts.principal')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h1>
				Editar: {{ $user->fullName }}
			</h1>
		</div>
		<div class="col-sm-12">
				{!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'patch']) !!}
				<div class="row">
						@include('users.fields')
				</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
