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
			@foreach($events as $event)
			@if($event->state==1)
			<tr>
				<td>{!! $event->name !!}</td>
				<td>{!! $event->initdate!!}</td>
				<td>{!! $event->enddate!!}</td>
				<td>
					<a href="{{route('events.show',[$event->id]) }}" class='btn btn-info btn-xs'><i class="fas fa-eye"></i></a>
				</td>
			</tr>
			@endif
			@endforeach
		</tbody>
	</table>
</div>
