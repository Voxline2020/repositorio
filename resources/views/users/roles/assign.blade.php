@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-9">
		<h2 class=font-weight-bold> {{ $user->fullName }} </h2>
		<h3>{{ $user->NamesOfRoles}}</h3>
		<h4 class="{{ $user->state == 0 ? "red-text" : "green-text" }}">
			({{ $user->stateName }})
		</h4>
	</div>
</div>

{!! Form::open(['route' => ['users.roles.assign',$user->id],'method'=>'PUT'] ) !!}

<div class="row">
	<!-- Roles Field -->
	<div class="form-group col-sm-6">
		{!! Form::label('role_id', 'Rol:') !!}
		<select class="js-select2" style="width: 100%" name="role_id" id="role_id">
			@foreach ($roles as $key => $role)
			<option value="{{ $role->id }}" >
				{{ $role->name }}
			</option>
			@endforeach
		</select>
	</div>
	<div class="form-group col-sm-12 text-right">
			{!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
			<a href="{!! route('users.index') !!}" class="btn btn-warning">Cancelar</a>
	</div>
</div>
{!! Form::close() !!}



@endsection
