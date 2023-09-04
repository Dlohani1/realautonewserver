@extends('layouts.app')
@section('title', 'RealAuto | Facebook Lead Integration')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->
<!--
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
-->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />

<!--	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>



<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v11.0&appId=3312603435483167&autoLogAppEvents=1" nonce="PwoHvEZv"></script>
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



.select2-container {
  /*min-width: 400px;*/
}

.select2-results__option {
  padding-right: 20px;
  vertical-align: middle;
}
.select2-results__option:before {
  content: "";
  display: inline-block;
  position: relative;
  height: 20px;
  width: 20px;
  border: 2px solid #e9e9e9;
  border-radius: 4px;
  background-color: #fff;
  margin-right: 20px;
  vertical-align: middle;
}
.select2-results__option[aria-selected=true]:before {
  font-family:fontAwesome;
  content: "\f00c";
  color: #fff;
  background-color: #f77750;
  border: 0;
  display: inline-block;
  padding-left: 3px;
}
.select2-container--default .select2-results__option[aria-selected=true] {
	background-color: #fff;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
	background-color: #eaeaeb;
	color: #272727;
}
.select2-container--default .select2-selection--multiple {
	margin-bottom: 10px;
}
.select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
	border-radius: 4px;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
	border-color: #f77750;
	border-width: 2px;
}
.select2-container--default .select2-selection--multiple {
	border-width: 2px;
}
.select2-container--open .select2-dropdown--below {
	
	border-radius: 6px;
	box-shadow: 0 0 10px rgba(0,0,0,0.5);

}
.select2-selection .select2-selection--multiple:after {
	content: 'hhghgh';
}
/* select with icons badges single*/
.select-icon .select2-selection__placeholder .badge {
	display: none;
}
.select-icon .placeholder {
	display: none;
}
.select-icon .select2-results__option:before,
.select-icon .select2-results__option[aria-selected=true]:before {
	display: none !important;
	/* content: "" !important; */
}
.select-icon  .select2-search--dropdown {
	display: none;
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
                        <h4 class="mb-1 mt-0">Facebook Integration</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <!--<div class="card">
                            <div class="card-body">
								@if(Session::has('message'))
                                    <div class="alert alert-success alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{!! session('message') !!}</strong>
                                    </div>
                                @endif
                                <div class="row align-items-center">
                                    <div class="col text-right">
                                        <button class="btn btn-primary" id="btn-new-event" onclick="window.location.href='<?php echo url('/leads-master'); ?>'"><i class="uil-plus mr-1"></i>View Leads</button>
                                    </div>
                                </div> 
                            </div>
                        </div> -->

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
            <button onclick="fillForm()" style="display:none"> Fill Form </button>
            <form id="myForm" action="https://www.realauto.in/add-leads-post-data" method="post" name="add-leads-post-data" class="form-horizontal" onsubmit="return checkDigit()">
              
               <div class="form-group row mb-3 " style="display:none">
                <label for="inputPassword5" class="col-4 col-form-label">Choose App <span class="required">*</span></label>
                <div class="col-6">
                  <select name="project_type" class="custom-select mb-2" onchange="GetProjects(this.value)">
                    <option value="">Select App</option>
                    <option value="1"><i class="fa fa-facebook-square"></i> Facebook Lead Add</option>
                    <option value="2">WhatsApp Lead Add</option>
                  </select>
                  <span class="text-danger"></span> </div>
              </div>
              <div class="form-group row mb-3 ">
                <div class="col-3"></div>
                <div class="col-sm-6 col-xl-6">
<div class="fb-login-button" data-width="" data-size="medium" data-button-type="continue_with" data-layout="rounded" data-auto-logout-link="false"
data-use-continue-as="false" data-scope="pages_read_engagement,pages_manage_metadata,pages_manage_ads,email,pages_show_list,leads_retrieval,public_profile"
onlogin="checkLoginState();"></div>

                   <style>
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 5vh; /* Adjust the height as needed */
    }
</style>

<div class="container">
    <button type="button" class="btn btn-primary" style="display:none">Connect With Facebook</button>
