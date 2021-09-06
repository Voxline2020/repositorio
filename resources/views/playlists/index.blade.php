@extends('layouts.principal')

@section('content')
    <div class="container">
      <div class="row">
          <div class="col-sm-12 col-md-6">
              <h1 class="text-left">Listas de reproduccion</h1>
          </div>
          <div class="col-sm-12 col-md-6">
              <h1 class="text-right">
                  <a class="btn btn-primary pull-right" style="" href="{!! route('playlists.create') !!}">Añadir nuevo</a>
               </h1>
          </div>
          @include('flash::message')
          <div class="col-sm-12">
            @include('playlists.table')
          </div>
      </div>
    </div>
@endsection

