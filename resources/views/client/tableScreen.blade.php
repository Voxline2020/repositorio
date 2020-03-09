<div class="table-responsive">
	<table class="table table-hover" id="screens-table">
		<thead class="thead-dark">
			<tr>
				<th>Pantallas</th>
				<th>Estado</th>
				<th>Cant. de eventos</th>
				<th>Sucursal</th>
				<th>Ultimo chequeo</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($screens as $screen)
			<tr>
				<td>{!! $screen->name !!}</td>
				@if($screen->state==0)
				<td style="color:#FF0000">Inactivo</td>
				@endif
				@if($screen->state==1)
				<td style="color:#01DF01">Activo</td>
				@endif
				@php
				$today=date('Y-m-d H:i:s');
				$eventAssigns = App\Models\EventAssignation::whereHas('content', function ($query) use ($today) {
					$query->whereHas('event', function ($query) use ($today){
						$query->where('enddate','>=',$today);
					});
				})->where('screen_id',$screen->id)->get();
				@endphp
				<td style="text-align:center;">{{$eventAssigns->count()}}</td>
				<td>
					{{$screen->computer->store->name}}
				</td>
				<td>{{$screen->updated_at}}</td>
				<td>
					<div class='btn-group'>
							<a href="{{ route('clients.show',[$screen->id]) }}" class="btn btn-info"><i class="fas fa-eye"></i></a>
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	{{ $screens->links() }}

</div>
