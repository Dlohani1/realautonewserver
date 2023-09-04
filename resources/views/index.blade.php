<!doctype html>
<html lang="en">

<head>


    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ url('/') . '/img/fab-i.png' }}" type="image/x-icon" />
    <link rel="icon" href="{{ url('/') . '/img/fab-i.png' }}" type="image/x-icon" />

    <!-- Bootstrap v4.0 CSS -->
    <!--<link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">-->
    <link rel="stylesheet" href={{ url('/') . '/css/font-awesome.min.css' }}>
    <link rel="stylesheet" href={{ url('/') . '/css/bootstrap.min.css' }}>
    <link rel="stylesheet" href={{ url('/') . '/css/owlcarousel/owl.carousel.css' }}>
    <link rel="stylesheet" href={{ url('/') . '/css/owlcarousel/owl.theme.default.css' }}>
    <link rel="stylesheet" href={{ url('/') . '/css/my_style.css' }}>
    <link rel="stylesheet" href={{ url('/') . '/css/fonts.css' }}>
    <title>Lead Marketing Automation with CRM - RealAuto</title>
    <meta name="description"
        content="RealAuto increases your sales by automating the entire lead generation and responding process by sending automatic whatsapp/emails/sms to your prospects.">
    <!-- HTML5 shim and Respond.js"}} for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js"}} doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js" }}"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js" }}"></script>
    <![endif]-->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>

</head>

