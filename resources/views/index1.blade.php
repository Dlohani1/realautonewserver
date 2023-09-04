<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{url('/assets/home/img/fab-i.png')}}" type="image/x-icon" />
    <link rel="icon" href="{{url('/assets/home/img/fab-i.png')}}" type="image/x-icon" />

    <!-- Bootstrap v4.0 CSS -->
    <!--<link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">-->
    <link rel="stylesheet" href="{{url('/assets/home/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{url('/assets/home/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{url('/assets/home/css/owlcarousel/owl.carousel.css')}}">
    <link rel="stylesheet" href="{{url('/assets/home/css/owlcarousel/owl.theme.default.css')}}">
    <link rel="stylesheet" href="{{url('/assets/home/css/my_style.css')}}">
    <link rel="stylesheet" href="{{url('/assets/home/css/fonts.css')}}">
    <title>Real Auto</title>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WKJRLL7');</script>
    <!-- End Google Tag Manager -->
</head>
<body>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WKJRLL7" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <header class="top-nav">
        <div class="container">
            <div class="row menu-bg">
                <div class="col-lg-6 col-sm-6 w-sm-60">
                    <div class="logo-div"><a href="{{ url('/') }}"><img src="{{url('/assets/home/img/logo.png')}}" alt="Logo"></a></div>
                </div>
                <div class="col-lg-6 col-sm-6 w-sm-40"><button class="btn btn-border pull-right" onclick="window.location.href='/login'"><b>Login</b> / Signup</button></div>
            </div>
        </div>
    </header>

    <section class="heder-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-7 banner-top">
                    <h5>"Real Estate Follow Up Automation Tools"</h5>
                    <div class="d-flex">
                        <div class="col-10 p-0"><h2>Automate Your Follow Up By</h2></div>
                        <div class="col-2 p-0">
                            <div class="owl-carousel owl-theme owl-bann">
                                <div class="item"><img src="{{url('/assets/home/img/whatsapp-i.png')}}" alt="" style="width: 42px;margin-top: 5px;"></div>
                                <div class="item"><img src="{{url('/assets/home/img/sms.png')}}" alt="" style="width: 42px;margin-top: 5px;"></div>
                                <div class="item"><img src="{{url('/assets/home/img/gmail.png')}}" alt="" style="width: 42px;margin-top: 5px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="text-scall-w p-0">
                            <div class="owl-carousel owl-theme owl-bann tex-scall-bg">
                                <div class="item"><h4>Acquire</h4></div>
                                <div class="item"><h4>Engage</h4></div>
                                <div class="item"><h4>Convert</h4></div>
                            </div>
                        </div>
                        <div class=""><h4>&nbsp; Your LEADS 3X</h4></div>
                    </div>
                    <p class="">Best Automation Software to do Complete <b><i>(Whatsapp/Email/Sms)</i></b> Automation to Increase the Conversion with Less Cost.</p>
                    <button onClick="window.location.href='#form-1';" class="btn btn-custom-big mb-4">Request Live Demo &nbsp; <i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
                </div>

                <div class="col-lg-4 col-md-5" id="form-1">
                    <div class="hero-from">
                        <h4>Need More Information?</h4>
                        <h5>(Fill the details below)</h5>
                        <?php if(Session::has('message')) { ?>
                            <div class="alert alert-success"> <?php echo Session::get('message'); ?> </div>
                        <?php } ?>
