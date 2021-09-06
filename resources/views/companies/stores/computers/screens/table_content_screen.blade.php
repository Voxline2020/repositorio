
<div class="table-contents">
	<table class="table table-hover">
		<thead class="thead-dark">
			<tr>
				<th>Pantalla</th>
				<th>Ancho</th>
				<th>Alto</th>
				<th><i class="fas fa-check-circle"></i></th>
			</tr>
		</thead>
		<tbody>
			@foreach($screens as $screen)
			<tr>
				<td>{!! $screen->name !!}</td>
				<td>{!! $screen->height !!}</td>
				<td>{!! $screen->width !!}</td>
				<td><input type="checkbox" name="pantallas[]" value="{{$screen->id}}">
					</form>
			</tr>
			@endforeach
		</tbody>
	</table>
	<input type="submit" class="btn btn-primary " value="Asignar">
</div>
