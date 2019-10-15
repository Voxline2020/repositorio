<!-- Name Field -->
<!-- Languaje -->
<script src="{{asset('datePicker/locales/bootstrap-datepicker.es.min.js')}}"></script>
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre del evento:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Initdate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('initdate', 'Fecha de inicio:') !!}
		{!! Form::input('dateTime-local', 'initdate', null, ['min'=>Carbon\Carbon::today()->format('Y-m-d\TH:i:s'),'class' => 'form-control']) !!}

</div>

<!-- Enddate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('enddate', 'Fecha de termino:') !!}
    {!! Form::input('dateTime-local','enddate', null, [ 'min'=>Carbon\Carbon::today()->format('Y-m-d\TH:i:s'),'class' => 'form-control']) !!}
</div>

<!-- State Field -->
<div class="form-group col-sm-6">
		<br>
    <label class="checkbox-inline">
				{!! Form::hidden('state', false) !!}
        {!! Form::hidden('state', '1', null) !!}
		</label>

</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('eventsOld.index') !!}" class="btn btn-info">Cancelar</a>
</div>

