<div class="table-responsive">
	<h3>Contenidos:</h3>
	<table class="table">
		<thead>
			<tr>
				<th>Orden</th>
				<th>ID</th>
				<th>Nombre</th>
				<th>Tipo</th>
				<th>Cambiar Posición</th>
			</tr>
		</thead>
		<tbody>
			@foreach($details AS $detail)
				@if($detail->content != null)
				<tr>
					<td>{!! $detail->orderContent !!}</td>
					<td>{!! $detail->content->id !!}</td>
					<td>{!! $detail->content->name !!}</td>
					<td>{!! $detail->content->filetype !!}</td>
					<td>
						<div class='btn-group'>
						@if ($detail->orderContent != 1)
						{!! Form::model($screen, ['route' => ['clients.changeUp', $detail->id], 'method' => 'put']) !!}
							{!! Form::hidden('order', $detail->orderContent) !!}
							{!! Form::button('<i class="fas fa-arrow-up"></i>', ['type' => 'submit','class' => 'btn btn-info']) !!}
						{!! Form::close() !!}
						@endif
						@if ($details->count() != $detail->orderContent)
							{!! Form::model($screen, ['route' => ['clients.changeDown', $detail->id], 'method' => 'put']) !!}
							{!! Form::hidden('order', $detail->orderContent) !!}
							{!! Form::button('<i class="fas fa-arrow-down"></i>', ['type' => 'submit','class' => 'btn btn-info']) !!}
						{!! Form::close() !!}
						@endif


						</div>
					</td>
				</tr>
				@endif
			@endforeach
		</tbody>
	</table>
</div>
