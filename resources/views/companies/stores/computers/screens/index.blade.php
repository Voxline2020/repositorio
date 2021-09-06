@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-9">
		<h1 class="font-weight-bold">{{ $computers->code }} </h1>
	</div>
			<div class="col-md-3">
				<a class="btn btn-success w-100" href="{!! route('screens.createOneParam',$computers->id)!!}">Nueva pantalla</a>
			</div>

</div>

&nbsp;<br>
<div class="content">
	<div class="clearfix"></div>

	@include('flash::message')

	<div class="clearfix"></div>
	<div class="box box-primary">
		<div class="box-body">
			@include('screen.table')
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$("#company").change(function(event){
		$.get("{{route('computers.store_id').'?id='}}"+event.target.value,function(response,company){
				$("#store").empty();
				for(i=0; i<response.length; i++){
					$("#store").append("<option value='"+ response[i].id+"'> "+response[i].name+"</option>");
				}
		});
});

$("#company").val();
</script>

@endsection
