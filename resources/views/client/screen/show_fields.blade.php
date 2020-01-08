@include('flash::message')
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
							@if ($detail->orderContent == 1)
								<button class="disabled btn btn-info"><i class="fas fa-arrow-up"></i></button>
							@endif
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
							@if ($details->count() == $detail->orderContent)
								<button class="disabled btn btn-info"><i class="fas fa-arrow-down"></i></button>
							@endif
							<button href="#" class="btn btn-info"><i class="fas fa-exchange-alt" data-toggle="modal" data-target="#changejump" data-order={{$detail->orderContent}} data-id={{$detail->id}}></i></button>
						</div>
					</td>
				</tr>
				@endif
			@endforeach
		</tbody>
	</table>
	<!-- Modal -->
	<div class="modal fade" id="changejump" tabindex="-1" role="dialog" aria-labelledby="changejump" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="changejumpLabel">Cambiar posición</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				{!! Form::model($screen, ['route' => ['clients.changeJump'], 'method' => 'put']) !!}
				<div class="modal-body">
					{!! Form::hidden('id') !!}
					{!! Form::hidden('order') !!}
					{!! Form::label('neworder','Nueva Posicion') !!}
					{!! Form::number('neworder','value') !!}
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
					{!! Form::button('Cambiar', ['type' => 'submit','class' => 'btn btn-primary']) !!}
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
	<!-- FIN Modal -->
</div>
