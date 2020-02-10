
<div class="form-group col-sm-6">
	{!! Form::label('name', 'Nombre') !!}
	{!! Form::text('name', $pivot->name, ['class' => 'form-control','required']) !!}
</div>
<div class="form-group col-sm-6">
	{!! Form::label('ip', 'Ip:') !!}
	{!! Form::text('ip', $pivot->ip, ['class' => 'form-control','required']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('code', 'Codigo') !!}
    {!! Form::number('code', $pivot->code, ['class' => 'form-control','required']) !!}
</div>
<div class="form-group col-sm-6">
	{!! Form::label('pass', 'Contraseña') !!}
	{!! Form::input('password','pass', $pivot->pass, ['class' => 'form-control','required']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('teamviewer_code', 'Codigo teamviewer') !!}
    {!! Form::text('teamviewer_code', $pivot->teamviewer_code, ['class' => 'form-control','required']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('teamviewer_pass', 'Contraseña teamviewer :') !!}
    {!! Form::input('password','teamviewer_pass', $pivot->teamviewer_pass, ['class' => 'form-control','required']) !!}
</div>
{{-- <div class="form-group col-sm-6">
		{!! Form::label('company_id', 'Empresa:') !!}
    <select name="company_id" id="company_id" class="browser-default custom-select" required>
			@foreach ($companies as $company)
				@if($company->id==$pivot->company_id)
				<option selected value="{{$company->id}}">{{$company->name}}</option>
				@else
				<option value="{{$company->id}}">{{$company->name}}</option>
				@endif
			@endforeach
		</select>
</div> --}}
<div class="form-group col-sm-6">
	{!! Form::label('location', 'Tienda/Sucursal:') !!}
	{!! Form::hidden('company_id',$pivot->company_id) !!}
	<select name="location" id="location" class="browser-default custom-select" required>
		@foreach ($stores as $store)
			@if($store->company_id==$pivot->company_id)
				@if($store->name==$pivot->location)
				<option selected value="{{$store->name}}">{{$store->name}}</option>
				@else
				<option value="{{$store->name}}">{{$store->name}}</option>
				@endif
			@endif
		@endforeach
	</select>
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{{route('companies.pivots.index',[$pivot->company_id]) }}" class="btn btn-secondary">Cancelar</a>
</div>

