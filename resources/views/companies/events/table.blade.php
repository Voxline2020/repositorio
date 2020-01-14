<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Estado</th>
				<th>Cant. Contenidos</th>
				<th>Contenido sin asignar</th>
				<th>Fecha de Inicio</th>
				<th>Fecha de Termino</th>

				<th colspan="1">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($events as $event)
			<tr>
				<td>{!! $event->name !!}</td>
				<td class="{{ $event->StateString == "Activo" ? "green-text" : "red-text" }}">{{ $event->StateString }}</td>
				<td style="text-align: center" class="{!! $event->contents->count() == 0 ? 'red-text': '' !!}">
					{!! $event->contents->count() !!}
				</td>
					@php
					$count = 0;
					@endphp
					@foreach($event->contents AS $content)
						@if($content->versionPlaylistDetails->count() == 0)
							@php
							$count = $count+1;
							@endphp
						@endif
					@endforeach
					<td style="text-align: center" class="{!! $count == "0" ? "green-text" : "red-text" !!}">
					{!! $count !!}
				</td>
				<td>{!! $event->InitDateF!!}</td>
				<td>{!! $event->EndDateF!!}</td>
				<td>
					<div class='btn-group'>
						{!! Form::open(['route' => ['events.destroy',  $event], 'method' => 'delete']) !!}
						<div class='btn-group'>
							<a href="{{route('clients.events.show',[$event->id]) }}" class='btn btn-primary btn-xs'><i
							class="fas fa-eye"></i></a>
							<a href="{!! route('events.show', [ $event->id]) !!}" class='btn btn-warning btn-xs'><i
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
