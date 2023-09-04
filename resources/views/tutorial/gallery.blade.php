@extends('layouts.app')
@section('title', 'Gallery')
@section('content')
<style>
.modal-content {
    margin-top: 109px;
}
.modal-footer.justify-content-center {
    height: 49px;
    margin-left: -104px;
}
.centered {
  position: absolute;
  top: 40%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #fff;
}
a img.img-fluid.z-depth-1 {
  border-radius: 10px;
  border: 1px solid #5369f8;
  position: relative;
  text-align: center;
  color: #5369f8;
}
a img.img-fluid.z-depth-1:hover {
  border-radius: 10px;
  border: 1px solid #000;
  position: relative;
  text-align: center;
  color: #000;
  border: 1px solid;
  padding: 3px;
  box-shadow: 5px 8px #888888;
 /* animation: shake 0.5s;
  animation-iteration-count: infinite;*/
}
/*@keyframes shake {
  0% { transform: translate(1px, 1px) rotate(0deg); }
  10% { transform: translate(-1px, -2px) rotate(-1deg); }
  20% { transform: translate(-3px, 0px) rotate(1deg); }
  30% { transform: translate(3px, 2px) rotate(0deg); }
  40% { transform: translate(1px, -1px) rotate(1deg); }
  50% { transform: translate(-1px, 2px) rotate(-1deg); }
  60% { transform: translate(-3px, 1px) rotate(0deg); }
  70% { transform: translate(3px, 1px) rotate(-1deg); }
  80% { transform: translate(-1px, -1px) rotate(1deg); }
  90% { transform: translate(1px, 2px) rotate(0deg); }
  100% { transform: translate(1px, -2px) rotate(-1deg); }
}*/

.modal-footer {
    display: none;
}
.modal-content {
    width: 915px;
    margin-left: -192px;
    margin-top: 139px;
}
.modal-header {
    display: none;
}
.modal-body {
    position: relative;
    padding-bottom: 16px !important;
    padding: 0px;
}

</style>

<!-- ============================================================== -->
  <!-- Start Page Content here -->
<!-- ============================================================== -->

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="row page-title">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb" class="float-right mt-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Gallery</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 mt-0">Video Library</h4>
                </div>
            </div>

          <!-- end row -->
            <div class="row">
              <div class="col-xl-12">
                <div class="card">
                  <div class="card-body">

                    @if(Session::has('message'))
                      <div class="alert alert-success alert-block">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          <strong>{!! session('message') !!}</strong>
                      </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif 

                    <!-- Grid row -->
                    <div class="row">
                      @foreach($tutorial as $value)
                      <!-- Grid column -->
                      <div class="col-lg-4 col-md-12 mb-4">
                        <!--Modal: Name-->
                        <div class="modal fade" id="modal1{{$loop->iteration}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-lg" role="document">

                            <!--Content-->
                            <div class="modal-content">

                              <!--Body-->
                              <div class="modal-body mb-0 p-0">

                                <div class="embed-responsive embed-responsive-16by9 z-depth-1-half">
                                   <iframe src="https://www.youtube.com/embed/{{$value->url}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; 
gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>

                              </div>

                              <!--Footer-->
                              <div class="modal-footer justify-content-center">
                                <a type="button" class="btn-floating btn-sm btn-fb"><i class="fab fa-facebook-f"></i></a>
                                <!--Twitter-->
                                <a type="button" class="btn-floating btn-sm btn-tw"><i class="fab fa-twitter"></i></a>
                                <!--Google +-->
                                <a type="button" class="btn-floating btn-sm btn-gplus"><i class="fab fa-google-plus-g"></i></a>
                                <!--Linkedin-->
                                <a type="button" class="btn-floating btn-sm btn-ins"><i class="fab fa-linkedin-in"></i></a>
                                <button type="button" class="btn btn-outline-primary btn-rounded btn-md ml-4" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                            <!--/.Content-->
                          </div>
                        </div>
                        <!--Modal: Name-->
                        @if(empty($value->image))
                        <a><img class="img-fluid z-depth-1" src="https://mdbootstrap.com/img/screens/yt/screen-video-1.jpg" alt="video"
                            data-toggle="modal" data-target="#modal1{{$loop->iteration}}"></a>
                          <h4 class="mb-1 mt-0 centered" style="text-align: center;">{{$loop->iteration}}. Video</h4>
                        @else
                        <a><img class="img-fluid z-depth-1" src="{{url('/')}}/video/images/{{$value->image}}" alt="video"
                            data-toggle="modal" data-target="#modal1{{$loop->iteration}}"></a>
                          <h4 class="mb-1 mt-0 centered" style="text-align: center;">{{$loop->iteration}}. Video</h4>
                        @endif  
                      </div>
                      <!-- Grid column -->
                      @endforeach
                    </div>
                    {{$tutorial->links()}}
                    <!-- Grid row -->
                  </div>  <!-- end card-body -->
                </div>  <!-- end card -->
              </div>  <!-- end col -->
           </div>
                <!-- end row -->
      </div> <!-- container-fluid -->
  </div> <!-- content -->

        <!-- Footer Start -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <?php echo date('Y'); ?> &copy; RealAuto. All Rights Reserved. Crafted with <i class='uil uil-heart text-danger font-size-12'></i> by <a href="https://active-digital.tech/home/" target="_blank">Active Digital Technology</a>
            </div>
        </div>
    </div>
</footer>
<!-- end Footer -->
</div>



@endsection
