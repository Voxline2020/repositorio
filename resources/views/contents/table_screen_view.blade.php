
<div class="table-contents">
	<table class="table table-hover">
		<thead class="thead-light">
			<tr>
				<th>Pantalla</th>
				<th>Ancho</th>
				<th>Alto</th>
			</tr>
		</thead>
		<tbody>
			@foreach($screens as $screen)
			<tr>
				<td>{!! $screen->name !!}</td>
				<td>{!! $screen->height !!}</td>
				<td>{!! $screen->width !!}</td>
					</form>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
