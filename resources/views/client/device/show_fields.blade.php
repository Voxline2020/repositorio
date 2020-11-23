<div class="col-md-7">
	@php $mytime = Carbon\Carbon::now()@endphp
	<h3>Eventos Actuales: ( {{ \Carbon\Carbon::parse($mytime)->format('d-m-Y')}} )</h3>
</div>
<div class="col-md-2.5">
	<a href="#" onclick="asd({{$device->id}})" id="btnSubirCambios" type="button" class="btn btn-warning w-100" >Subir cambios</a>
</div>
<div class="col-md-2">
	<a href="" type="button" class="btn btn-success w-100" data-toggle="modal" data-target="#assignEvent">Añadir
		Evento</a>
</div>
<div class="col-md-1.5">
	<a href="{{ route('clients.index') }}" type="button" class="btn btn-outline-primary w-100">Atras</a>
</div>
<br>
<br>
<div class="table-responsive">
	<table class="table table-hover">
		<thead class="thead-dark">
			<tr>
				<th>Orden</th>
				<th>Nombre Evento</th>
				<th>Duración</th>
				<th>Fecha Inicio</th>
				<th>Fecha Termino</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@if($eventAssigns->count()!=0)
			@foreach($eventAssigns AS $assign)
			<tr>
				<td>{{$assign->order}}</td>
				<td>{{$assign->content->event->name}}</td>
				<td>{{$assign->content->duration}}</td>
				<td>{!! \Carbon\Carbon::parse($assign->content->event->initdate)->format('d-m-Y H:i') !!}</td>
				<td>{!! \Carbon\Carbon::parse($assign->content->event->enddate)->format('d-m-Y H:i') !!}</td>
				<td>
					<div class='btn-group'>
						{{ Form::open([ 'method' => '']) }}
							<a href="{!! route('clients.events.show', [ $assign->content->event->id]) !!}" class='btn btn-info'><i
									class="fas fa-eye"></i></a>
						{!! Form::close() !!}
						&nbsp;
						{{ Form::open([ 'method' => '']) }}
						<a type="button" class="btn btn-info" data-toggle="modal" data-target="#changeOrder" data-id="{{$assign->id}}"
						data-device="{{$device->id}}"><i class="fas fa-sync"></i></a>
						{!! Form::close() !!}
						&nbsp;
						{!! Form::model($device,['route' => ['clients.devices.cloneEvent'], 'method' => 'put']) !!}
						{!! Form::hidden('content_id',$assign->content->id) !!}
						{!! Form::hidden('device_id',$device->id) !!}
						{!! Form::hidden('order',$assign->order) !!}
						{!! Form::hidden('user_id',$assign->user_id) !!}
						{!! Form::hidden('state',$assign->state) !!}
						<button type="submit" href="#" class="btn btn-info"><i class="fas fa-clone"></i></button>
						{!! Form::close() !!}
						&nbsp;
						{{ Form::open(['route' => ['clients.events.destroyAssign',$assign], 'method' => 'delete']) }}
						{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs',
							'onclick' => "return confirm('¿Estas seguro que quieres eliminar?')"]) !!}
						{!! Form::close() !!}
					</div>
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td>No hay eventos asignados.</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			@endif
		</tbody>
	</table>
	{{$eventAssigns->links()}}
	<div class="col-md-9">
		<h3>Eventos Proximos:</h3>
	</div>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead class="thead-dark">
				<tr>
					<th>Orden</th>
					<th>Nombre Evento</th>
					<th>Fecha Inicio</th>
					<th>Fecha Termino</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody>
				@if($eventInactives->count()!=0)
				@foreach($eventInactives AS $inactive)
				<tr>
					<td>{{$inactive->order}}</td>
					<td>{{$inactive->content->event->name}}</td>
					<td>{!! \Carbon\Carbon::parse($inactive->content->event->initdate)->format('d-m-Y H:i') !!}</td>
					<td>{!! \Carbon\Carbon::parse($inactive->content->event->enddate)->format('d-m-Y H:i') !!}</td>
					<td>
						<div class='btn-group'>
							<a href="{!! route('clients.events.show', [ $inactive->content->event->id]) !!}" class='btn btn-info'><i
									class="fas fa-eye"></i></a>
							<a type="button" class="btn btn-info" data-toggle="modal" data-target="#changeOrder" data-id="{{$inactive->id}}"
							data-device="{{$device->id}}"><i class="fas fa-sync"></i></a>
							{!! Form::model($device,['route' => ['clients.devices.cloneEvent'], 'method' => 'put']) !!}
							{!! Form::hidden('content_id',$inactive->content->id) !!}
							{!! Form::hidden('device_id',$device->id) !!}
							{!! Form::hidden('order',$inactive->order) !!}
							{!! Form::hidden('user_id',$inactive->user_id) !!}
							{!! Form::hidden('state',$inactive->state) !!}
							<button type="submit" href="#" class="btn btn-info"><i class="fas fa-clone"></i></button>
							{!! Form::close() !!}
							{{ Form::open(['route' => ['clients.events.destroyAssign',$inactive], 'method' => 'delete']) }}
							{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs',
								'onclick' => "return confirm('¿Estas seguro que quieres eliminar?')"]) !!}
							{!! Form::close() !!}
						</div>
					</td>
				</tr>
				@endforeach
				@else
				<tr>
					<td>No hay eventos proximos.</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				@endif
			</tbody>
		</table>
		{{$eventInactives->links()}}
	</div>
</div>
	<!-- Modal changeOrder -->
	<div class="modal fade" id="changeOrder" tabindex="-1" role="dialog" aria-labelledby="changeOrderLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="changeOrderLabel">Cambiar posición</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				{!! Form::model($eventAssigns, ['route' => ['clients.devices.changeOrder'], 'method' => 'put']) !!}
				<div class="modal-body">
					{!! Form::hidden('id') !!}
					{!! Form::hidden('device') !!}
					{!! Form::label('neworder','Nuevo Nº Orden') !!}
					{!! Form::number('neworder','',['min'=>'0']) !!}
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					{!! Form::button('Cambiar', ['type' => 'submit','class' => 'btn btn-primary']) !!}
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
	<!-- FIN Modal changeOrder -->
	<!-- Modal AssignEvent-->
	<div class="modal fade" id="assignEvent" tabindex="-1" role="dialog" aria-labelledby="assignEventLabel"
		aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="assignEventLabel">Asignar Evento a Pantalla: "{{$device->name}}"</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					{!! Form::model($device, ['route' => ['clients.devices.eventAssign', $device->id], 'method' => 'put']) !!}
					<div class="table-responsive">
						<table class="table table-hover">
							<thead class="thead-dark">
								<tr>
									<th>Nombre Evento</th>
									<th>Fecha Inicio</th>
									<th>Fecha Termino</th>
									<th>Seleción</th>
								</tr>
							</thead>
							<tbody>
								@foreach($events as $key => $event)
								<tr>
									<td>{{$event->name}}</td>
									<td>{{$event->initdate}}</td>
									<td>{{$event->enddate}}</td>
									<td><input type="checkbox" name="event_id[{{$key}}]" id="{!! $event->id !!}" value="{!! $event->id !!}">
									</td>
								</tr>
								@endforeach
								{!! Form::hidden('user_id', Auth::user()->id) !!}
								{!! Form::hidden('order', 999) !!}
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						{!! Form::submit('Asignar', ['class' => 'btn btn-primary']) !!}
						{{-- <button type="button" class="btn btn-primary">Asignar</button> --}}
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
	<!-- FIN Modal AssignEvent-->

