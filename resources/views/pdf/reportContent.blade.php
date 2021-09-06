<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Reporte contenido</title>
	<style>
		table {
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 100%;

		}
 th{
		border: 1px solid #dddddd;
		background-color: orange;
}
tr:nth-child(even){
	background-color: #dddddd;
}
		</style>
</head>

<body>
		<img src="\voxcrm\public\assets\logo.png" alt="" height="30" width="auto">
	@php $mytime = Carbon\Carbon::now()@endphp
		<h5>	Fecha: {{$mytime->toDateTimeString()}} </h5>
	<table>
		<tr>
			<th>Nombre</th>
			<th>Tamaño</th>
			<th>Alto</th>
			<th>Ancho</th>
		</tr>
		@foreach ($contents as $content)
		<tr>
		<td>{{$content->name}}</td>
			<td>{{$content->size}}</td>
			<td>{{$content->height}}</td>
			<td>{{$content->width}}</td>
		</tr>
		@endforeach
	</table>
</body>

</html>
