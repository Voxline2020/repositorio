@extends('layouts.principal')

@section('content')

<div class="row">

@foreach ($screens as $screen)
<div class="col-md-4 p-1">
	<div class="card">
		<img src="https://picsum.photos/400/200" class="card-img-top" alt="...">
		<div class="card-body">
			<h5 class="card-title text-center">
				<b>Pantalla:</b> {{ $screen->name }}
			</h5>
			<div class="row p-0 m-0">
				<div class="col-lg-12 py-1 px-1">
					<a href="{{ route('screens.show', [$screen]) }}" class="btn btn-success w-100">Ver Detalle</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endforeach


</div>

@endsection
