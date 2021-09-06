@extends('layouts.principal')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			@include('adminlte-templates::common.errors')
		</div>
		<div class="col-sm-12">
			<h2>
				Editar computador: {{$computer->code}}
			</h2>
		</div>
		<div class="col-sm-12">
				{!! Form::model($computer, ['route' => ['companies.computers.update',$company,$computer], 'method' => 'put']) !!}
				<div class="row">
						@include('companies.computers.editfields')
				</div>
			 {!! Form::close() !!}
		</div>
	</div>
</div>
@endsection

