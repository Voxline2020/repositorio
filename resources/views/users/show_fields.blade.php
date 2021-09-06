
<div class="col-md-6">
	<p><b>RUT:</b> {!! $user->rut !!}</p>
</div>
<div class="col-md-6">
	<p><b>Email:</b> {!! $user->email !!}</p>
</div>
<div class="col-md-6">
	<p><b>Creado:</b> {!! $user->created_at !!}</p>
</div>
<div class="col-md-6">
	<p><b>Modificado:</b> {!! $user->updated_at !!}</p>
</div>

@if($user->hasRole('Cliente'))
	@include('users.clients.show_fields')
@else

@endrole
