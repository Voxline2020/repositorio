<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name', 'Nombre de la  compáñia:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('companies.index') !!}" class="btn btn-secondary">Cancelar</a>
</div>