<body>

    <style>
        .pricing-sect {
            background-color: #e5e9ea;
            padding: 60px 0px;
        }

        .monthly-package {
            border-radius: 10px;
            overflow: hidden;
            background-color: #FFFFFF;
            box-shadow: -1px -1px 5px 0px rgba(0, 0, 0, 0.35);
            -webkit-box-shadow: -1px -1px 5px 0px rgba(0, 0, 0, 0.35);
            -moz-box-shadow: -1px -1px 5px 0px rgba(0, 0, 0, 0.35);
            max-width: 425px;
            padding-bottom: 30px;
        }

        .hed {
            background-color: #f47b20;
            color: #FFFFFF;
            text-transform: uppercase;
            padding: 15px 0px;
            text-align: center;
            font-size: 22px;
        }

        ul.monthly-list {
            margin: 20px 20px;
            padding: 0;
        }

        ul.monthly-list li {
            list-style-type: none;
            padding: 6px 0px;
            color: #000;
            font-weight: 300;
            display: flex;
        }

        ul.monthly-list li i {
            color: #f47b20;
            margin-right: 7px;
        }

        .amm-btn {
            color: #fff;
            font-weight: 600;
            font-size: 22px;
            padding: 10px 50px;
            background: rgb(162, 34, 243);
            background: linear-gradient(98deg, rgba(162, 34, 243, 1) 0%, rgba(155, 159, 248, 1) 100%);
            border-radius: 40px;
            width: max-content;
            margin: 0px auto;
        }

        .hed-credit {
            color: #535353;
            font-size: 30px;
            text-align: center;
        }

        .hrd-line {
            width: 75px;
            height: 2px;
            background-color: #C3C3C3;
            margin: 10px auto;
        }

        .plan-box-1 {
            background-color: #a220f4;
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            padding: 15px 10px;
            color: #FFFFFF;
            margin: 35px 0px 0px 0px;
        }

        .plan-box-2 {
            background-color: #f47b20;
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            padding: 15px 10px;
            color: #FFFFFF;
            margin: 35px 0px 0px 0px;
        }

        .am-box-left {
            border-right: 1px solid #FFFFFF;
            padding: 0px 30px;
        }

        .am-box-left h4 {
            color: #FFFFFF;
            font-size: 36px;
            margin-bottom: 0px;
        }

        .am-box-left p {
            color: #FFFFFF;
            font-size: 20px;
            margin-bottom: 0px;
            font-weight: 300;
        }

        .am-box-right {
            padding-left: 30px;
        }

        .am-box-right p {
            color: #FFFFFF;
            font-size: 22px;
            margin-bottom: 0px;
            text-align: left;
            font-weight: 300;
        }

        .subscription-plan-sect {
            padding: 50px 0px;
        }

        .hed-plan {
            color: #584f50;
            font-size: 30px;
            font-weight: 600;
        }

        .hrd-line-or {
            width: 100px;
            height: 2px;
            background-color: #f47b20;
            margin: 10px auto;
        }

        .add-on-box {
            box-shadow: -1px -1px 5px 0px rgba(0, 0, 0, 0.35);
            -webkit-box-shadow: -1px -1px 5px 0px rgba(0, 0, 0, 0.35);
            -moz-box-shadow: -1px -1px 5px 0px rgba(0, 0, 0, 0.35);
            max-width: 425px;
            border-radius: 10px;
            margin-top: 30px;
        }

        .add-on-box-left {
            width: 80px;
        }

        .add-on-box-right1 {
            background-color: #a220f4;
            border-radius: 10px;
            padding: 15px;
            color: #FFFFFF;
            overflow: hidden;
            text-align: left;
        }

        .add-on-box-right1 h4 {
            color: #FFFFFF;
            border-bottom: 1px solid;
            padding-bottom: 10px;
        }

        .add-on-box-right1 h4 span {
            font-size: 16px;
            font-weight: 300;
        }

        .add-on-box-right1 p {
            color: #FFFFFF;
            font-weight: 300;
            margin-bottom: 0;
        }

        .add-on-box-right1 p i {}

        .add-on-box-right2 {
            background-color: #f47b20;
            border-radius: 10px;
            padding: 15px;
            color: #FFFFFF;
            overflow: hidden;
            text-align: left;
        }

        .add-on-box-right2 h4 {
            color: #FFFFFF;
            border-bottom: 1px solid;
            padding-bottom: 10px;
        }

        .add-on-box-right2 h4 span {
            font-size: 16px;
            font-weight: 300;
        }

        .add-on-box-right2 p {
            color: #FFFFFF;
            font-weight: 300;
            margin-bottom: 0;
        }

        .add-on-box-right2 p i {}

        .bor-r-4 {
            border-right: 4px solid #d7d7d7;
        }


        .checkbox-xl {
            margin-top: -40px;
            margin-left: 25px;
        }

        .checkbox-xl .custom-control-label::before,
        .checkbox-xl .custom-control-label::after {
            top: 1.2rem;
            width: 2.5rem;
            height: 2.5rem;
        }

        .checkbox-xl .custom-control-label {
            padding-top: 23px;
            padding-left: 10px;
        }


        .profile-section {
            background-color: #cce4ed;
            padding: 60px;
        }

        .img-prof {
            border: 5px solid #8fbecf;
            border-radius: 50%;
            overflow: hidden;
        }

        .img-prof img {}

        .prof-details {
            margin-left: 20px;
        }

        .prof-details h4 {
            font-weight: 900;
            font-size: 30px;
            margin-bottom: 20px;

        }

        .prof-details p {}







        @media (max-width: 767px) {}

        @media (max-width: 375px) {}


        @media (max-width: 326px) {}


        @media (max-width: 320px) {}
    </style>


    <!-- Google Tag Manager (noscript) -->


    <!-- End Google Tag Manager (noscript) -->
    <header class="top-nav" id="go-top">
        <div class="container">
            <div class="row menu-bg">
                <div class="col-lg-6 w-sm-60">
                    <style>
                        .logo-div img {
                            width: 250px;
                            /* Adjust the width to make the logo bigger */
                            height: auto;
                            /* Maintain the aspect ratio */
                        }
                    </style>

                    <div class="logo-div">
                        <img src="{{ url('/') . '/img/logo.png' }}" alt="">
                    </div>

                </div>
                <div class="col-lg-6 w-sm-40">
                    <!--<nav class="navbar navbar-expand-lg navbar-light pull-right">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"><i class="fa fa-bars" aria-hidden="true"></i></span>
              </button>
            
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                  <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Contact us</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">Help</a>
                  </li>
                  <li class="nav-item">
                   <button class="btn btn-border pull-right" onclick="window.location.href="{{ url('/') . 'login.html' }}">Login / Signup</button>
                  </li>
                </ul>
              </div>
            </nav>-->
                    <style>
                        .btn {
                            font-size: 18px;
                            /* Adjust the value as per your preference */
                        }
                    </style>

                    <button class="btn btn-border pull-right" onclick="window.location.href='<?php echo url('/') . '/login'; ?>'">
                        <strong>Login / Signup</strong>
                    </button>


                </div>
            </div>
        </div>
    </header>

    <section class="heder-top">
        <div class="container-fluid">
            <div class="row">
                <div class="bton-bg"></div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-7 banner-top">
                    <h4>A Complete Lead Automation System</h4>
                    <img src="{{ url('/') . '/img/grap-v2.png' }}" alt="" class="img-fluid">
                    <div class="bott-section">
                        <div class="d-flex">
                            <table class="cust-table">
                                <tr>
                                    <td>
                                        <h2>Automate Your</h2>
                                    </td>
                                    <td style="display:flex; width:30px;">
                                        <div class="owl-carousel owl-theme owl-bann">
                                            <div class="item"><img src="{{ url('/') . '/img/google-ads.png' }}"
                                                    alt="" style="width: 28px;margin-top: 5px;"></div>
                                            <div class="item"><img src="{{ url('/') . '/img/facebook-ads.png' }}"
                                                    alt="" style="width: 28px;margin-top: 5px;"></div>
                                        </div>
                                    </td>
                                    <td>
                                        <h2>Leads</h2>
                                    </td>
                                </tr>
                            </table>
                            <!--<div class="col-8 p-0"><h2>Automate Your</h2></div>
            <div class="col-1 p-0">
              <div class="owl-carousel owl-theme owl-bann">
                <div class="item"><img src="img/google-ads.png'}}" alt="" style="width: 28px;margin-top: 5px;"></div>
                <div class="item"><img src="img/facebook-ads.png'}}" alt="" style="width: 28px;margin-top: 5px;"></div>
              </div>
              </div>
              <div class="col-1 p-0"><h2>Lead</h2></div>-->

                        </div>
                        <!--<h5>Automate Your Follow Up By <span>3x</span></h5>-->
                        <!--<button class="btn btn-custom-big mb-2" style="text-transform:uppercase; font-weight:600;">get 3 Days FREE Trial &nbsp; <i class="fa 
