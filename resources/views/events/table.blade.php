<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Estado</th>
				<th>Fecha de Inicio</th>
				<th>Fecha de Termino</th>

				<th colspan="1">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($events as $event)
			<tr>
				<td>{!! $event->name !!}</td>
				@if($event->state==0)
				<td style="color:#FF0000;">Inactivo</td>
				@endif
				@if($event->state==1)
				<td style="color:#01DF01;">Activo</td>
				@endif
				<td>{!! $event->initdate!!}</td>
				<td>{!! $event->enddate!!}</td>
				<td>
					<div class='btn-group'>
						{{-- <a href="{!! route('events.edit', [$event->id]) !!}" class='btn btn-info btn-xs'><i
								class="fas fa-info-circle"></i></a> --}}
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
