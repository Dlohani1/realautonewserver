@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->
	
	<style>
	


#funnel {
	width: 300px;
	height: 400px;
	margin: 0px auto;
}

.view {
  /*margin: auto;*/
  width: 90%;
  background-color: white;
  max-width:330px;
}

.wrapper {
  position: relative;
  overflow: auto;
 /* border: 1px solid black; */
  white-space: nowrap;
}

.sticky-col {
  position: -webkit-sticky;
  position: sticky;
  background-color: white;
}

.first-col {
  /*width: 100px;
  min-width: 100px;
  max-width: 100px;
  left: 0px;
  */
}

.second-col {
 /* width: 150px;
  min-width: 150px;
  max-width: 150px;
  left: 100px;
  */
  left:0px;
}

</style>
	
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="row page-title align-items-center">
                    <div class="col-sm-4 col-xl-6">
                        <h4 class="mb-1 mt-0">Dashboard</h4>
                    </div>
                </div>
				@if (Auth::user()->usertype == 1)
					
                <div class="row">
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold"> Total Users</span>
                                        <h2 class="mb-0">{{ $total_users }}</h2>
                                    </div>
                                    {{--<div class="align-self-center">
                                        <div id="today-revenue-chart" class="apex-charts"></div>
                                        <span class="text-success font-weight-bold font-size-13">
                                            <i class='uil uil-arrow-up'></i> 10.21%
                                        </span>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold"> Trial Users</span>
                                        <h2 class="mb-0">{{ $trialUsers }}</h2>
                                    </div>
                                    {{--<div class="align-self-center">
                                        <div id="today-revenue-chart" class="apex-charts"></div>
                                        <span class="text-success font-weight-bold font-size-13">
                                            <i class='uil uil-arrow-up'></i> 10.21%
                                        </span>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold"> Active Users</span>
                                        <h2 class="mb-0">{{ $activeUsers }}</h2>
                                    </div>
                                    {{--<div class="align-self-center">
                                        <div id="today-revenue-chart" class="apex-charts"></div>
                                        <span class="text-success font-weight-bold font-size-13">
                                            <i class='uil uil-arrow-up'></i> 10.21%
                                        </span>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
				@endif
               
               
</div>




</div> <!-- content -->
   
<script>
$(document).ready(function(){
  $("#myModal").modal("show");
  
})
function showVideo() {
	$("#myModal").modal("show");
}
    var todayDate = new Date();
    var month = todayDate.getMonth() +1; 
    var year = todayDate.getUTCFullYear(); 
    var tdate = todayDate.getDate(); 
    if(month < 10){
    month = "0" + month 
    }
    if(tdate < 10){
    tdate = "0" + tdate;
    }
    var maxDate = year + "-" + month + "-" + tdate;
  //  document.getElementById("demo").setAttribute("min", maxDate);
    console.log(maxDate);
    //time

    var time = new Date();
    var houre = time.getHours();
    var minutes = time.getMinutes();
    var second = time.getSeconds();
    var td = houre + ':' + minutes;
    function  myTime(){
    var x = document.getElementById('daytime').value;
    //alert(x);
    if(x >= td ){
       return true;
    }
    else{
        alert('please select future time');
        return false;
    }
    }
    console.log(time)
;

    function validatetime() {
        if(myTime()){
            return true;
        }
        return false;
    }
	
</script>
<script src="{{ asset('/assets/libs/d3-funnel/d3.min.js') }}"></script>
<script src="{{ asset('/assets/libs/d3-funnel/d3-funnel.js?1') }}"></script>
		
		</script>


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
<script>
setInterval("loadScanner()", 10000);


function loadScanner() {
	//var url = "<?php echo URL::to('/'); ?>"
	//document.getElementById("whatsapp-scanner").src=url+"assets/images/dummyqr.png";
/*	
	$.ajaxSetup({
		headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	})
*/
	var formData = new FormData();

	//formData.append('_token', {{ csrf_token() }});

	$.ajax({
		type:'POST',
		url: "{{ url('load-scanner')}}",
		data: formData,
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		cache:false,
		contentType: false,
		processData: false,
		success: (data) => {
			console.log(data)
			document.getElementById("whatsapp-scanner").src=data;
			//alert("Leads deleted successfully")
			
		},
		error: function(data){
				console.log(data);
		}
	})
	
}
</script>
@endsection
