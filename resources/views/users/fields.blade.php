<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control','required']) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', 'Contraseña:') !!}
    {!! Form::password('password', ['class' => 'form-control','required']) !!}
</div>

<!-- Rut Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rut', 'RUT:') !!}
    {!! Form::text('rut', null, ['class' => 'form-control','required']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-3">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','required']) !!}
</div>
<!-- Lastname Field -->
<div class="form-group col-sm-3">
	{!! Form::label('lastname', 'Apellido:') !!}
	{!! Form::text('lastname', null, ['class' => 'form-control','required']) !!}
</div>
<!-- Company Field -->
<div class="form-group col-sm-3">
	{!! Form::label('company_id', 'Empresa:') !!}
	<select name="company_id" id="company_id" class="form-control" >
			<option value="" selected disabled>seleccione</option>
			@foreach ($companies as $company)
					<option value="{{$company->id}}">{{$company->name}}</option>
			@endforeach
	</select>
</div>
<!-- rol Field -->
<div class="form-group col-sm-3">
	{!! Form::label('role_id', 'Rol:') !!}
	<select name="role_id" id="role_id" class="form-control" >
			<option value="" selected disabled>seleccione</option>
			@foreach ($roles as $role)
					<option value="{{$role->id}}">{{$role->name}}</option>
			@endforeach
	</select>
</div>
<!-- Middlename Field -->
<div class="form-group col-sm-3">
		{!! Form::label('middlename', '2º Nombre:') !!}
		{!! Form::text('middlename', null, ['class' => 'form-control']) !!}
</div>

<!-- Surname Field -->
<div class="form-group col-sm-3">
    {!! Form::label('surname', '2º Apellido:') !!}
    {!! Form::text('surname', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
		{!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
		<a href="{!! route('users.index') !!}" class="btn btn-secondary">Cancelar</a>
</div>