</div>

               </div>
              </div>
    
              
              <div class="form-group row mb-3 ">
               <label for="inputPassword5" class="col-4 col-form-label">Page <span class="required">*</span></label>
                <div class="col-6">
                  <select id = "pages-list" name="project_type" class="custom-select mb-2" onchange = "subscribePage()">
                    <option value="0">Select Page</option>
                  </select>
				  <span id="select_page_error" style="color:red"> </span>
                  <span class="text-danger"></span> </div>
              </div>
              
              <div class="form-group row mb-3 ">
               <label for="inputPassword5" class="col-4 col-form-label">Optin Form <span class="required">*</span></label>
                <div class="col-6">
                  <select id="forms-list" name="project_type" class="custom-select mb-2" onchange="showFields()">
                    <option value="0">Select Form</option>
                  </select>
				  <span id="select_form_error" style="color:red"> </span>
                  <span class="text-danger"></span> </div>
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
       <div class="form-group row mb-3 {{ $errors->has('campaign_id')? 'has-error':'' }}">
		<label for="inputPassword5" class="col-4 col-form-label">Name <span class="required">*</span></label>
		<div class="col-6">
			<select name="field_name" class="custom-select mb-2" id="field_name">
				<option value="0">Select Field</option>
				
			</select>	
		</div>
	</div>          
<div class="form-group row mb-3 {{ $errors->has('campaign_id')? 'has-error':'' }}">
		<label for="inputPassword5" class="col-4 col-form-label">Phone <span class="required">*</span></label>
		<div class="col-6">
			<select name="field_phone" class="custom-select mb-2" id="field_phone">
				<option value="0">Select Field</option>
				
			</select>
		</div>
	</div>
	<div class="form-group row mb-3 {{ $errors->has('campaign_id')? 'has-error':'' }}">
		<label for="inputPassword5" class="col-4 col-form-label">Email <span class="required">*</span></label>
		<div class="col-6">
			<select name="field_email" class="custom-select mb-2" id="field_email">
				<option value="0">Select Field</option>
			</select>
		</div>
	</div>


	<div class="form-group row mb-3 {{ $errors->has('campaign_id')? 'has-error':'' }}">
		<label for="inputPassword5" class="col-4 col-form-label">Other Fields <span class="required">*</span></label>
		<div class="col-6">
			<select id="other_fields" class="js-select2" multiple="multiple" name="other_fields[]" onchange="test()">
				
			</select>
		</div>
	</div>


