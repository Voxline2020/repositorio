@extends('layouts.principal')


@section('content')

<div class="row">
	<div class="col-md-9">
		@if(Auth::user()->hasRole('Administrador'))
		<h2 class=font-weight-bold> Compañias &#127970; </h2>
		@elseif(Auth::user()->hasRole('Supervisor'))
			@foreach ($companies as $company)
				@if($company->id ==  auth()->user()->company_id)
					<h2 class=font-weight-bold> {{ $company->name }} &#127970; </h2>
				@endif
			@endforeach
		@endif
	</div>
	<div class="col-md-12">
		@include('flash::message')
	</div>
</div>

<div class="row">
	@include('companies.table')
</div>
@endsection

