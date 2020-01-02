<div class="table-responsive">
	<table class="table table-hover" id="screens-table">
		<thead class="thead-light">
			<tr>
				<th>Pantallas</th>
				<th>Estado</th>
				<th>Sucursal</th>
				<th>Ultimo chequeo</th>
				<th>Capturas</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($screens as $screen)
			<tr>
				<td>{!! $screen->name !!}</td>
				<td>
				{!! Form::model($screen, ['route' => ['screens.changeStatus', $screen->id], 'method' => 'put']) !!}
					@if($screen->state==0)
						{!! Form::hidden('state', 1) !!}
						{!! Form::submit('Inactivo', ['class' => 'btn btn-danger']) !!}
					@endif
					@if($screen->state==1)
						{!! Form::hidden('state', 0) !!}
						{!! Form::submit('Activo', ['class' => 'btn btn-success']) !!}
					@endif
					
					
				{!! Form::close() !!}	
				</td>
				<td>
							{{$screen->computer->store->name}}
				</td>
				<td>{{$screen->updated_at}}</td>
				<td>
					<a href="#" class="btn btn-primary"><i
					class="fas fa-camera"></i></a>
				</td>
				<td>
					<div class='btn-group'>
							<a href="{{ route('playlists.show',[$screen->playlist_id]) }}" class="btn btn-info"><i class="fas fa-info-circle"></i></a>
							<a href="{{ route('screens.editTwoParam',[$screen->id, $screen->computer_id]) }}" class="btn btn-warning"><i
									class="fas fa-edit"></i></a>
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	{{ $screens->links() }}

</div>
