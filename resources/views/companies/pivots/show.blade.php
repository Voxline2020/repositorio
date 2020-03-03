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
			<a  type="button" class="btn btn-outline-primary w-100" href="{{route('companies.pivots.index',[$pivot->company_id]) }}">Atras</a>
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
			{!! Form::hidden('computer_pivot_id', $pivot->id) !!}
			{!! Form::hidden('state', 1) !!}
			<div class="row">
				<div class="col-md-4">
					<input id="codeFiltrar" name="codeFiltrar" type="text"  class='form-control' placeholder = 'Codigo'>
				</div>
				<div class="col-md-3">
					<select name="store" id="store" class="form-control">
						<option null selected disabled>Tienda/Sucursal</option>
					</select>
				</div>
				<div class="col-md-3">
					<select name="type" id="type" class="form-control">
						<option null selected disabled>Tipo de acceso</option>
					</select>
				</div>
				<div class="col-md-2">
					<button id="clear" type="button" class="btn btn-secondary w-100">Limpiar</button>
				</div>
			</div>
			<hr>

			<div class="table-responsive">
				<table id="onpivot" class="table table-hover">
					<thead class="thead-dark">
						<tr>
							<th>Codigo</th>
							<th>Ubicacion</th>
							<th>Tipo de acceso</th>
							<th>Seleccionar</th>
						</tr>
					</thead>
					<tbody>
						@foreach($computers as $key => $computer)
						<tr>
							<td>{{$computer->code}}</td>
							<td>{{$computer->store->name}}</td>
							<td>{{$computer->type->name}}</td>
						<td><input type="checkbox" name="computer_id[{{$key}}]" id="{!! $computer->id !!}" value="{!! $computer->id !!}">
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				{!! Form::submit('Agregar', ['class' => 'btn btn-primary']) !!}
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
</div>
<!-- FIN Modal Add Onpivot-->
@endsection
@section('script')
<script>
	$(document).ready(function() {
		$('#onpivot').DataTable( {
			"sDom": '<"top">tip',
			"bPaginate": false,
			"bInfo": false,
			initComplete: function () {
				this.api().columns([1]).every( function () {
					var column = this;
					var select = $('#store');
					select.on( 'change', function () {
						var val = $.fn.dataTable.util.escapeRegex($(this).val());
						column.search( val ? '^'+val+'$' : '', true, false ).draw();
					});
					column.data().unique().sort().each( function ( d, j ) {
						select.append( '<option value="'+d+'">'+d+'</option>' )
					});
				});
				this.api().columns([2]).every( function () {
					var column = this;
					var select = $('#type').on( 'change', function () {
						var val = $.fn.dataTable.util.escapeRegex($(this).val());
						column.search( val ? '^'+val+'$' : '', true, false ).draw();
					});
					column.data().unique().sort().each( function ( d, j ) {
						select.append( '<option value="'+d+'">'+d+'</option>' )
					});
				});
				$('#codeFiltrar').keyup(function(){
					$('#onpivot').DataTable().search($(this).val()).draw();
				});
				$('#clear').click(function(){
						$('#onpivot').DataTable().search('').draw();
						$('#onpivot').DataTable().columns([1]).search('').draw();
						$('#onpivot').DataTable().columns([2]).search('').draw();
						$('.bs-filters').val('');
						$('#codeFiltrar').val('');
						$('#store').val('Tienda/Sucursal');
						$('#type').val('Tipo de acceso');
				});
			}
		});
	});
</script>
@endsection
@section('style')
<style>
.enlace {
	display:inline;
	border:0;
	padding:0;
	margin:0;
	text-decoration:underline;
	background:none;
	color:#000088;
	font-family: arial, sans-serif;
	font-size: 1em;
	line-height:1em;
}
</style>
@endsection
