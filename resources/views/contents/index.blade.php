@extends('layouts.principal')

@section('content')
    <div class="container">
				@include('flash::message')
      <div class="row">
          <div class="col-sm-12 col-md-9">
              <h2 class=font-weight-bold>Contenidos</h2>
          </div>
          <div class="col-sm-12 col-md-3">
              <h1 class="text-right">
                  <a class="btn btn-success pull-right w-100" style="" href="{!! route('contents.create') !!}">Añadir nuevo</a>
               </h1>
          </div>
          <div class="col-sm-12">
            @include('contents.table')
					</div>
					<div class="col-sm-12 col-md-3">
              <h1 class="text-right">
                  <a class="btn btn-danger pull-right w-100" style="" href="{!! route('pdf.generateContent') !!}">Generar PDF</a>
               </h1>
          </div>
      </div>
    </div>
@endsection

