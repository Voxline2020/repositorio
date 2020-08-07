<div class="table-responsive" style="height:400px;overflow-y:scroll">
	{{-- <div class="table-responsive"> --}}
	<table class="table table-hover">
		<thead class="table-dark">
			<tr>
				<th>Nombre</th>
				<th>Fecha Inicio</th>
				<th>Fecha Termino</th>
				<th colspan="3">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@if($eventsActive->count()!=0)
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
			@else
			<td>No hay eventos actuales.</td>
			<td></td>
			<td></td>
			<td></td>
			@endif
		</tbody>
	</table>
</div>
