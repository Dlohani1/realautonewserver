<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Realauto :: Forgot Password</title>
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
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WKJRLL7');
    </script>
    <!-- End Google Tag Manager -->
</head>

<body class="authentication-bg">

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WKJRLL7" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
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
                                        <img src="{{url('/assets/home/img/logo.png')}}" alt="" height="50" class="mx-auto d-block"/>
                                    </a>
                                </div>

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

                                <?php if(Session::has('success')) { ?>
                                    <div class="alert alert-success"> <?php echo Session::get('success'); ?> </div>
                                <?php } ?>

                                <?php if(Session::has('error')) { ?>
                                    <div class="alert alert-danger"> <?php echo Session::get('error'); ?> </div>
                                <?php } ?>

                                <form action="{{ route('save_forgot_password') }}" method="post" name="forgot">
                                    @csrf
                                    <div class="form-group mb-none">
                                        <div class="input-group">
                                            <input name="email" type="email" placeholder="E-mail" class="form-control input-lg" />
                                            <span class="input-group-btn">
					                            <button class="btn btn-primary btn-lg" type="submit">Reset!</button>
					                        </span>
                                        </div>
                                    </div>
                                </form>
                                <p class="text-center mt-lg">Remembered? <a href="{{ route('login') }}">Sign In!</a>
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
