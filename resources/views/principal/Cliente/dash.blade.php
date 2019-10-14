@extends('layouts.principal')

@section('content')
<div class="container">
	@include('flash::message')
	<div class="row">
		<div class="col-sm-12 col-md-5">
			<div class="float-left" id="chart_div" style="width:400; height:300"></div>
		</div>

		<div class="col-sm-12 col-md-7">
			<h2 class=font-weight-bold>Eventos actuales &#x1F4C6;</h2>
			@include('events.table')
		</div>
		<div class="col-sm-12">
			@include('companies.table')
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
		data.addColumn('number', 'id');
		data.addRows([
			['activa(s)', 100000, 1],
			['desactivada(s)', 57654, 2],
		]);

		// Set chart options
		var options = {'title':'Pantallas',
									'width':550,
									'height':1000};

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
