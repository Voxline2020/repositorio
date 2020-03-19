<div class="form-group col-sm-4">
	{!! Form::hidden('id',$device->id) !!}
	{!! Form::label('name', 'Nombre:') !!}
	{!! Form::input('text','name', $device->name, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4">
	{!! Form::label('width', 'Ancho:') !!}
	{!! Form::input('number','width', $device->width, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4">
	{!! Form::label('height', 'Alto:') !!}
	{!! Form::input('number','height', $device->height, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-4">
	{!! Form::label('type_id', 'Tipo:') !!}
	<select name="type_id" id="type_id" class="form-control">
		@foreach ($types as $type)
		@if($type->id==$device->type->id)
			<option value="{{$type->id}}" selected>{{$type->name}}</option>
		@else
			<option value="{{$type->id}}">{{$type->name}}</option>
		@endif
		@endforeach
	</select>
</div>
<div class="form-group col-sm-4">
	{!! Form::label('imei', 'Imei:') !!}
	{!! Form::input('text','imei', $device->imei, ['class' => 'form-control']) !!}
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('companies.computers.show',['company' => $company,'computer'=>$computer]) !!}" class="btn btn-secondary">Cancelar</a>
</div>
