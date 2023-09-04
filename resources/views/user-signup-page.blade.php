<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Realauto :: Signup Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{url('/assets/home/img/fab-i.png')}}" type="image/x-icon" />
    <link rel="icon" href="{{url('/assets/home/img/fab-i.png')}}" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- App css -->
    <link href="{{url('/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Google Tag Manager -->
   
    <!-- End Google Tag Manager -->
    <style>
        .form-group {
            margin-bottom: 10px;
        }
    </style>
</head>

<body class="authentication-bg">


<div class="account-pages my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row">

                            <div class="col-md-12 p-5">
                                <div class="mx-auto mb-4">
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


                                <form action="{{ route('user_save_signup') }}" class="authentication-form" method="post" name="userSignup">
                                    @csrf
                                    <div class="form-group {{ $errors->has('name')? 'has-error':'' }}">
                                        <label class="form-control-label">Name</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-user-o" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter your name">
                                        </div>
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>

                                    <div class="form-group {{ $errors->has('email')? 'has-error':'' }}">
                                        <label class="form-control-label">E-Mail</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email">
                                        </div>
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    </div>

                                    <div class="form-group {{ $errors->has('mobile')? 'has-error':'' }}">
                                        <label class="form-control-label">Contact No</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="mobile" class="form-control" id="mobilel" placeholder="Enter your contact no">
                                        </div>
                                        <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                    </div>

                                    <div class="form-group {{ $errors->has('password')? 'has-error':'' }}">
                                        <label class="form-control-label">Password</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password">
                                        </div>
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    </div>

                                    <div class="form-group {{ $errors->has('conf-password')? 'has-error':'' }}">
                                        <label class="form-control-label">Confirm Password</label>
                                        <div class="input-group input-group-merge">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <input type="password" name="conf-password" class="form-control" id="password" placeholder="Confirm Password">
                                        </div>
                                        <span class="text-danger">{{ $errors->first('conf-password') }}</span>
                                    </div>

                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-primary btn-block" type="submit"> Sign up</button>
                                    </div>
                                </form>
                                <div class="py-3 text-center"><span class="font-size-16 font-weight-bold">Or</span></div>
                                <div class="text-center"><p class="text-gray">Already a Member? <a href="{{ route('login') }}">Sign In</a></p></div>
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
