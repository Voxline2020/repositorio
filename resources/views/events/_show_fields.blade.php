<div class="col-md-4">
	Duracion evento:
	{!!number_format((int)(strtotime($event->enddate)-strtotime($event->initdate))/60/60/24)!!} dia(s)
</div>
<div class="col-md-4">
	Fecha inicio: {!! $event->InitDateF!!}
</div>
<div class="col-md-4">
	Fecha Termino: {!! $event->EndDateF!!}
</div>
<div class="col-md-12 my-2">
	<div class="table table-responsive">
		<table class="table">
			<thead class="thead-dark">
				<tr>
					<th>Nombre del contenido</th>
					<th>Tamaño (mb)</th>
					<th>Resolucion</th>
					<th>Duración</th>
					<th colspan="1">Asignar Pantalla</th>
				</tr>
			</thead>

			<tbody>
				@if($event->contents->count() == 0)
				<tr>
					<td>Aun no hay contenido.</td>
				</tr>
				@endif
				@foreach($event->contents as $content)
				<tr>
					<td class="text-nowrap">{!! $content->name !!}</td>
					<td class="text-nowrap">{!! $content->SizeMB !!}</td>
					<td class="text-nowrap">{!! $content->Resolution !!}</td>
					<td class="text-nowrap">{!! $content->durationMod !!}</td>

					<td class="text-nowrap">
						{!! Form::open(['route' => ['contents.destroy', $content->id], 'method' => 'delete', 'id'=>'form']) !!}
						<div class='btn-group'>
							<a href="{{route('contents.download',$content) }}" class='btn btn-success btn-xs'><i
								class="fa fa-download"></i></a>
						{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs',
						'onclick' => "return confirm('¿Seguro quiere borrar este contenido?')"]) !!}
						{!! Form::close() !!}
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
