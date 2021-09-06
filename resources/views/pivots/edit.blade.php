@extends('layouts.principal')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			@include('adminlte-templates::common.errors')
		</div>
		<div class="col-sm-12">
			<h2>
				{{$pivot->name}} <i class="fas fa-server"></i>
			</h2>
		</div>
		<div class="col-sm-12">
				{!! Form::model($pivot, ['route' => ['pivots.update', $pivot->id], 'method' => 'patch']) !!}
				<div class="row">
						@include('pivots.editfields')
				</div>
			 {!! Form::close() !!}
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
$("#company_id").change(function(event){
	var stores = {!! $stores !!}
	var selected = $('#company_id').val();
	$('#location').empty();
	$("#location").append("<option null selected disabled>Seleccione</option>");
	for(i=0; i<stores.length; i++){
		if(stores[i].company_id==selected){
			$("#location").append("<option value='"+stores[i].name+"'>"+stores[i].name+"</option>");
		}
	}
});
$("#company_id").val();
</script>
@endsection
