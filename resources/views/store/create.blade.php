@extends('layouts.principal')

@section('content')
    <section class="content-header">
        <h2>
            Nueva Sucursal
        </h2>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'stores.store']) !!}

                        @include('store.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
