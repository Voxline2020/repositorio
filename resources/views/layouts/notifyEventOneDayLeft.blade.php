<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Notificación</title>
	<style>
	</style>
</head>
<body>
		<h4>Estimado {{$user->name}}</h4>
		<p>Buen dia, el sistema VxCMS le informa que los siguientes eventos estan proximos a deshabilitarse:</p>
		<div style="border:0;min-height: 0.01%;overflow-x: auto;margin-bottom: 0; text-align:center;display: block;width: 100%;overflow-x: auto;-webkit-overflow-scrolling: touch">
			<table cellspacing="1" cellpadding="4" border="1" style="background-color: #FBFBFB;width: 100%;margin-bottom: 1rem;color: #212529;">
				<thead style="color: #fff;background-color: #343a40;border-color: #343a40;">
					<tr>
						<th style="1px solid #343a40;">Nombre evento</th>
						<th>Estado</th>
						<th>Fecha de Termino</th>
						<th>Termina</th>
					</tr>
				</thead>
				<tbody>
					@foreach($events as $event)
					<tr>
						<td style="text-align:left;">{!! $event->name !!}</td>
						<td>{!! $event->StateString !!}</td>
						<td>{!! $event->enddatef !!}</td>
						<td>{{ \Carbon\Carbon::parse($event->enddate)->diffForHumans() }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
</body>
</html>
