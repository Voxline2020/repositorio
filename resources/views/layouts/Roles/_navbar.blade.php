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
			@if(Auth::user()->hasRole('Supervisor'))
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
					$eventalert = App\Models\Event::where('company_id', Auth::user()->company_id);
					$alert1 = $screensalert->where('state', 0)->count();
					$alert2 = $eventalert->where('state', 0)->count();
					$alerts = $alert1;
					@endphp
					@if($alerts>0) <span class="badge badge-danger navbar-badge">{{ $alerts }}</span>@endif
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
					{{-- Eventos inactivos --}}
					{{-- @foreach($eventalert as $event)
						@if($event->state==0)
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fas fa-calendar-week mr-2"></i> Evento "{!! $event->name !!}" Inactivo
							<!--span class="float-right text-muted text-sm"> 3 mins</span-->
						</a>
						@endif
					@endforeach --}}
          <div class="dropdown-divider"></div>
          <a style="text-align: center" href="" id="ver_todo" class="dropdown-item dropdown-footer">Ver todo</a>
        	</div>
      </li>
			<!--/Fin Alertas -->
			@endif
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
