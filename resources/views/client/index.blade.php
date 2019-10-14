@extends('layouts.principal')

@section('content')
@php
		$screenActive = $screens->where('state', 1)->count();
		$screenInactive = $screens->where('state', 0)->count();
@endphp
<div class="container">
	@include('flash::message')
	<div class="row">
		<div class="col-sm-12 col-md-6">
			<h4>Estado Reproductores</h4>
			<div id="chart_div" style="width:650; height:500"></div>
		</div>

		<div class="col-sm-12 col-md-6">
			<h4 class=font-weight-bold> Bienvenido {{ Auth::user()->name }} </h4>
			@php $mytime = Carbon\Carbon::now()@endphp
			<h4 class=font-weight-bold> Fecha: {{$mytime->toDateTimeString()}} </h4>
			<br><br>
			<h4>Eventos actuales &#x1F4C6;</h4>
			@include('client.tableActivo')
			<br><br>
			<h4>Eventos proximos &#x1F4C6;</h4>
			@include('client.tableInactivo')
		</div>
		<div class="col-sm-12">
				<h4 > Estado reproductores </h4>
				@include('client.tableScreen')
		</div>
	</div>
</div>
@endsection
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
			['activa(s)', {{ $screenActive }}],
			['inactiva(s)', {{ $screenInactive }}],
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
				document.location.href="{!!  route('computers.index'); !!}";
			}
		}

		google.visualization.events.addListener(chart, 'select', selectHandler);
		chart.draw(data, options);
	}

</script>
