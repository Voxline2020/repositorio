<div class="table-responsive">
	<table class="table table-hover" id="computers-table">
		<thead class="thead-light">
			<tr>
				<th>Codigo</th>
				<th>Ubicacion</th>
				<th>Tipo de acceso</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($computers as $computer)
			<tr>
				<td>{!! $computer->code !!}</td>
				<td>{!! $computer->location !!}</td>
			<td>{{ $computer->type}}</td>
				<td>
					{!! Form::open(['route' => ['computers.destroy', $computer->id], 'method' => 'delete']) !!}
					<div class='btn-group'>
							
							<a href="{{ route('computers.editTwoParam',[$computer->id, $computer->store_id]) }}" class='btn btn-warning btn-xs'><i
								class="fas fa-edit"></i></a>

							<a href="{{route('screens.show',[$computer->id]) }}" class='btn btn-primary btn-xs'><i
								class="fas fa-eye"></i></a>
								{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn
						btn-danger', 'onclick' => "return confirm('desea eliminar?')"]) !!}
						<a>
					</div>
					{!! Form::close() !!}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
