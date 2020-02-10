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

{!! Form::open(['route' => ['users.companies.assign',$user],'method'=>'PUT'] ) !!}

<div class="row">
	<!-- Roles Field -->
	<div class="form-group col-sm-6">
		{!! Form::label('company_id', 'Compañias:') !!}
		<select class="js-select2" style="width: 100%" name="company_id" id="company_id">
			@foreach ($companies as $key => $company)
			<option value="{{ $company->id }}" >
				{{ $company->name }}
			</option>
			@endforeach
		</select>
	</div>
	<div class="form-group col-sm-12 text-right">
			{!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
			<a href="{!! route('users.index') !!}" class="btn btn-secondary">Cancelar</a>
	</div>
</div>
{!! Form::close() !!}



@endsection
