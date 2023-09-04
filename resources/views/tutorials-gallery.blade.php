@extends('layouts.app')
@section('title', 'All Leads')
@section('content') 
<!-- CSS -->
<link rel="stylesheet" 
href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,600">
<link rel="stylesheet" 
href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" 
integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" 
crossorigin="anonymous">
<link rel="stylesheet" href="{{ 
asset('/assets1/css/animate.css')}}">
<link rel="stylesheet" href="{{ 
asset('/assets1/css/style.css')}}">
<link rel="stylesheet" href="{{ 
asset('/assets1/css/media-queries.css')}}">

<!-- Favicon and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" 
href="{{ asset('/assets1/ico/apple-touch-icon-144-precomposed.png')}}">
<link rel="apple-touch-icon-precomposed" sizes="114x114" 
href="{{ asset('/assets1/ico/apple-touch-icon-114-precomposed.png')}}">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ 
asset('/assets1/ico/apple-touch-icon-72-precomposed.png')}}">
<link rel="apple-touch-icon-precomposed" href="{{ 
asset('/assets1/ico/apple-touch-icon-57-precomposed.png')}}">
<div class="content-page">
  <div class="content"> 
    <!-- Start Content-->
    <div class="container-fluid"> 
      <div class="gallery-container section-container">
        <div class="container">
          <div class="row">
            <div class="col gallery section-description wow fadeIn">
              <h2>Tutorials</h2>
              <div class="divider-1 wow fadeInUp"><span></span></div>
            </div>
          </div>
          <div class="row">
            <div class="col"> 
              <!-- First row of images -->
              <div class="row">
                @foreach($tutorial as $value)
                <div class="col-md-4 gallery-box wow fadeInDown"> 
                  <div data-toggle="modal" data-target="#myModal"> <img src="{{url('/')}}/video/images/{{$value->image}}" alt="Image 1" data-target="#myCarousel" data-slide-to="{{$value->id}}"> </div>
                  {{ucwords($value->topic)}}
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body"> 
        
        <!-- Carousel -->
        <div id="myCarousel" class="carousel slide" >
          <ol class="carousel-indicators">
          @foreach($tutorial as $value)
            <li data-target="#myCarousel" data-slide-to="{{$value->id}}" class="{{$value->id == 1 ? 'active' : ' '}}"></li>
          @endforeach
          </ol>
          <div class="carousel-inner">
            @foreach($tutorial as $value)
            <div class="carousel-item {{$value->id == 1 ? 'active' : ''}}">
              <div class="modal-header"> {{$value->topic}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
              </div>
              <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{$value->url}}" allowfullscreen></iframe>
              </div>
            </div>
            @endforeach
        
          </div>
          <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span> <span class="sr-only">Next</span> </a> </div>
       </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script src="{{ asset('assets1/js/jquery-3.5.1.min.js')}}"></script> 
<script src="{{ asset('/assets1/js/jquery-migrate-3.3.0.min.js')}}"></script> 
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" crossorigin="anonymous"></script> 
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" crossorigin="anonymous"></script> 
<script src="{{ asset('/assets1/js/jquery.backstretch.min.js')}}"></script> 
<script src="{{ asset('/assets1/js/wow.min.js')}}"></script> 
<script src="{{ asset('/assets1/js/waypoints.min.js')}}"></script> 
<script src="{{ asset('/assets1/js/scripts.js')}}"></script> 
@endsection 