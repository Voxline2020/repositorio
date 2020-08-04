<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="UTF-8">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">


	<title>{{ config('app.name', 'VxCMS') }}</title>

	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">


	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
	<link rel="stylesheet" href="{{ asset('css/all.css') }}">
	<link rel="stylesheet" href="{{ asset('css/colors.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/fontawesome5/css/all.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/select2/css/select2-bootstrap4.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/dropzonejs/dropzone.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/tempusdominus/css/tempusdominus-bootstrap-4.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/jquery.datatable/css/jquery.dataTables.css') }}">


	<!-- css personalisado por gustavo -->
	<link rel="stylesheet" href="{{ asset('css/clientStores.css') }}">

</head>
<body>
	<div id="app">
		@auth
		@include('layouts.roles._navbar')
		@endauth
		
	</div>

	<script src="{{ asset('vendor/fontawesome5/js/all.js') }}"></script>
	<script src="{{ asset('js/vendor/moments/momentjs-with-locales.js') }}"></script>
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('vendor/dropzonejs/dropzone.js') }}"></script>
	<script src="{{ asset('vendor/select2/js/select2.js')}}"></script>
	<script src="{{ asset('vendor/tempusdominus/js/tempusdominus-bootstrap-4.js')}}"></script>
	<script src="{{ asset('vendor/moment/moment-with-locales.js')}}"></script>
	<script src="{{ asset('vendor/jquery.datatable/js/jquery.dataTables.js')}}"></script>

	< <script src="{{ asset('js/clientStore.js') }}"></script> 
	<!-- Languaje -->
	<script>
		$(function () {
			$('.js-select2').select2({
				tags: false,
				theme: 'bootstrap4',
			});
		});
	</script>

	<!-- inicio drop funciones -->
	

<!-- Menu lateral  -->
	<div class="page-wrapper chiller-theme toggled col-sm-3 col-md-3" >
	  <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
	    <i class="fas fa-bars"></i>
	  </a>
	  <nav id="sidebar" class="sidebar-wrapper">
	    <div class="sidebar-content">
		      <!-- Start TTILE  -->
		      <div class="sidebar-brand">
		        <a href="#">Sucursales</a>        
		      </div>
		      <!-- End TITLE  -->
		      <!-- Start sidebar-search  -->
		    <!--<div class="sidebar-search">
			        <div>
			          <div class="input-group">
			            <input type="text" id="search" class="form-control search-menu" placeholder="Search...">
			            <div class="input-group-append">
			              <span class="input-group-text">
			                <i class="fa fa-search" aria-hidden="true"></i>
			              </span>
			            </div>
			          </div>
			        </div> 
		    </div>--> <!-- END sidebar-search  -->
      	
      	<!-- Star sidebar-Stores  -->
      
		      @foreach ($stores as $store)							
			      <div class="sidebar-header" >			
				       <div class="user-pic" >
				          <img onclick="openStore('{{$store->id}}');"  class="img-responsive img-rounded" src="{{ asset('assets/tienda.jpg') }}" alt="User picture">	        	
				        </div>
				        <div class="user-info">
				          <span class="user-name"> <strong>{{$store->name}}</strong> </span>
				          <span class="user-role">{{$store->address}}</span>				          
				        </div>
			      </div>
		      @endforeach

	      <!-- END sidebar-Stores  -->      
	      <!-- sidebar-menu  -->
	    </div> <!-- end sidebar-cotent" -->
	    <!-- sidebar-content  -->
	    
	   </nav> <!-- end nav -->
	</div> <!-- END menu lateral  -->
	
	<!-- mensajes de error --> 
	 @if (\Session::has('error'))
	    <div class="alert alert-danger" id="mensaje">	        
	            {!! \Session::get('error') !!}
	    </div>
	@endif
	@if (\Session::has('success'))
	    <div class="alert alert-success" id="mensaje">	        
	            {!! \Session::get('success') !!}
	    </div>
	@endif

	<style> 

	</style>
	<div id = "titulo">
		<h3>Seleccione una sucursal en el menu de la izquierda</h3>
	</div>
	<div id ="contenedor_ajax" class="container">
		<div class="row" id="devices">							
				
		</div>
	</div>


		<!--
	 	<div id="response-container" class="response-container col-sm-6 col-md-9"  >
        	<h3>Seleccione una sucursal en el menu de la izquierda</h3>
     	</div>	 -->
     	

	
	
      	
  <!-- page-content" -->
