<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Reporte</title>
	<style>
		table {
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 100%;

		}

		th {
			border: 1px solid #dddddd;
			background-color: orange;
		}

		tr:nth-child(even) {
			background-color: #dddddd;
		}
	</style>
</head>

<body>
	<img src="\voxcrm\public\assets\logo.png" alt="" height="30" width="auto">
	<h2 class="font-weight-bold"> Reporte de evento: {{$event->name}}</h2>
	@php $mytime = Carbon\Carbon::now()@endphp
	<h5> Fecha: {{$mytime->toDateTimeString()}} </h5>
	<table>
		<tr>
			<th>Ubicacion</th>
			<th>Nombre pantalla</th>
			<th>Alto</th>
			<th>Ancho</th>
			<th>Computador</th>
			<th>Tipo</th>
			<th>Acceso</th>
			<th>Contenido actual</th>
		</tr>
		@php $screenNew=null @endphp
		@foreach ($screens2 as $screen)
		<tr>
			@if($screenNew!=$screen)
			<td>{{$screen->computer->location}}</td>
			<td>{{$screen->name}}</td>
			<td>{{$screen->height}}</td>
			<td>{{$screen->width}}</td>

			<td>{{$screen->computer->code}}</td>
			@if($screen->computer->type_id==1)
			<td>Teamviewer</td>
			@endif
			@if($screen->computer->type_id==2)
			<td>Aamyy</td>
			@endif
			@if($screen->computer->type_id==3)
			<td>IP</td>
			@endif
			@if($screen->computer->type_id==4)
			<td>UltraVNC</td>
			@endif
			@if($screen->computer->type_id==1)
			<td>{{$screen->computer->teamviewer_code}}</td>
			@endif
			@if($screen->computer->type_id==2)
			<td>{{$screen->computer->aamyy_code}}</td>
			@endif
			@if($screen->computer->type_id==3)
			<td>{{$screen->computer->ip}}</td>

			@endif
			<td>{{$screen->content}}</td>
	

			@else

			@endif




		</tr>
		@endforeach
	</table>
</body>

</html>
