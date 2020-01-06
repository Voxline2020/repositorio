<div class="table-responsive">
	<h3>Contenidos:</h3>
	<table class="table">
		<thead>
			<tr>
				<th>ID</th>
				<th>Nombre</th>
				<th>Resolucion</th>
				<th>Tipo</th>
				<th>Tamaño (mb)</th>
			</tr>
		</thead>
		<tbody>
			@foreach($playlist->versionPlaylists AS $version)
			@endforeach
			@foreach($contents AS $content)
			<tr>
				<td>{!! $content->id !!}</td>
				<td>{!! $content->name !!}</td>
				<td>{!! $content->height !!}x{!! $content->width !!}</td>
				<td>{!! $content->filetype !!}</td>
				<td>{!! $content->SizeMB !!}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>