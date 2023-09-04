@extends('layouts.app')
@section('title', 'Whatsapp API Capture')
@section('content')

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
                                <li class="breadcrumb-item active" aria-current="page">WhatsApp API</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Update WhatsApp API</h4>
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

                                <form action="<?php echo url('/post-whats-app-api-capture'); ?>" method="post" name="post-whats-app-api-capture" class="form-horizontal">
                                    @csrf

                                    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">WhatsApp API Key <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="whatsapp_api_key" class="form-control" placeholder="WhatsApp API Key" required value="{{ $whatsappapikey }}" @if(Auth::user()->name != 'admin' && Auth::user()->whatsapp_api_key_lock == 2) readonly @endif>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">WhatsApp Username <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="whatsapp_username" class="form-control" placeholder="WhatsApp Username" required value="{{ $whatsapp_username }}"  @if(Auth::user()->name != 'admin' && Auth::user()->whatsapp_api_key_lock == 2) readonly @endif>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-info">Submit</button>
                                            <a href="{{ route('dashboard') }}"><button type="button" class="btn btn-success">Back</button></a>
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

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

@endsection
@push('scripts')
    <script>
        function GetProjects(projectid){
            if(projectid == 2){
                $("#hideprojects_name").show();
                $("#hideprojects_list").hide();
            }else{
                $("#hideprojects_list").show();
                $("#hideprojects_name").hide();
            }
        }

        function GetSegment(segmentid){

            if(segmentid == 2){
                $("#hidesegment_name").show();
                $("#hidesegment_list").hide();
            }else{
                $("#hidesegment_list").show();
                $("#hidesegment_name").hide();
            }

        }
    </script>
@endpush
