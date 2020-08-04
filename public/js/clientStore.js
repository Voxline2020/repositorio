
		//funcion drop
		function drop(ev , $device_id , $width , $height) {
			// Prevent default behavior (Prevent file from being opened)
			ev.preventDefault();
		   console.log('File(s) dropped');
		   //seleccionarDevice(0 , 0 , 0);

	  		 
	  		 //obtenemos el archivo dropeado
	  		 var file = ev.dataTransfer.items[0].getAsFile();
	  		 //extraemos informacion del archivo
	  		 var file_ext = file.type;
	  		 var file_name = file.name;

			 //comprovamos que esun archivo de video por la extencion
	  		 if(file_ext.includes("video") )
	  		 {
	  		 	var events = $('#events').val();
				 //transformamos el string en un archivo json 
				 events = JSON.parse(events);
				 

	  		 	//le asignamos el video recojido del drop al input file id = contenido
	  		 	document.querySelector('#contenido').files = ev.dataTransfer.files;	  		 	
	  		 	//le asignamos el nombre del video al nombre evento 
	  		 	$('#list_events').val(file_name);

	  		 	console.log(buscarEvento(events , file_name ));
	  		 	var buscar = buscarEvento(events , file_name );
	  		 	if(buscar[0])
	  		 	{
	  		 		var fecha_inicio = buscar[1];
	  		 		var fecha_termino = buscar[2];
	  		 		var event_id = 	buscar[3];

	  		 		$("#initdate").find("input").val(fecha_inicio);
				 	$("#enddate").find("input").val(fecha_termino);				 				
				 	$('#textinitdate').prop('readonly', true);
				 	$('#textenddate').prop('readonly', true);
				 	$("#event_id").val(event_id);				 		
				 	alert('a seleccionado un evento existente');
				 	
				 		
	  		 		console.log("encontro");
	  		 	}else
	  		 	{	
	  		 		$("#initdate").find("input").val("");
				 	$("#enddate").find("input").val("");				 				
				 	$('#textinitdate').prop('readonly', false);
				 	$('#textenddate').prop('readonly', false);
				 	$("#event_id").val("");	
	  		 		console.log("no encontro");
	  		 	}
				
				//llamamos a la funcion seleccionar device
				console.log($device_id , $width , $height);
	  		 	seleccionarDevice($device_id , $width, $height);

	  		 	
	  		 	
	  		 }else{
	  		 	alert("Error solo puede arastrar archivos de video");
	  		 	
	  		 } 
	  		 
	  		 //console.log(ev.dataTransfer);
	  		 //console.log(file.type);
   
		}//fin funcion drop

		//crea una zona de drop
		function allowDrop(ev) {
		  console.log("arrastrando algo");

		  ev.preventDefault();
		}//fin crear zona de drop


		//funcion que abre el modal.
		$('img').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget)
			var id = button.data('id')
			var device = button.data('device')
			var modal = $(this)
			modal.find('input[name="id"]').val(id)
			modal.find('input[name="device"]').val(device)
		})

		function buscarEvento($events , $input ){
			var encontro = false;
			var fecha_inicio = "";
			var fecha_termino = "";
			var event_id = ""

			$.each($events, function($events, event)
				 {
				 	//comparamos si los nombres son identicos
				 	if($input.toUpperCase() == event['name'].toUpperCase())
				 	{	
				 		encontro = true;
				 		event_id = event['id'];				 		

				 		//modificamos la fecha de inicio al formato del datetimepicker
				 		var date = new Date(event['initdate']);
				 		var day  = date.getDate().toString();				 		
				 		if(day.length == 1)
				 		{
				 			day = "0" + day;
				 		}
				 		var month = date.getMonth() + 1;
				 		month = month.toString();
				 		if(month.length == 1)
				 		{
				 			month = "0" + month;
				 		}
				 		var hour = date.getHours().toString();				 		
				 		if(hour.length == 1)
				 		{
				 			hour = "0" + hour;
				 		}

				 		var minutes = date.getMinutes().toString();				 		
				 		if(minutes.length == 1)
				 		{
				 			minutes = "0" + minutes;
				 		}

				 		fecha_inicio = day + '/'+ month + '/' +date.getFullYear()+' '+hour+':'+minutes ;

				 		//modificamos la fecha de termino al formato del datetimepicker
				 		date = new Date(event['enddate']);
				 		day  = date.getDate().toString();				 		
				 		if(day.length == 1)
				 		{
				 			day = "0" + day;
				 		}
				 		month = date.getMonth() + 1;
				 		month = month.toString();
				 		if(month.length == 1)
				 		{
				 			month = "0" + month;
				 		}
				 		hour = date.getHours().toString();				 		
				 		if(hour.length == 1)
				 		{
				 			hour = "0" + hour;
				 		}

				 		minutes = date.getMinutes().toString();				 		
				 		if(minutes.length == 1)
				 		{
				 			minutes = "0" + minutes;
				 		}

				 		fecha_termino = day + '/'+ month + '/' +date.getFullYear()+' '+hour+':'+minutes ;
				 		

				 		//console.log("pase por el if");
				 	}//fin busqueda por nombre
				 	
				 }); //fin for	
			return [encontro, fecha_inicio , fecha_termino , event_id];
		} //fin buscarEvento
	
		 //modifica los datapickers del modal
		 $(function () {                
                $('#initdate').datetimepicker({                	                	
                	locale: 'es'
                });

                $('#enddate').datetimepicker({
                    locale: 'es'
                });
            });
		 
				
		//agrega contenido a los inputs ocultos en el modal;
		function seleccionarDevice($device_id , $width , $height)
		{
				
			//limpiamos los campos
			//$("#initdate").find("input").val("");
			//$("#enddate").find("input").val("");
			//$("#contenido").find("input").val("");
			//abrimos el modal
			$('#miModal').modal('show');				

			$("#contenido").change(function(e){
				var file_name = e.target.files[0].name;            	
				//file_name = file_name.replace(''++'/'g,'-');
				$('#list_events').val(file_name);
				$( "#list_events" ).focus();
				$( "#textinitdate" ).focus();
				//alert('The file "' + file_name +  '" has been selected.');
				//console.log(e.target.files);            	
        	});
			
			//una ves selecciona el evento genera una busqueda para conprovar si existe con anterioridad
			$('#list_events').blur(function(){
				console.log('blur');
				//rescatamos el string json almacenado en un input oculto				
				 var events = $('#events').val();
				 //transformamos el string en un archivo json 
				 events = JSON.parse(events);
				 
				 var encontro = false;

				 //extrarmos el nombre del evento
				 var input = $('#list_events').val();

				 var fecha_inicio ;
				 var fecha_termino ;
				 var event_id ;


				 var buscar = buscarEvento(events , input);	  		 	
				 if(buscar[0])
				 {
				 	 fecha_inicio = buscar[1];
	  		 		 fecha_termino = buscar[2];
	  		 		 event_id = buscar[3];

				 	console.log("encontro");
				 	console.log("event_id");
				 		alert("Selecciono un evento existente.");
				 		
				 		//le asignamos los valores del evento al formulario
				 		$("#initdate").find("input").val(fecha_inicio);
				 		$("#enddate").find("input").val(fecha_termino);				 				
				 		$('#textinitdate').prop('readonly', true);
				 		$('#textenddate').prop('readonly', true);
				 		$("#event_id").val(event_id);				 		
				 		
				 	//console.log("encontro");
				 }else{

				 		//$("#initdate").find("input").val("");
				 		//$("#enddate").find("input").val("");				 		
				 		$('#textinitdate').prop('readonly', false);
				 		$('#textenddate').prop('readonly', false);
				 		$("#event_id").val("");
				 	//console.log("not found");
				 }
				
			}); //fin busqueda evento

			//asignamos los datos del device selccionado a los inputs ocultos
			$('#device_id').val($device_id);
			$('#device_width').val($width);
			$('#device_height').val($height);			

		}

		//buscador barra lateral
		
		

		//se ejecuta cuando se hace click en una tienda
		function openStore($idsucursal){
			funcionAjax($idsucursal);
			$('#mensaje').hide();
			//alert($idsucursal);
			//mostrarPantallas($sucursal);				
		} 

		function mostrarPantallas($sucursal){			  
			 alert('mostrar pantallas ?')
			  document.getElementById('screens').innerHTML = 
			  "<h1>"+ $sucursal[id] +"</h1>";
		}			



		function funcionAjax($idsucursal) {
			//alert("en la funcion ajax"+ $idsucursal);           
               $.ajax({
               	dataType: 'json',
               	type: "POST",
				url: "/verScreens",
				data: {
				    "_token": $("meta[name='csrf-token']").attr("content"),
				    "idStore": $idsucursal,
				},
				success: function(data) {				
				
				//guardamos los eventos traidos por ajax en una variable 
				//todos los eventos pertenecientes a la compañia del usuario en sesion 				
				var events = JSON.stringify(data.events);
				//asignamos os eventos al input hidden del modal
				$('#events').val(events);
				var titulo = "<h2> Sucursal : " + data.sucursal + "</h2>";
				var output2 = "";
				if (data.devices.length > 0)
				{
					//recorremos las pantallas
					$.each(data.devices, function(key , value){				  		
					var height = value.height; 
				    var width = value.width;
				    var alto = value.height / 10;
				    var ancho = value.width / 10;
				    var resolucion = ""+value.width+" x "+value.height
				    var state = value.state;	                    
				    var device_id = value.id;
				    var state = "inactivo";
				    if(value.state == '1')
				    {
				        state = "activo";
				    }

				    output2 += '<div id="device"  ondrop="drop(event , '+device_id+','+width+','+height+')" ondragover="allowDrop(event)">';
					    output2 +='<table>';
					     	output2 +='<tr >';
					      		output2 +='<td>';
					       			output2 += '<img onclick="seleccionarDevice('+device_id+','+width+','+height+');"   src="assets/pantalla.jpg" alt="Pantalla" width="'+ancho+'" height="'+alto+'">';
					      		output2 +='</td>';
					      		output2 +='<td>';
					       			output2 +='<table>';
					        			output2 +='<tr>';
					         				output2 +='<td>';
					          					output2 += '<strong> Nombre :  </strong>';
					         				output2 +='</td>';
					         				output2 +='<td>';
					          					output2 += '<strong>'+value.name +'</strong>';
					         				output2 +='</td>';
					        			output2 +='</tr>';
					        			output2 +='<tr>';
					         				output2 +='<td>';
					          					output2 += '<strong> Resolucion :  </strong>';
					         				output2 +='</td>';
					         				output2 +='<td>';
					          					output2 += '<strong>'+resolucion+'</strong>' ;
					         				output2 +='</td>';
					        			output2 +='</tr>';
					        			output2 +='<tr>';
					         				output2 +='<td>';
					          					output2 += ' <strong> Estado :  </strong>'
					         				output2 +='</td>';
					         				output2 +='<td>';
					                    		output2 += '<strong>'+ state + '</strong>';
					                    	output2 +='</td>';
					                    output2 +='</tr>'
					                    output2 +='<tr>';
					                    	output2 +='<td>';
					                    		output2 += ' <strong> Tipo :  </strong>'
					                    	output2 +='</td>';
					                    	output2 +='<td>';
					                    		output2 +='<strong>'+ value.type_id + '</strong>';
					                    	output2 +='</td>';
					                    	output2 +='<tr>';
					                    		output2 +='<td></td>';					
					                    		output2 +='<td></td>';					
					                    	output2 +='</tr>';
					                    	output2 +='<tr>';
					                    		output2 +='<td>';					
					                    		output2 +='</td>';
					                    		output2 +='<td>';
					                    			output2 += '<a href="clients/device/'+value.id+'" class="btn btn-info btn-xxs"><i class="fas fa-eye"></i></a>' ;
					                    		output2 +='</td>';
					                    	output2 +='</tr>';					
					                    output2 +='</table>';
					                output2 +='</td>';
					            output2 +='</tr>';
					        output2 +='</table>';	
						output2 += "</div>";


					}); // fin del foreach
						  	
					
				}else
				{
					titulo = "";
					output2 = "<h2> No se encuentrar pantallas asignadas a esta sucursal </h2>";		
				}
				
				  	//$("#response-container").html(output);
				  	$("#titulo").html(titulo);
				  	$("#devices").html(output2);
				  	

				  /*	document.getElementById('msg').innerHTML = 
				  	data.mensaje; */

				  /*	document.getElementById('screens').innerHTML = 
				  	data.consulta; */
				  	
					console.log(data);
					console.log('en el success');
					//alert(msg);
				},
				error: function () {
					console.log('en el error');	  
				}
				
            });
        }


	