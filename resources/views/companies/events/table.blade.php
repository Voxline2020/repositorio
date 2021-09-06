<div class="table-responsive">
	<table class="table table-hover">
		<thead class="thead-dark">
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
			@if($events->count()!=0)
			@foreach($events as $event)
			<tr>
				<td>{!! $event->name !!}</td>
				<td class="{{ $event->StateString == "Activo" ? "green-text" : "red-text" }}">{{ $event->StateString }}</td>
				<td style="text-align: center" class="{!! $event->contents->count() == 0 ? 'red-text': '' !!}">
					{!! $event->contents->count() !!}
				</td>
				<td>{!! $event->InitDateF!!}</td>
				<td>{!! $event->EndDateF!!}</td>
				<td>
					<div class='btn-group'>
						{!! Form::open(['route' => ['companies.events.destroy',$company,$event], 'method' => 'delete']) !!}
						<div class='btn-group'>
							<a href="{{route('companies.events.show',['company'=>$company,'event'=>$event]) }}" class='btn btn-primary btn-xs'><i
							class="fas fa-eye"></i></a>
							<a href="{!! route('companies.events.edit',['company'=>$company,'event'=>$event]) !!}" class='btn btn-warning btn-xs'><i
									class="fas fa-edit"></i></a>
							{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs',
							'onclick' => "return confirm('Estas seguro?')"]) !!}
						</div>
						{!! Form::close() !!}
					</div>
				</td>
			</tr>
			@endforeach
			@else
				<tr>
					<td>No hay ningun evento agregado.</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			@endif
		</tbody>
	</table>
	{{ $events->links() }}
</div>
