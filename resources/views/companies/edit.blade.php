@extends('layouts.principal')

@section('content')
    <section class="content-header">
        <h1>
            Compañia
        </h1>
   </section>
   <div class="content">

       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($company, ['route' => ['companies.update', $company->id], 'method' => 'patch']) !!}

                        @include('companies.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
