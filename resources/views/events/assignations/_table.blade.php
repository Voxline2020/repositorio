
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

					<tr id="{{$screen->id}}">
						<td data-name="name">{!! $screen->name !!}</td>
						<td data-name="store_name">{!! $screen->computer->store->name !!}</td>
						<td data-name="type">{!! $screen->type !!}</td>
						<td data-name="resolution">{!! $screen->width !!}x{!! $screen->height !!}</td>
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
	<button type="button" class="btn btn-primary" id="btnAssign" data-toggle="modal" data-target="#confirmAssignation">Asignar</button>
</div>

<div class="col-md-12">
	{{ $screens->links() }}
</div>
<!-- Modal -->
<div class="modal fade" id="confirmAssignation" tabindex="-1" role="dialog" aria-labelledby="confirmAssignationLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">¿Desea confirmar esta asignación?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
					<div class="col-md-12">
						<h4>Contenido:</h4>
					</div>
					<div class="col-md-12">
						<h4><span class="badge badge-light">Nombre: {!! $content->name !!}</span></h4>
					</div>
					<div class="col-md-12">
						<h4><span class="badge badge-light">Tamaño: {!! $content->SizeMB !!}</span></h4>
					</div>
					<div class="col-md-12">
						<h4><span class="badge badge-light">Resolución: {!! $content->Resolution !!}</span></h4>
					</div>
					<div class="col-md-12">
						<h4>En pantalla(s):</h4>
						<div class="table-reponsive ny-2">
							<table class="table table-hover" id="tableSelected">
								<thead class="thead-dark">
									<tr>
										<th>Pantalla</th>
										<th>Sucursal</th>
										<th>Tipo</th>
										<th>Medidas</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>

					</div>
				</div>
      </div>
      <div class="modal-footer">
				<input type="submit" class="btn btn-primary " value="Confirmar">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
<!-- FIN Modal -->



