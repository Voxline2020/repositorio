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
	      
	    </div> <!-- end sidebar-cotent" -->
	    
	    
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
	<a  style="display: none;" id="btnback" type="button" class="btn btn-outline-primary w-100" href="/clients ">Atras</a>
	<div id ="contenedor_ajax" class="container">
		<div class="row" id="devices">							
				
		</div>
	</div>

	
      	
  <!-- page-content" -->
</div>
<!-- page-wrapper -->

<!-- modal -->
	<div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">		
		 <div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<!-- modal header -->
		      	<div class="modal-header col-12">
		      		<h4 class="modal-title" id="myModalLabel">Cargar contenido</h4>
		        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          		<span aria-hidden="true">&times;</span>
		        	</button>
		        
		      	</div><!-- fin header -->

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
							</div> <!-- fin row -->
							<hr>
							<div class="row align-items-center">
								<div class="col-1">
								</div>
								<div class="col-4" >
									Nombre Evento : 
								</div>
								<div class="col-6">
									<!--<input type="text" class="form-control" id="event_name" name = "event_name" required> -->

									<input list="event_name" name="event_name" type="text"  id="list_events" required>
									<datalist id="event_name">										<?php foreach ($events as $event): ?>
											<option value="{{$event->name}}"></option>	
										<?php endforeach ?>
									    									    
									</datalist>
								</div>
								<div class="col-1">
								</div>
							</div> <!-- fin row -->
							<hr>
							<div class="row align-items-center">
								<div class="col-1">
								</div>								
								<div class="col-10">
									<div class="form-group">
										<div class="input-group date" id="initdate" data-target-input="nearest" >
											    <input type="text" class="form-control datetimepicker-input" data-target="#initdate" name = "initdate" placeholder="Fecha Inicio" required id="textinitdate"/>
											    <div class="input-group-append" data-target="#initdate" data-toggle="datetimepicker">
                        						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
                   								 </div>
										</div>
									</div>
								</div>
								                    
								<div class="col-1">
								</div>
							</div> <!-- fin row-->
							<br>
							<div class="row align-items-center">
								<div class="col-1">
								</div>								
								<div class="col-10">
									<div class="form-group">
										<div class="input-group date" id="enddate" data-target-input="nearest" >
											    <input type="text" class="form-control datetimepicker-input" data-target="#enddate" name = "enddate" placeholder="Fecha Termino" required  id="textenddate"/>
											    <div class="input-group-append" data-target="#enddate" data-toggle="datetimepicker">
                        						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
                   								 </div>
										</div>
									</div>
								</div>
								<div class="col-1">
								</div>
							</div>	<!-- fin row -->					  	
							<hr>	                    	
			      		</div> <!-- final form group -->
			      		<div class="row align-items-center" >
								<div class="col-8" >
								</div>								
								<div class="col-2" onclick="openGift();">
									<button type="submit" class="btn btn-primary">Guardar</button>
								</div>
								<div class="col-2">
									<button type="button" class="btn btn-danger " data-dismiss="modal" aria-label="Close">Cerrar </button>
								</div>
						</div> <!-- final row -->
			      	</form> <!-- final form -->
			    </div> <!-- final modal body -->
			</div> <!-- fin modal content -->
		</div> <!-- fin mdal dialog -->
	</div> <!-- fin modal -->
<!-- modal gif -->
<div class="modal fade" tabindex="-1" role="dialog" id="giftModal"> <!-- modal gift cargando -->
  <div class="modal-dialog" role="document" >
    <div class="modal-content" style="background-color: rgba(0,0,0,.0001); border: 0;" >
      
      <div class="modal-body">
        <img src="https://acegif.com/wp-content/uploads/loading-13.gif" width="357" height="357" style="position: absolute; top: 0px; left: 0px;">
      </div>
      
    </div>
  </div> <!-- fin modal gift cargando -->
</div> <!-- fin modal gif -->
</body>

