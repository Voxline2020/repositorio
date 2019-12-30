<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Tamaño</th>
				<th>Ancho</th>
				<th>Alto</th>
				<th>Usuario</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($contents as $content)
			<tr>
				<td>{!! $content->name !!}</td>
				<td>{!! $content->size !!}</td>
				<td>{!! $content->width !!}</td>
				<td>{!! $content->height !!}</td>
				@if (isset($content->user_id) && !is_null($content->user_id))
				<td>{!! $content->user_id !!}</td>
				@endif
				<td>
					{{-- {!! Form::open(['route' => ['contents.destroy', $content->id], 'method' => 'delete']) !!}
					<div class='btn-group'>

						<a href="{!! route('contents.edit', [$content->id]) !!}" class='btn btn-warning btn-xs'><i
								class="fas fa-edit"></i></a>
						{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs',
						'onclick' => "return confirm('Estas seguro?')"]) !!}
					</div>
					{!! Form::close() !!} --}}
					<a href="{!! route('contents.ScreenView', [$content->id]) !!}" class='btn btn-primary btn-xs'><i
						class="fas fa-eye    "></i></a>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</div>
