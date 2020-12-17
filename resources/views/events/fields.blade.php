<!-- Name Field -->
<!-- Languaje -->
<div class="form-group col-sm-6">
	{!! Form::label('name', 'Nombre del evento:') !!}
	{!! Form::text('name', $event->name, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Initdate Field -->
<!-- Initdate Field -->
<div class="form-group col-sm-6">
	{!! Form::label('initdate', 'Fecha de inicio:') !!}
	<div class="input-group date" id="initdate" data-target-input="nearest">
		<input type="text" name="initdate" class="form-control datetimepicker-input" data-target="#initdate" value="{!! \Carbon\Carbon::parse($event->initdate)->format('d-m-Y H:i') !!}" required/>
		<div class="input-group-append" data-target="#initdate" data-toggle="datetimepicker">
			<div class="input-group-text"><i class="fa fa-calendar"></i></div>
		</div>
	</div>
</div>

<!-- Enddate Field -->
<div class="form-group col-sm-6">
	{!! Form::label('enddate', 'Fecha de termino:') !!}
	<div class="input-group date" id="enddate" data-target-input="nearest">
		<input type="text" name="enddate" class="form-control datetimepicker-input" data-target="#enddate" value="{!! \Carbon\Carbon::parse($event->enddate)->format('d-m-Y H:i') !!}" required/>
		<div class="input-group-append" data-target="#enddate" data-toggle="datetimepicker">
			<div class="input-group-text"><i class="fa fa-calendar"></i></div>
		</div>
	</div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
	{!! Form::hidden('company_id', $event->company_id) !!}
	{!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
	<a href="{!! route('companies.events.index',$event->company_id) !!}" class="btn btn-secondary">Cancelar</a>
</div>

@section('script')
<script>
	$(function () {
			$('#initdate').datetimepicker({
				icons: {
						time: 'fas fa-clock',
						date: 'fas fa-calendar',
						up: 'fas fa-arrow-up',
						down: 'fas fa-arrow-down',
						previous: 'fas fa-chevron-left',
						next: 'fas fa-chevron-right',
						today: 'fas fa-calendar-check-o',
						clear: 'fas fa-trash',
						close: 'fas fa-times'
				},
				focusOnShow: true,
				allowInputToggle: true,
				locale: "es"

			});
			$('#enddate').datetimepicker({
				icons: {
						time: 'fas fa-clock',
						date: 'fas fa-calendar',
						up: 'fas fa-arrow-up',
						down: 'fas fa-arrow-down',
						previous: 'fas fa-chevron-left',
						next: 'fas fa-chevron-right',
						today: 'fas fa-calendar-check-o',
						clear: 'fas fa-trash',
						close: 'fas fa-times'
				},
				focusOnShow: true,
				allowInputToggle: true,
				locale: "es",
				useCurrent: false,
			});

		$("#initdate").on("change.datetimepicker", function (e) {
				$('#enddate').datetimepicker('minDate', e.date);
				console.log("change");
		});
		$("#enddate").on("change.datetimepicker", function (e) {
				//$('#initdate').datetimepicker('maxDate', e.date);
				console.log("change");
		});
	});
</script>
@endsection
