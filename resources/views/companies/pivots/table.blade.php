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
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@if($pivots->count()!=0)
			@foreach($pivots as $pivot)
			<tr>
				<td>{!! $pivot->name !!}</td>
				<td>{!! $pivot->code !!}</td>
				<td>{!! $pivot->ip !!}</td>
				<td>{!! $pivot->location !!}</td>
				<td>{!! $pivot->teamviewer_code !!}</td>
				<td>{!! $pivot->teamviewer_pass !!}</td>
				<td>

					{!! Form::open(['route' => ['companies.pivots.destroy',$pivot->id,$pivot->company_id], 'method' => 'delete']) !!}
					<div class='btn-group'>
						<a href="{{route('companies.pivots.show',['pivot' =>$pivot->id, 'company' =>$pivot->company_id]) }}" class='btn btn-primary btn-xs'><i class="fas fa-eye"></i></a>
						<a href="{{route('companies.pivots.edit',['pivot' =>$pivot->id, 'company' =>$pivot->company_id]) }}" class='btn btn-warning btn-xs'><i class="fas fa-edit"></i></a>
						{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('¿seguro que desea eliminar este elemento?')"]) !!}
					</div>
					{!! Form::close() !!}

				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td>No hay ningun Pivote agregado.</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			@endif
		</tbody>
	</table>
	{{ $pivots->links()}}
</div>
