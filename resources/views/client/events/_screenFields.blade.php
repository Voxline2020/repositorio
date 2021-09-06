<div class="table-responsive">
	<table class="table table-hover" id="devices-table">
		<thead class="thead-dark">
			<tr>
				<th>nombre</th>
				<th>Sucursal</th>
				<th>Resolución</th>
				<th>Tipo</th>
				<th>estado</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@if($devices->count()!=0)
			@foreach($devices as $device)
			<tr>
				<td>{!! $device->name !!}</td>
				<td>{!! $device->computer->store->name !!}</td>
				<td>{!! $device->width !!}x{!! $device->height !!}</td>
				<td>{!! $device->type->name !!}</td>
				@if($device->state==0)
				<td style="color:#FF0000;">Inactivo</td>
				@endif
				@if($device->state==1)
				<td style="color:#01DF01;">Activo</td>
				@endif
				<td><a href="{{ route('clients.show',[$device->id]) }}" class="btn btn-info"><i class="fas fa-eye"></i></a></td>
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
