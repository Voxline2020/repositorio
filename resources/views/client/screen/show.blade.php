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
				<div class="col-md-3">
					<p><b>Resolución: </b> {!! $screen->width !!}x{!! $screen->height !!}</p>
			</div>
			<div class="col-md-3">
				<p><b>Version: </b>@if($screen->version==null) N/A @else{!! $screen->version !!}@endif</p>
		</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				@include('flash::message')
			</div>
		</div>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('client.screen.show_fields')
                </div>
            </div>
        </div>
    </div>
@endsection

