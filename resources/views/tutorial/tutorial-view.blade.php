@extends('layouts.app')
@section('title', 'Add Tutorial')
@section('content')
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 83px;
  height: 28px;
}
.switch input {display:none;}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ca2222;
  -webkit-transition: .4s;
  transition: .4s;
   border-radius: 34px;
}
.slider:before {
  position: absolute;
  content: "";
  height: 22px;
  width:22px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius: 50%;
}
input:checked + .slider {
  background-color: #2ab934;
}
input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}
input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(55px);
}
/*------ ADDED CSS ---------*/
.slider:after
{
 content:'OFF';
 color: white;
 display: block;
 position: absolute;
 transform: translate(-50%,-50%);
 top: 50%;
 left: 50%;
 font-size: 10px;
 font-family: Verdana, sans-serif;
}
input:checked + .slider:after
{  
  content:'ON';
}
img.images{
  border-radius: 50%;
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
                        <li class="breadcrumb-item active" aria-current="page">Add Tutorial</li>
                    </ol>
                </nav>
                <h4 class="mb-1 mt-0">Add Tutorial Video</h4>
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

                        <form action="<?php echo url('/tutorial-save'); ?>" method="post" name="save-admin" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row mb-3" >
                              <label for="inputEmail3" class="col-3 col-form-label">URL <span class="required">*</span></label>
                              <div class="col-9">
                              <p>Hint: Upload Only the Blue Color.</p>
                              <p>https://www.youtube.com/embed/<span style="color: blue;background-color: lightgray">TnwDNiQ4jRY</span></p>
                              <input type="text" name="url" class="form-control" placeholder="Enter Your URL" required>
                              </div>
                              <label for="inputEmail3" class="col-3 col-form-label"> Topic <span class="required">*</span></label>
                              <div class="col-9" style="padding-top:10px">
                                  <input type="text" name="topic" class="form-control" placeholder="Enter Your Topic" required>
                              </div>
                              <label for="inputEmail3" class="col-3 col-form-label">Description <span class="required">*</span></label>
                              <div class="col-9" style="padding-top:10px">
                                  <input type="text" name="description" class="form-control" placeholder="Enter Your Description" required>
                              </div>

                              <label for="inputEmail3" class="col-3 col-form-label">Image <span class="required">*</span></label>
                              <div class="col-9" style="padding-top:10px">
                                  <input type="file" name="image" class="form-control" required>
                              </div>
                            </div>
                         
                            <div class="form-group mb-0 justify-content-end row">
                              <div class="col-9">
                                <button type="submit" class="btn btn-info">Submit</button>
                                <a href="{{ route('manage-admins') }}"><button type="button" class="btn btn-success">Back</button></a>
                              </div>
                            </div>
                        </form>
                    </div>  <!-- end card-body -->
                </div>  <!-- end card -->
                            
                            <div class="card">
                            <div class="card-body">
                                <table id="idsssss" class="table dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>Sl.no</th>
                        <th>Image</th>
                                            <th>URL</th>  
                        <th>Topic</th>                             
                        <th>Description</th>
                                            <th>Action</th> 
                        <th>Status</th>                
                                        </tr>
                                    </thead>
                                    <tbody>
                    @foreach($tutorial as $value)
                                        <tr>
                                            <td>{{$loop->index +1}}</td>
                      <td><img src="{{url('/')}}/video/images/{{$value->image}}" style="height: 50px;width: 50px;" class="images"></td>
                                        <td>{{$value->url}}</td>
                      <td>{{ucwords($value->topic)}}</td>
                      <td>{{ucwords($value->description == '' ? 'No Description' : $value->description)}}</td>
                                            <td>
                                             <div class="icon-item">
                        <a href="{{url('/')}}/tutorial-edit/{{$value->id}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </a>
                        <a href="{{url('/')}}/tutorial-delete/{{$value->id}}" onclick="return confirm('Are you sure you want to delete this user.')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg>
                        </a>
                      <td>
                     </div>
                                  </td>
                    <td>
                      @if($value->is_active == 1)
                          <label class="switch" onchange="myTutorialOff({{$value->id}});" style="margin-left: -97px;">
                          <input type="hidden" name="is_active" id="is_active_off" value="0">
                          <input type="checkbox" checked>
                          <span class="slider round"></span>
                        </label>
                        @else
                        <label class="switch"  onclick="myTutorialOn({{$value->id}});" style="margin-left: -97px;">
                          <input type="hidden" name="is_active" id="is_active_on" value="1">
                          <input type="checkbox" >
                          <span class="slider round"></span>
                        </label>
                      @endif
                     </td>
                            </tr>
                  @endforeach
                                </tbody>
                            </table>
            </div> <!-- end card body-->
          </div> <!-- end card -->
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

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->
<script type="text/javascript">
function myTutorialOff(id){
    var status = document.getElementById('is_active_off').value;
   
    $.ajax({
         method: "POST",
         url: "/tutorial-off/"+id,
         data: {
                _token: '<?php echo csrf_token();?>',
                id: id,
                status:status
            },
        success: function (data) {
                alert(data);
                window.location.reload()
            }    
    });
}
function myTutorialOn(id){
    var status = document.getElementById('is_active_on').value;
   
    $.ajax({
         method: "POST",
         url: "/tutorial-off/"+id,
         data: {
                _token: '<?php echo csrf_token();?>',
                id: id,
                status:status
            },
        success: function (data) {
                alert(data);
                window.location.reload()
            }    
    });
}
</script>
@endsection
