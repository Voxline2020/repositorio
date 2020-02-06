<div class="row">
	<label>	</label>


</div>
<div class="table-responsive">
<table class="table table-hover" id="screens-table">
<thead class="thead-dark">
<tr>
	<th>Pantallas</th>
	<th>Resolución</th>
	<th> Acciones </th>

</tr>
</thead>
<tbody>
@if($screens->count()!=0)
	@foreach($screens as $screen)
	<tr>
		<td>{!! $screen->name !!}</td>
		<td>{!! $screen->width !!}x{!! $screen->height !!}</td>
		<td>
			{!! Form::open(['route' => ['screens.destroy', $screen->id], 'method' => 'delete']) !!}
			<div class='btn-group'>
				<a href="{{ route('companies.computers.showScreen',['company'=>$company,'computer'=>$computer,'screen'=>$screen]) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
				<a href="{{ route('companies.computers.editScreen',['company'=>$company,'screen'=>$screen,'computer'=>$computer]) }}" class="btn btn-warning"><i class="fas fa-edit"></i></a>
				{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger','onclick' => "return confirm('¿Seguro que desea eliminar?')"]) !!}
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
