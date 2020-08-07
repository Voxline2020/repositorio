		<div class="menulateral">
			<!--<hr>
			<label href="/clients">  <strong> Inicio </strong> </label> -->
			<hr>
			<a href="#" onclick="indexStore();"> <strong> Sucursales </strong>  </a> 			
			
			<div id="liststores" style="display: none;">	
				<ul>
				@foreach ($stores as $store)										      
				<hr>
				    <div style="margin-left: 0px">
					  <li>  <a href="#" onclick="openStore('{{$store->id}}');" class="user-name"> <strong>{{$store->name}}</strong> </a> </li>
					    <!--<span class="user-role">{{$store->address}}</span>	-->          
				    </div>
			    @endforeach
			    <ul>
		    </div>		    
		    <hr>
		    
							
			@if (Auth::user()->hasRole('Administrador'))					
					<a href="{{ route('users.index') }}"> <strong> Eventos </strong> </a> 								
			@elseif(Auth::user()->hasRole('Cliente'))
				
					<a href="{{ route('clients.events.index') }}"> <strong> Eventos </strong> </a> 
				
			@elseif(Auth::user()->hasRole('Supervisor'))				
					<a href="{{ route('clients.events.index') }}"> <strong> Eventos </strong> </a> 
			@endif

		    <hr>
		</div>