<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>RealAuto :: Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{url('/assets/home/img/fab-i.png')}}" type="image/x-icon" />
    <link rel="icon" href="{{url('/assets/home/img/fab-i.png')}}" type="image/x-icon" />
    <!-- App css -->
    <link href="{{url('/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Google Tag Manager -->


    <!-- End Google Tag Manager -->
    <style>
        .text-gray {
            color: #5f7d95;
        }
        .text-gray a{
            color: #0d60c8;
            cursor: pointer;
            font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>

<body class="authentication-bg">
    

<!-- End Google Tag Manager (noscript) -->

<div class="account-pages my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row">

                            <div class="col-md-12 p-5">
                                <div class="mx-auto mb-5">
                                    <a href="{{ url('/') }}">
                                        <img src="{{url('/assets/home/img/logo.png')}}" alt="Logo" height="50" class="mx-auto d-block"/>
                                    </a>
                                </div>

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

                                <?php if (Session::has('login-error-message')){ ?>
                                    <div class="alert alert-danger"><?php echo  Session::get('login-error-message'); ?></div>
                                <?php } ?>


                                <form action="{{ url('login') }}" class="authentication-form" method="post" name="userlogin">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-control-label">Email Address</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="icon-dual" data-feather="mail"></i>
                                                </span>
                                            </div>
                                            <input value="{{old('email', "")}}" type="email" name="email"  class="form-control" id="email" placeholder="Enter your email">
                                        </div>
                                    </div>

                                    <div class="form-group mt-4">
                                        <label class="form-control-label">Password</label>
                                        <a href="{{ route('forgot_password') }}" class="float-right text-muted text-unline-dashed ml-1">Forgot your Password?</a>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="icon-dual" data-feather="lock"></i>
                                                </span>
                                            </div>
                                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password">
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                   id="checkbox-signin" checked>
                                            <label class="custom-control-label" for="checkbox-signin">Remember Me</label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-primary btn-block" type="submit"> Log In</button>
                                    </div>
                                </form>
                                <div style="display:none" class="py-3 text-center"><span class="font-size-16 font-weight-bold">Or</span></div>
                                <div style="display:none" class="text-center"><p class="text-gray">Not a Member? <a href="{{ route('user_signup') }}">Sign up</a></p></div>
                            </div>
                        </div>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->
            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->

<!-- Vendor js -->
<script src="{{url('/assets/js/vendor.min.js')}}"></script>
<!-- App js -->
<script src="{{url('/assets/js/app.min.js')}}"></script>
</body>
</html>
