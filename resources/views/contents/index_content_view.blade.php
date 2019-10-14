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
							<h3 class="font-weight-bold">Pantallas asociadas</h3>
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
				<form method="post" action="{{route('contents.ScreenView', [$content->id])}}">
						{{csrf_field()}}
			@include('contents.table_screen_view')
				</form>
		</div>
	</div>
</div>
@endsection
