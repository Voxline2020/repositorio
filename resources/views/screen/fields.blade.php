<!-- Name Field -->
<div class="form-group col-sm-12">
	{!! Form::label('name', 'Nombre de la pantalla:') !!}
	{!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-12">
	{!! Form::label('height', 'Ingrese alto:') !!}
	{!! Form::input('number','height', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-12">
	{!! Form::label('width', 'Ingrese ancho:') !!}
	{!! Form::input('number','width', null, ['class' => 'form-control']) !!}
</div>
@foreach ($computers as $computer)
<input type="hidden" id="computer_id" name="computer_id" value="{{$computer->id}}">
@endforeach
<!-- Submit Field -->
<div class="form-group col-sm-12">
	{!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
	<a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
</div>
