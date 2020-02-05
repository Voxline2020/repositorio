<div class="table-responsive">
    <table class="table table-hover" id="companies-table">
					<thead class="thead-dark">
            <tr>
								<th>Nombre</th>
								<th>Direccion</th>
								<th>Link mapa</th>
								<th>Acciones</th>

            </tr>
					</thead>
        <tbody>
        @foreach($stores as $store)
            <tr>
								<td>{!! $store->name !!}</td>
								<td>{!! $store->address !!}</td>
								<td>{!! $store->link_map !!}</td>
                <td>
                    {!! Form::open(['route' => ['stores.destroy', $store->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
												{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('desea eliminar?')"]) !!}
												<a>
												<a href="{{route('stores.editTwoParam',[$store->id, $store->company_id])}}" class='btn btn-warning btn-xs'><i
													class="fas fa-edit"></i></a>
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
