@extends('layouts.app')
@section('title', 'Change Your Password')
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
                                <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Change Password</h4>
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

                                <?php if(Session::has('error')) { ?>
                                    <div class="alert alert-danger"> <?php echo Session::get('error'); ?> </div>
                                <?php } ?>

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


				<div class="form-group row mb-3">
                                    <label for="inputEmail3" class="col-3 col-form-label">Registered Mobile No<span class="required">*</span></label>
                                    <div class="col-6">
                                        <input type="text" class="form-control" value="{{substr($user->phone_no, -10)}}" readonly disabled>
                                    </div>
                                </div>


                                <div class="form-group row mb-3">
                                    <label for="inputEmail3" class="col-3 col-form-label">Registered Email<span class="required">*</span></label>
                                    <div class="col-6">
                                        <input type="text" class="form-control" value="{{$user->email}}" readonly disabled>
                                    </div>
                                </div>

                                <form action="{{ route('post-user-update-password') }}" method="post" name="post-user-update-password" class="form-horizontal">
                                    @csrf
                                    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">Old Password<span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="password" name="oldpassword" class="form-control" placeholder="Enter Your Old Password" required >
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">New Password<span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="password" name="new_password" class="form-control" placeholder="Enter Your New Password" required>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">Confirm Password<span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="password" name="c_f_password" class="form-control" placeholder="Confirm Your New Password" required >
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


    </script>
@endpush