<style type="text/css">
	/*Primer menu lateral----------toggeled sidebar----------------*/

  @keyframes swing {
    0% {
      transform: rotate(0deg);
    }
    10% {
      transform: rotate(10deg);
    }
    30% {
      transform: rotate(0deg);
    }
    40% {
      transform: rotate(-10deg);
    }
    50% {
      transform: rotate(0deg);
    }
    60% {
      transform: rotate(5deg);
    }
    70% {
      transform: rotate(0deg);
    }
    80% {
      transform: rotate(-5deg);
    }
    100% {
      transform: rotate(0deg);
    }
  }

  @keyframes sonar {
    0% {
      transform: scale(0.9);
      opacity: 1;
    }
    100% {
      transform: scale(2);
      opacity: 0;
    }
  }
  body {
    font-size: 0.9rem;
  }
  .page-wrapper .sidebar-wrapper,
  .sidebar-wrapper .sidebar-brand > a,
  .sidebar-wrapper .sidebar-dropdown > a:after,
  .sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu li a:before,
  .sidebar-wrapper ul li a i,
  .page-wrapper .page-content,
  .sidebar-wrapper .sidebar-search input.search-menu,
  .sidebar-wrapper .sidebar-search .input-group-text,
  .sidebar-wrapper .sidebar-menu ul li a,
  #show-sidebar,
  #close-sidebar {
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
  }

  /*----------------page-wrapper----------------*/

  /*.page-wrapper {
    height: 100vh;
  } */

  .page-wrapper .theme {
    width: 40px;
    height: 40px;
    display: inline-block;
    border-radius: 4px;
    margin: 2px;
  }

  .page-wrapper .theme.chiller-theme {
    background: #1e2229;
  }

  /*----------------toggeled sidebar----------------*/

  .page-wrapper.toggled .sidebar-wrapper {
    left: 0px;
  }

  @media screen and (min-width: 768px) {
    .page-wrapper.toggled .page-content {
      padding-left: 300px;
    }
  }
  /*----------------show sidebar button----------------*/
  #show-sidebar {
    position: fixed;
    left: 0;
    top: 10px;
    border-radius: 0 4px 4px 0px;
    width: 35px;
    transition-delay: 0.3s;
  }
  .page-wrapper.toggled #show-sidebar {
    left: -40px;
  }
  /*----------------sidebar-wrapper----------------*/

  .sidebar-wrapper {
    margin-top: -32px;
    width: 260px;
    height: 592px;
    
    position: absolute;
    top: 0;
    left: -300px;
    z-index: 999;
  }

  .sidebar-wrapper ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
  }

  .sidebar-wrapper a {
    text-decoration: none;
  }

  /*----------------sidebar-content----------------*/

  .sidebar-content {
    max-height: calc(100% - 30px);
    height: calc(100% - 30px);
    overflow-y: auto;
    position: relative;
  }

  .sidebar-content.desktop {
    overflow-y: hidden;
  }

  /*--------------------sidebar-brand----------------------*/

  .sidebar-wrapper .sidebar-brand {
    padding: 10px 20px;
    display: flex;
    align-items: center;
  }

  .sidebar-wrapper .sidebar-brand > a {
    text-transform: uppercase;
    font-weight: bold;
    flex-grow: 1;
  }

  .sidebar-wrapper .sidebar-brand #close-sidebar {
    cursor: pointer;
    font-size: 20px;
  }
  /*--------------------sidebar-header----------------------*/

  .sidebar-wrapper .sidebar-header {
    padding: 20px;
    overflow: hidden;
  }

  .sidebar-wrapper .sidebar-header .user-pic {
    float: left;
    width: 60px;
    padding: 2px;
    border-radius: 12px;
    margin-right: 15px;
    overflow: hidden;
  }

  .sidebar-wrapper .sidebar-header .user-pic img {
    object-fit: cover;
    height: 100%;
    width: 100%;
  }

  .sidebar-wrapper .sidebar-header .user-info {
    float: left;
  }

  .sidebar-wrapper .sidebar-header .user-info > span {
    display: block;
  }

  .sidebar-wrapper .sidebar-header .user-info .user-role {
    font-size: 12px;
  }

  .sidebar-wrapper .sidebar-header .user-info .user-status {
    font-size: 11px;
    margin-top: 4px;
  }

  .sidebar-wrapper .sidebar-header .user-info .user-status i {
    font-size: 8px;
    margin-right: 4px;
    color: #5cb85c;
  }

  /*-----------------------sidebar-search------------------------*/

  .sidebar-wrapper .sidebar-search > div {
    padding: 10px 20px;
  }

  /*----------------------sidebar-menu-------------------------*/

  .sidebar-wrapper .sidebar-menu {
    padding-bottom: 10px;
  }

  .sidebar-wrapper .sidebar-menu .header-menu span {
    font-weight: bold;
    font-size: 14px;
    padding: 15px 20px 5px 20px;
    display: inline-block;
  }

  .sidebar-wrapper .sidebar-menu ul li a {
    display: inline-block;
    width: 100%;
    text-decoration: none;
    position: relative;
    padding: 8px 30px 8px 20px;
  }

  .sidebar-wrapper .sidebar-menu ul li a i {
    margin-right: 10px;
    font-size: 12px;
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    border-radius: 4px;
  }

  .sidebar-wrapper .sidebar-menu ul li a:hover > i::before {
    display: inline-block;
    animation: swing ease-in-out 0.5s 1 alternate;
  }

  .sidebar-wrapper .sidebar-menu .sidebar-dropdown > a:after {
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    content: "\f105";
    font-style: normal;
    display: inline-block;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    background: 0 0;
    position: absolute;
    right: 15px;
    top: 14px;
  }

  .sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu ul {
    padding: 5px 0;
  }

  .sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu li {
    padding-left: 25px;
    font-size: 13px;
  }

  .sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu li a:before {
    content: "\f111";
    font-family: "Font Awesome 5 Free";
    font-weight: 400;
    font-style: normal;
    display: inline-block;
    text-align: center;
    text-decoration: none;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    margin-right: 10px;
    font-size: 8px;
  }

  .sidebar-wrapper .sidebar-menu ul li a span.label,
  .sidebar-wrapper .sidebar-menu ul li a span.badge {
    float: right;
    margin-top: 8px;
    margin-left: 5px;
  }

  .sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu li a .badge,
  .sidebar-wrapper .sidebar-menu .sidebar-dropdown .sidebar-submenu li a .label {
    float: right;
    margin-top: 0px;
  }

  .sidebar-wrapper .sidebar-menu .sidebar-submenu {
    display: none;
  }

  .sidebar-wrapper .sidebar-menu .sidebar-dropdown.active > a:after {
    transform: rotate(90deg);
    right: 17px;
  }

  /*--------------------------side-footer------------------------------*/

  .sidebar-footer {
    position: absolute;
    width: 100%;
    bottom: 0;
    display: flex;
  }

  .sidebar-footer > a {
    flex-grow: 1;
    text-align: center;
    height: 30px;
    line-height: 30px;
    position: relative;
  }

  .sidebar-footer > a .notification {
    position: absolute;
    top: 0;
  }

  .badge-sonar {
    display: inline-block;
    background: #980303;
    border-radius: 50%;
    height: 8px;
    width: 8px;
    position: absolute;
    top: 0;
  }

  .badge-sonar:after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    border: 2px solid #980303;
    opacity: 0;
    border-radius: 50%;
    width: 100%;
    height: 100%;
    animation: sonar 1.5s infinite;
  }

  /*--------------------------page-content-----------------------------*/

  .page-wrapper .page-content {
    display: inline-block;
    width: 100%;
    padding-left: 0px;
    padding-top: 20px;
  }

  .page-wrapper .page-content > div {
    padding: 20px 40px;
  }

  .page-wrapper .page-content {
    overflow-x: hidden;
  }

  /*------scroll bar---------------------*/



  /*-----------------------------chiller-theme-------------------------------------------------*/
  
  .chiller-theme .sidebar-wrapper {
      background: #31353D;
  }

  .chiller-theme .sidebar-wrapper .sidebar-header,
  .chiller-theme .sidebar-wrapper .sidebar-search,
  .chiller-theme .sidebar-wrapper .sidebar-menu {
      border-top: 1px solid #3a3f48;
  }

  .chiller-theme .sidebar-wrapper .sidebar-search input.search-menu,
  .chiller-theme .sidebar-wrapper .sidebar-search .input-group-text {
      border-color: transparent;
      box-shadow: none;
  }

  .chiller-theme .sidebar-wrapper .sidebar-header .user-info .user-role,
  .chiller-theme .sidebar-wrapper .sidebar-header .user-info .user-status,
  .chiller-theme .sidebar-wrapper .sidebar-search input.search-menu,
  .chiller-theme .sidebar-wrapper .sidebar-search .input-group-text,
  .chiller-theme .sidebar-wrapper .sidebar-brand>a,
  .chiller-theme .sidebar-wrapper .sidebar-menu ul li a,
  .chiller-theme .sidebar-footer>a {
      color: #818896;
  }

  .chiller-theme .sidebar-wrapper .sidebar-menu ul li:hover>a,
  .chiller-theme .sidebar-wrapper .sidebar-menu .sidebar-dropdown.active>a,
  .chiller-theme .sidebar-wrapper .sidebar-header .user-info,
  .chiller-theme .sidebar-wrapper .sidebar-brand>a:hover,
  .chiller-theme .sidebar-footer>a:hover i {
      color: #b8bfce;
  }

  .page-wrapper.chiller-theme.toggled #close-sidebar {
      color: #bdbdbd;
  }

  .page-wrapper.chiller-theme.toggled #close-sidebar:hover {
      color: #ffffff;
  }

  .chiller-theme .sidebar-wrapper ul li:hover a i,
  .chiller-theme .sidebar-wrapper .sidebar-dropdown .sidebar-submenu li a:hover:before,
  .chiller-theme .sidebar-wrapper .sidebar-search input.search-menu:focus+span,
  .chiller-theme .sidebar-wrapper .sidebar-menu .sidebar-dropdown.active a i {
      color: #16c7ff;
      text-shadow:0px 0px 10px rgba(22, 199, 255, 0.5);
  }

  .chiller-theme .sidebar-wrapper .sidebar-menu ul li a i,
  .chiller-theme .sidebar-wrapper .sidebar-menu .sidebar-dropdown div,
  .chiller-theme .sidebar-wrapper .sidebar-search input.search-menu,
  .chiller-theme .sidebar-wrapper .sidebar-search .input-group-text {
      background: #3a3f48;
  }

  .chiller-theme .sidebar-wrapper .sidebar-menu .header-menu span {
      color: #6c7b88;
  }

  .chiller-theme .sidebar-footer {
      background: #3a3f48;
      box-shadow: 0px -1px 5px #282c33;
      border-top: 1px solid #464a52;
  }

  .chiller-theme .sidebar-footer>a:first-child {
      border-left: none;
  }

  .chiller-theme .sidebar-footer>a:last-child {
      border-right: none;
  }
  
/*menu lateral fin */

</style>