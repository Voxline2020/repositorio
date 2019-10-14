@extends('layouts.principal')

@section('content')
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          @include('adminlte-templates::common.errors')
        </div>
        <div class="col-sm-12">
          <h1>
            Playlist
          </h1>
        </div>
        <div class="col-sm-12">
            {!! Form::open(['route' => 'playlists.store']) !!}
            <div class="row">
                @include('playlists.fields')
            </div>
           {!! Form::close() !!}
        </div>
      </div>
    </div>
@endsection




