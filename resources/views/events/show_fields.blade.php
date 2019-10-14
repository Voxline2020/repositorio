
<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $event->name !!}</p>
</div>

<!-- Initdate Datetime(0) Field -->
<div class="form-group">
    {!! Form::label('initdate datetime(0)', 'Initdate Datetime(0):') !!}
    <p>{!! $event->initdate  !!}</p>
</div>

<!-- Enddate Datetime(0) Field -->
<div class="form-group">
    {!! Form::label('enddate datetime(0)', 'Enddate Datetime(0):') !!}
    <p>{!! $event->enddate  !!}</p>
</div>

<!-- State Field -->
<div class="form-group">
    {!! Form::label('state', 'State:') !!}
    <p>{!! $event->state !!}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{!! $event->slug !!}</p>
</div>