fa-long-arrow-right"></i></button>-->
                        <button class="btn btn-custom-big-w mb-2"
                            style="text-transform:uppercase; font-weight:600;">Get a Free Account &nbsp; <i
                                class="fa fa-long-arrow-right"></i></button> &nbsp;
                        <!-- <button class="btn btn-custom-big-b mb-2" style="text-transform:uppercase; font-weight:600;">Book a Demo
&nbsp; <i class="fa fa-long-arrow-right"></i></button> -->
                        <button href="#" data-toggle="modal" data-target="#videoModal"
                            data-src="https://www.youtube.com/embed/6zxdUvul0yE?playlist=6zxdUvul0yE&loop=1"
                            class="btn btn-custom-big-b mb-2 video-btn"
                            style="text-transform:uppercase; font-weight:600;">Watch the Demo &nbsp; <i
                                class="fa fa-long-arrow-right"></i></button>
                        <div class="modal fade" id="videoModal" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content" style="width: max-content; margin-top: 9%;">
                                    <div class="modal-body">
                                        <iframe id="video" src="" frameborder="0"
                                            allow="autoplay; fullscreen; picture-in-picture" width="560"
                                            height="315" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-4 col-md-5" id="form-1">
                    <div class="hero-from">
                        <!--<h4>Get <span>3 Days FREE</span> Trial</h4>-->
                        <h4>Create a <span>FREE</span> Account</h4>
                        <h5>(Fill in your details below)</h5>
                        <?php if(Session::has('message')) { ?>
                        <div class="alert alert-success"> <?php echo Session::get('message'); ?> </div>
                        <?php } ?>
                        @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @error('phone')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <form action="{{ route('contact-more-information') }}" method="POST" class="from-box"
                            onsubmit="return validateForm()">
                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" value="{{ old('name', '') }}" name="name"
                                    class="form-control" id="exampleFormControlInput1" placeholder="Enter Your Name"
                                    required>
                            </div>
                            <div class="form-group">
                                <label>Phone # with country code</label>
                                <input type="tel" value="{{ old('phone', '') }}" name="phone"
                                    class="form-control" id="phone" placeholder="+19709851535"
                                    autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label>Email ID</label>
                                <input type="email" value="{{ old('email', '') }}" name="email"
                                    class="form-control" id="exampleFormControlInput3"
                                    placeholder="Enter Your Email Id" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label>Company Name</label>
                                <input type="text" value="{{ old('company_name', '') }}" name="company_name"
                                    class="form-control" id="exampleFormControlInput4"
                                    placeholder="Your Company Name" required>
                            </div>
                            <div class="form-group">
                                <label>Industry</label>
                                <select class="form-control" name="industry" id="industry">
                                    <option value="0">Select Your Industry </option>
                                    <option value="2">Real Estate</option>
                                    <option value="3">Finance</option>
                                    <option value="4">Manufacturing</option>
                                    <option value="5">Hospital & Healthcare</option>
                                    <option value="6">Transportation</option>
                                    <option value="7">Insurance</option>
                                    <option value="8">Pharma</option>
                                    <option value="9">Media & Advertisement</option>
                                    <option value="10">Technology</option>
                                    <option value="11">Other</option>
                                </select>

                            </div>
                            <div class="d-none form-group">
                                <label>Referral Code (optional) : </label>
                                <input type="hidden" value="{{ request('ref') }}" name="code"
                                    class="form-control" placeholder="Code">
                            </div>
                            <div class="form-group">
                                <label for="agree">
                                    <input type="checkbox" id="agree" name="agree" checked /> I agree to the <a
                                        href="/tou" style="text-decoration:none">terms of use </a> and the <a
                                        href="/privacy" style="text-decoration:none">privacy policy</a>
                                </label>
                            </div>

                            <div class="form-group text-center">
                                <button class="btn btn-register">Register Now</button>
                            </div>
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
                    <h3>80% of Sales Require About <span>5 Follow-ups</span> but 44% of Salespeople Give up After Just
                        One!</h3>
                    <h5>What if We Give You a Complete Automation Followup System to Increase your Sales?</h5>
                </div>
            </div>
            <div class="row mt-5 mb-5">
                <div class="col-md-5 text-center mb-4">
                    <!--<img src="img/colap.png'}}" alt="" class="img-fluid">-->
                    <div class="video-add">
                        <!--<iframe class="you-video" id="ytplayer" src="https://youtu.be/0OY8oDbBumI?autoplay=1" allow="autoplay"  frameborder="0" allowfullscreen></iframe> -->
                        <iframe class="" id="ytplayer"
                            src="https://www.youtube.com/embed/0OY8oDbBumI?playlist=0OY8oDbBumI&loop=1"
                            allow="autoplay" frameborder="0" style="width: 100% ; 
