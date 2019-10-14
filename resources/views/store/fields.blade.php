<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name', 'Nombre de la sucursal:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('address', 'Direccion:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-12">
    {!! Form::label('lat', 'Ingrese latitud:') !!}
    {!! Form::text('lat', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-12">
    {!! Form::label('lng', 'Ingrese longitud:') !!}
    {!! Form::text('lng', null, ['class' => 'form-control']) !!}
</div>
				@foreach ($companies as $company)
						<input type="hidden" id="company_id" name="company_id" value="{{$company->id}}">
				@endforeach

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ url()->previous() }}" class="btn btn-info">Cancelar</a>
</div>
