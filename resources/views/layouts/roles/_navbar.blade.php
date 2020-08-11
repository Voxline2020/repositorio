@auth
<nav class="navbar navbar-expand-lg mb-2" style="background-color: #353535">
	<a class="navbar-brand" href="{{ route('dash') }}">
		<img src="{{ asset('assets/logo.png') }}" alt="" height="40" width="auto">
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01"
		aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarColor01">
		<ul class="navbar-nav ml-auto">
			@if(!Auth::user()->hasRole('Administrador'))
			@if(!Auth::user()->hasRole('Terreno'))
			<!--Alertas -->
			<li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
					<i class="far fa-bell"></i>
					@php
					$screensalert =  App\Models\Screen::with(['computer','computer.store'])->whereHas('computer', function ($query) {
						$query->whereHas('store', function ($query) {
							$query->where('company_id', Auth::user()->company_id);
						});
					})->get();
					$alert1 = $screensalert->where('state', 0)->count();
					$alerts = $alert1;
					@endphp
					@if($alerts>0)
					<span class="badge badge-danger navbar-badge">{{ $alerts }}</span>
					@endif
				</a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
					<span style="text-align: center" class="dropdown-item dropdown-header"><i class="fas fa-exclamation-circle"></i> @if($alerts>0){{ $alerts }} @else Sin @endif Alerta(s)</span>
					{{-- Pantallas inactivas --}}
					@foreach($screensalert as $screen)
						@if($screen->state==0)
						<div class="dropdown-divider"></div>
						<a href="{{ route('clients.show',[$screen->id]) }}" class="dropdown-item">
							<i class="fas fa-desktop mr-2"></i> Pantalla "{!! $screen->name !!}" Inactiva
							<!--span class="float-right text-muted text-sm"> 3 mins</span-->
						</a>
						@endif
					@endforeach
					{{-- Fin Pantallas inactivas --}}
					{{-- Footer (ver todo) --}}
          {{-- <div class="dropdown-divider"></div>
          <a style="text-align: center" href="" id="ver_todo" class="dropdown-item dropdown-footer">Ver todo</a>
					</div> --}}
					{{-- Fin Footer --}}
      </li>
			<!--/Fin Alertas -->
			@endif
			@endif
			<li class="nav-item {{ Route::is('dash') ? 'active': null }}">
				<a class="nav-link" href="{{route('dash') }}">Inicio</a>
			</li>
			@if (Auth::user()->hasRole('Administrador'))
				<li class="nav-item {{ Route::is('users.index') ? 'active': null }}">
					<a class="nav-link" href="{{ route('users.index') }}">Usuarios</a>
				</li>
			@elseif(Auth::user()->hasRole('Cliente'))
				<li class="nav-item {{ Route::is('events.clients.index') ? 'active': null }}">
					<a class="nav-link" href="{{ route('clients.events.index') }}">Eventos</a>
				</li>
			@elseif(Auth::user()->hasRole('Supervisor'))
				<li class="nav-item {{ Route::is('clients.events.index') ? 'active': null }}">
					<a class="nav-link" href="{{ route('clients.events.index') }}">Eventos</a>
				</li>
			@endif
			<div class="nav-item dropdown">
				<button class="btn nav-link dropdown-toggle" data-toggle="dropdown" >
					<span id="spanname class="hidden-xs">{{Auth::User()->name.' '.Auth::User()->lastname}}</span>
					<img src="{{ asset('assets/user.png') }}" width="25" height="25" class="rounded-circle">
				</button>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="#" data-toggle="modal" data-target="#exampleModal">Cambiar contraseña</a>
					<div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}">{{ __('Cerrar sesion') }}</a>
				</div>
			</div>
		</ul>
		</ul>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				@php
						$users =  App\Models\User::all();
				@endphp
				{{ Form::open(['route' =>['users.changePassword'], 'method' => 'GET']) }}
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Cambiar contraseña</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					{!! Form::input('password','passOld',null, ['class' => 'form-control','placeholder'=>'Contraseña Actual', 'required'=>'required']) !!}
					<hr>
					{!! Form::input('password','passNew',null, ['class' => 'form-control','placeholder'=>'Contraseña Nueva','required'=>'required']) !!}
					<br>
					{!! Form::input('password','passNewVerify',null, ['class' => 'form-control','placeholder'=>'Repita la Contraseña Nueva','required'=>'required']) !!}
				</div>
				<div class="modal-footer">
					{!! Form::submit('Cambiar', ['class' => 'btn btn-success']) !!}
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
	<!-- Fin Modal -->
</nav>
@endauth
