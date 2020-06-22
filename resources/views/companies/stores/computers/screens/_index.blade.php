

<div class="row my-2">
	<div class="col-md-3">
		<select name="sector" id="sector" class="form-control">
			<option null selected disabled>Sector</option>
			<option value="ropa hombre">Ropa hombre</option>
			<option value="ropa mujer">Ropa mujer</option>
			<option value="joyeria">joyeria</option>
			<option value="perfumeria">perfumeria</option>
		</select>
	</div>
	<div class="col-md-3">
		<select name="floor" id="floor" class="form-control">
			<option null selected disabled>Piso</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
		</select>
	</div>

	<div class="col-md-3">
		<select name="type" id="type" class="form-control">
			<option null selected disabled>Tipo</option>
			<option value="colgante">Colgante</option>
			<option value="espejo">Espejo</option>
			<option value="madera">Madera</option>
			<option value="pilar">Pilar</option>
		</select>
	</div>
</div>

<div class="table-responsive">
	<table class="table table-hover" id="screens-table">
		<thead class="thead-dark">
			<tr>
				<th>Pantallas</th>
				<th>Ancho</th>
				<th>Alto</th>
				<th> Acciones </th>
			</tr>
		</thead>
		<tbody>
			@foreach ($store->computers as $computer)
					@foreach ($computer->devices as $device)
					<tr>
						<td>{!! $device->name !!}</td>
						<td>{!! $device->height !!}</td>
						<td>{!! $device->width !!}</td>
						<td>
							{!! Form::open(['route' => ['screens.destroy', $device->id], 'method' => 'delete']) !!}
							<div class='btn-group'>
								<a>
									<a href="{{ route('screens.editTwoParam',[$device->id, $device->computer_id]) }}"
										class="btn btn-warning"><i class="fas fa-edit"></i></a>
									{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger
									btn-xs',
									'onclick' => "return confirm('Desea eliminar?')"]) !!}
							</div>

							{!! Form::close() !!}
						</td>
					</tr>
					@endforeach
			@endforeach
		</tbody>
	</table>
</div>
