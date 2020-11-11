<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>Reproductor</title>

	<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}" defer></script>

	<!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

	<!-- Styles -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>

<video id="videoplayer"  preload="auto" autoplay="autoplay"  width="100%" height="100%"  controls loop  muted autoplay>
    
    @foreach ($videos as $video)            
            <source src="/storage/{{$tienda}}/{{$video}}" type="video/mp4">  
        @endforeach

    Your browser does not support the video tag.

</video>






<!-- <img  class="img-responsive img-rounded" src="/storage/imagen/pantalla.jpg" alt="User picture">	        	 -->


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    
  /*window.onload = function ready() {
    alert('DOM is ready');

    $("videoplayer").on("loadstart", function() {
      alert("video");
        console.log(3);
    });
  };*/

 /*var video = document.getElementById("videoplayer");
        video.onloadeddata = function() {
            alert("Browser has loaded the current frame");
            
            var promise = document.querySelector('#videoplayer').play();
            
            if (promise !== undefined) {
            promise.then(_ => {
                // Autoplay started!
                console.log("start");
            }).catch(error => {
                // Autoplay was prevented.
                // Show a "Play" button so that user can start playback.
                console.log(error);
            });
            }
        }; */

 

  //document.addEventListener("load", ready);


</script> 
