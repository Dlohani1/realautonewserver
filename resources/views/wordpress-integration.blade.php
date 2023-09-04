@extends('layouts.app')
@section('title', 'RealAuto | Wordpress Lead Integration')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v11.0&appId=3312603435483167&autoLogAppEvents=1" nonce="PwoHvEZv"></script>
<style>

.table-bordered {
border: 1px solid #dddddd;
border-collapse: separate;
border-left: 0;
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
border-radius: 4px;
}

.table {
width: 100%;
margin-bottom: 20px;
background-color: transparent;
border-collapse: collapse;
border-spacing: 0;
display: table;
}

.widget.widget-table .table {
margin-bottom: 0;
border: none;
}

.widget.widget-table .widget-content {
padding: 0;
}

.widget .widget-header + .widget-content {
border-top: none;
-webkit-border-top-left-radius: 0;
-webkit-border-top-right-radius: 0;
-moz-border-radius-topleft: 0;
-moz-border-radius-topright: 0;
border-top-left-radius: 0;
border-top-right-radius: 0;
}

.widget .widget-content {
padding: 20px 15px 15px;
background: #FFF;
border: 1px solid #D5D5D5;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
}

.widget .widget-header {
position: relative;
height: 40px;
line-height: 40px;
background: #E9E9E9;
background: -moz-linear-gradient(top, #fafafa 0%, #e9e9e9 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #fafafa), color-stop(100%, #e9e9e9));
background: -webkit-linear-gradient(top, #fafafa 0%, #e9e9e9 100%);
background: -o-linear-gradient(top, #fafafa 0%, #e9e9e9 100%);
background: -ms-linear-gradient(top, #fafafa 0%, #e9e9e9 100%);
background: linear-gradient(top, #fafafa 0%, #e9e9e9 100%);
text-shadow: 0 1px 0 #fff;
border-radius: 5px 5px 0 0;
box-shadow: 0 2px 5px rgba(0,0,0,0.1),inset 0 1px 0 white,inset 0 -1px 0 rgba(255,255,255,0.7);
border-bottom: 1px solid #bababa;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FAFAFA', endColorstr='#E9E9E9');
-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#FAFAFA', endColorstr='#E9E9E9')";
border: 1px solid #D5D5D5;
-webkit-border-top-left-radius: 4px;
-webkit-border-top-right-radius: 4px;
-moz-border-radius-topleft: 4px;
-moz-border-radius-topright: 4px;
border-top-left-radius: 4px;
border-top-right-radius: 4px;
-webkit-background-clip: padding-box;
}

thead {
display: table-header-group;
vertical-align: middle;
border-color: inherit;
}

.widget .widget-header h3 {
top: 2px;
position: relative;
left: 10px;
display: inline-block;
margin-right: 3em;
font-size: 14px;
font-weight: 600;
color: #555;
line-height: 18px;
text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
}

.widget .widget-header [class^="icon-"], .widget .widget-header [class*=" icon-"] {
display: inline-block;
margin-left: 13px;
margin-right: -2px;
font-size: 16px;
color: #555;
vertical-align: middle;
}

.website {
	display: inline-block;
    width: 100%;
    height: calc(1.5em + 1rem + 2px);
    padding: .5rem 1.75rem .5rem .75rem;
    font-size: .875rem;
    font-weight: 400;
    line-height: 1.5;
    color: #4b4b5a;
    vertical-align: middle;
    /* background: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e) no-repeat 
right .75rem center/8px 10px; */
    background-color: #fff;
    border: 1px solid #e2e7f1;
    /* border-radius: .3rem; */
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none
}


</style>



<div id="fb-root"></div>

    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="row page-title">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb" class="float-right mt-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Manage Integration</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Wordpress Integration</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
								@if(Session::has('message'))
                                    <div class="alert alert-success alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{!! session('message') !!}</strong>
                                    </div>
                                @endif
                                <div class="row align-items-center">
                                    <div class="col text-right">
					<button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" onclick="window.open('<?php echo route('wp-integration-help'); ?>',  '_blank' )"> How to Integrate? </button>
				    </div>
                                       
                                    
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">                           

<!-- fb start -->
<div class="content">
  <div class="container-fluid">
    <div class="row page-title align-items-center">
      <div class="col-sm-6 col-xl-6" style="display:none">
        <h4 class="mb-1 mt-0">Work Flow</h4>
      </div>
      <div class="col-sm-6 col-xl-6" style="display:none">
        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModal">Add <i class="fa fa-plus"></i></button>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            
            <form id="myForm" action="{{ route('save-wordpress-integration')}}" method="post" name="save-wordpress-integration" class="form-horizontal" >
                                    @csrf
               
              <div class="form-group row mb-3 {{ $errors->has('project_type')? 'has-error':'' }}">
                                        <label for="inputPassword5" class="col-4 col-form-label">Website <span class="required">*</span></label>
                                        <div class="col-6">
                                           <input type="text" class="website" name="website" id="website" />
                                            <span class="text-danger">{{ $errors->first('project_type') }}</span>
                                        </div>
                                    </div>
							

									<div class="form-group row mb-3 {{ $errors->has('project_type')? 'has-error':'' }}">
                                        <label for="inputPassword5" class="col-4 col-form-label">Project <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select id="project_type" name="project_type" class="custom-select mb-2" onchange="GetProjects(this.value)">
                                                <option value="0">Select Project</option>
                                                <option value="1">Old</option>
                                                <option value="2">New</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('project_type') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3" id="hideprojects_name" style="display: none;">
                                        <label for="inputPassword5" class="col-4 col-form-label">Project Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" id="project_name" name="project_name" class="form-control" placeholder="Enter New Project Name">
											<span id="project_name_error" style="color:red"></span>
										</div>
                                    </div>

                                    <div class="form-group row mb-3" id="hideprojects_list" style="display: none;">
                                        <label for="inputPassword5" class="col-4 col-form-label">Project Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="project_id" class="custom-select mb-2" id="project_id">
                                                <option value="0">Select Project Name</option>
                                                @foreach($projects as $rowproject)
                                                <option value="{{ $rowproject->id }}">{{ $rowproject->project_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3 {{ $errors->has('segment_type')? 'has-error':'' }}">
                                        <label for="inputPassword5" class="col-4 col-form-label">Segment <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select id="segment_type" name="segment_type" class="custom-select mb-2" onchange="GetSegment(this.value)">
                                                <option value="0">Select Segment</option>
                                                <option value="1">Old</option>
                                                <option value="2">New</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('segment_type') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3 {{ $errors->has('segment_name')? 'has-error':'' }}" id="hidesegment_name" style="display: none;">
                                        <label for="inputPassword5" class="col-4 col-form-label">Segment Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" id="segment_name" name="segment_name" class="form-control" placeholder="Enter New Segment Name" >
                                            <span id="segment_name_error" style="color:red"> </span>
											<span class="text-danger">{{ $errors->first('segment_name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3" id="hidesegment_list" style="display: none;">
                                        <label for="inputPassword5" class="col-4 col-form-label">Segment Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="segment_id" class="custom-select mb-2" id="segment_id">
                                                <option value="0">Select Segment Name</option>
                                                @foreach($segments as $rowsegments)
                                                <option value="{{ $rowsegments->id }}">{{ $rowsegments->segment_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3 {{ $errors->has('campaign_id')? 'has-error':'' }}">
                                        <label for="inputPassword5" class="col-4 col-form-label">Campaign <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="campaign_id" class="custom-select mb-2" id="campaign_id">
                                                <option value="0">Select Campaign</option>
                                                @foreach($campaigns as $rowcampaign)
                                                <option value="{{ $rowcampaign->id }}">{{ $rowcampaign->campaigns_name }}</option>
                                                @endforeach
                                            </select>
                                            <span id="select_campaign_error" style="color:red"> </span>
											<span class="text-danger">{{ $errors->first('campaign_id') }}</span>
											
                                        </div>
                                    </div>
          
       <div class="form-group mb-0 row">
                <div class="col-4"></div>
                <div class="col-6">
                  <button id="submit"  class="btn btn-info" >Submit</button>
                  <a href="https://www.realauto.in/leads-master" style="display:none">
                  <button type="button" class="btn btn-success">Back</button>
                  </a> </div>
              </div>
            </form>
          </div>
          <!-- end card-body --> 
        </div>
        <!-- end card --> 
      </div>
      <!-- end col --> 
    </div>
  </div>
</div>

<div class="span7">   
<div class="widget stacked widget-table action-table">
    				
				<div class="widget-header">
					<i class="icon-th-list"></i>
					<h3>Integrated Accounts</h3>
				</div> <!-- /widget-header -->
				
				<div class="widget-content">
					<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th> Sl.no </th>
		                      	<th>Integration Date </th>		
								<th>Website </th>
								<th>Webhook url</th>
								<th>Campaign Name</th>
								<th class="td-actions"></th>
							</tr>
						</thead>
						<tbody>

							<?php	
		
							$i = 1;
							
							foreach($wpWebhooks as $key => $value) {?>
							
							<tr>
								
								<td>{{$i}}</td>


						<td> {{date("jS F, Y", strtotime($value->created_at))}}</td>
								<td>{{$value->website}}</td>
							        <td><span id="webhook-url">{{"https://realauto.in/wordpress-leads/".$value->user_url_code}}</span><button 
onclick="myFunction()" 
type="button" class="btn btn-link" title="copy url">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" 
ry="1"></rect></svg></button></td>
								<td>{{$value->campaigns_name}}</td>
							</tr>	


							<?php 
								$i++;
							}
							
							?>
							
							</tbody>
						</table>
					</div>
				</div> <!-- /widget-content -->
			
			</div> <!-- /widget -->
            </div>
<!-- end of table -->




<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Work Flow Name</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group row mb-3 ">
            <div class="col-12">
              <label for="inputEmail3" class="col-form-label">Name</label>
              <input id="workflowname" type="text" name="name" class="form-control" value="">
              <span class="text-danger"></span> 
            </div>
           </div>
           <div class="form-group row mb-3 ">
            <div class="col-12">
              <button type="button" class="btn btn-primary">Submit</button>
            </div>
           </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- fb end -->
                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
                <!-- end row-->
            </div> <!-- container-fluid -->
        </div> <!-- content -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?php echo date('Y'); ?> &copy; Realauto. All Rights Reserved. Crafted with <i class='uil uil-heart text-danger font-size-12'></i> by <a href="#" 
target="_blank">Realauto</a>
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

function GetProjects(projectid){
	if(projectid == 2){
		$("#hideprojects_name").show();
		$("#hideprojects_list").hide();
	}else{
		$("#hideprojects_list").show();
		$("#hideprojects_name").hide();
	}
}

function GetSegment(segmentid){

	if(segmentid == 2){
		$("#hidesegment_name").show();
		$("#hidesegment_list").hide();
	}else{
		$("#hidesegment_list").show();
		$("#hidesegment_name").hide();
	}
}

//facebook integration

window.fbAsyncInit = function() {
    FB.init({
      appId      : '3312603435483167',
      xfbml      : true,
      version    : 'v11.0'
    });
};

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
   
function checkLoginState() {               // Called when a person is finished with the Login Button.
    FB.getLoginStatus(function(response) {   // See the onlogin handler
      statusChangeCallback(response);
    });
}

function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
    console.log('statusChangeCallback');
    console.log(response);                   // The current login status of the person.
    
	if (response.status === 'connected') {   // Logged into your webpage and Facebook.
		// myFacebookLogin();
		//    document.getElementById("pageaccesstoken").value = response.authResponse.accessToken;
	FB.api('/me/accounts', function(response) {
	console.log('Successfully retrieved pages', response);
	var pages = response.data;

	$.each(pages , function (key, value) {

		$('#pages-list').append($('<option>',
			{
			value: value.id+"|"+value.access_token,
			text: value.name
		}));
	});
});

    } else {                                 
// Not logged into your webpage or we are unable to tell.
      document.getElementById('status').innerHTML = 'Please log into this webpage.';
    }
  }

function submitLeads() {

	$.ajaxSetup({
		headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	})
	
	
	
	var pages = document.getElementById("pages-list");

	var forms = document.getElementById("forms-list");

	var campaignId = document.getElementById("campaign_id");

	var projectType = document.getElementById("project_type").value;
	
	var segmentType = document.getElementById("segment_type").value;
	
	var projectId = document.getElementById("project_id").value;

	var segmentId = document.getElementById("segment_id").value;
	
	var projectName = 0;
	var segmentName = 0;

	if (projectType == "2") {
		if (document.getElementById("project_name").value.trim().length > 0) {
			document.getElementById("project_name_error").innerHTML = "";
			projectName = document.getElementById("project_name").value;
		} else {
			document.getElementById("project_name").focus();
			document.getElementById("project_name_error").innerHTML = "Enter New Project Name"
			return false;
		}
	}
	
	if (segmentType == "2") {
		if (document.getElementById("segment_name").value.trim().length > 0) {
			document.getElementById("segment_name_error").innerHTML = "";
			segmentName = document.getElementById("segment_name").value;
		} else {
			document.getElementById("segment_name").focus();
			document.getElementById("segment_name_error").innerHTML = "Enter New Segment Name"
			return false;
		}
	}
	
	if(pages.value == "0" || forms.value == "0" || campaignId.value == "0" || (projectId == "0" && projectType == "1") || (segmentId == "0" && segmentName == "1")) {
		var error = 0;
		if (pages.value == "0") {
			error = 1;
			pages.focus();
			document.getElementById("select_page_error").innerHTML = "Select Page"
		} else {
			document.getElementById("select_page_error").innerHTML = ""
		}
		
		if (forms.value == "0") {
			if (error  == 0) {
				error = 1;
				forms.focus();
			}
			document.getElementById("select_form_error").innerHTML = "Select Form"
		} else {
			document.getElementById("select_form_error").innerHTML = ""
		}
		
		if (campaignId.value == "0") {
			if (error  == 0) {
				error = 1;
				campaignId.focus();
			}
			document.getElementById("select_campaign_error").innerHTML = "Select Campaign"
		} else {
			document.getElementById("select_campaign_error").innerHTML = ""
		}
		
		swal("Error!","Fill Complete Form!", "error");
		//alert("Please fill complete form")
		return false;
	}

	var pageData = pages.value;

	var data = pageData.split("|");
	
	var formData = new FormData();

	var pageId = data[0];

	var pageToken = data[1];

	var pageName = pages.options[pages.selectedIndex].text;

	var formId = forms.options[forms.selectedIndex].value;

	var formName = forms.options[forms.selectedIndex].text;

	formData.append('pageToken', pageToken);

	formData.append('pageId', pageId);
	
	formData.append('pageName', pageName);
	
	formData.append('formId', formId);
	
	formData.append('formName', formName);

	formData.append('campaignId', campaignId.value);

	formData.append('projectId', projectId);

	formData.append('segmentId', segmentId);

	formData.append('projectName', projectName);
	
	formData.append('segmentName', segmentName);

	$.ajax({
		type:'POST',
		url: "{{ url('subscribe-page')}}",
		data: formData,	
		cache:false,
		contentType: false,
		processData: false,

		success: (data) => {
			   
			console.log('d',data);
			swal("Success!","Leads Integrated Successfully!", "success");

			setTimeout(function () {
				window.location.href="https://www.realauto.in/leads-master";
			}, 2000);
		},
		
		error: function(data){
				console.log(data);
		}
    })
}
function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("webhook-url").innerHTML;

  /* Select the text field */
  //copyText.select();
 // copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText);

  /* Alert the copied text */
  alert("Copied Webhook URL ");
}

</script>
@endsection

