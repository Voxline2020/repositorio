<!-- Name Field -->
<!-- Languaje -->
<div class="form-group col-sm-6">
	{!! Form::label('name', 'Nombre del evento:') !!}
	{!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Initdate Field -->
<!-- Initdate Field -->
<div class="form-group col-sm-6">
	{!! Form::label('initdate', 'Fecha de inicio:') !!}
	<div class="input-group date" id="initdate" data-target-input="nearest">
		<input type="text" name="initdate" class="form-control datetimepicker-input" data-target="#initdate" required/>
		<div class="input-group-append" data-target="#initdate" data-toggle="datetimepicker">
			<div class="input-group-text"><i class="fa fa-calendar"></i></div>
		</div>
	</div>
</div>

<!-- Enddate Field -->
<div class="form-group col-sm-6">
	{!! Form::label('enddate', 'Fecha de termino:') !!}
	<div class="input-group date" id="enddate" data-target-input="nearest">
		<input type="text" name="enddate" class="form-control datetimepicker-input" data-target="#enddate" required/>
		<div class="input-group-append" data-target="#enddate" data-toggle="datetimepicker">
			<div class="input-group-text"><i class="fa fa-calendar"></i></div>
		</div>
	</div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
	{!! Form::hidden('company_id', $company->id) !!}
	{!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
	<a href="{!! route('events.index') !!}" class="btn btn-info">Cancelar</a>
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
		});
		$("#enddate").on("change.datetimepicker", function (e) {
				$('#initdate').datetimepicker('maxDate', e.date);
		});
	});
</script>
@endsection
