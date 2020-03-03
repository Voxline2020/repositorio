@extends('layouts.principal')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<h2 class=font-weight-bold>Eventos &#x1F4C6;</h2>
		</div>
		@if($old_check==0)
			<div class="col-md-2">
				<a class="btn btn-outline-secondary w-100" href="{!! route('companies.events.view_old',$company) !!}">Ver Antiguos</a>
			</div>
		@else
			<div class="col-md-2">
				<a class="btn btn-outline-secondary w-100" href="{!! route('companies.events.index',$company) !!}">Ocultar Antiguos</a>
			</div>
		@endif
		<div class="col-md-2">
			<a class="btn btn-secondary w-100" href="{!! route('companies.events.index',$company) !!}">Limpiar</a>
		</div>
		<div class="col-md-2">
			<a class="btn btn-success w-100" href="{!! route('companies.events.create',$company) !!}">Nuevo Evento</a>
		</div>
		<div class="col-md-2">
			<a class="btn btn-outline-primary w-100" href="{!! route('companies.index') !!}">Atras</a>
		</div>
	</div>
	<hr>
	{{ Form::open(['route' =>['companies.events.filterEvent_by',$company], 'method' => 'GET']) }}
	<div class="row">
		<div class="col-md-2">
			{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'Nombre']) !!}
		</div>
		<div class="col-md-2">
			<select name="state" id="state" class="form-control">
				<option null selected disabled>Estado</option>
				<option value="0" style="color: red;">Inactivo</option>
				<option value="1" style="color: green;">Activo</option>
			</select>
		</div>
		<div class="col-md-3">
			<div class="input-group date" id="initdate" data-target-input="nearest">
				{!! Form::text('initdate',null, ['class'=> 'form-control datetimepicker-input', 'placeholder' => 'Fecha Inicio']) !!}
				<div class="input-group-append" data-target="#initdate" data-toggle="datetimepicker">
					<div class="input-group-text"><i class="fa fa-calendar"></i></div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="input-group date" id="enddate" data-target-input="nearest">
				{!! Form::text('enddate',null, ['class'=> 'form-control datetimepicker-input', 'placeholder' => 'Fecha Termino']) !!}
				<div class="input-group-append" data-target="#enddate" data-toggle="datetimepicker">
					<div class="input-group-text"><i class="fa fa-calendar"></i></div>
				</div>
			</div>
		</div>
		<div class="col-md-2">
			<button type="submit" class="btn btn-primary w-100">Buscar</button>
		</div>
	</div>
	{!! Form::hidden('old_check',$old_check)	!!}
	{!! Form::close() !!}
	<hr>
	<div class="row">
		<div class="col-md-12">
			@include('flash::message')
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			@include('companies.events.table')
		</div>
	</div>

</div>
@endsection
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
