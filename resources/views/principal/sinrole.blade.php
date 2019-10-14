@extends('layouts.principal')

@section('content')

<div class="row">
	<div class="col-md-12 text-center">
		<p>Aun no se le ha asignado ningun rol, contacte con el administrador</p>
		<a class="btn btn-danger" href="{{ route('logout') }}" role="button">Cerrar sesion</a>
	</div>
</div>


@endsection
