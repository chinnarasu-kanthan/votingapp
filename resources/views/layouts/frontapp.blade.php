<!DOCTYPE html>
<html lang="en">

{{-- Include Head --}}
<head>
  <html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
 <style>
 .carousel-control{
	 position: relative!important;
 }
 </style>
</head>


<div class="container">
  
  <form action="{{url('postcandidate')}}" method="POST">
   @csrf
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    

    <!-- Wrapper for slides -->
	
    <div class="carousel-inner">
      

      @foreach ($data as $key => $dat)
        
      

      @if($dat['type'] ==1)
      
          <div class="item @if($key ==0) active @endif">
          
          <h6> {{ $dat['question'] }}</h6>
          @foreach ($dat['items'] as $item)
          <input type="checkbox" id="" name="answers[]" value="{{$item->id}}">
            <label for="vehicle1">{{ $item->answer }}</label><br>
          @endforeach
            
        </div>

      @else
      <div class="item @if($key ==0) active @endif">
		  
          <h6> {{ $dat['question'] }}</h6>
          @foreach ($dat['items'] as $item)
          <input type="checkbox" id="" name="statements[]" value="{{$item->id}}">
            <label for="vehicle1">{{ $item->statement }}</label><br>
          @endforeach
            
        </div>
       
        @endif
    
      @endforeach
	 
    </div>

    <!-- Left and right controls -->
	<ul>
		<li><a class=" carousel-control" href="#myCarousel" data-slide="prev">Previous</a></li>
		<li><a class=" carousel-control" href="#myCarousel" data-slide="next">Next</a></li>
	</ul>
    
    
	<input type="submit" value="submit"/>
	</form>
  </div>
</div>

</body>
<script>
$(document).ready(function(){
  // Activate Carousel
  $("#myCarousel").carousel({interval: false, wrap: false});
    
  // Enable Carousel Indicators
  $(".item1").click(function(){
    $("#myCarousel").carousel(0);
  });
  $(".item2").click(function(){
    $("#myCarousel").carousel(1);
  });
  $(".item3").click(function(){
    $("#myCarousel").carousel(2);
  });
  $(".item4").click(function(){
    $("#myCarousel").carousel(3);
  });
    
  // Enable Carousel Controls
  $(".left").click(function(){
    $("#myCarousel").carousel("prev");
  });
  $(".right").click(function(){
    $("#myCarousel").carousel("next");
  });
});
</script>
</html>