@extends('layouts.principal')

@section('content')

<div class="content">
	<div class="clearfix"></div>

	@include('flash::message')

	<div class="clearfix"></div>
	<div class="box box-primary">
		<div class="box-body">
				<div class="row">
						<div class="col-md-9">
							<h3 class="font-weight-bold">Asignacion de contenido a pantallas</h3>
						</div>
						<br>
					</div>
					<br>
					<div class="row">
						<div class="col-md-3">
							<h4><span class="badge badge-light"> {!! $content->name !!}</span></h4>
						</div>
						<div class="col-md-3">
							<h4><span class="badge badge-light">Tamaño: {!! $content->size !!}</span></h4>
						</div>
						<div class="col-md-3">
							<h4><span class="badge badge-light">Ancho: {!! $content->width !!}</span></h4>
						</div>
						<div class="col-md-3">
							<h4><span class="badge badge-light">Alto: {!! $content->height !!}</span></h4>
						</div>
					</div>
					<br>
					{{ Form::open(['route' =>['screens.filter_by_name', $content->id], 'method' => 'GET']) }}

					<div class="row">
						<div class="col-md-9">
							{!! Form::text('nameFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'buscar pantalla']) !!}
						</div>
						<div class="col-md-3">
							<button type="submit" class="btn btn-primary w-100">Buscar </button>
						</div>
					</div>
					<br>
					{!! Form::close() !!}
				<form method="post" action="{{route('screens.ScreenPlaylistAsign', [$content->id])}}">
						{{csrf_field()}}
			@include('screen.table_content_screen')
				</form>
		</div>
	</div>
</div>
@endsection
