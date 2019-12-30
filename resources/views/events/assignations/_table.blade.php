
<div class="col-md-12">

	<div class="table-reponsive ny-2">
		<table class="table table-hover">
			<thead class="thead-dark">
				<tr>
					<th>Pantalla</th>
					<th>Sucursal</th>
					<th>Tipo</th>
					<th>Medidas</th>
					<th>Asignado A</th>
				</tr>
			</thead>
			<tbody>
				@foreach($screens as $screen)
					
					<tr>
						<td>{!! $screen->name !!}</td>
						<td>{!! $screen->computer->store->name !!}</td>
						<td>{!! $screen->type !!}</td>
						<td>{!! $screen->height !!}x{!! $screen->width !!}</td>
						<td>

							<div class="custom-control custom-checkbox">
								<input type="checkbox" id="screenChbx{{ $screen->id }}" name="screensChbx[]" value="{{ $screen->id }}" class="custom-control-input">
								<label class="custom-control-label" style="width:2rem; height:2rem;" for="screenChbx{{ $screen->id }}"></label>

							</div>
					</tr>

				@endforeach
			</tbody>
		</table>
	</div>

</div>

<div class="col-md-12 text-right" >
	<input type="submit" class="btn btn-primary " value="Asignar">
</div>

<div class="col-md-12">
	{{ $screens->links() }}
</div>


