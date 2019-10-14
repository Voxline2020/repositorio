<div class="table-responsive">
    <table class="table table-hover" id="companies-table">
					<thead class="thead-dark">
            <tr>
								<th>Nombre</th>
								<th>Direccion</th>
								{{-- <th>Link mapa</th> --}}
								<th>Acciones</th>

            </tr>
					</thead>
        <tbody>
        @foreach($stores as $store)
            <tr>
								<td>{!! $store->name !!}</td>
								<td>{!! $store->address !!}</td>
								{{-- <td>{!! $store->link_map !!}</td> --}}
                <td>
                    {!! Form::open(['route' => ['companies.stores.destroy',$company,$store], 'method' => 'delete']) !!}
                    <div class='btn-group'>
												<a href="{{route('companies.stores.show',[$company,$store])}}" class='btn btn-primary btn-xs'>
													<i class="fas fa-eye"></i>
												</a>
												<a href="{{route('companies.stores.edit',[$company,$store])}}" class='btn btn-warning btn-xs'>
													<i class="fas fa-edit"></i>
												</a>
												{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('desea eliminar?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
