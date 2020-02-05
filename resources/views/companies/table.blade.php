{{-- @foreach ($companies as $company)
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
				<div class="col-lg-6 py-1 px-1">
							<a href="{{ route('computers.index', [$company]) }}" class="btn btn-primary w-100">Computadores</a>
					</div>
					<div class="col-lg-6 py-1 px-1">
						<a href="{{ route('pivots.index', [$company]) }}" class="btn btn-dark w-100"><i class="fas fa-server"></i></a>
					</div>
			</div>
		</div>
	</div>
</div>
@endforeach --}}

{{-- @if(Auth::user()->hasRole('Administrador'))
<div class="col-md-4 p-1">
	<div class="card h-100 ">
		<a href="{!! route('companies.create') !!}"
			class="btn btn-success w-100 h-100 d-flex justify-content-center align-items-center font-weight-bolder"
			style="font-size:3rem">+</a>
	</div>
</div>
@endif --}}
<div class="table-responsive">
	<table class="table table-hover">
		<thead class="thead-dark">
			<tr>
				<th>Nombre</th>
				<th>Nº Sucursales <i class="fas fa-store"></i></th>
				<th>Nº Incidencias <i class="fas fa-fire"></i></th>
				<th>Nº Eventos <i class="fas fa-calendar"></th>
				<th>Nº Computadores <i class="fas fa-desktop"></i></th>
				<th>Nº Pivotes <i class="fas fa-server"></i></th>
				<th colspan="1">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($companies as $company)
			<tr>
				<td>{!! $company->name !!}</td>
				<td style="text-align: center" class="{!! $company->stores->count() == 0 ? 'red-text': '' !!}">
					{!! $company->stores->count() !!}
				</td>
				<td style="text-align: center; color: red;">1</td>
				<td style="text-align: center" class="{!! $company->events->count() == 0 ? 'red-text': '' !!}">
					{!! $company->events->count() !!}
				</td>
				<td style="text-align: center" class="{!! $computers->count() == 0 ? 'red-text': '' !!}">
					{!! $computers->count() !!}
				</td>
				<td style="text-align: center">0</td>
				<td>
					<div class='btn-group'>
						<a href="{{ route('companies.stores.index', [$company]) }}" class="btn btn-success w-100"><i class="fas fa-store"></i></a>
						<a href="#" class="btn btn-danger w-100"><i class="fas fa-fire"></i></a>
						<a href="{{ route('companies.events.index', [$company]) }}" class="btn btn-warning w-100"><i class="fas fa-calendar"></i></a>
						<a href="{{ route('computers.index', [$company]) }}" class="btn btn-primary w-100"><i class="fas fa-desktop"></i></a>
						<a href="{{ route('companies.pivots.index', [$company]) }}" class="btn btn-dark w-100"><i class="fas fa-server"></i></a>
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	{{ $companies->links() }}
</div>
