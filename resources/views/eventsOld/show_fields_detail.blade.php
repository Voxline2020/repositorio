@foreach($event as $events)
<div class="row">
	<div class="col-md-4">
		<h4><span class="badge badge-light">Duracion evento:
				{!!number_format((int)(strtotime($events->enddate)-strtotime($events->initdate))/60/60/24)!!} dia(s)</span></h4>
	</div>
	<div class="col-md-4">
		<h4><span class="badge badge-light">Fecha inicio: {!! $events->initdate!!}</span></h4>
	</div>
	<div class="col-md-4">
		<h4><span class="badge badge-light">Fecha Termino: {!! $events->enddate!!}</span></h4>
	</div>
</div>
@endforeach
@if(count($content)==0)
<table class="table table-hover">
	<thead class="thead-light">
		<div class="panel-body">
			{!! Form::open(['route'=> ['file.store',$events->id], 'method' => 'POST', 'files'=>'true', 'id' =>
			'my-dropzone' , 'class' => 'dropzone'] ) !!}

			<button type="submit" class="btn btn-success" id="submit">Guardar Contenido</button>
			{!! Form::close() !!}
			<a href="{!! route('events.index') !!}" class="btn btn-info"> Atras</a>
		</div>
	</thead>
</table>
@else
<table class="table table-hover">
	<thead class="thead-light">
		<tr>
			<th>Nombre del contenido</th>
			<th>Tamaño</th>
			<th>Ancho</th>
			<th>Alto</th>
			<th>Usuario</th>
			<th colspan="1">Asignar Pantalla</th>
		</tr>
	</thead>

	<tbody>
		@foreach($content as $contents)
		<tr>
			<td>{!! $contents->name !!}</td>
			<td>{!! $contents->size !!}</td>
			<td>{!! $contents->width !!}</td>
			<td>{!! $contents->height !!}</td>
			<td>{!! $contents->user->name !!}</td>
			<td>
				{!! Form::open(['route' => ['contents.destroy', $contents->id], 'method' => 'delete']) !!}
				<!--
								<div class='btn-group'>
									<a href="{!! route('contents.edit', [$contents->id]) !!}" class='btn btn-warning btn-xs'><i
											class="fas fa-edit"></i></a>
									{!! Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger
									btn-xs',
									'onclick' => "return confirm('Estas seguro?')"]) !!} -->
				<a href="{{route('screens.AssignContent',$contents->id) }}" class='btn btn-primary btn-xs'><i
						class="fas fa-desktop"></i></a>
				<a href="{{route('download.content',$contents->id) }}" class='btn btn-info btn-xs'><i
						class="fa fa-download"></i></a>

				{!! Form::close() !!}
			</td>
		</tr>
		@endforeach
	</tbody>
</table>
@endif

{!! Html::script('js/dropzone.js'); !!}
<script>
	Dropzone.options.myDropzone = {
				acceptedFiles: '.mp4',
				autoDiscover: false,
				autoProcessQueue: false,
				uploadMultiple: true,
				maxFilezise: 500,
				maxFiles: 100,
				parallelUploads: 5,
				upload_max_filesize: 10000,
				addRemoveLinks: true,

				headers: {
        'X-CSRFToken': $('meta[name="token"]').attr('content')
				},
				sending: function(file, xhr, formData) {
        // Pass token. You can use the same method to pass any other values as well such as a id to associate the image with for example.
        formData.append("_token", $('[name=_token').val()); // Laravel expect the token post value to be named _token by default
    },


				init: function() {
						var submitBtn = document.querySelector("#submit");
						myDropzone = this;

						submitBtn.addEventListener("click", function(e){
								e.preventDefault();
								e.stopPropagation();
								myDropzone.processQueue();
						});
						this.on("addedfile", function(file) {

						});

						this.on("complete", function(file) {
								myDropzone.removeFile(file);
								if(myDropzone.files.length==0){
										location.reload();
									}
						});

						this.on("success",

								myDropzone.processQueue.bind(myDropzone)

						);


				}

		};
</script>
