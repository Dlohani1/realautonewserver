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
		<div class="alert alert-warning" style="display:none;margin-left:4%" id="planExpiry">
			<strong>Plan Expired!</strong> <span id="info"></span>
		</div>
        <ul class="navbar-nav flex-row ml-auto d-flex list-unstyled topnav-menu float-right mb-0">
            <li  data-placement="left" title="Log Out">
                <a href="{{ url('logout') }}" class="nav-link right-bar-toggle">
                    <i data-feather="log-out"></i>
                </a>
            </li>
        </ul>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
		  Schedule A Demo
		</button> &nbsp;&nbsp;
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#videoModal">
		  Watch Demo Video
		</button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="top:10%">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 143%;margin-top: 34%;"> 
      <div class="modal-body">
 <iframe src="https://player.vimeo.com/video/598228595?h=765274455d" width="640" height="400" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>     
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
			var planEndDate = new Date(obj.end_date)
		    var readableDate = planEndDate.getDate()+" "+ obj.end_month + ","+ obj.end_year
			document.getElementById("planExpiry").style.display = "block"
			document.getElementById("info").innerHTML = "Your Plan will expire on " + readableDate
		}
		//data - response from server
	},
	error: function (jqXHR, textStatus, errorThrown)
	{
 
	}
});	


</script>
