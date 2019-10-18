<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Estado</th>
				<th>Cant. Contenidos</th>
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
				<td class="{!! $event->contents->count() == 0 ? 'red-text': '' !!}">
					{!! $event->contents->count() !!}
				</td>

				<td>{!! $event->initdate!!}</td>
				<td>{!! $event->enddate!!}</td>
				<td>
					<div class='btn-group'>
						{!! Form::open(['route' => ['events.destroy',  $event], 'method' => 'delete']) !!}
						<div class='btn-group'>
							<a href="{!! route('events.show', [ $event]) !!}" class='btn btn-primary btn-xs'><i
							class="fas fa-eye"></i></a>
							<a href="{!! route('events.edit', [ $event]) !!}" class='btn btn-warning btn-xs'><i
									class="fas fa-edit"></i></a>
							{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs',
							'onclick' => "return confirm('Estas seguro?')"]) !!}
						</div>
						{!! Form::close() !!}
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
