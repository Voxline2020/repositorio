@extends('layouts.principal')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			@include('adminlte-templates::common.errors')
		</div>
		<div class="col-sm-12">
			<h2>
				Editar Pantalla: {{$screen->name}}
			</h2>
		</div>
		<div class="col-sm-12">
				{!! Form::model($computer, ['route' => ['companies.computers.updateScreen',$company,$computer], 'method' => 'put']) !!}
				<div class="row">
						@include('companies.computers.editScreenfields')
				</div>
			 {!! Form::close() !!}
		</div>
	</div>
</div>
@endsection

