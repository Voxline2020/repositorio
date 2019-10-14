<!-- Name Field -->
<div class="form-group col-sm-12 col-md-6">
    {!! Form::label('name', 'Nombre de la sucursal:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12 col-md-6">
    {!! Form::label('address', 'Direccion:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>
{{-- <div class="form-group col-sm-12 col-md-6">
    {!! Form::label('lat', 'Ingrese latitud:') !!}
    {!! Form::text('lat', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-12 col-md-6">
    {!! Form::label('lng', 'Ingrese longitud:') !!}
    {!! Form::text('lng', null, ['class' => 'form-control']) !!}
</div> --}}

<!-- Submit Field -->
<div class="form-group col-sm-12 col-md-6">
		{!! Form::hidden('company_id', $company->id)!!}
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('companies.stores.index', $company) }}" class="btn btn-danger">Cancelar</a>
</div>