<!--
              <div class="form-group row mb-3 ">
                <label for="inputPassword5" class="col-3 col-form-label">Project <span class="required">*</span></label>
                <div class="col-6">
                  <select name="project_type" class="custom-select mb-2" onchange="GetProjects(this.value)">
                    <option value="">Select Project</option>
                    <option value="1">Old</option>
                    <option value="2">New</option>
                  </select>
                  <span class="text-danger"></span> </div>
              </div>
              <div class="form-group row mb-3 ">
                <label for="inputPassword5" class="col-3 col-form-label">Segment <span class="required">*</span></label>
                <div class="col-6">
                  <select name="segment_type" class="custom-select mb-2" onchange="GetSegment(this.value)">
                    <option value="">Select Segment</option>
                    <option value="1">Old</option>
                    <option value="2">New</option>
                  </select>
                  <span class="text-danger"></span> </div>
              </div>
              <div class="form-group row mb-3 " id="hidesegment_name" style="display: none;">
                <label for="inputPassword5" class="col-3 col-form-label">Segment Name <span class="required">*</span></label>
                <div class="col-6">
                  <input type="text" name="segment_name" class="form-control" placeholder="Enter New Segment Name">
                  <span class="text-danger"></span> </div>
              </div>
              <div class="form-group row mb-3" id="hidesegment_list" style="display: none;">
                <label for="inputPassword5" class="col-3 col-form-label">Segment Name <span class="required">*</span></label>
                <div class="col-6">
                  <select name="segment_id" class="custom-select mb-2">
                    <option value="">Select Segment Name</option>
                    <option value="15">Webinar</option>
                    <option value="16">test</option>
                    <option value="23">Flat 2</option>
                    <option value="27">Balaji-Nagpur</option>
                    <option value="52">Prestimge</option>
                    <option value="53">Magic</option>
                    <option value="65">For Trial</option>
                    <option value="87">Facebook</option>
                    <option value="88">test-bulk</option>
                    <option value="92">test attachment</option>
                    <option value="111">Dubai expo</option>
                    <option value="112">ss</option>
                    <option value="113">Test 2</option>
                    <option value="115">Demo</option>
                  </select>
                </div>
              </div>
              <div class="form-group row mb-3 ">
                <label for="inputPassword5" class="col-3 col-form-label">Campaign <span class="required">*</span></label>
                <div class="col-6">
                  <select name="campaign_id" class="custom-select mb-2">
                    <option value="">Select Campaign</option>
                    <option value="9">Realauto Webinar-14-6-2021</option>
                    <option value="10">Realauo.in</option>
                    <option value="16">Ayan</option>
                    <option value="18">Flat 20L New</option>
                    <option value="20">Deepak Test</option>
                    <option value="26">SILVER PART</option>
                    <option value="98">test attachment</option>
                    <option value="102">Deepak 1</option>
                    <option value="109">testsms</option>
                    <option value="140">Before Site Visit</option>
                    <option value="141">WEDNESS DAY</option>
                    <option value="146">test-cron</option>
                    <option value="153">Copy-Ayan</option>
                    <option value="157">Copy-testsms</option>
                    <option value="161">Demo</option>
                    <option value="168">new cron logic</option>
                    <option value="171">Test-16.08.21</option>
                    <option value="176">Copy-new cron logic</option>
                    <option value="179">TODAY TEST</option>
                    <option value="180">testeditevent</option>
                    <option value="181">test image</option>
                    <option value="189">testreport</option>
                  </select>
                  <span class="text-danger"></span> </div>
              </div>
