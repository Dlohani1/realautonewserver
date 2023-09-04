<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>RealAuto :: @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Active Digital Technology" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{url('/assets/home/img/fab-i.png')}}" type="image/x-icon" />
    <link rel="icon" href="{{url('/assets/home/img/fab-i.png')}}" type="image/x-icon" />
    @include('elements.topheader')
</head>

<body>

<!-- Google Tag Manager (noscript) -->


<!-- End Google Tag Manager (noscript) -->


    <!-- Begin page -->
    <div id="wrapper">
        <!-- Topbar Start -->
        @include('elements.topbar')
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu">
            <div class="sidebar-content">
                <!--- Sidemenu -->
                @include('elements.leftsidebar')
                <!-- End Sidebar -->
                <div class="clearfix"></div>
            </div>
            <!-- Sidebar -left -->
        </div>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->

        @yield('content')

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->
    @include('elements.footer_js')

    @stack('scripts')
</body>
</html>
