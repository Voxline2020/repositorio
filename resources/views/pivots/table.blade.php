<div class="table-responsive">
	<table class="table table-hover" id="computers-table">
		<thead class="thead-dark">
			<tr>
				<th>Codigo</th>
				<th>Password</th>
				<th>Nombre</th>
				<th>IP</th>
				<th>Ubicacion</th>
				<th>TeamViewer Code</th>
				<th>TeamViewer Pass</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($pivots as $pivot)
			<tr>
				<td>{!! $pivot->code !!}</td>
				<td>{!! $pivot->pass !!}</td>
				<td>{!! $pivot->name !!}</td>
				<td>{!! $pivot->ip !!}</td>
				<td>{!! $pivot->location !!}</td>
				<td>{!! $pivot->teamviewer_code !!}</td>
				<td>{!! $pivot->teamviewer_pass !!}</td>
				<td>
					<div class='btn-group'>
						<a href="#" class='btn btn-primary btn-xs'><i class="fas fa-eye"></i></a>
						<a href="#" class='btn btn-warning btn-xs'><i class="fas fa-edit"></i></a>
						<a href="#" class='btn btn-danger btn-xs'><i class="fas fa-trash"></i></a>
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	{{ $pivots->links()}}
</div>
