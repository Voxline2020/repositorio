<div class="table-responsive">
	<table class="table table-hover" id="computers-table">
		<thead class="thead-dark">
			<tr>
				<th>Codigo</th>
				<th>Ubicacion</th>
				<th>Tipo de acceso</th>
				<th>Tienda/Sucursal</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@if($computers->count()!=0)
			@foreach($computers as $computer)
			<tr>
				<td>{!! $computer->code !!}</td>
				<td>{!! $computer->location !!}</td>
				<td>{{ $computer->type->name}}</td>
				<td>{{ $computer->store->name}}</td>
				<td>
					{!! Form::open(['route' => ['companies.computers.destroy', $company,$computer], 'method' => 'delete']) !!}
					<div class='btn-group'>
						<a href="{{route('companies.computers.show',['company'=>$company,'computer'=>$computer]) }}" class='btn btn-primary btn-xs'><i class="fas fa-eye"></i></a>
						<a href="{{route('companies.computers.edit',['company'=>$company,'computer'=>$computer,'store'=>$computer->store]) }}" class='btn btn-warning btn-xs'><i class="fas fa-edit"></i></a>
						{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('desea eliminar?')"]) !!}
					</div>
					{!! Form::close() !!}
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td>No hay ningun computador agregado.</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			@endif
		</tbody>
	</table>
	{{ $computers->links()}}
</div>
