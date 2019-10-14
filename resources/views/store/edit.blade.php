@extends('layouts.principal')

@section('content')
    <section class="content-header">
        <h4>
            Editar Sucursal
        </h4>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($store, ['route' => ['stores.update', $store->id], 'method' => 'patch']) !!}

                        @include('store.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
