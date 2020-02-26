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
		<a href="#" type="button" class="btn btn-success w-100" data-toggle="modal" data-target="#addScreen">Nueva Pantalla</a>
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
<!-- Modal Add screen-->
<div class="modal fade" id="addScreen" tabindex="-1" role="dialog" aria-labelledby="addScreenLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title" id="addScreenLabel">Crear nueva pantalla para computador: {{$computer->code}}</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
			{{ Form::open(['route' =>['companies.storeScreen',$company,$computer]]) }}
			<div class="content">
					<div class="box box-primary">
							<div class="box-body">
									<div class="row">
											<div class="form-group col-sm-4">
												{!! Form::label('name', 'Nombre de la pantalla:') !!}
												{!! Form::text('name', null, ['class' => 'form-control']) !!}
											</div>
											<div class="form-group col-sm-4">
												{!! Form::label('width', 'Ingrese ancho:') !!}
												{!! Form::input('number','width', null, ['class' => 'form-control']) !!}
											</div>
											<div class="form-group col-sm-4">
												{!! Form::label('height', 'Ingrese alto:') !!}
												{!! Form::input('number','height', null, ['class' => 'form-control']) !!}
											</div>
											<input type="hidden" id="computer_id" name="computer_id" value="{{$computer->id}}">
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
<!-- FIN Modal Add screen-->
@endsection

