<style>

.alert {
    position: relative;
    padding: 8px 15px;
    margin-bottom: 0;
    border: 1px solid transparent;
    border-radius: 0.3rem;
    margin-left: 34px;
   visibility: visible;
    top: 0;
    font-weight: bolder;
    background-color: rgb(128, 44, 185) !important;
    border-color: rgb(128, 44, 185) !important;
}

#test:hover {
  color: black !important;
}
.navbar-custom .topnav-menu .nav-link svg {
    height: 35px;
    width: 35px;
    color: #802cb9;
    /*fill: rgba(75,75,90,.12);*/
}

.video-play-button {
  position: absolute;
  z-index: 10;
  top: 50%;
  right:0%;
  /*left: 50%;*/
  transform: translateX(-50%) translateY(-50%);
  box-sizing: content-box;
  display: block;
  width: 32px;
  height: 44px;
  /* background: #fa183d; */
  border-radius: 50%;
  /*padding: 18px 20px 18px 28px;*/
  padding: 48px 23px 28px 40px;
}

.video-play-button:before {
  content: "";
  position: absolute;
  z-index: 0;
  left: 50%;
  top: 50%;
  transform: translateX(-50%) translateY(-50%);
  display: block;
  width: 40px;
  height: 40px;
 /* background: #ba1f24; */
  background:#802cb9;
  border-radius: 50%;
  animation: pulse-border 1500ms ease-out infinite;
}

.video-play-button:after {
  content: "";
  position: absolute;
  z-index: 1;
  left: 50%;
  top: 50%;
  transform: translateX(-50%) translateY(-50%);
  display: block;
  width: 40px;
  height: 40px;
/*  background: #fa183d;*/
background:#802cb9;

  border-radius: 50%;
  transition: all 200ms;
}

.video-play-button:hover:after {
/*  background-color: darken(#fa183d, 10%);*/
background-color:#802cb9;

}

.video-play-button img {
  position: relative;
  z-index: 3;
  max-width: 100%;
  width: auto;
  height: auto;
}

.video-play-button span {
  display: block;
  position: relative;
  z-index: 3;
  width: 0;
  height: 0;
  border-left: 22px solid #fff;
	border-top: 12px solid transparent;
	border-bottom: 12px solid transparent;
}

@keyframes pulse-border {
  0% {
    transform: translateX(-50%) translateY(-50%) translateZ(0) scale(1);
    opacity: 1;
  }
  100% {
    transform: translateX(-50%) translateY(-50%) translateZ(0) scale(1.5);
    opacity: 0;
  }
}



.video-overlay {
  position: fixed;
  z-index: -1;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0,0,0,0.80);
  opacity: 0;
  transition: all ease 500ms;
}

.video-overlay.open {
  position: fixed;
  z-index: 1000;
  opacity: 1;
}

.video-overlay-close {
  position: absolute;
  z-index: 1000;
  top: 15px;
  right: 20px;
  font-size: 36px;
  line-height: 1;
  font-weight: 400;
  color: #fff;
  text-decoration: none;
  cursor: pointer;
  transition: all 200ms;
}

.video-overlay-close:hover {
  color: #fa183d;
}

.video-overlay iframe {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translateX(-50%) translateY(-50%);
  /* width: 90%; */
  /* height: auto; */
  box-shadow: 0 0 15px rgba(0,0,0,0.75);
}
</style>
<div class="navbar navbar-expand flex-column flex-md-row navbar-custom">
    <div class="container-fluid">
        <!-- LOGO -->
        <a href="{{ url('/dashboard') }}" class="navbar-brand mr-0 mr-md-2 logo">
            <span class="logo-lg">
                <img src="{{url('/assets/home/img/logo.png')}}" alt="" height="50" />
            </span>
            <span class="logo-sm">
                <img src="{{ asset('/assets/images/logo.png') }}" alt="" height="24">
            </span>
        </a>

        <ul class="navbar-nav bd-navbar-nav flex-row list-unstyled menu-left mb-0">
            <li class="">
                <button class="button-menu-mobile open-left disable-btn">
                    <i data-feather="menu" class="menu-icon"></i>
                    <i data-feather="x" class="close-icon"></i>
                </button>
            </li>
        </ul>

 <div class="alert alert-info"  id="pack" style="visibility: hidden;">
    <strong>STARTER PACK</strong>
  </div>

		<div class="alert alert-info" style="display:none;margin-left:4%" id="planExpiry">
			<strong>Plan Expired!</strong> <span id="info"></span>
		</div>
        <ul class="navbar-nav flex-row ml-auto d-flex list-unstyled topnav-menu float-right mb-0">
            <li  data-placement="left" title="Log Out" style="right: 0%;
