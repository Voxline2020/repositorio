@extends('layouts.principal')



@section('content')
<hr>
<div class="panel-heading">
	<h1>Listar compañias</h1>
</div>
<div class="panel-body">

	<div class="table-responsive">
		@if($registro_company)
		{{ Form::open(['route' => 'companies.index', 'method' => 'GET']) }}

		{{ Form::close() }}

		<table class="table">
			<thead>

				<tr>
					<td>id</td>
					<td>nombre</td>
					<td>slug</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
				@foreach($registro_company as $row)

				<tr>
					<td>{{ $row->id }}</td>
					<td>{{ $row->name }}</td>
					<td>{{ $row->slug }}</td>
					<td>
						<a href="{{ route('companies.destroy', $row->id) }}" class="btn btn-danger">eliminar</a>
						<a href="{{ action('CompanyController@edit',$row['id']) }}" class="btn btn-warning">editar</a>
						<a href="{{ route('store.listar2', $row->id) }}" class="btn btn-primary">tiendas</a>

					</td>
				</tr>
			</tbody>

			@endforeach
		</table>
		@endif



@endsection
