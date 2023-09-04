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
    <title>RealAuto</title>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Tag Manager -->


    <!-- End Google Tag Manager -->
</head>
<body>
    
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
	
	@yield('content')
	
	   <footer class="footer-bg">
  <div class="container-fluid" style="background-color:#161615; padding:50px 15px;">
      <div class="row">
        <div class="container">
          <div class="row">
            
            <div class="col-md-3">
              <table class="table-foot">
                <tr>
                  <td style="display: block;"><i class="fa fa-envelope-o" aria-hidden="true"></i></td>
                  <td>
                    <h5>eMail Us</h5>
                    <p>
                       <a href="#" style="color:#df8317;">support@realauto.in</a>
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
                       <a href="#" style="color:#df8317;">+1 (970) 985-1535</a>
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
                       <a href="{{url('about-us')}}" style="color:#df8317;">About Us</a>
                    </p>
		    <p>
                       <a href="{{url('https://blogs.realauto.in')}}" style="color:#df8317;">Blogs</a>
                    </p>

                  </td>
                </tr>
              </table>
            </div>
            <div class="col-md-4">
              <table class="table-foot">
                <tr>
                  <td style="display: block;"><i class="fa fa-map-marker" aria-hidden="true"></i></td>
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
                  <td style="display: block;"><i class="fa fa-calendar-times-o" aria-hidden="true"></i></td>
                  <td>
                    <h5>Working Hours</h5>
                    <p>
                       <b>Monday-Saturday</b><br>
                       8:00 a.m. to 8:00 p.m. MST
                    </p>
                  </td>
                </tr>
              </table>
            </div>
            <div class="col-md-2">
               
            </div>
            <div class="col-md-3">
              <ul class="socel-i">
                <li><a href="https://www.facebook.com/Realauto.in"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
                <li><a href="https://www.instagram.com/realauto.in"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                <li><a href="https://twitter.com/RealautoIn"><i class="fa fa-twitter-square" aria-hidden="true"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <p class="text-white pt-4 pb-2" style="font-size:12px;">© Copyright 2021-2023 RealAuto | <a href="{{url('tou')}}" style="color:#FCFCFC;">Terms of Use</a> | <a 
href="{{url('privacy')}}" style="color:#FCFCFC;">Privacy Policy</a></p>
        </div>
      </div>
    </div>
  </footer>
  
<!-- Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      
      <div class="modal-body">
        <div class="row m-0">
          <div class="col-6 p-0" style="background-color:#482a4a;">
            <img src="img/popup-img.png" alt="" class="img-fluid">
          </div>
          <div class="col-6 p-0">
            <div class="mod-form">
             <h4>Request A Demo</h4>
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
    
    

  </body>
</html>

