@extends('layouts.app')


@section('content')


<video width="800" height="600" id="videoplayer" controls loop >
    
    @foreach ($videos as $video)            
            <source src="/storage/{{$tienda}}/{{$video}}" type="video/mp4">  
        @endforeach 

    Your browser does not support the video tag.

</video>






<!-- <img  class="img-responsive img-rounded" src="/storage/imagen/pantalla.jpg" alt="User picture">	        	 -->


@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!--<script>
    
    window.onload = function () {
        alert("cargado...");
    }

    $(document).ready(function(){
        alert("cargado...2");
    });

    var myvid = document.getElementById('videoplayer');
    var myvids = [
    "http://www.w3schools.com/html/mov_bbb.mp4", 
    "http://www.w3schools.com/html/movie.mp4"
    ];

    var activeVideo = 0;

    
    myvid.addEventListener('ended', function(e) {
        console.log("add event 2");
        // update the new active video index
        activeVideo = (++activeVideo) % myvids.length;

        // update the video source and play
        myvid.src = myvids[activeVideo];
        myvid.play();
        
    });
</script> -->