-->
              <div class="form-group mb-0 row">
                <div class="col-4"></div>
                <div class="col-6">
                  <button id="submit" type="button" class="btn btn-info" onclick="submitLeads()"><strong>Submit</strong></button>
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
					<h3>Integrated Pages</h3>
				</div> <!-- /widget-header -->
				
				<div class="widget-content">
					<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th> Sl.no </th>
		                      	<th>Integration Date </th>		
								<th>Page Name</th>
								<th>Form Name</th>
								<th>Campaign Name</th>
								<th class="td-actions">Actions</th>
							</tr>
						</thead>
						<tbody>

							<?php	
		
							$i = 1;

							foreach($fbPages as $key => $value) {?>
							
							<tr>
								
								<td>{{$i}}</td>

								<td> {{date("jS F, Y", strtotime($value->created_at))}}</td>
								<td>{{$value->page_name}}</td>
							        <td>{{$value->form_name}}</td>
								<td>{{$value->campaigns_name}}</td>
								<td><label class="switch">
                        					<input type="checkbox" id="switch"  <?php if ($value->is_active > 0) {echo "checked";} ?> onchange="saveSettings(<?php echo $value->id;?>,'switch')">
                        					<span class="slider round"></span>
                        					</label></td>
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


  function subscribePage() {

        var pageData = document.getElementById("pages-list").value;
        const data = pageData.split("|");
        var page_id = data[0]
        var page_access_token = data[1]


     //document.getElementById("pageaccesstoken").value = page_access_token;
     //document.getElementById("pageid").value = page_id;
     //var e = document.getElementById("pages-list");

     //var text=e.options[e.selectedIndex].text;
     //document.getElementById("pagename").value = text;

    //console.log('Subscribing page to app! ' + page_id);
    

 
FB.api(
      '/' + page_id + '/subscribed_apps',
      'post',
      {access_token: page_access_token, subscribed_fields: ['leadgen','name','phone']},
      function(response) {
        console.log('Successfully subscribed page', response);
        alert('Successfully Subscribed Page');
      });

FB.api(
    "/"+page_id+"/leadgen_forms",
    'get',
      {access_token: page_access_token,limit:200},

    function (response) {
	console.log('ress',response)

      	if (response && !response.error) {

		var leadForms = response.data;

		$.each(leadForms, function (key, value) {

				$('#forms-list').append($('<option>',
                                    {
                                    value: value.id,
                                    text: value.name
                                }));

		});

        	/* handle the result */
      	}
//showFields(page_access_token);
    });
}

function showFields() {
	$.ajaxSetup({
                headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        })


	var formData = new FormData();
        var pages = document.getElementById("pages-list");
var pageData = pages.value;

        var data = pageData.split("|");

       //var formData = new FormData();

        //var pageId = data[0];

        var pageToken = data[1];

var forms = document.getElementById("forms-list");
    var formId = forms.options[forms.selectedIndex].value;

	formData.append('accessToken', pageToken);

	formData.append('formId', formId);
	
	$.ajax({
		type:'POST',
		url: "{{ url('show-fb-form-fields')}}",
		data: formData,	
		cache:false,
		contentType: false,
		processData: false,
		
		success: (data) => {
			   
			console.log('da',data);
		data = JSON.parse(data);
console.log('ed',data) 
  		$.each(data, function (key, value) {

                               //console.log('a',value)
 			$('#field_name').append($('<option>',
                                    {
                                    value: value[0],
                                    text: value[1]
                                }));

                        $('#field_phone').append($('<option>',
                                    {
                                    value: value[0],
                                    text: value[1]
                                }));


                         $('#field_email').append($('<option>',
                                    {
                                    value: value[0],
                                    text: value[1]
                                }));
/*
			$('#other_fields').append($('<option>',
                                    {
                                    value: value[0],
                                    text: value[1]
                                }));
*/

$('.js-select2').append($('<option>', {
    value: value[0],
    text: value[1]
}));

});

/*
$('.js-select2').append($('<option>', {
    value: value[0],
    text: value[1]
}));
*/




			//swal("Success!","Leads Integrated Successfully!", "success");
		},
		
		error: function(data){
				console.log(data);
		}
    })
	
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

  $('#submit').attr('disabled', 'disabled');

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


	formData.append('nameField', document.getElementById("field_name").value);

        formData.append('emailField', document.getElementById("field_email").value)

        formData.append('phoneField', document.getElementById("field_phone").value)

 // var otherFields = document.getElementById("other_fields"); 
 //var otherFieldsValue = otherFields.options[pages.selectedIndex].text;
/*
var otherFields = ""

					for(i=0;i<$(".js-select2").select2('data').length;i++) {
						//console.log($(".js-select2").select2('data')[i].id)
						otherFields += $(".js-select2").select2('data')[i].id
						var j = i+1;
						if (j != $(".js-select2").select2('data').length) {
							otherFields += ","
						}
					}
*/
// formData.append('otheField', otherFields)

console.log($(".js-select2").val() )

 formData.append('otherField', $(".js-select2").val() )

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
				location.reload();
				//window.location.href="https://www.realauto.in/leads-master";
			}, 2000);
		},
		
		error: function(data){
				console.log(data);
		}
    })
}

$(".js-select2").select2({
	closeOnSelect : false,
	placeholder : " Select Fields",
	allowHtml: true,
	allowClear: true,
	tags: true 
});

//console.log('ad',$(".js-select2").select2('data'))
      

function test() {
// console.log('k',$(".js-select2").select2('data'))
}

function saveSettings(pageId,id) {
    //alert(pageId)

    var field = document.getElementById(id);
    switchStatus = $(field).is(':checked');

    if (switchStatus) {
        switchStatus = 1;
    } else {
        switchStatus = 0;
    }


    var formData = {_token: "{{ csrf_token() }}",id:pageId,status:switchStatus}; //Array 
    var url = "{{ url('save-fbpage-settings')}}";
    $.ajax({
        url : url,
        type: "POST",
        data : formData,
        success: function(data, textStatus, jqXHR)
        {                  

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
     
        }
    });
}

</script>
@endsection