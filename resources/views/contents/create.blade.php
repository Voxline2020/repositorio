@extends('layouts.principal')

@section('content')
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1>
            Contenidos
          </h1>
        </div>
        <div class="col-sm-12">
            {!! Form::open(['route' => 'contents.store', 'files' => true]) !!}
            <div class="row">
                @include('contents.fields')
            </div>
           {!! Form::close() !!}
        </div>
      </div>
    </div>
@endsection




