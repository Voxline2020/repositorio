<div class="table-responsive">
	<table class="table table-hover" id="screens-table">
		<thead class="thead-dark">
			<tr>
				<th>nombre</th>
				<th>sector</th>
				<th>piso</th>
				<th>tipo</th>
				<th>estado</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@if($screens->count()!=0)
			@foreach($screens as $screen)
			<tr>
				<td>{!! $screen->name !!}</td>
				<td>{!! $screen->sector !!}</td>
				<td>{!! $screen->floor !!}</td>
				<td>{!! $screen->type !!}</td>
				@if($screen->state==0)
				<td style="color:#FF0000;">Inactivo</td>
				@endif
				@if($screen->state==1)
				<td style="color:#01DF01;">Activo</td>
				@endif
				<td><a href="{{ route('companies.computers.showScreen',[$company,$screen->computer,$screen]) }}" class="btn btn-info"><i class="fas fa-eye"></i></a></td>
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

