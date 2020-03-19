<div class="row">
	<label>	</label>


</div>
<div class="table-responsive">
<table class="table table-hover" id="devices-table">
<thead class="thead-dark">
<tr>
	<th>ID</th>
	<th>Nombre</th>
	<th>Tipo</th>
	<th>IMEI</th>
	<th>Estado</th>
	<th>Resolución</th>
	<th> Acciones </th>

</tr>
</thead>
<tbody>
@if($devices->count()!=0)
	@foreach($devices as $device)
	<tr>
		<td>{!! $device->id !!}</td>
		<td>{!! $device->name !!}</td>
		<td>{!! $device->type->name !!}</td>
		<td>{!! $device->imei !!}</td>
		<td>
		{!! Form::model($device, ['route' => ['companies.computers.changeStatusDevice', 'company'=>$company,'computer'=>$computer,'device'=>$device], 'method' => 'put', 'id' => 'changestatus']) !!}
			@if($device->state==0)
					{!! Form::hidden('state', 1) !!}
					{!! Form::submit('Inactivo', ['class' => 'btn btn-outline-danger']) !!}
			@endif
			@if($device->state==1)
					{!! Form::hidden('state', 0) !!}
					{!! Form::submit('Activo', ['class' => 'btn btn-outline-success']) !!}
			@endif
		{!! Form::close() !!}
		</td>
		<td>{!! $device->width !!}x{!! $device->height !!}</td>
		<td>
			{!! Form::open(['route' => ['companies.computers.destroyDevice',$company, $computer, $device], 'method' => 'delete']) !!}
			<div class='btn-group'>
				<a href="{{ route('companies.computers.showDevice',['company'=>$company,'computer'=>$computer,'device'=>$device]) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
				<a href="{{ route('companies.computers.editDevice',['company'=>$company,'device'=>$device,'computer'=>$computer]) }}" class="btn btn-warning"><i class="fas fa-edit"></i></a>
				{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger','onclick' => "return confirm('¿Seguro que desea eliminar este dispositivo?')"]) !!}
			</div>

			{!! Form::close() !!}
		</td>
	</tr>
	@endforeach
@else
	<tr>
		<td>Aun no hay pantallas asignadas.</td>
		<td></td>
		<td></td>
	</tr>
@endif
</tbody>
</table>
</div>
