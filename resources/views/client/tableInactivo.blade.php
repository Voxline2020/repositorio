 <div class="table-responsive" style="height:300px;overflow-y:scroll">
	{{-- <div class="table-responsive"> --}}
	<table class="table table-hover">
		<thead id="tableorange">
			<tr>

				<td>Nombre</td>
				<td>Fecha Inicio</td>
				<td>Fecha Termino</td>
				<td colspan="3">Acciones</td>
			</tr>
		</thead>
		<tbody>
			@if($eventsInactive->count()!=0)
			@foreach($eventsInactive as $event)
			<tr>
				<td>{!! $event->name !!}</td>
				<td>{!! \Carbon\Carbon::parse($event->initdate)->format('d-m-Y H:i')!!}</td>
				<td>{!! \Carbon\Carbon::parse($event->enddate)->format('d-m-Y H:i')!!}</td>
				<td>
					<a  href="{{route('clients.events.show',[$event->id]) }}" id="btndark" class='btn btn-info btn-xs'><i class="fas fa-eye"></i></a>
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
</div>