height: 252px;"></iframe>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-6 right-side-t text-center">
                    <p>1. &nbsp; <span>Only 2% of sales happen during the first point of contact.</span></p>
                    <p>2. &nbsp; <span>60% of customers say no four times before saying yes.</span></p>
                    <p>3. &nbsp; <span>90% of all sales reps will only send one email to their prospect.</span></p>
                    <p><span>The only thing that you're missing is a proper follow up system to sell your products or
                            services. <i style="color: #fa9813;font-size: 20px;">RealAuto</i> can help you automate
                            your entire
                            lead marketing business and increase your sales to up to 3x.</span></p>

                    <!--<button class="btn btn-custom-big mt-4" data-toggle="modal" data-target="#exampleModal1">Request for live Demo</button>-->
                    <!--<button class="btn btn-custom-big mt-4" style="text-transform:uppercase; font-weight:600;" onclick="window.location.href='#go-top';">get 3 Days FREE Trial</button>-->
                    <button class="btn btn-custom-big mt-4" style="text-transform:uppercase; font-weight:600;"
                        onclick="window.location.href='#go-top';">Get A Free Account</button>
                </div>
            </div>
        </div>
    </section>

    <section class="benefits-section-top">
        <div class=" container-fluid">
            <div class="row">
                <div class="col-md-12 p-0">
                    <img src="{{ url('/') . '/img/bg-blu-top.png' }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <section class="benefits-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 p-0">
                    <div class="benefits-bar">
                        <h3>How Can <span><b>RealAuto</b></span> Benefit Your Business?</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-4">
                <div class="col-md-8 right-side-t2">
                    <p>1. &nbsp; <span>Manage Your Leads in a Single Dashboard.</span></p>
                    <p>2. &nbsp; <span>Set up your Sequence and Forget about the Follow-Up Process. RealAuto will Take
                            Care Everything using WhatsApp, Email & SMS.</span></p>
                    <p>3. &nbsp; <span>Set Up Daily, Weekly or Monthly Follow Ups using WhatsApp, SMS, Email.</span></p>
                    <p>4. &nbsp; <span>Get Real-time Lead Notifications.</span></p>
                    <p>5. &nbsp; <span>Real-time Incoming Lead Sharing With your Team.</span></p>
                    <p>6. &nbsp; <span>One Tap Call to Customer Feature.</span></p>

                    <!--<button onclick="window.location.href='#go-top';" class="btn btn-knowmor mt-4 mb-4" style="text-transform:uppercase; font-weight:600;">get 3 Days FREE Trial &nbsp; <i
class="fa fa-angle-double-right" aria-hidden="true"></i></button>-->
                    <button onclick="window.location.href='#go-top';" class="btn btn-knowmor mt-4 mb-4"
                        style="text-transform:uppercase; font-weight:600;">Get A Free Account &nbsp; <i
                            class="fa 
