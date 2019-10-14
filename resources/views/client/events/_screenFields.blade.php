<div class="table-responsive">
	<table class="table table-hover" id="screens-table">
		<thead class="thead-light">
			<tr>
				<th>nombre</th>
				<th>sector</th>
				<th>piso</th>
				<th>tipo</th>
				<th>estado</th>
			</tr>
		</thead>
		<tbody>
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
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
