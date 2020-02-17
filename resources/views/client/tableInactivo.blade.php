{{-- <div class="table-responsive" style="height:150px;overflow-y:scroll"> --}}
	<div class="table-responsive">
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
			@php
					$dateNow = \Carbon\Carbon::now()->format('Y-m-d\TH:i:s');
					$eventsInactive = $events->where('state',0)->where('initdate','>',$dateNow)->paginate();
			@endphp
			@if($eventsInactive->count()!=0)
			@foreach($eventsInactive as $event)
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
			<td>No hay eventos proximos.</td>
			<td></td>
			<td></td>
			<td></td>
			@endif
		</tbody>
	</table>
	{{$eventsInactive->links()}}
</div>
