@extends('layouts.principal')

@section('content')
    <section class="content-header">
        <h1>
            {!! $playlist->name !!}
        </h1>

    </section>
    @foreach($playlist->versionPlaylists AS $versionPlaylist)
    @endforeach
    <div class="row my-lg-4 my-md-4 my-sm-1">
        <div class="col-md-4">
            <p><b>Version actual:</b> {!! $versionPlaylist->version !!}</p>
        </div>
        <div class="col-md-4 ">
            <b>Estado: </b>
            <a class="{{ $versionPlaylist->state == "1" ? "green-text" : "red-text" }}">
            @if($versionPlaylist->state == 1)
            Activa
            @elseif($versionPlaylist->state == 0)
            Inactiva
            @endif
            </a>
        </div>
        <div class="col-md-4">
            <p><b>Slug:</b> {!! $playlist->slug !!}</p>
        </div>
        <div class="col-md-4">
            <p><b>Descripcion:</b> {!! $playlist->description !!}</p>
        </div>
        <div class="col-md-4">
            <p><b>Creada:</b> {!! $playlist->created_at !!}</p>
        </div>
    </div>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('playlists.show_fields')
                    <a href="{!! route('playlists.index') !!}" class="btn btn-info">Atras</a>
                </div>
                
            </div>
        </div>
    </div>


@endsection
