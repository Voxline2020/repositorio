<div class="form-group col-sm-4">
	{!! Form::label('name', 'Nombre de la pantalla:') !!}
	{!! Form::hidden('id',$screen->id) !!}
	{!! Form::text('name', $screen->name, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4">
	{!! Form::label('width', 'Ingrese ancho:') !!}
	{!! Form::input('number','width', $screen->width, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4">
	{!! Form::label('height', 'Ingrese alto:') !!}
	{!! Form::input('number','height', $screen->height, ['class' => 'form-control']) !!}
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('companies.computers.show',['company' => $company,'computer'=>$computer]) !!}" class="btn btn-secondary">Cancelar</a>
</div>
