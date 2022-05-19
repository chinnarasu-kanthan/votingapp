@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@push('css')
<link rel="stylesheet" href="{{ URL::asset('assets/css/custom.css') }}">
@endpush

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center mb-3">Welcome To Voting-Admin Dashboard!</h2>
        </div>
    </div>

    <!-- Content Row -->
    
    <div class="slider">
	
	<form action="" method="POST">
       @csrf
    @foreach ($data as $key => $dat)
        
      <div class="slide @if($key ==0) active @endif">
      
        <div class="info">
          <h6> {{ $dat['question'] }}</h6>
          @foreach ($dat['items'] as $item)
          <input type="checkbox" id="" name="answers[]" value="{{$item->id}}">
            <label for="vehicle1">{{ $item->answer }}</label><br>
          @endforeach
            
        </div>
      </div>
      @endforeach
      <div class="slide">
      
     <!-- <div class="info">
        <h6> Statements</h6>
        @foreach ($statements as $statement)
        <input type="checkbox" id="" name="statemets[]" value="{{$statement->id}}">
          <label for="vehicle1">{{ $statement->statement }}</label><br>
        @endforeach
          
      </div>-->
    </div>
     
      
      <div class="navigation">
        <i class="fas fa-chevron-left prev-btn">Next</i>
        <i class="fas fa-chevron-right next-btn">Prev</i>
      </div>
      <input  style="position: relative;left: 109px;" type="submit" value="submit" >
	  </form>
    </div>
        
    </div>

    

</div>
@endsection
@section('scripts')
<script type="text/javascript">
    const slider = document.querySelector(".slider");
    const nextBtn = document.querySelector(".next-btn");
    const prevBtn = document.querySelector(".prev-btn");
    const slides = document.querySelectorAll(".slide");
    //const slideIcons = document.querySelectorAll(".slide-icon");
    const numberOfSlides = slides.length;
    var slideNumber = 0;

    //image slider next button
    nextBtn.addEventListener("click", () => {
      slides.forEach((slide) => {
        slide.classList.remove("active");
      });
      

      slideNumber++;

      if(slideNumber > (numberOfSlides - 1)){
        
          slideNumber = 0;
      }
      console.log(numberOfSlides);

      slides[slideNumber].classList.add("active");
      
    });

    //image slider previous button
    prevBtn.addEventListener("click", () => {
      slides.forEach((slide) => {
        slide.classList.remove("active");
      });
     

      slideNumber--;

      if(slideNumber < 0){
        slideNumber = numberOfSlides - 1;
      }

      slides[slideNumber].classList.add("active");
    
    });

    //image slider autoplay
    var playSlider;

   
    //repeater();

    //stop the image slider autoplay on mouseover
    slider.addEventListener("mouseover", () => {
      clearInterval(playSlider);
    });

    //start the image slider autoplay again on mouseout
    slider.addEventListener("mouseout", () => {
      //repeater();
    });
    </script>
      @endsection