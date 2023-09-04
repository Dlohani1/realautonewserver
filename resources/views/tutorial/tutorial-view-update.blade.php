@extends('layouts.app')
@section('title', 'Update Tutorial')
@section('content')
<style type="text/css">
    img.images:hover {
    height: 230px !important;
    width: 320px !important;
}
</style>
<div class="content-page">
<div class="content">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row page-title">
            <div class="col-md-12">
                <nav aria-label="breadcrumb" class="float-right mt-1">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Update Tutorial</li>
                    </ol>
                </nav>
                <h4 class="mb-1 mt-0">Update Tutorial Video</h4>
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

                        <form  method="post" name="save-admin" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row mb-3">
                                <label for="inputEmail3" class="col-3 col-form-label">URL <span class="required">*</span></label>
                                <div class="col-9">
                                    <input type="text" name="url" value="{{$tutorialupdate->url}}" class="form-control" placeholder="Enter your URL">
                                </div><br><br>
                                 <label for="inputEmail3" class="col-3 col-form-label">Topic <span class="required">*</span></label>
                                <div class="col-9">
                                    <input type="text" name="topic" value="{{$tutorialupdate->topic}}" class="form-control" placeholder="Enter your Topic">
                                </div><br><br>

                                <label for="inputEmail3" class="col-3 col-form-label">Description <span class="required">*</span></label>
                                <div class="col-9">
                                    <input type="text" name="description" value="{{$tutorialupdate->description}}" class="form-control" placeholder="Enter your Description">
                                </div><br><br>

                                <label for="inputEmail3" class="col-3 col-form-label">Image <span class="required">*</span></label>
                                <div class="col-9">
                                    <input type="file" name="image" class="form-control">
                                    <br>
                                    <img src="{{url('/')}}/video/images/{{$tutorialupdate->image}}" style="height: 50px;width: 50px;border-radius: 50%;" class="images">
                                </div>
                            </div>
                            <div class="form-group mb-0 justify-content-end row">
                                <div class="col-9">
                                    <button type="submit" class="btn btn-info">Submit</button>
                                    <a href="{{ URL::previous() }}"><button type="button" class="btn btn-success">Back</button></a>
                                </div>
                            </div>
                        </form>
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
