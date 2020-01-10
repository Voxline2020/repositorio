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
</head>

<body>
	<div id="app">
		@auth
		@include('layouts.roles._navbar')
		@endauth
		<div class="container py-4 px-5" style="background-color: white ">
			@yield('content')
		</div>
	</div>
	<script src="{{ asset('vendor/fontawesome5/js/all.js') }}"></script>
	<script src="{{ asset('js/vendor/moments/momentjs-with-locales.js') }}"></script>
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('vendor/dropzonejs/dropzone.js') }}"></script>
	<script src="{{ asset('vendor/select2/js/select2.js')}}"></script>
	<script src="{{ asset('vendor/tempusdominus/js/tempusdominus-bootstrap-4.js')}}"></script>
	<script src="{{ asset('vendor/moment/moment-with-locales.js')}}"></script>

	<!-- Languaje -->
	<script>
		$(function () {
			$('.js-select2').select2({
				tags: false,
				theme: 'bootstrap4',
			});
		});
	</script>
	<script>
		$('#changejump').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget)
			var id = button.data('id')
			var order = button.data('order')
			var modal = $(this)
			modal.find('input[name="id"]').val(id)
			modal.find('input[name="order"]').val(order)
		})
	</script>
	<script type="text/javascript">
		$("#btnAssign").click(function(){
			//validacion chbx
			if (! $("input[type=checkbox]").is(":checked") ) {
         alert("Por favor seleccione una pantalla para realizar asignación.");
         return false;
			}
			//llenar tabla con los datos seleccionados
			llenarTabla();
		});
		function llenarTabla(){
			var array = lista();
			array.forEach(e => {
				document.getElementById("tableSelected").insertRow(-1).innerHTML = '<td>'+e.name+'</td><td>'+e.store_name+'</td><td>'+e.type+'</td><td>'+e.resolution+'</td>';
			});
		}
		function lista(){
      var lista = [];
      $("input[type=checkbox").each(function (index) {
        if($(this).is(':checked')){
					var idVal = $(this).val();
					var fila = $('#' + idVal);

					var obj = {};
					fila.find('td').each (function() {
						var td = this;
						fillFila(td, idVal, lista, obj)
					});
					lista.push(obj);
        }
      });
    	return lista;
    }
		function fillFila(td, id, lista, obj){
			var td =td;
			var tdName = $(td).data('name');
			if(tdName != undefined){
				obj[tdName] = td.innerHTML;
			}
		}
		function limpiarTabla(){
			var count = document.getElementById("tableSelected").rows.length;
			for (var i = 1; i < count; i++) {
				document.getElementById("tableSelected").deleteRow(-1);
			}
		}
		$("#confirmAssignation").on("hidden.bs.modal", function () {
			limpiarTabla();
		});
	</script>
	@yield('script')
</body>

</html>