fa-angle-double-right" aria-hidden="true"></i></button>
                </div>
                <div class="col-md-4 text-center">
                    <img src="{{ url('/') . '/img/business-people-1.png' }}" alt="" class="img-fluid mb-4">
                </div>
            </div>
        </div>
    </section>
    <section class="benefits-section-butt">
        <div class=" container-fluid">
            <div class="row">
                <div class="col-md-12 p-0">
                    <img src="{{ url('/') . '/img/bg-blu-butt.png' }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <section class="reasons-sect">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 p-0">
                    <img src="img/questen.jpg" alt="" class="img-fluid">
                </div>
                <div class="col-md-5 reasons-right-sec">
                    <h4>Familiar with these Problems?</h4>
                    <div class="border1"></div>
                    <p><span><i class="fa fa-check-square-o"></i></span> Me or My Team don't know exactly how many
                        leads came in this month.</p>

                    <p><span><i class="fa fa-check-square-o"></i></span> Me or My Team can't tell which leads are most
                        important for our goals.</p>


                    <p><span><i class="fa fa-check-square-o"></i></span> Me & My Team are loosing our sales due to a
                        poor follow-up system </p>
                    <p><span><i class="fa fa-check-square-o"></i></span> Me & My Team don't have a follow up system
                        that's reliable.</p>


                    <p><span><i class="fa fa-check-square-o"></i></span> Me & My Team want to stop the lead leakage.
                    </p>
                    <p><span><i class="fa fa-check-square-o"></i></span> Me & My Team want to save time by getting lead
                        information in seconds.</p>

                    <p><span><i class="fa fa-check-square-o"></i></span> Me & My Team want to implement Follow-up
                        Automation in our Business</p>

                    <!--<button class="btn btn-custom-big mt-4 mb-2" style="text-transform:uppercase; font-weight:600;" onclick="window.location.href='#go-top';">get 3 Days FREE
