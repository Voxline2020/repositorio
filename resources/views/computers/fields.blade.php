
<div class="form-group col-sm-6">
    {!! Form::label('code', 'codigo') !!}
    {!! Form::text('code', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('location', 'ubicacion:') !!}
    {!! Form::text('location', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('teamviewer_code', 'Codigo teamviewer') !!}
    {!! Form::text('teamviewer_code', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('teamviewer_pass', 'Contraseña teamviewer :') !!}
    {!! Form::input('password','teamviewer_pass', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('ip', 'Ip:') !!}
    {!! Form::text('ip', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('aamyy_code', 'codigo aamyy:') !!}
    {!! Form::text('aamyy_code', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('aamyy_pass', 'aamyy pass:') !!}
    {!! Form::input('password','aamyy_pass', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
		{!! Form::label('company', 'Ingrese sucursal:') !!}
    <select name="store_id" id="store_id" class="browser-default custom-select">
				<option null selected disabled>seleccione</option>
				@foreach ($stores as $store)
						<option value="{{$store->id}}">{{$store->name}}</option>
				@endforeach
		</select>
</div>
<div class="form-group col-sm-6">
		{!! Form::label('tipo', 'Ingrese tipo:') !!}
    <select name="type_id" id="type_id" class="browser-default custom-select">
				<option null selected disabled>seleccione</option>
				@foreach ($types as $type)
						<option value="{{$type->id}}">{{$type->name}}</option>
				@endforeach
		</select>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('computers.index') !!}" class="btn btn-secondary">Cancelar</a>
</div>
