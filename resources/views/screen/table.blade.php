



<div class="row">
				<label>	</label>


	</div>
<div class="table-responsive">
	<table class="table table-hover" id="screens-table">
		<thead class="thead-light">
			<tr>
				<th>Pantallas</th>
				<th>Ancho</th>
				<th>Alto</th>

				<th> Acciones </th>

			</tr>
		</thead>
		<tbody>
			@foreach($screens as $screen)
			<tr>
				<td>{!! $screen->name !!}</td>
				<td>{!! $screen->height !!}</td>
				<td>{!! $screen->width !!}</td>
				<td>
					{!! Form::open(['route' => ['screens.destroy', $screen->id], 'method' => 'delete']) !!}
					<div class='btn-group'>
						<a>
							<a href="{{ route('screens.editTwoParam',[$screen->id, $screen->computer_id]) }}"
								class="btn btn-warning"><i class="fas fa-edit"></i></a>
							{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger
							btn-xs',
							'onclick' => "return confirm('Desea eliminar?')"]) !!}
					</div>

					{!! Form::close() !!}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
