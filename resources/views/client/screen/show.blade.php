@extends('layouts.principal')

@section('content')
    <section class="content-header">
        <h1>
            Pantalla: {!! $screen->name !!}
        </h1>

    </section>
    <div class="row my-lg-4 my-md-4 my-sm-1">
        <div class="col-md-3">
            <p><b>Sector:</b> {!! $screen->sector !!}</p>
        </div>
        <div class="col-md-3">
            <p><b>Piso:</b> {!! $screen->floor !!}</p>
        </div>
        <div class="col-md-3">
            <p><b>Tipo:</b> {!! $screen->type !!}</p>
        </div>
        <div class="col-md-3">
            {!! Form::model($screen, ['route' => ['screens.changeStatus', $screen->id], 'method' => 'put']) !!}
                @if($screen->state==0)
                    {!! Form::hidden('state', 1) !!}
                    <b>Estado: </b>{!! Form::submit('Inactivo', ['class' => 'btn btn-danger']) !!}
                @endif
                @if($screen->state==1)
                    {!! Form::hidden('state', 0) !!}
                    <b>Estado: </b>{!! Form::submit('Activo', ['class' => 'btn btn-success']) !!}
                @endif
            {!! Form::close() !!}
        </div>
    </div>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('client.screen.show_fields')
                    <a href="{{ URL::previous() }}" class="btn btn-info">Atras</a>
                </div>
                
            </div>
        </div>
    </div>


@endsection
