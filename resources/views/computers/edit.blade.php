@extends('layouts.principal')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			@include('adminlte-templates::common.errors')
		</div>
		<div class="col-sm-12">
			<h2>
				Editar computador
			</h2>
		</div>
		<div class="col-sm-12">
				{!! Form::model($computer, ['route' => ['computers.update', $computer->id], 'method' => 'patch']) !!}
				<div class="row">
						@include('computers.fields')
				</div>
			 {!! Form::close() !!}
		</div>
	</div>
</div>
@endsection