@error('email')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
@error('phone')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
                        <form action="{{ route('contact-more-information') }}" class="from-box" method="POST" name="more_information">
                            @csrf
                            <div class="form-group">
                                <input type="text" value="{{old('name', "")}}" name="name" class="form-control" id="exampleFormControlInput1" placeholder="Enter Your Name" required>
                            </div>
                            <div class="form-group">
                                <input type="text" value="{{old('phone', "")}}" name="phone" class="form-control" id="exampleFormControlInput2" placeholder="Enter Your Phone" required>
                            </div>
                            <div class="form-group">
                                <input type="email" value="{{old('email', "")}}" name="email" class="form-control" id="exampleFormControlInput3" placeholder="Enter Your Email Id" required>
                            </div>
                            <div class="form-group">
                                <input type="text" value="{{old('company_name', "")}}" name="company_name" class="form-control" id="exampleFormControlInput4" placeholder="Your Company Name" required>
                            </div>
                            <button class="btn btn-submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container pt-5 pb-4">
            <div class="row">
                <div class="col-md-12 hed-text">
                    <h3>80% of the site visit happen after the <span>5th Follow Up...</span></h3>
                    <h5>(What, If I Give a Complete Automation Followup System to Increase your Site Visit)</h5>
                </div>
            </div>
            <div class="row mt-5 mb-5">
                <div class="col-md-5 text-center mb-4">
                    <img src="{{url('/assets/home/img/colap.png')}}" alt="" class="img-fluid">
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-6 right-side-t">
                    <p>1. &nbsp; <span>44% of salespeople give up after one follow-up.</span></p>
                    <p>2. &nbsp; <span>60% of customers say no four times before saying yes.</span></p>
                    <p>3. &nbsp; <span>Only 3% of your market is actively buying; 56% are not ready, while 40% are poised to begin.</span></p>
                    <p><span>The only thing what you want is a proper follow up system to sell your property. <i style="color: #fa9813;font-size: 20px;">Realauto</i>  can help you automate your lead follow up and increase your property sales to up to 2X.</span></p>
                    <button onClick="window.location.href='#form-1';" class="btn btn-custom-big mt-4">Request Live Demo &nbsp; <i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 p-0">
                    <div class="benefits-bar">
                        <h3>How <b>Realauto</b> Can Benefits You</h3>
                        <div class="et_pb_bottom_inside_divider" style=""></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section style="background-color:#edf3ff;">
        <div class="container pt-4 pb-5">
            <div class="row mt-5 mb-5">
                <div class="col-md-6 right-side-t pr-5">
                    <p>1. &nbsp; <span>Manage Enquire & Lead in a single dashboard.</span></p>
                    <p>2. &nbsp; <span>Set up your Follow Up sequence once and forget about the follow-up. Realauto will take care everything by WhatsApp, Email & Sms followup.</span></p>
                    <p>3. &nbsp; <span>Set up daily, weekly or monthly follow up using WhatsApp, Sms, Email.</span></p>
                    <p>4. &nbsp; <span>Increase the brand value.</span></p>
                    <p>5. &nbsp; <span>Reduce manpower for the follow-up.</span></p>
                    <button onClick="window.location.href='#form-1';" class="btn btn-custom-big mt-4 mb-5">Know More &nbsp; <i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{url('/assets/home/img/business-people.png')}}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <section style="background-color:#6C2EB9;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-7 text-white autoEmailer-text">
                    <h3><i>Realauto is your 24*7 Virtual Sales Executive who helps you to closing your deal up to 4X.</i></h3>
                    <!--<p><i>The difference is AutoEmailer runs 24 x7 and never misses to follow up the lead in time</i></p>-->
                    <button onClick="window.location.href='#form-1';" class="btn btn-custom-big mt-3">Request Live Demo &nbsp; <i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
                </div>
                <div class="col-lg-1 col-md-1"></div>
                <div class="col-lg-5 col-md-4 text-center">
                    <img src="{{url('/assets/home/img/robots-1.png')}}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-bg">
        <div class="container-fluid" style="background-color:#282347; padding:50px 15px;">
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <div>
                                <img src="img/footer-logo.png" alt="" style="width: 150px;">
                            </div>
                            <ul class="socel-i">
                                <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <table class="table-foot">
                                <tr>
                                    <td style="display: block;"><i class="fa fa-envelope" aria-hidden="true"></i></td>
                                    <td>
                                        <h5>Write to Us</h5>
                                        <p><a href="#" style="color:#FCFCFC;">support@realauto.in</a></p>
                                    </td>
                                </tr>
                            </table>
                            <table class="table-foot">
                                <tr>
                                    <td style="display: block;"><i class="fa fa-phone" aria-hidden="true"></i></td>
                                    <td>
                                        <h5>Call Us</h5>
                                        <p><a href="#" style="color:#FCFCFC;">+91 77779 28708</a></p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <table class="table-foot">
                                <tr>
                                    <td style="display: block;"><i class="fa fa-map-marker" aria-hidden="true"></i></td>
                                    <td>
                                        <h5>Registered Office:</h5>
                                        <p>
                                            <a href="#" style="color:#FCFCFC;">
                                            #68, 100 Feet Ring Rd, Vysya Bank Colony, Stage 2, BTM 2nd Stage, Bengaluru, Karnataka 560076
                                            </a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <table class="table-foot">
                                <tr>
                                    <td style="display: block;"><i class="fa fa-calendar-times-o" aria-hidden="true"></i></td>
                                    <td>
                                        <h5>Working Hours</h5>
                                        <p>
                                            <b>Monday-Saturday</b><br>
                                            09:30 a.m. to 07:30 p.m.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="text-white pt-4 pb-2" style="font-size:14px;">© Copyright <?php echo date('Y'); ?> RealAuto | <a href="#" style="color:#FCFCFC;">Terms and Conditions</a> 
| <a href="#" style="color:#FCFCFC;">Privacy Policy</a></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap 4.0 JS -->
    <script src="{{url('/assets/home/js/jquery-3.2.1.slim.min.js')}}"></script>
    <script src="{{url('/assets/home/js/popper.min.js')}}"></script>
    <script src="{{url('/assets/home/js/bootstrap.min.js')}}"></script>
    <script src="{{url('/assets/home/css/owlcarousel/owl.carousel.min.js')}}"></script>
    <script>
        $('.owl-bann').owlCarousel({
            loop:true,
            margin:0,
            autoplay:true,
            autoplayTimeout:2000,
            responsiveClass:true,
            responsive:{
                0:{
                   items:1,
                   nav:false
                },
                600:{
                   items:1,
                   nav:false
                },
                1000:{
                   items:1,
                   nav:false,
                   loop:true
                }
            }
        });

        $('.owl-testi').owlCarousel({
            loop:true,
            margin:30,
            autoplay:true,
            autoplayTimeout:10000,
            responsiveClass:true,
            responsive:{
                0:{
                   items:1,
                   nav:false
                },
                600:{
                   items:2,
                   nav:false
                },
                1000:{
                   items:2,
                   nav:false,
                   loop:true
                }
           }
        });

        $('.owl-partners').owlCarousel({
            loop:true,
            margin:30,
            autoplay:true,
            autoplayTimeout:10000,
            responsiveClass:true,
            responsive:{
                0:{
                    items:2,
                    nav:false
                },
                600:{
                    items:3,
                    nav:false
                },
                1000:{
                    items:6,
                    nav:false,
                    loop:true
                }
            }
        });
    </script>
</body>
</html>
