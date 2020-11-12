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
  <link href="https://vjs.zencdn.net/7.8.4/video-js.css" rel="stylesheet" />

	<!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">


  <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>

<video id="videoplayer"  preload="auto"  width="100%" height="100%"  controls loop  muted autoplay class="video-js vjs-fill">
    
    @foreach ($videos as $video)            
            <source id="source" src="/storage/{{$tienda}}/{{$video}}" type="video/mp4">  
        @endforeach
        <p class="vjs-no-js">
              To view this video please enable JavaScript, and consider
              upgrading to a web browser that
              <a href="https://videojs.com/html5-video-support/" target="_blank"
                >supports HTML5 video</a
              >
            </p>

</video>






<!-- <img  class="img-responsive img-rounded" src="/storage/imagen/pantalla.jpg" alt="User picture">	        	 -->


</body>

<script>


    
  window.onload = function ready() {
    //alert('DOM is ready');
    //openFullscreen();

    $("videoplayer").on("loadstart", function() {
      alert("video");
        console.log(3);
    });
  };

  var myVar = setInterval(myTimer, 10000);
  function myTimer() {
    var d = new Date();
    console.log("timer");
    $.ajax({
            dataType: 'json',
            type: "POST",
          url: "/versionMasUno",
          data: {
                      "_token": $("meta[name='csrf-token']").attr("content"),
                      "video" :  '{{$video}}',
                      "tienda" : '{{$tienda}}', 
            
          },
          success: function(data) {
                      
            console.log('success');
            console.log(data);
            var src = '/storage/{{$tienda}}/'+data['videos'][2];
            console.log(src);
            if(data['sucess'] == "true")
            {
              console.log("true");
              var player = document.getElementById('videoplayer');
              ChangeVideo(player, src);
              //StopVideo();
              //PlayVideo(src);
              //location.reload();
              
            }
            
            
            
            },
          error: function () {
            console.log('en el error');	  
          }
          
        });    
  }

/* Get the element you want displayed in fullscreen mode (a video in this example): */


/* When the openFullscreen() function is executed, open the video in fullscreen.
Note that we must include prefixes for different browsers, as they don't support the requestFullscreen method yet */
function openFullscreen() {
  var docElm = document.getElementById("videoplayer");
  console.log("open full screen");
if  ( docElm . requestFullscreen )  {
    docElm . requestFullscreen ( ) ;
}
else  if  ( docElm . mozRequestFullScreen )  {
    docElm . mozRequestFullScreen ( ) ;
}
else  if  ( docElm . webkitRequestFullScreen )  {
    docElm . webkitRequestFullScreen ( ) ;
}
else  if  ( docElm . msRequestFullscreen )  {
    docElm . msRequestFullscreen ( ) ;
}
  console.log("fin full screen");
}


    
    function ChangeVideo(_player, src) {
      console.log("ChangeVideo");
        console.log(src);        
        document.getElementById('source').src = src; 
        var player = document.getElementById('videoplayer')     ;          
        _player.load();
      }




</script> 
