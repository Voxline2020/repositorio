<div class="table-responsive">
	<table class="table table-hover" id="computers-table">
		<thead class="thead-dark">
			<tr>
				<th>Nombre</th>
				<th>Codigo</th>
				<th>IP</th>
				<th>Ubicacion</th>
				<th>TeamViewer Code</th>
				<th>TeamViewer Pass</th>
				<th>Empresa</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($pivots as $pivot)
			<tr>
				<td>{!! $pivot->name !!}</td>
				<td>{!! $pivot->code !!}</td>
				<td>{!! $pivot->ip !!}</td>
				<td>{!! $pivot->location !!}</td>
				<td>{!! $pivot->teamviewer_code !!}</td>
				<td>{!! $pivot->teamviewer_pass !!}</td>
				<td>{!! $pivot->company->name !!}</td>
				<td>

					{!! Form::open(['route' => ['pivots.destroy', $pivot], 'method' => 'delete']) !!}
					<div class='btn-group'>
						<a href="{{route('pivots.show',[$pivot->id]) }}" class='btn btn-primary btn-xs'><i class="fas fa-eye"></i></a>
						<a href="{{route('pivots.edit',[$pivot->id]) }}" class='btn btn-warning btn-xs'><i class="fas fa-edit"></i></a>
						{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('desea eliminar?')"]) !!}
					</div>
					{!! Form::close() !!}

				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	{{ $pivots->links()}}
</div>
