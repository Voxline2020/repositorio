<div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">		
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

									<input list="event_name" name="event_name" type="text"  id="list_events" autocomplete="off" required>
<<<<<<< HEAD
									<datalist id="event_name">										<?php foreach ($eventsmenu as $event): ?>
											<option value="{{$event->name}}"></option>	
									<?php endforeach ?>
									    									    
=======
									<datalist id="event_name">											<?php foreach ($eventsmenu as $event): ?>
											<?php if ($event->state == '1'): ?>
												<option class="optionEvent" value="{{$event->name}}"></option>	
											<?php else: ?>
												<option value="{{$event->name}}"></option>	
											<?php endif ?>
												
										<?php endforeach ?>
>>>>>>> arreglo2
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
								<div class="col-7" >
								</div>								
								<div class="col-2" onclick="openGift();" style="margin-right:5px">
									<button type="submit" class="btn btn-primary">Guardar</button>
								</div>
								<div class="col-2" style="margin-left:5px">
									<button type="button" class="btn btn-danger " data-dismiss="modal" aria-label="Close">Cerrar </button>
								</div>
						</div> <!-- final row -->
			      	</form> <!-- final form -->
			    </div> <!-- final modal body -->
			</div> <!-- fin modal content -->
		</div> <!-- fin mdal dialog -->
</div> 