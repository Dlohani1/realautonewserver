@extends('layouts.app')
@section('title', 'Email API Capture')
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
                                <li class="breadcrumb-item active" aria-current="page">STRIPE Setting</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Update Stripe API Capture</h4>
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

                                <form action="<?php echo url('/post-stripe-api-capture'); ?>" method="post" name="post-stripe-api-capture" class="form-horizontal">
                                    @csrf
                                    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">Stripe Publish Key<span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="publish_key" class="form-control" placeholder="Publsih Key" value="{{ $publish_key }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                    <label for="inputpassword" class="col-3 col-form-label">Stripe Secret Key<span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="secret_key" class="form-control" placeholder="Secret Key" required value="{{ $secret_key }}">
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
                        <?php echo date('Y'); ?> &copy; Realauto. All Rights Reserved. Crafted with <i class='uil uil-heart text-danger font-size-12'></i> by <a href="#" target="_blank">Realauto</a>
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


    </script>
@endpush