position: absolute;
top: 0px;">
                <a id="test" href="{{ url('logout') }}" class="nav-link right-bar-toggle">
                    <i data-feather="log-out"></i>
                </a>
            </li>
        </ul>
		<button type="button" class="alert alert-info" style="right: 8%;position: relative;
background-color:#802cb9;border-color:#802cb9;" data-toggle="modal" data-target="#exampleModal1">
		  Schedule A Demo
		</button>
	
		<a id="play-video" class="video-play-button video-btn" href="#" data-toggle="modal" data-target="#videoModal" 
data-src="https://www.youtube.com/embed/6zxdUvul0yE">
  <span></span>
</a>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="top:10%">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: max-content; margin-top: 35%;"> 
      <div class="modal-body">
 <iframe id="video" src="" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>     
 </div>
</div>
        
      </div>
      
    </div>
	
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Schedule A Demo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
      <div class="modal-body">
        <form action="{{ route('ask-for-demo') }}" method="post">
            @csrf
        <input type="date" id="txtdate" name="date" class="form-control"><br>
        <input type="time" name="time" class="form-control">
        <br>
        <input type="submit" name="submit" value="Submit" class="btn btn-primary">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Schedule A Demo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="row m-0">
          <div class="col-6 p-0" style="background-color:#482a4a;">
            <img src="img/popup-img.png" alt="" class="img-fluid">
          </div>
          <div class="col-6 p-0">
            <div class="mod-form" style="padding:10px">
             <h4>Request a Live Demo</h4>
             <form class="from-box">
               <div class="form-group">
                <label>Date</label>
                <input type="date" class="form-control" id="">
               </div>
               <div class="form-group">
                <label>Time</label>
                <input type="time" class="form-control" id="">
               </div>
               
               <div class="form-group text-center mb-0" style="background: #482a4a;">
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
    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>


var formData = {_token: "{{ csrf_token() }}"}; //Array 

$.ajax({
	url: "{{ url('user-plan-details')}}",
	type: "POST",
	data : formData,
	success: function(data, textStatus, jqXHR)
	{
		const obj = JSON.parse(data);
		if(undefined !== obj.end_date) {
			console.log('obj',obj)
			if (obj.pack == "1") {
				document.getElementById("pack").style.visibility = "visible"
			} else  {
			var planEndDate = new Date(obj.end_date)
		  var readableDate = planEndDate.getDate()+" "+ obj.end_month + ","+ obj.end_year
			document.getElementById("planExpiry").style.display = "block"
			document.getElementById("info").innerHTML = "Your Plan will expire on " + readableDate
			}
		}
		//data - response from server
	},
	error: function (jqXHR, textStatus, errorThrown)
	{
 
	}
});	

$(document).ready(function() {
  var $videoSrc;  
  $('.video-btn').click(function() {
      $videoSrc = $(this).data( "src" );
  });
  $('#videoModal').on('shown.bs.modal', function (e) {
    $("#video").attr('src',$videoSrc + "?autoplay=1&modestbranding=1&showinfo=0" ); 
  })
  $('#videoModal').on('hide.bs.modal', function (e) {
        $("#video").attr('src',$videoSrc); 
  }) 
});

</script>