</div>
<!-- page-wrapper -->

<!-- modal -->
	<div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">		
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header col-12">
		      	<h4 class="modal-title" id="myModalLabel">Cargar contenido</h4>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		        
		      </div>
			    <div class="modal-body">
			      	<form action="/asignarContenido" id="asignarContenido" method="post" enctype="multipart/form-data">
						<div id="form-group" >
							@csrf
							<input type="hidden"  class="form-control-file" id="device_id" name = "device_id">
							<input type="hidden"  class="form-control-file" id="device_width" name = "device_width">							
							<input type="hidden"  class="form-control-file" id="device_height" name = "device_height">											
							<input type="hidden"  class="form-control-file" id="event_id" name = "event_id">											
							<input type="hidden"  class="form-control-file" id="events">								
							<div class="row align-items-center">
								<div class="col-1">
								</div>								
								<div class="col-10">
									<input type="file" class="form-control-file" id="contenido" name = "contenido" accept="video/mp4,video/x-m4v,video/*"   required>
								</div>
								<div class="col-1">
								</div>
							</div>
							<hr>
							<div class="row align-items-center">
								<div class="col-1">
								</div>
								<div class="col-4" >
									Nombre Evento : 
								</div>
								<div class="col-6">
									<!--<input type="text" class="form-control" id="event_name" name = "event_name" required> -->

									<input list="event_name" name="event_name" type="text"  id="list_events" >
									<datalist id="event_name">										
										<?php foreach ($events as $event): ?>
											<option value="{{$event->name}}"></option>	
										<?php endforeach ?>
									    									    
									</datalist>
								</div>
								<div class="col-1">
								</div>
							</div>
							<hr>
							<div class="row align-items-center">
								<div class="col-1">
								</div>								
								<div class="col-10">
									<div class="form-group">
										<div class="input-group date" id="initdate" data-target-input="nearest">
											    <input type="text" class="form-control datetimepicker-input" data-target="#initdate" name = "initdate" placeholder="Fecha Inicio" id="textinitdate"/>
											    <div class="input-group-append" data-target="#initdate" data-toggle="datetimepicker">
                        						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
                   								 </div>
										</div>
									</div>
								</div>
								                    
								<div class="col-1">
								</div>
							</div>
							<br>
							<div class="row align-items-center">
								<div class="col-1">
								</div>								
								<div class="col-10">
									<div class="form-group">
										<div class="input-group date" id="enddate" data-target-input="nearest">
											    <input type="text" class="form-control datetimepicker-input" data-target="#enddate" name = "enddate" placeholder="Fecha Termino" id="textenddate"/>
											    <div class="input-group-append" data-target="#enddate" data-toggle="datetimepicker">
                        						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
                   								 </div>
										</div>
									</div>
								</div>
								<div class="col-1">
								</div>
							</div>						  	
							<hr>	                    	
			      		</div> <!-- final form group -->
			      		<div class="row align-items-center">
								<div class="col-8">
								</div>								
								<div class="col-2">
									<button type="submit" class="btn btn-primary">Guardar</button>
								</div>
								<div class="col-2">
									<button type="button" class="btn btn-danger " data-dismiss="modal" aria-label="Close">Cerrar </button>
								</div>
							</div>
			      		
			      		
			      	</form> <!-- final form -->
			    </div> <!-- final modal body -->
	
</body>
