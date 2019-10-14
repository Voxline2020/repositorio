@extends('layouts.principal')

@section('content')
    <section class="content-header">
        <h1>
            Content
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('contents.show_fields')
                    <a href="{!! route('contents.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>


@endsection
