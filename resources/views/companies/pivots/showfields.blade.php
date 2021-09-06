<div class="table-responsive">
	<table class="table table-hover" id="pivots-table">
		<thead class="thead-dark">
			<tr>
				<th>Codigo</th>
				<th>Ubicacion</th>
				<th>Tipo de acceso</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@if($onpivots->count()!=0)
			@foreach($onpivots AS $onpivot)
			<tr>
				<td>{!! $onpivot->computer->code !!}</td>
				<td>{!! $onpivot->computer->store->name !!}</td>
				<td>{!! $onpivot->computer->type->name !!}</td>
				<td>
					{!! Form::open(['route' => ['companies.destroyOnpivot',$onpivot->id,'company'=>$pivot->company_id], 'method' => 'delete']) !!}
					<div class='btn-group'>
						{{-- <a href="{{route('screens.show',[$onpivot->computer->id]) }}" class='btn btn-primary btn-xs'><i class="fas fa-eye"></i></a> --}}
						{{-- <a href="#" class='btn btn-danger btn-xs'><i class="fas fa-trash"></i></a> --}}
						{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('¿Seguro que desea eliminar?')"]) !!}
					</div>
					{!! Form::close() !!}
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td>No hay computadores asignados.</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			@endif
		</tbody>
	</table>
	{{ $onpivots->links() }}
</div>
