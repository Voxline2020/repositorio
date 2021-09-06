
<div class="form-group col-sm-6">
    {!! Form::label('code', 'codigo') !!}
    {!! Form::text('code', $computer->code, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('location', 'ubicacion:') !!}
    {!! Form::text('location', $computer->location, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('teamviewer_code', 'Codigo teamviewer') !!}
    {!! Form::text('teamviewer_code', $computer->teamviewer_code, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('teamviewer_pass', 'Contraseña teamviewer :') !!}
    {!! Form::input('password','teamviewer_pass', $computer->teamviewer_pass, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('ip', 'Ip:') !!}
    {!! Form::text('ip', $computer->ip, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('aamyy_code', 'codigo aamyy:') !!}
    {!! Form::text('aamyy_code', $computer->aamyy_code, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('aamyy_pass', 'aamyy pass:') !!}
    {!! Form::input('password','aamyy_pass', $computer->aamyy_pass, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
		{!! Form::label('company', 'Ingrese sucursal:') !!}
    <select name="store_id" id="store_id" class="browser-default custom-select">
				@foreach ($stores as $store)
					@if($store->id==$computer->store_id)
						<option selected value="{{$store->id}}">{{$store->name}}</option>
					@else
						<option value="{{$store->id}}">{{$store->name}}</option>
					@endif
				@endforeach
		</select>
</div>
<div class="form-group col-sm-6">
		{!! Form::label('tipo', 'Ingrese tipo:') !!}
    <select name="type_id" id="type_id" class="browser-default custom-select">
				@foreach ($types as $type)
					@if($type->id==$computer->type_id)
						<option selected value="{{$type->id}}">{{$type->name}}</option>
					@else
							<option value="{{$type->id}}">{{$type->name}}</option>
					@endif
				@endforeach
		</select>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('companies.computers.index',['company' => $company]) !!}" class="btn btn-secondary">Cancelar</a>
</div>
