@extends('layouts.principal')

@section('content')
		<div class="container">
				@include('flash::message')
			<div class="row">
					<div class="col-sm-12 col-md-9">
							<h2 class="font-weight-bold">{{ $event->name }} &#x1F4C6; </h2>
					</div>

					<div class="col-sm-12 col-md-3">
							<h1 class="text-right">
									<a class="btn btn-success w-100" href="{{ route('pdf.generate',[$event->id]) }}">Generar PDF</a>
							</h1>
					</div>
				<hr>
					<div class="col-sm-12">
							@include('companies.events.show_fields_detail')
					</div>
			</div>
		</div>
@endsection

