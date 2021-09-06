



<div class="row">
				<label>	</label>
		<div class="col-md-4">
				<h4><span class="badge badge-light  pull-right">Lugar:{!! $computers->location !!}</span></h4>
		</div>
		<div class="col-md-4">
				<h4><span class="badge badge-light">Tipo: {{ $computers->type->name}}</span></h4>
		</div>
		<div class="col-md-4">
				@if($computers->type_id==1)
				<h4><span class="badge badge-light">Acceso: {{ $computers->teamviewer_code}}</span></h4>
			@endif
			@if($computers->type_id==2)
			<h4><span class="badge badge-light">Acceso: {{ $computers->aamyy_code}}</span></h4>
			@endif
			@if($computers->type_id==3)
			<h4><span class="badge badge-light">Acceso: {{ $computers->ip}}</span></h4>
			@endif
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
			@foreach($screens as $screen)
			<tr>
				<td>{!! $screen->name !!}</td>
				<td>{!! $screen->height !!}</td>
				<td>{!! $screen->width !!}</td>
				<td>
					{!! Form::open(['route' => ['screens.destroy', $screen->id], 'method' => 'delete']) !!}
					<div class='btn-group'>
						<a>
							<a href="{{ route('screens.editTwoParam',[$screen->id, $screen->computer_id]) }}"
								class="btn btn-warning"><i class="fas fa-edit"></i></a>
							{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger
							btn-xs',
							'onclick' => "return confirm('Desea eliminar?')"]) !!}
					</div>

					{!! Form::close() !!}
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
