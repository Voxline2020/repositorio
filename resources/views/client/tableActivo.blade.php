<div class="table-responsive" style="height:150px;overflow-y:scroll">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Fecha Inicio</th>
				<th>Fecha Termino</th>
				<th colspan="3">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@php
					$eventsActive = $events->where('state',1)->take(5);
			@endphp
			@foreach($eventsActive as $event)
			<tr>
				<td>{!! $event->name !!}</td>
				<td>{!! \Carbon\Carbon::parse($event->initdate)->format('d-m-Y H:i')!!}</td>
				<td>{!! \Carbon\Carbon::parse($event->enddate)->format('d-m-Y H:i')!!}</td>
				<td>
				<a href="{{route('clients.events.show',[$event->id]) }}" class='btn btn-info btn-xs'><i class="fas fa-eye"></i></a>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
