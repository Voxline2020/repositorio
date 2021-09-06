<div class="table-responsive">
	<table class="table table-hover" id="devices-table">
		<thead class="thead-dark">
			<tr>
				<th>Pantallas</th>
				<th>Estado</th>
				<th>Tipo</th>
				<th>Resolución</th>
				<th>Cant. de eventos</th>
				<th>Sucursal</th>
				<th>Ultimo chequeo</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($devices as $device)
			<tr>
				<td>{!! $device->name !!}</td>
				@if($device->state==0)
				<td style="color:#FF0000">Inactivo</td>
				@endif
				@if($device->state==1)
				<td style="color:#01DF01">Activo</td>
				@endif
				<td>{!! $device->type->name !!}</td>
				<td>{!! $device->width !!}x{!! $device->height !!}</td>
				@php
				$today=date('Y-m-d H:i:s');
				$eventAssigns = App\Models\EventAssignation::whereHas('content', function ($query) use ($today) {
					$query->whereHas('event', function ($query) use ($today){
						$query->where('enddate','>=',$today);
					});
				})->where('device_id',$device->id)->get();
				@endphp
				<td style="text-align:center;">{{$eventAssigns->count()}}</td>
				<td>{{$device->computer->store->name}}</td>
				<td>{{$device->updated_at}}</td>
				<td>
					<div class='btn-group'>
							<a href="{{ route('clients.show',[$device->id]) }}" class="btn btn-info"><i class="fas fa-eye"></i></a>
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	{{ $devices->links() }}

</div>
