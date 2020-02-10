<div class="table-responsive">
    <table class="table table-hover" id="companies-table">
					<thead class="thead-dark">
            <tr>
								<th>Nombre</th>
								<th>Direccion</th>
								<th>Cant. Reprod.</th>
								{{-- <th>Link mapa</th> --}}
								<th>Acciones</th>

            </tr>
					</thead>
        <tbody>
				@if($stores->count()!=0)
        @foreach($stores as $store)
            <tr>
								<td>{!! $store->name !!}</td>
								<td>{!! $store->address !!}</td>

								@php
										$screensQty = 0;
										foreach ($store->computers as $computer) {
											$screensQty += $computer->screens->count();
										}
								@endphp
								<td>{!! $screensQty !!}</td>
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
				@else
						<tr>
							<td>No hay ninguna Tienda o Sucursal agregada.</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
				@endif
        </tbody>
    </table>
</div>
