<div class="table-responsive">
	<h3>Contenidos:</h3>
	<table class="table">
		<thead>
			<tr>
				<th>Orden</th>
				<th>ID</th>
				<th>Nombre</th>
				<th>Tipo</th>
				<th>Tamaño (mb)</th>
			</tr>
		</thead>
		<tbody>
			@foreach($details AS $detail)
				@if($detail->content != null)
				<tr>
					<td>{!! $detail->orderContent !!}</td>
					<td>{!! $detail->content->id !!}</td>
					<td>{!! $detail->content->name !!}</td>
					<td>{!! $detail->content->filetype !!}</td>
					<td>{!! $detail->content->SizeMB !!}</td>
				</tr>
				@endif
			@endforeach
		</tbody>
	</table>
</div>