@auth
<nav class="navbar navbar-expand-lg navbar-dark grey darken-2 mb-2">
	<a class="navbar-brand" href="{{ route('dash') }}">
		<img src="{{ asset('assets/logo.png') }}" alt="" height="40" width="auto">
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01"
		aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarColor01">
		<ul class="navbar-nav ml-auto">
			<li class="nav-item {{ Route::is('dash') ? 'active': null }}">
				<a class="nav-link" href="{{route('dash') }}">Inicio</a>
			</li>
			@if (Auth::user()->hasRole('Administrador'))
				<li class="nav-item {{ Route::is('playlist.index') ? 'active': null }}">
					<a class="nav-link" href="{{ route('playlists.index') }}">Playlists</a>
				</li>
				{{-- <li class="nav-item {{ Route::is('events.index') ? 'active': null }}">
					<a class="nav-link" href="{{ route('eventsOld.index') }}">Eventos</a>
				</li> --}}
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
			<li class="nav-item">
				<a class="nav-link" href="{{ route('logout') }}">{{ __('Cerrar sesion') }}</a>
			</li>
		</ul>
		</ul>
	</div>
</nav>
@endauth
