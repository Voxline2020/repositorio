<!-- Name Field -->
<div class="form-group col-sm-12">
	{!! Form::label('name', '*Nombre:') !!}
	{!! Form::text('name', null, ['class' => "form-control ".($errors->has('name') ? 'is-invalid' : ''), 'id'=>'name']) !!}
	@error('name')
	{{-- <div class="invalid-feedback">
		<strong>
			{{ $errors->first('name') }}
		</strong>
	</div> --}}
	@endif
</div>
<div class="form-group col-sm-12">
	{!! Form::label('file', 'Suba su archivo:') !!}

	<div class="custom-file">
		<input name="video" type="file" class="custom-file-input no-space" id="file" aria-describedby="inputGroupFileAddon01">
		<label class="custom-file-label" id="label-file" for="file">Seleccione archivo (.mp4)</label>
	</div>

</div>

<!-- Size Field -->
<div class="form-group col-sm-4">
	{!! Form::label('size', 'Tamaño (bytes):') !!}
	{!! Form::number('size', null, ['class' => 'form-control', 'id'=>'size', 'readonly']) !!}
	<span id="size-number">Aproximadamente: </span>
</div>

<!-- Width Field -->
<div class="form-group col-sm-4">
	{!! Form::label('width', 'Ancho:') !!}
	{!! Form::number('width', null, ['class' => 'form-control','id'=>'width','readonly']) !!}
</div>

<!-- Height Field -->
<div class="form-group col-sm-4">
	{!! Form::label('height', 'Alto:') !!}
	{!! Form::number('height', null, ['class' => 'form-control', 'id'=>'height','readonly']) !!}
</div>



<!-- Submit Field -->
<div class="form-group col-sm-12">
	{!! Form::hidden('user_id', Auth::user()->id); !!}
	{!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
	<a href="{{ url()->previous() }}" class="btn btn-info">Cancelar</a>
</div>

@section('scripts')
<script>
	$('#file').on('change', function(evt) {
		var file = $('#file')[0].files[0];
		var reader  = new FileReader();
		var fileType =  file.type;

		if(fileType == "video/mp4"){
			var megabyte = parseInt(this.files[0].size) / 1048576;
			$('#size').val(this.files[0].size);
			$('#size-number').html("Aproximadamente: "+ megabyte.toFixed(3) + " mb");
			reader.addEventListener("load", function () {
				var dataUrl =  reader.result;
				var videoId = "videoMain";
				$('#label-file').text(file.name);
				var file_sinmp4 = (file.name).replace(".mp4", "");
				$('#name').val(file_sinmp4);
				var $videoEl = $('<video id="' + videoId + '"></video>');
				$videoEl.attr('src', dataUrl);
				var videoTagRef = $videoEl[0];
				videoTagRef.addEventListener('loadedmetadata', function(e){
					$('#width').val(videoTagRef.videoWidth);
					$('#height').val(videoTagRef.videoHeight);
				});

			}, false);

			if (file) {
				reader.readAsDataURL(file);
			}
		}
		else {
			alert("Solo se acepta formato .mp4");
			$('#size').val(null);
			$('#size-number').html("Aproximadamente: ");
			$('#label-file').text("Seleccione archivo (.mp4)");
			$('#width').val(null);
			$('#height').val(null);
			$('#file').val(null);
			$('#name').val("");

		}


	});

	(function ($) {
		$('#done-button').on('click', function () {

		});
	})(jQuery);
</script>

@endsection