Trial</button>-->
                    <button class="btn btn-custom-big mt-4 mb-2" style="text-transform:uppercase; font-weight:600;"
                        onclick="window.location.href='#go-top';">Get Free Account</button>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </section>
    <section class="reasons-section-butt-left">
        <div class=" container-fluid">
            <div class="row">
                <div class="col-md-12 p-0">
                    <img src="{{ url('/') . '/img/bg-blu-butt-left.png' }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <section class="features-sect">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center reasons-right-sec">
                    <h4>key features</h4>
                    <div class="border1"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="features-box">
                        <img src="{{ url('/') . '/img/i1.png' }}" alt="">
                        <h5>REAL-TIME LEAD SYNC</h5>
                        <p>Sync Facebook lead forms, landing page forms or any other lead sources without any coding &
                            send welcome WhatsApp, SMS & Emails.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="features-box">
                        <img src="{{ url('/') . '/img/i2.png' }}" alt="">
                        <h5>EASY TO USE CRM</h5>
                        <p>Qualify and close deals faster with our easy-to-use CRM, ZERO technical knowledge is required
                            to use the CRM.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="features-box">
                        <img src="{{ url('/') . '/img/i3.png' }}" alt="">
                        <h5>INSTANT WELCOME WHATSAPP</h5>
                        <p>Send instant welcome WhatsApp messages to your leads without depending on another
                            autoresponder!</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="features-box">
                        <img src="{{ url('/') . '/img/i5.png' }}" alt="">
                        <h5>INSTANT WELCOME EMAILS</h5>
                        <p>Send instant welcome eMails to your leads without depending on another autoresponder!</p>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-3 col-sm-6">
                    <div class="features-box">
                        <img src="{{ url('/') . '/img/i6.png' }}" alt="">
                        <h5>INSTANT WELCOME SMS</h5>
                        <p>Send instant welcome SMS messages to your leads without depending on another autoresponder!
                        </p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="features-box">
                        <img src="{{ url('/') . '/img/i4.png' }}" alt="">
                        <h5>ADD UNLIMITED USERS OR TEAMMATES</h5>
                        <p>Collaborate with your team by adding sub-users and assign leads for follow-ups using Excel
                            Sheets.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="features-box">
                        <img src="{{ url('/') . '/img/i7.png' }}" alt="">
                        <h5>ONE DASHBOARD TO SEE EVERYTHING</h5>
                        <p>Track your leads in one backoffice to avoid the lead leakage and see in real time what is
                            going on like total leads, total site visits and more..</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="features-box">
                        <img src="{{ url('/') . '/img/i8.png' }}" alt="">
                        <h5>FOLLOW-UP AUTOMATION</h5>
                        <p>Follow-up with your prospects for 3, 6 or 9 months without touching anything using WhatsApp,
                            SMS, Emails.</p>
                    </div>
                </div>
                <div class="col-md-12 text-center mt-4 mb-5">
                    <!--<button class="btn btn-knowmor" style="text-transform:uppercase; font-weight:600;" onclick="window.location.href='#go-top';">get 3 Days FREE Trial</button>-->
                    <button class="btn btn-knowmor" style="text-transform:uppercase; font-weight:600;"
                        onclick="window.location.href='#go-top';">Get A Free Account</button>
                </div>
            </div>
        </div>
    </section>

    <section class="profile-section">
        <div class="container">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="img-prof">
                                <img src="{{ url('/') . '/img/img-prof.png' }}" alt="" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="prof-details">
                                <h4>Brought To You By</h4>
                                <h5><strong>Mr Garrett Mersman</strong></h5>
                                <p>
                                    A Digital Marketing Specialist | Entrepreneur
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </section>

    <section class="pricing-sect">
        <div class="container">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <div class="monthly-package">
                        <div class="hed">STARTER</div>
                        <div class="month-am"><span><i class="fa fa-usd"></i> 0</span>/Month</div>
                        <ul class="monthly-list">
                            <li><i class="fa fa-check"></i> 1 User</li>
                            <li><i class="fa fa-check"></i> 50 Lead Interactions per Month</li>
                            <li><i class="fa fa-check"></i> Instant WhatsApp Notifications</li>
                            <li><i class="fa fa-check"></i> SMS/Email/WhatsApp</li>
                            <li><i class="fa fa-check"></i> Built-In CRM</li>
                            <li><i class="fa fa-check"></i> Facebook Integration</li>
                            <li><i class="fa fa-check"></i> Website Integration</li>
                            <li><i class="fa fa-check"></i> On-Boarding Call</li>
                            <li><i class="fa fa-check"></i> Instant Lead Notiﬁcations</li>
                            <li><i class="fa fa-check"></i> Add & Upload Leads Feature</li>
                            <li><i class="fa fa-check"></i> Schedule WhatsApp Messages* ( Must have credits )</li>
                            <li><i class="fa fa-check"></i> Schedule SMS Feature* ( Must have credits )</li>
                            <li><i class="fa fa-check"></i> Schedule Email Feature* ( Must have credits )</li>
                            <li><i class="fa fa-check"></i> Message Reporting</li>
                        </ul>
                        <!--<div class="amm-btn"><i class="fa fa-inr"></i> 0</div>-->
                        <div class="text-center">
                            <button class="btn btn-knowmor" style="text-transform:uppercase; font-weight:600;"
                                onclick="window.location.href='#go-top';">Get A Free Account</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="monthly-package">
                        <div class="hed">PREMIUM</div>
                        <div class="month-am"><span><i class="fa fa-usd"></i> 67</span>/Month</div>
                        <ul class="monthly-list">
                            <li><i class="fa fa-star"></i> Unlimited Users</li>
                            <li><i class="fa fa-star"></i> Unlimited Lead Intractions</li>
                            <li><i class="fa fa-check"></i> Instant WhatsApp Notifications</li>
                            <li><i class="fa fa-check"></i> SMS/Email/WhatsApp</li>
                            <li><i class="fa fa-check"></i> Built-In CRM</li>
                            <li><i class="fa fa-check"></i> Facebook Integration</li>
                            <li><i class="fa fa-check"></i> Website Integration</li>
                            <li><i class="fa fa-check"></i> On-Boarding Call</li>
                            <li><i class="fa fa-check"></i> Instant Lead Notiﬁcations</li>
                            <li><i class="fa fa-check"></i> Lead Add & Upload Feature</li>
                            <li><i class="fa fa-check"></i> Schedule WhatsApp Messages* ( Must have credits )</li>
                            <li><i class="fa fa-check"></i> Schedule SMS Feature* ( Must have credits )</li>
                            <li><i class="fa fa-check"></i> Schedule Email Feature* ( Must have credits )</li>
                            <li><i class="fa fa-check"></i> Message Reporting</li>
                        </ul>
                        <div class="text-center">
                            <button class="btn btn-knowmor" style="text-transform:uppercase; font-weight:600;"
                                onclick="window.location.href='#go-top';">Get A Free Account</button>
                        </div>
                    </div>
                </div>


                <!--<div class="col-md-4 text-center">
          <div class="hed-credit">Credit Pack Add-on</div>
          <div class="hrd-line"></div>
          
          <div class="plan-box-1">
            <table>
              <tr>
                <td>
                 <div class="am-box-left">
                  <h4><i class="fa fa-inr"></i> 750</h4>
                  <p>Monthly</p>
                 </div>
                </td>
                <td>
                 <div class="am-box-right">
                  <p>1,000 Sms credits</p>
                  <p>1,000 Whatsapp credits</p>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="plan-box-2">
            <table>
              <tr>
                <td>
                 <div class="am-box-left">
                  <h4><i class="fa fa-inr"></i> 1800</h4>
                  <p>Monthly</p>
                 </div>
                </td>
                <td>
                 <div class="am-box-right">
                  <p>4,000 Sms credits</p>
                  <p>4,000 Whatsapp credits</p>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class="plan-box-1">
            <h3 style="font-weight:300;">Customise Plan Available</h3>
          </div>
        </div>-->
            </div>
        </div>
    </section>


    <!--  <section style="background-color:#e5e9ea;">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <h4 class="pric-hed">Pricing</h4>
          <div class="border1"></div>
        </div>
      </div>
      <div class="row pb-5">
        <div class="col-md-1"></div>
        <div class="col-md-4 text-center">
          <div class="pric-box">
           <h5>Basic</h5>
           <p><span><i class="fa fa-check"></i></span> Unlimited User </p>
            <p><span><i class="fa fa-check"></i></span>Inbuilt CRM</p>
            <p><span><i class="fa fa-check"></i></span>Facebook Integration</p>
            <p><span><i class="fa fa-check"></i></span>On-Boarding Call</p>
            <p><span><i class="fa fa-check"></i></span>Live Lead Status </p>
            <p><span><i class="fa fa-check"></i></span>Lead Add & Upload Feature</p>
           <button class="btn btn-knowmor get-start-btn" style="text-transform:uppercase; font-weight:600;" onclick="window.location.href='#go-top';">Get 3 Days FREE Trial</button>
           <div class="inpoat-text">*INR 250 rupees for credit</div>
