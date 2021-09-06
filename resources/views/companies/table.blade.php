<div class="table-responsive">
	<table class="table table-hover">
		<thead class="thead-dark">
			<tr>
				<th>Nombre</th>
				<th>Nº Sucursales <i class="fas fa-store"></i></th>
				{{-- <th>Nº Incidencias <i class="fas fa-fire"></i></th> --}}
				<th>Nº Eventos <i class="fas fa-calendar"></th>
				<th>Nº Computadores <i class="fas fa-desktop"></i></th>
				<th>Nº Pivotes <i class="fas fa-server"></i></th>
				<th colspan="1">Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($companies as $company)
			@php
			$computersCount=0;
			foreach($computers AS $computer){
				if($computer->store->company_id==$company->id){
					$computersCount=$computersCount+1;
				}
			}
			$pivotCount=0;
			foreach($pivots AS $pivot){
				if($pivot->company_id==$company->id){
					$pivotCount=$pivotCount+1;
				}
			}
			$storeCount = 0;
			if($company->stores != null){
				$storeCount = $company->stores->count();
			}
			$eventCount = 0;
			if($company->events != null){
				$eventCount = $company->events->count();
			}
			@endphp
			<tr>
				<td>{!! $company->name !!}</td>
				<td style="text-align: center" class="{!! $storeCount== 0 ? 'red-text': '' !!}">
					{!! $storeCount !!}
				</td>
				<td style="text-align: center" class="{!! $eventCount == 0 ? 'red-text': '' !!}">
					{!! $eventCount !!}
				</td>
				<td style="text-align: center" class="{!! $computersCount == 0 ? 'red-text': '' !!}">
					{!! $computersCount !!}
				</td>
				<td style="text-align: center" class="{!! $pivotCount == 0 ? 'red-text': '' !!}">
					{!! $pivotCount !!}
				</td>
				<td>
					<div class='btn-group'>
						<a href="{{ route('companies.stores.index', [$company]) }}" class="btn btn-success w-100"><i class="fas fa-store"></i></a>
						{{-- <a href="#" class="btn btn-danger w-100"><i class="fas fa-fire"></i></a> --}}
						<a href="{{ route('companies.events.index', [$company]) }}" class="btn btn-warning w-100"><i class="fas fa-calendar"></i></a>
						<a href="{{ route('companies.computers.index', [$company]) }}" class="btn btn-primary w-100"><i class="fas fa-desktop"></i></a>
						<a href="{{ route('companies.pivots.index', [$company]) }}" class="btn btn-dark w-100"><i class="fas fa-server"></i></a>
					</div>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	{{ $companies->links() }}
</div>
