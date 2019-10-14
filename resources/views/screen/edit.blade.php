@extends('layouts.principal')

@section('content')
    <section class="content-header">
        <h4>
            Editar Pantalla
        </h4>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($screen, ['route' => ['screens.update', $screen->id], 'method' => 'patch']) !!}

                        @include('screen.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