<button class="btn btn-knowmor get-start-btn" style="text-transform:uppercase; font-weight:600;">INR 999/Month</button>
          </div>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-4 text-center">
          <div class="pric-box2">
            <h6>Advanced</h6>
            <p><span><i class="fa fa-check"></i></span> Unlimited User </p>
            <p><span><i class="fa fa-check"></i></span>Instant Welcome SMS/Email/Whats-app</p>
            <p><span><i class="fa fa-check"></i></span>Inbuilt CRM</p>
            <p><span><i class="fa fa-check"></i></span>Facebook Integration</p>
            <p><span><i class="fa fa-check"></i></span>On-Boarding Call</p>
            <p><span><i class="fa fa-check"></i></span>Live Lead Status </p>
            <p><span><i class="fa fa-check"></i></span>Lead Add & Upload Feature</p>
            <p><span><i class="fa fa-check"></i></span>Schedule Whats app Feature</p>
            <p><span><i class="fa fa-check"></i></span>Schedule SMS Feature</p>
            <p><span><i class="fa fa-check"></i></span>Schedule Email Feature</p>
           <button class="btn btn-knowmor mb-2 mt-2" style="text-transform:uppercase; font-weight:600;" onclick="window.location.href='#go-top';">Get Premium</button>
           <div class="inpoat-text">*INR 250 rupees only for credit</div>
