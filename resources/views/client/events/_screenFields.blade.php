<div class="table-responsive">
	<table class="table table-hover" id="screens-table">
		<thead class="thead-dark">
			<tr>
				<th>nombre</th>
				<th>Sucursal</th>
				<th>estado</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@if($screens->count()!=0)
			@foreach($screens as $screen)
			<tr>
				<td>{!! $screen->name !!}</td>
				<td>{!! $screen->computer->store->name !!}</td>
				@if($screen->state==0)
				<td style="color:#FF0000;">Inactivo</td>
				@endif
				@if($screen->state==1)
				<td style="color:#01DF01;">Activo</td>
				@endif
				<td><a href="{{ route('clients.show',[$screen->id]) }}" class="btn btn-info"><i class="fas fa-eye"></i></a></td>
			</tr>
			@endforeach
			@else
			<tr>
			<td>No hay pantallas con {{$event->name}} asignado.</td>
			<td></td>
			<td></td>
			<td></td>
			</tr>
			@endif
		</tbody>
	</table>
</div>
