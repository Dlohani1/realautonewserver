@extends('layouts.app')
@section('title', 'Account Settings')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

/* body start */

body{
margin-top:20px;
color: #8e9194;
background-color: #f4f6f9;
}
.text-muted {
    color: #aeb0b4 !important;
}
.text-muted {
    font-weight: 300;
}
    position: absolute;
    width: 100%;
    height:100%;
    left: 0;
    top: 0;
    display: none;
    align-items: center;
    background-color: #000;
    z-index: 999;
    opacity: 0.5;
}
.loading-icon{ position:absolute;border-top:2px solid #fff;border-right:2px solid #fff;border-bottom:2px solid #fff;border-left:2px solid 
#767676;border-radius:25px;width:25px;height:25px;margin:0 auto;position:absolute;left:50%;margin-left:-20px;top:50%;margin-top:-20px;z-index:4;-webkit-animation:spin 1s linear 
infinite;-moz-animation:spin 1s linear infinite;animation:spin 1s linear infinite;}
@-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
@-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
@keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } } 
/* body end */


/* Absolute Center Spinner */
.loading {
  position: fixed;
  z-index: 999;
  height: 2em;
  width: 2em;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

/* Transparent Overlay */
.loading:before {
  content: '';
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
    background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

  background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
  /* hide "loading..." text */
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

.loading:not(:required):after {
  content: '';
  display: block;
  font-size: 10px;
  width: 1em;
  height: 1em;
  margin-top: -0.5em;
  -webkit-animation: spinner 150ms infinite linear;
  -moz-animation: spinner 150ms infinite linear;
  -ms-animation: spinner 150ms infinite linear;
  -o-animation: spinner 150ms infinite linear;
  animation: spinner 150ms infinite linear;
  border-radius: 0.5em;
  -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, 
rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, 
rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-o-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}


</style>
<div class="container">
 <div class="row page-title">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb" class="float-right mt-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Account Settings</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Account Settings</h4>
                    </div>
                </div>
<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8 mx-auto" style="margin-top:50px">
        <h2 class="h3 mb-4 page-title">Settings</h2>
        <div class="my-4">
            <!--<ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Security</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Notifications</a>
                </li>
            </ul>-->
			<?php
			$isAutoAssign = 0;
			$sendEmail = 0;
			$sendWp = 0;
			$sendStaff = 0;

            $useAssignedStaff = 0;
            $sendReport = 0;

			if (isset($settings[0])) {
				$isAutoAssign = $settings[0]->auto_assign_leads;
				$sendEmail = $settings[0]->send_notification_via_email;
				$sendWp = $settings[0]->send_notification_via_whatsapp;
				$sendStaff = $settings[0]->send_notification_to_staff;
                $useAssignedStaff = $settings[0]->assignStaffWhatsappEvent;
                $sendReport = $settings[0]->send_daily_report;
			}
			?>
            <h5 class="mb-0 mt-5">Account Settings</h5>
            <p>These settings help keep your account updated.</p>
            <div class="list-group mb-5 shadow">
             <div class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col">
                            <strong class="mb-2">Enable Auto Assign Leads</strong>
							<?php
							if ($isAutoAssign > 0) {?>
								<span id="badge1" class="badge badge-pill badge-success">Enabled</span>
							<?php } else { ?>
								<span id="badge1" class="badge badge-pill badge-danger">Disabled</span>
							<?php }
							?>
                            <p class="text-muted mb-0"></p>
                        </div>
                        <div class="col-auto">
                            <div class="custom-control custom-switch">
							
							<label class="switch">
  <input type="checkbox" id="auto-lead"  <?php if ($isAutoAssign > 0) {echo "checked";} ?> onchange="saveSettings('auto_assign_leads','auto-lead')">
  <span class="slider round"></span>
</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col">
                            <strong class="mb-2">Send New Lead Notification via Email</strong>
                            <?php
							if ($sendEmail > 0) {?>
								<span id="badge2" class="badge badge-pill badge-success">Enabled</span>
							<?php } else { ?>
								<span id="badge2" class="badge badge-pill badge-danger">Disabled</span>
							<?php }
							?>
                            <p class="text-muted mb-0"></p>
                        </div>
                        <div class="col-auto">
						 
							<label class="switch">
  <input type="checkbox" id="send-email"  <?php if ($sendEmail > 0) {echo "checked";} ?> onchange="saveSettings('send_via_email','send-email')">
  <span class="slider round"></span>
</label>
						 
                        </div>
                    </div>
                </div>
                <div class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col">
                            <strong class="mb-2">Send New Lead Notification via WhatsApp</strong>
							<?php
							if ($sendWp > 0) {?>
								<span id="badge3" class="badge badge-pill badge-success">Enabled</span>
							<?php } else { ?>
								<span id="badge3" class="badge badge-pill badge-danger">Disabled</span>
							<?php }
							?>
                            <p class="text-muted mb-0"></p>
                        </div>
                        <div class="col-auto">
                    
							
							<label class="switch">
  <input type="checkbox" id="send-wp"  <?php if ($sendWp > 0) {echo "checked";} ?> onchange="saveSettings('send_via_whatsapp','send-wp')">
  <span class="slider round"></span>
</label>
                        </div>
                    </div>
                </div>

                <div class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col">
                            <strong class="mb-2">Send New Lead Notification to Staff</strong>
                            <?php
                                                        if ($sendStaff > 0) {?>
                                                                <span id="badge4" class="badge badge-pill badge-success">Enabled</span>
                                                        <?php } else { ?>
                                                                <span id="badge4" class="badge badge-pill badge-danger">Disabled</span>
                                                        <?php }
                                                        ?>
                            <p class="text-muted mb-0"></p>
                        </div>
                        <div class="col-auto">

                                                        <label class="switch">
  <input type="checkbox" id="send-staff"  <?php if ($sendStaff > 0) {echo "checked";} ?> onchange="saveSettings('send_staff','send-staff')">
  <span class="slider round"></span>
</label>
                      
                        </div>
                    </div>
                </div>


                <div class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col">
                            <strong class="mb-2">Use Assigned Staff Whatsapp</strong>
                            <?php
                                if ($useAssignedStaff > 0) {?>
                                        <span id="badge5" class="badge badge-pill badge-success">Enabled</span>
                                <?php } else { ?>
                                        <span id="badge5" class="badge badge-pill badge-danger">Disabled</span>
                                <?php }
                                ?>
                            <p class="text-muted mb-0"></p>
                        </div>
                        <div class="col-auto">

                        <label class="switch">
                        <input type="checkbox" id="use-staff"  <?php if ($useAssignedStaff > 0) {echo "checked";} ?> onchange="saveSettings('use_staff','use-staff')">
                        <span class="slider round"></span>
                        </label>
                        </div>
                    </div>
                </div>

                <div class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col">
                            <strong class="mb-2">Send Daily Report</strong>
                            <button type="button" onclick="sendReport()">Send Report </button>
                            <?php
                                if ($sendReport > 0) {?>
                                        <span id="badge6" class="badge badge-pill badge-success">Enabled</span>
                                <?php } else { ?>
                                        <span id="badge6" class="badge badge-pill badge-danger">Disabled</span>
                                <?php }
                                ?>
                            <p class="text-muted mb-0"></p>
                        </div>
                        <div class="col-auto">

                        <label class="switch">
                        <input type="checkbox" id="send-report"  <?php if ($sendReport > 0) {echo "checked";} ?> onchange="saveSettings('send_report','send-report')">
                        <span class="slider round"></span>
                        </label>
                        </div>
                    </div>
                </div>


            </div>

              

			<!--
            <h5 class="mb-0">Recent Activity</h5>
            <p>Last activities with your account.</p>
            <table class="table border bg-white">
                <thead>
                    <tr>
                        <th>Device</th>
                        <th>Location</th>
                        <th>IP</th>
                        <th>Time</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="col"><i class="fe fe-globe fe-12 text-muted mr-2"></i>Chrome - Windows 10</th>
                        <td>Paris, France</td>
                        <td>192.168.1.10</td>
                        <td>Apr 24, 2019</td>
                        <td><a hreff="#" class="text-muted"><i class="fe fe-x"></i></a></td>
                    </tr>
                    <tr>
                        <th scope="col"><i class="fe fe-smartphone fe-12 text-muted mr-2"></i>App - Mac OS</th>
                        <td>Newyork, USA</td>
                        <td>10.0.0.10</td>
                        <td>Apr 24, 2019</td>
                        <td><a hreff="#" class="text-muted"><i class="fe fe-x"></i></a></td>
                    </tr>
                    <tr>
                        <th scope="col"><i class="fe fe-globe fe-12 text-muted mr-2"></i>Chrome - iOS</th>
                        <td>London, UK</td>
                        <td>255.255.255.0</td>
                        <td>Apr 24, 2019</td>
                        <td><a hreff="#" class="text-muted"><i class="fe fe-x"></i></a></td>
                    </tr>
                </tbody>
            </table>
			-->
        </div>
    </div>
</div>
</div>
<div id="loading-overlay">
    <div class="loading-icon"></div>
</div>
<div class="loading">Loading...</div>


    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->
<script>
setTimeout(function(){document.getElementsByClassName("loading")[0].style.display="none" }, 500);
function saveSettings(fieldName, id) {
	document.getElementsByClassName("loading")[0].style.display="block"
	$('body').append('<div id="over" style="position: absolute;top:0;left:0;width: 100%;height:100%;z-index:2;opacity:0.4;filter: alpha(opacity = 50)"></div>');
var field = document.getElementById(id);
switchStatus = $(field).is(':checked');

if (id == "auto-lead"){
	if(switchStatus) {
	document.getElementById("badge1").classList.add("badge-success")
	document.getElementById("badge1").classList.remove("badge-danger")
	} else {
	document.getElementById("badge1").classList.add("badge-danger")
	document.getElementById("badge1").classList.remove("badge-success")
	}
	document.getElementById("badge1").innerHTML = switchStatus ? "Enabled" : "Disabled"
}

if (id == "send-email"){
	if(switchStatus) {
	document.getElementById("badge2").classList.add("badge-success")
	document.getElementById("badge2").classList.remove("badge-danger")
	} else {
	document.getElementById("badge2").classList.add("badge-danger")
	document.getElementById("badge2").classList.remove("badge-success")
	}
	document.getElementById("badge2").innerHTML = switchStatus ? "Enabled" : "Disabled"
}

if (id == "send-wp"){
	if(switchStatus) {
	document.getElementById("badge3").classList.add("badge-success")
	document.getElementById("badge3").classList.remove("badge-danger")
	} else {
	document.getElementById("badge3").classList.add("badge-danger")
	document.getElementById("badge3").classList.remove("badge-success")
	}
	document.getElementById("badge3").innerHTML = switchStatus ? "Enabled" : "Disabled"
}   

if (id == "send-staff"){
        if(switchStatus) {
        document.getElementById("badge4").classList.add("badge-success")
        document.getElementById("badge4").classList.remove("badge-danger")
        } else {
        document.getElementById("badge4").classList.add("badge-danger")
        document.getElementById("badge4").classList.remove("badge-success")
        }
        document.getElementById("badge4").innerHTML = switchStatus ? "Enabled" : "Disabled"
}

if (id == "use-staff"){
        if(switchStatus) {
        document.getElementById("badge5").classList.add("badge-success")
        document.getElementById("badge5").classList.remove("badge-danger")
        } else {
        document.getElementById("badge5").classList.add("badge-danger")
        document.getElementById("badge5").classList.remove("badge-success")
        }
        document.getElementById("badge5").innerHTML = switchStatus ? "Enabled" : "Disabled"
}


if (id == "send-report"){
        if(switchStatus) {
        document.getElementById("badge6").classList.add("badge-success")
        document.getElementById("badge6").classList.remove("badge-danger")
        } else {
        document.getElementById("badge6").classList.add("badge-danger")
        document.getElementById("badge6").classList.remove("badge-success")
        }
        document.getElementById("badge6").innerHTML = switchStatus ? "Enabled" : "Disabled"
}

let path = window.location.pathname
const uri = path.split("/");
var adminId = uri[2]


var formData = {_token: "{{ csrf_token() }}",fieldName:fieldName,status:switchStatus,admin_id: adminId}; //Array 
			var url = "{{ url('save-admin-settings')}}";
			$.ajax({
				url : url,
				type: "POST",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{document.getElementsByClassName("loading")[0].style.display="none"
					console.log('dd',data)
					$("#over").remove();
					//data - response from server
					//window.location.reload()
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
			 
				}
			});
}

function sendReport() {
    let path = window.location.pathname
    const uri = path.split("/");
    var adminId = uri[2]
    var baseUrl = "<?php echo url('/') ; ?>"
    var url = baseUrl+"/daily-whatsapp-report/"+adminId;

    var formData = {_token: "{{ csrf_token() }}"}; //Array 
   
    $.ajax({
    url : url,
    type: "GET",
    data : formData,
    success: function(data, textStatus, jqXHR)
    {
        //data - response from server
        //window.location.reload()
    },
    error: function (jqXHR, textStatus, errorThrown)
    {

    }
    });
}
</script>
@endsection

