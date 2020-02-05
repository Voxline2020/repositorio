
<div class="form-group col-sm-6">
	{!! Form::label('name', 'Nombre') !!}
	{!! Form::text('name', null, ['class' => 'form-control','required']) !!}
</div>
<div class="form-group col-sm-6">
	{!! Form::label('ip', 'Ip:') !!}
	{!! Form::text('ip', null, ['class' => 'form-control','required']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('code', 'Codigo') !!}
    {!! Form::text('code', null, ['class' => 'form-control','required']) !!}
</div>
<div class="form-group col-sm-6">
	{!! Form::label('pass', 'Contraseña') !!}
	{!! Form::text('pass', null, ['class' => 'form-control','required']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('teamviewer_code', 'Codigo teamviewer') !!}
    {!! Form::text('teamviewer_code', null, ['class' => 'form-control','required']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('teamviewer_pass', 'Contraseña teamviewer :') !!}
    {!! Form::input('password','teamviewer_pass', null, ['class' => 'form-control','required']) !!}
</div>
<div class="form-group col-sm-6">
		{!! Form::label('company_id', 'Empresa:') !!}
    <select name="company_id" id="company_id" class="browser-default custom-select" required>
				<option null selected disabled>Seleccione</option>
				@foreach ($companies as $company)
						<option value="{{$company->id}}">{{$company->name}}</option>
				@endforeach
		</select>
</div>
<div class="form-group col-sm-6">
	{!! Form::label('location', 'Tienda/Sucursal:') !!}
	<select name="location" id="location" class="browser-default custom-select" required>
			<option null selected disabled>Seleccione</option>
	</select>
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('pivots.index') !!}" class="btn btn-info">Cancelar</a>
</div>

