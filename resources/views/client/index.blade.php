@extends('layouts.principalmenu')

@section('content')
@php
		$deviceActive = $devicesCount->where('state', 1)->count();
		$deviceInactive = $devicesCount->where('state', 0)->count();
		$mytime = Carbon\Carbon::now()
@endphp
<div class="clientcontainer2" id="clientcontainer2">
	<div class = row>
		<div class="lateralcontainer col-md-2" id="lateralcontainer">
			@include('client.menuLateral')
		</div> <!-- fin contenedor lateral  -->
		<div class= "col-md-10">
			<div class = "row" style="margin-top : 10px ">
				<div class= "col-sm-6 col-md-8" >
					<div id="titulo" style="margin-left : 0px "> </div>		
				</div>
				<div class= "col-sm-6 col-md-2" >					
					<a  style="text-decoration:none; display: none;" id="btnRefresh" type="button"  class="btnoutlineorange" href="#" onclick="actualizarSreenShots(751)">refrescar</a>
				</div>
				<div class= "col-sm-6 col-md-2" >					
					<a  style="text-decoration:none; display: none;" id="btnback" type="button"  class="btnoutlineorange" href="/clients ">Atras</a>
				</div>

				
					
			</div>
			
			<div class="row2" id="devices">	
			
			
				{{-- <div class="col-sm-12 col-md-6">
					<h4>Estado Reproductores</h4>
					<div id="chart_div" style="width:650; height:500"></div>
				</div> --}}

				<div class="col-sm-12 col-md-6">
					<h4 class=font-weight-bold> Bienvenido {{ Auth::user()->name }} </h4>			
				</div>
				<div class="col-sm-12 col-md-6">
					<h4 class=font-weight-bold> Fecha: {{$mytime->toDateTimeString()}} </h4>
				</div>
				 <div class="col-md-12">
				<!--	<h4 > Estado reproductores </h4>  -->
					@include('flash::message')
				</div>
				<div class="col-sm-12 col-md-12">
					<hr>
					<div class="row">
						<div class="col-md-9">
							<h4>Eventos actuales &#x1F4C6;</h4>
						</div>
						<div class="col-sm-3">
							<button type="button" id="btndark" class="btn  w-100" onclick="location.href='/clients'">Limpiar Busquedas</button>
						</div>
					</div>
					<br>
					{!! Form::open(['route' =>['clients.filter_active'], 'method' => 'GET']) !!}
						<div class="row">
							<div class="col-md-3">
								{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'Nombre evento']) !!}
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
							<div class="col-md-3">
								{!! Form::submit('Buscar',['id' => 'btnorange', 'class'=>'btn  w-100']) !!}
							</div>
						</div>
						{!! Form::close() !!}
						<hr>
					@include('client.tableActivo')
					<hr>
					<h4>Eventos proximos &#x1F4C6;</h4>
					{!! Form::open(['route' =>['clients.filter_inactive'], 'method' => 'GET']) !!}
						<div class="row">
							<div class="col-md-3">
								{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'Nombre evento']) !!}
							</div>
							<div class="col-md-3">
								<div class="input-group date" id="initdate2" data-target-input="nearest">
									{!! Form::text('initdate',null, ['class'=> 'form-control datetimepicker-input', 'placeholder' => 'Fecha Inicio']) !!}
									<div class="input-group-append" data-target="#initdate2" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fa fa-calendar"></i></div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group date" id="enddate2" data-target-input="nearest">
									{!! Form::text('enddate',null, ['class'=> 'form-control datetimepicker-input', 'placeholder' => 'Fecha Termino']) !!}
									<div class="input-group-append" data-target="#enddate2" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fa fa-calendar"></i></div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								{!! Form::submit('Buscar',['id' => 'btnorange','class'=>'btn  w-100']) !!}
							</div>
						</div>
						{!! Form::close() !!}
					<hr>
					@include('client.tableInactivo')
					<hr>
				</div>
				<!--
				<div class="col-md-12">
					<br>
						{!! Form::open(['route' =>['clients.filter_by_name'], 'method' => 'GET']) !!}
						<div class="row">
							<div class="col-md-3">
							{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'Nombre Pantalla']) !!}
							</div>
							<div class="col-md-3">
								<select name="state" id="state" class="form-control">
									<option null selected disabled>Estado</option>
									<option value="0">Inactivo</option>
									<option value="1">Activo</option>
								</select>
							</div>
							<div class="col-md-3">
								<select name="store" id="store" class="form-control">
									<option null selected disabled>Tienda/Sucursal</option>
									@foreach ($stores as $store)
									<option value="{{$store->id}}">{{$store->name}}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-3">
								<button type="submit" class="btn btn-primary w-100">Buscar</button>
							</div>
						</div>
						{!! Form::close() !!}
					<hr>
				</div> 
				<div class="col-sm-12">
						@include('client.tableDevice')
				</div>
				-->
			</div><!-- fin row 2 -->
		</div>
	</div>
</div>


<!-- modal agregar contenido-->
	@include('client.modalAgregarContenido')
<!-- fin modal agegar contenido-->

<!-- modal ScreenShot-->
@include('client.modalScreenShot')
<!-- fin modal ScreenShot-->


<!-- modal gif -->
	@include('client.modalGifCargando')
<!-- fin modal gif -->



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
<script>
	$(function () {
			$('#initdate2').datetimepicker({
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
			$('#enddate2').datetimepicker({
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

		$("#initdate2").on("change.datetimepicker", function (e) {
				$('#enddate2').datetimepicker('minDate', e.date);
		});
		$("#enddate2").on("change.datetimepicker", function (e) {
				$('#initdate2').datetimepicker('maxDate', e.date);
		});
	});
</script>
<script src="{{ asset('js/clientStore.js') }}"></script> 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	// Load the Visualization API and the piechart package.
	google.charts.load('current', {'packages':['corechart']});

	// Set a callback to run when the Google Visualization API is loaded.
	google.charts.setOnLoadCallback(drawChart);

	// Callback that creates and populates a data table,
	// instantiates the pie chart, passes in the data and
	// draws it.
	function drawChart() {

		// Create the data table.
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Topping');
		data.addColumn('number', 'Slices');
		data.addRows([
			['activa(s)', {{ $deviceActive }}],
			['inactiva(s)', {{ $deviceInactive }}],
		]);

		// Set chart options
		var options = {
									 'width':650,
									 'height':500};

		// Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.PieChart(document.getElementById('chart_div'));

		function selectHandler() {
			var selectedItem = chart.getSelection()[0];
			if (selectedItem) {
				var topping = data.getValue(selectedItem.row, 1);
				var topping2 = data.getValue(selectedItem.row, 0);
				alert('hay ' + topping +' pantalla(s) '+ topping2 );
				document.location.href="{!!  route('companies.computers.index',Auth::user()->company_id); !!}";
			}
		}

		google.visualization.events.addListener(chart, 'select', selectHandler);
		chart.draw(data, options);
	}

</script>
@endsection

