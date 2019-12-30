<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Estado</th>
				<th>Version</th>
				<th>Descripcion</th>
				<th>Slug</th>
				<th colspan="3">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($playlists as $playlist)
				@foreach($playlist->versionPlaylists AS $versionPlaylist)
				@endforeach
				<tr>
					<td>{!! $playlist->name !!}</td>
					<td class="{{ $versionPlaylist->state == "1" ? "green-text" : "red-text" }}">
						@if($versionPlaylist->state == 1)
						Activa
						@elseif($versionPlaylist->state == 0)
						Inactiva
						@endif
					</td>
					<td>{{$versionPlaylist->version}}</td>
					<td>{!! $playlist->description !!}</td>
					<td>{!! $playlist->slug !!}</td>
					<td>
						{!! Form::open(['route' => ['playlists.destroy', $playlist->id], 'method' => 'delete']) !!}
						<div class='btn-group'>
							<a href="{!! route('playlists.show', [$playlist->id]) !!}" class='btn btn-primary btn-xs'><i
									class="fas fa-eye    "></i></a>
							<a href="{!! route('playlists.edit', [$playlist->id]) !!}" class='btn btn-warning btn-xs'><i
									class="fas fa-edit"></i></a>
							{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs',
							'onclick' => "return confirm('Estas seguro?')"]) !!}
						</div>
						{!! Form::close() !!}
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
