@extends('layouts.principal')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8">
			<h2 class=font-weight-bold>{{$pivot->name}} <i class="fas fa-server"></i></h2>
		</div>
		<div class="col-md-2">
			<a href="#" type="button" class="btn btn-success w-100" data-toggle="modal" data-target="#addOnpivot">Agregar</a>
		</div>
		<div class="col-md-2">
			<a  type="button" class="btn btn-primary w-100" href="{{route('companies.pivots.index',[$pivot->company_id]) }}">Volver</a>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-4">
			<h4><span class="badge badge-light">Codigo: {!! $pivot->code !!} </span></h4>
		</div>
		<div class="col-md-4">
			<h4><span class="badge badge-light">IP: {!! $pivot->ip !!}</span></h4>
		</div>
		<div class="col-md-4">
			<h4><span class="badge badge-light">Ubicacion: {!! $pivot->location !!}</span></h4>
		</div>
		<div class="col-md-4">
			<h4><span class="badge badge-light">Codigo TeamViewer: {!! $pivot->teamviewer_code !!} </span></h4>
		</div>
		<div class="col-md-4">
			<h4><span class="badge badge-light">Pass TeamViewer: {!! $pivot->teamviewer_pass !!}</span></h4>
		</div>
		<div class="col-md-4">
			<h4><span class="badge badge-light">Empresa: {!! $pivot->company->name !!}</span></h4>
		</div>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-md-12">
		@include('flash::message')
	</div>
	<div class="col-md-12">
		<h2>Computadores</h2>
	</div>
	<div class="col-md-12">
		@include('companies.pivots.showfields')
	</div>
</div>
<!-- Modal Add Onpivot-->
<div class="modal fade" id="addOnpivot" tabindex="-1" role="dialog" aria-labelledby="addOnpivotLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title" id="addOnpivotLabel">Agregar computador a {{$pivot->name}}</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			{!! Form::model($onpivots, ['route' => ['companies.storeOnpivot', $pivot->company_id,$pivot->id], 'method' => 'post']) !!}
			<div class="table-responsive">
				<table class="table table-hover">
					<thead class="thead-dark">
						<tr>
							<th>Codigo</th>
							<th>Ubicacion</th>
							<th>Tipo de acceso</th>
							<th>Seleccionar</th>
						</tr>
					</thead>
					<tbody>
						@foreach($computers as $computer)
						<tr>
							<td>{{$computer->code}}</td>
							<td>{{$computer->location}}</td>
							<td>{{$computer->type->name}}</td>
							<td><input type="radio" name="computer_id" id="{!! $computer->id !!}" value="{!! $computer->id !!}" required>
							</td>
						</tr>
						@endforeach
						{{-- {!! Form::hidden('pivot', $pivot->id) !!} --}}
						{!! Form::hidden('computer_pivot_id', $pivot->id) !!}
						{!! Form::hidden('state', 1) !!}
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				{!! Form::submit('Agregar', ['class' => 'btn btn-primary']) !!}
				{{-- <button type="button" class="btn btn-primary">Asignar</button> --}}
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
</div>
<!-- FIN Modal Add Onpivot-->
@endsection