<button class="btn btn-knowmor mb-2 mt-2" style="text-transform:uppercase; font-weight:600;">INR 4000/Month</button>
</div>
        </div>
        <div class="col-md-1"></div>
      </div>
    </div>
 </section>-->

    <!--<section style="background-color:#6C2EB9;">
    <div class="container">
      <div class="row">
        <div class="col-md-5 text-center">
          <img src="img/robots-1.png'}}" alt="" class="img-fluid">
        </div>
        <div class="col-md-7 text-white autoEmailer-text">
          <h3><i>Realauto is your 24*7 Virtual Sales Executive who helps you to closing your deal up to 4X.</i></h3>
          
          <button class="btn btn-custom-big mt-3" style="text-transform:uppercase; font-weight:600;" data-toggle="modal" data-target="#exampleModal1">get 3 Days FREE Trial </button>
        </div>
      </div>
    </div>
  </section>-->


    <footer class="footer-bg">
        <div class="container-fluid" style="background-color:#161615; padding:50px 15px;">
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">
                            <table class="table-foot">
                                <tr>
                                    <td style="display: block;"><i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    </td>
                                    <td>
                                        <h5>eMail Us</h5>
                                        <p>
                                            <a href="mailto:support@realauto.in"
                                                style="color:#df8317;">support@realauto.in</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <table class="table-foot">
                                <tr>
                                    <td style="display: block;"><i class="fa fa-phone" aria-hidden="true"></i></td>
                                    <td>
                                        <h5>Call Us</h5>
                                        <p>
                                            <a href="tel:+19709851535" style="color:#df8317;">+1 (970) 985-1535</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <table class="table-foot">
                                <tr>
                                    <td style="display: block;"><i class="fa fa-adjust" aria-hidden="true"></i></td>
                                    <td>
                                        <h5>Important Links</h5>
                                        <p>
                                            <a href="/about-us" style="color:#df8317;">About Us</a>
                                        </p>
                                        <p>
                                            <a href="https://blogs.realauto.in" style="color:#df8317;">Blogs</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table-foot">
                                <tr>
                                    <td style="display: block;"><i class="fa fa-map-marker" aria-hidden="true"></i>
                                    </td>
                                    <td>
                                        <h5>Location:</h5>
                                        <p>
                                            <a href="#" style="color:#df8317;">
                                                Grand Junction, Colorado, USA
                                            </a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <table class="table-foot">
                                <tr>
                                    <td style="display: block;"><i class="fa fa-calendar-times-o"
                                            aria-hidden="true"></i></td>
                                    <td>
                                        <h5>Working Hours</h5>
                                        <p>
                                            <b>Monday-Saturday</b><br>
                                            08:00 a.m. to 08:00 p.m. MST
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-3">
                            <ul class="socel-i">
                                <li><a href="https://www.facebook.com/Realauto.in"><i class="fa fa-facebook-square"
                                            aria-hidden="true"></i></a></li>
                                <li><a href="https://www.instagram.com/realauto.in"><i class="fa fa-instagram"
                                            aria-hidden="true"></i></a></li>
                                <li><a href="https://twitter.com/RealautoIn"><i class="fa fa-twitter-square"
                                            aria-hidden="true"></i></a></li </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="text-white pt-4 pb-2" style="font-size:12px;">© Copyright 2021-2023 RealAuto |<a
                            href="/tou" style="color:#FCFCFC;"> Terms of Use </a> | <a href="/privacy"
                            style="color:#FCFCFC;">Privacy Policy</a></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="modal-body">
                    <div class="row m-0">
                        <div class="col-6 p-0" style="background-color:#482a4a;">
                            <img src="img/popup-img.png'}}" alt="" class="img-fluid">
                        </div>
                        <div class="col-6 p-0">
                            <div class="mod-form">
                                <h4>Request for live demo</h4>
                                <form class="from-box">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" class="form-control" id="">
                                    </div>
                                    <div class="form-group">
                                        <label>Time</label>
                                        <input type="time" class="form-control" id="">
                                    </div>

                                    <div class="form-group text-center mb-0">
                                        <button class="btn btn-register">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js"}}, then Bootstrap 4.0 JS -->
    <script src={{ 'js/jquery-3.2.1.slim.min.js' }}></script>
    <script src={{ 'js/popper.min.js' }}></script>
    <script src={{ 'js/bootstrap.min.js' }}></script>
    <script src={{ 'css/owlcarousel/owl.carousel.min.js' }}></script>
    <script>
        $('.owl-bann').owlCarousel({
            loop: true,
            margin: 0,
            autoplay: true,
            autoplayTimeout: 2000,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 1,
                    nav: false
                },
                600: {
                    items: 1,
                    nav: false
                },
                1000: {
                    items: 1,
                    nav: false,
                    loop: true
                }
            }
        });

        function validateNumber(e) {
            const pattern = /^[0-9]$/;

            if (pattern.test(e.key)) {
                var phone = document.getElementById('exampleFormControlInput2').value.length;
                if (phone > 9) { //string starts from 0
                    return false;
                }
            }
            return pattern.test(e.key);
        }

        function validateForm() {

            var phone = document.getElementById('exampleFormControlInput2').value.length;
            console.log('ph', phone)

            if (phone != 10) {
                alert('phone number should be of 10 digits')
                return false;
            }

            if (document.getElementById("industry").value == 0) {

                alert("Select Industry")
                return false;
            }

            var checkBox = document.getElementById("agree");



            if (checkBox.checked != true) {

                alert('Please accept the terms of use and policy')

                return false

            }

            return true

        }
    </script>

    <!--<script>
        var tag = document.createElement('script');
        tag.src = "https://youtu.be/0OY8oDbBumI";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        var player;

        function onYouTubePlayerAPIReady() {
            player = new YT.Player('ytplayer', {
                height: '350',
                width: '100%',
                videoId: 'M7lc1UVf-VE'
            });
        }
    </script>-->

    <script>
        var input = document.querySelector("#phone");
        window.intlTelInput(input, {
            initialCountry: "auto",
            geoIpLookup: function(success) {
                // Get your api-key at https://ipdata.co/
                fetch("https://api.ipdata.co/?api-key=test")
                    .then(function(response) {
                        if (!response.ok) return success("");
                        return response.json();
                    })
                    .then(function(ipdata) {
                        // alert(ipdata);
                        success(ipdata.country_code);
                    });
            },
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.min.js",
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.iti__country').click(function() {

                // alert($(this).data('dial-code'));  
                $("#phone").val('+' + $(this).data('dial-code'));
                //var itemType = $('.iti__country-list li').find('.iti__active').data('data-dial-code'); 
                //alert(itemType);
            });
            var $videoSrc;
            $('.video-btn').click(function() {
                $videoSrc = $(this).data("src");
            });
            $('#videoModal').on('shown.bs.modal', function(e) {
                $("#video").attr('src', $videoSrc + "?autoplay=1&modestbranding=1&showinfo=0");
            })
            $('#videoModal').on('hide.bs.modal', function(e) {
                $("#video").attr('src', $videoSrc);
            })
        });
    </script>

</body>

</html>
