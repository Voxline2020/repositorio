
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
    {!! Form::number('code', null, ['class' => 'form-control','required']) !!}
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
	{!! Form::hidden('company_id',$id, ['class' => 'form-control','required']) !!}
	{!! Form::label('location', 'Tienda/Sucursal:') !!}
	<select name="location" id="location" class="browser-default custom-select" required>
			<option null selected disabled>Seleccione</option>
			@foreach ($stores as $store)
						<option value="{{$store->name}}">{{$store->name}}</option>
				@endforeach
	</select>
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{route('companies.pivots.index',[$company]) }}" class="btn btn-secondary">Cancelar</a>
</div>

