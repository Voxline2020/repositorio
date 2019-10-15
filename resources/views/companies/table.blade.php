@foreach ($companies as $company)
<div class="col-md-4 p-1">
	<div class="card">
		<img src="https://picsum.photos/400/200" class="card-img-top" alt="...">
		<div class="card-body">
			<h5 class="card-title">{{ $company->name }}</h5>
			<div class="row p-0 m-0">
				<div class="col-lg-6 py-1 px-1">
					<a href="{{ route('companies.stores.index', [$company]) }}" class="btn btn-success w-100">Sucursales</a>
				</div>
				<div class="col-lg-6 py-1 px-1">
					<a href="#" class="btn btn-danger w-100">Incidencias</a>
				</div>
				<div class="col-lg-6 py-1 px-1">
					<a href="{{ route('companies.events.index', [$company]) }}" class="btn btn-warning w-100">Eventos</a>
				</div>
				{{-- <div class="col-lg-6 py-1 px-1">
						<a href="#" class="btn btn-primary w-100">Go somewhere</a>
				</div> --}}
			</div>
		</div>
	</div>
</div>

@endforeach

<div class="col-md-4 p-1">
	<div class="card h-100 ">
		<a href="{!! route('companies.create') !!}"
			class="btn btn-success w-100 h-100 d-flex justify-content-center align-items-center font-weight-bolder"
			style="font-size:3rem">+</a>
	</div>
</div>
