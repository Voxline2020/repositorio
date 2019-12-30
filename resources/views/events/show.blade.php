@extends('layouts.principal')

@section('content')
<div class="row">
	<div class="col-md-9">
		<h2> Evento {{ $event->name }} </h2>
	</div>
	<div class="btn-group">
		<div class="col-md-6">
		<a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
		Agregar
		</a>
		</div>
		<div class="col-md-3">
			<a href="{!! route('companies.events.index', $event->company) !!}" class="btn btn-info">Atras</a>
		</div>
	</div>

</div>

<div class="row my-lg-4 my-md-4 my-sm-1">
	@include('events._show_fields')
</div>
<div class="collapse" id="collapseExample">
<div class="col-md-12">
	<table class="table table-hover">
		<thead class="thead-dark">
			<div class="panel-body text-right">
				{!! Form::open(['route'=> ['events.fileStore'], 'method' => 'POST', 'files'=>'true', 'id' =>
				'my-dropzone' , 'class' => 'dropzone my-2'] ) !!}
				<button type="submit" class="btn btn-success" id="submit">Guardar Contenido</button>
				{!! Form::close() !!}
			</div>
		</thead>
	</table>
</div>
</div>
@endsection
@section('script')
<script>
	Dropzone.options.myDropzone = {
		acceptedFiles: '.mp4',
		autoDiscover: false,
		autoProcessQueue: false,
		uploadMultiple: true,
		maxFilezise: 500,
		maxFiles: 25,
		parallelUploads: 5,
		upload_max_filesize: 10000,
		addRemoveLinks: true,
		dictDefaultMessage: "Suba los archivos aqui",
		headers: {
			'X-CSRF-TOKEN': "{{ csrf_token() }}"
		},
		init: function() {
				var submitBtn = document.querySelector("#submit");
				myDropzone = this;
				submitBtn.addEventListener("click", function(e){
						// e.preventDefault();
						// e.stopPropagation();
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

				this.on('sending', function(file, xhr, formData) {
						// Append all form inputs to the formData Dropzone will POST
						var data = $('#my-dropzone').serializeArray();
						formData.append("event_id",'{{ $event->id }}');

						$.each(data, function(key, el) {
							formData.append(el.name, el.value);

						});

				});

		},
	};
</script>
@endsection
