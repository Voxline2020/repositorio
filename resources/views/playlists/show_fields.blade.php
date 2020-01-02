<div class="table-responsive">
	<h3>Contenidos:</h3>
	<table class="table">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Resolucion</th>
				<th>Tipo</th>
				<th>Tamaño</th>
			</tr>
		</thead>
		<tbody>
			@foreach($playlist->versionPlaylists AS $version)
			@endforeach
			@foreach($version->versionPlaylistDetails AS $detail)
			<tr>
				<td>{!! $detail->content->name !!}</td>
				<td>{!! $detail->content->height !!}x{!! $detail->content->width !!}</td>
				<td>{!! $detail->content->filetype !!}</td>
				<td>{!! $detail->content->size !!}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>