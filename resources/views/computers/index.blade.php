@extends('layouts.principal')
@section('content')
<div class="row">
	<div class="col-md-9 text-left">
		<h2 class="font-weight-bold">Computadores &#128187;</h2>
	</div>
	<div class="col-md-3 text-right">
		<a class="btn btn-success w-100" href="{!! route('computers.create') !!}">Nuevo Computador</a>
	</div>
</div>
<br><br>
{{ Form::open(['route' =>'computers.filter_by_company_store', 'method' => 'GET']) }}
<div class="row">
	<div class="col-md-3">
		<select name="company" id="company" class="form-control">
			<option null selected disabled>Empresa</option>
			@foreach ($lists as $list)
			<option value="{{$list->id}}">{{$list->name}}</option>
			@endforeach
		</select>
	</div>
	<div class="col-md-3">
		{!! Form::select('store',['placeholder'=>'Tienda/Sucursal'],null,
		['id'=>'store', 'class'=>'browser-default custom-select'])!!}
	</div>
	<div class="col-md-3">
		<select name="type" id="type" class="form-control">
			<option null selected disabled>Tipo de acceso</option>
			@foreach ($types as $type)
			<option value="{{$type->id}}">{{$type->name}}</option>
			@endforeach
		</select>
	</div>
	<div class="col-md-3">
		<button type="submit" class="btn btn-primary w-100">Filtrar </button>
	</div>
</div>
{!! Form::close() !!}
<br>
{{ Form::open(['route' =>'computers.filter_by_name', 'method' => 'GET']) }}

<div class="row">
	<div class="col-md-9">
		{!! Form::text('codeFiltrar',null, ['class'=> 'form-control', 'placeholder' => 'buscar computador por codigo']) !!}
	</div>
	<div class="col-md-3">
		<button type="submit" class="btn btn-primary w-100">Buscar </button>
	</div>
</div>
<br>
<div class="col-md-2">
	<button type="button" class="btn btn-primary w-100" onclick="location.href='/computers'">Limpiar </button>
</div>
{!! Form::close() !!}
<br>
&nbsp;&nbsp;
<br>
&nbsp;&nbsp;
</td>
</section>
<br>
<div class="content">
	<div class="clearfix"></div>

	@include('flash::message')

	<div class="clearfix"></div>
	<div class="box box-primary">
		<div class="box-body">
			<div class="col-sm-12">
				@include('computers.table')
			</div>
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
