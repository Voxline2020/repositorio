@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-6">
		<h1 class="font-weight-bold">Computador: {{ $computer->code }} </h1>
	</div>
	<div class="col-md-2">
		<a class="btn btn-secondary w-100" href="{!! route('companies.computers.show',['company'=>$company,'computer'=>$computer])!!}">Limpiar</a>
	</div>
	<div class="col-md-2">
		<a href="#" type="button" class="btn btn-success w-100" data-toggle="modal" data-target="#addDevice">Nuevo Dispositivo</a>
	</div>
	<div class="col-md-2">
		<a class="btn btn-outline-primary w-100" href="{!! route('companies.computers.index',$company)!!}">Atras</a>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-md-5">
		{{Form::text('nameFilter',null,['class'=> 'form-control', 'placeholder' => 'Nombre'])}}
	</div>
	<div class="col-md-5">
		{{Form::text('resolution',null,['class'=> 'form-control', 'placeholder' => 'Resolución'])}}
	</div>
	<div class="col-md-2">
		<a class="btn btn-primary w-100" href="{!! route('companies.computers.index',$company)!!}">Buscar</a>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-md-12">
		@include('flash::message')
	</div>
</div>
<div class="content">
	<div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">
					@include('companies.computers.showfields')
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Add device-->
<div class="modal fade" id="addDevice" tabindex="-1" role="dialog" aria-labelledby="addDeviceLabel"
aria-hidden="true">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title" id="addDeviceLabel">Crear nuevo dispositivo</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			{{ Form::open(['route' =>['companies.storeDevice',$company,$computer]]) }}
			<div class="content">
					<div class="box box-primary">
							<div class="box-body">
									<div class="row">
											<div class="form-group col-sm-12">
												{!! Form::input('text','name', null, ['class' => 'form-control','placeholder' => 'Nombre','required']) !!}
											</div>
											<div class="form-group col-sm-12">
												{!! Form::input('number','width', null, ['class' => 'form-control','placeholder' => 'Ancho','required']) !!}
											</div>
											<div class="form-group col-sm-12">
												{!! Form::input('number','height', null, ['class' => 'form-control','placeholder' => 'Alto','required']) !!}
											</div>
											<div class="form-group col-sm-12">
												<select name="type_id" id="type_id" class="form-control">
													<option value="" selected disabled required>Tipo</option>
													@foreach ($types as $type)
													<option value="{{$type->id}}">{{$type->name}}</option>
													@endforeach
												</select>
											</div>
											<div class="form-group col-sm-12">
												{!! Form::input('text','imei', null, ['class' => 'form-control','placeholder' => 'IMEI','id'=>'imei','required']) !!}
											</div>
												{!! Form::hidden('version',0)!!}
												{!! Form::hidden('computer_id',$computer->id)!!}
											<!-- <input type="hidden" id="computer_id" name="computer_id" value="{{$computer->id}}"> -->
									</div>
							</div>
					</div>
			</div>
		<div class="modal-footer">
			{!! Form::submit('Crear', ['class' => 'btn btn-success']) !!}
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
		</div>
		{!! Form::close() !!}
		</div>
	</div>
</div>
</div>
<!-- FIN Modal Add device-->
@endsection
@section('script')
<script>
$("#type_id").change(function(){
	var selected = $(this).children("option:selected").text();
	if(selected=='Pantalla'){
		$("#imei").val("N/A");
	}
	if(selected=='MagicInfo'){
		$("#imei").val("N/A");
	}
});
</script>
@endsection


