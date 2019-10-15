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
				@if($screen->state==0)
				<td style="color:#FF0000;">Inactivo</td>
				@endif
				@if($screen->state==1)
				<td style="color:#01DF01;">Activo</td>
				@endif
				<td>{{$screen->computer->store->name}}</td>
				<td>09:30 15/10/2019</td>
				<td>
					<a href="#" class="btn btn-primary"><i
					class="fas fa-camera"></i></a>
				</td>
				<td>
					<div class='btn-group'>
							<a href="#" class="btn btn-info"><i
							class="fas fa-info-circle"></i></a>
							{{-- <a href="{{ route('screens.editTwoParam',[$screen->id, $screen->computer_id]) }}" class="btn btn-info"><i
									class="fas fa-info-circle"></i></a> --}}
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	{{ $screens->links() }}

</div>
