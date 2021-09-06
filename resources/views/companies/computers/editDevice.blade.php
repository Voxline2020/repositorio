@extends('layouts.principal')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			@include('adminlte-templates::common.errors')
		</div>
		<div class="col-sm-12">
			<h2>
				Editar dispositivo: {{$device->name}}
			</h2>
		</div>
		<div class="col-sm-12">
				{!! Form::model($computer, ['route' => ['companies.computers.updateDevice',$company,$computer], 'method' => 'put']) !!}
				<div class="row">
					<div class="col-sm-12">
						@include('companies.computers.editDevicefields')
					</div>
				</div>
			 {!! Form::close() !!}
		</div>
	</div>
</div>
@endsection

