@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<style type="text/css">
    .form-inline .form-group { margin-right:10px; }
    .well-primary {
    color: rgb(255, 255, 255);
    background-color: rgb(66, 139, 202);
    border-color: rgb(53, 126, 189);
    }
    .glyphicon { margin-right:5px; }

    .content-page {
    margin-top: 21px !important;
}
</style>
<!------ Include the above in your HEAD tag ---------->

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">
            <div class="container-fluid"> 
            <div class="row page-title">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb" class="float-right mt-1">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/manage-admins'); ?>"> Manage Admins </a></li>
                            <li class="breadcrumb-item active" aria-current="page">Admins</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 mt-0">Create Admin</h4>
                </div>
            </div>
             @if(Session::has('message'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{!! session('message') !!}</strong>
                    </div>
                @endif 

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel-group" id="accordion">
                           <form action="<?php echo url('/save-admin'); ?>" method="post" name="save-admin" onsubmit="return validateForm()">
                            @csrf
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-file">
                                        </span>Create Profile</a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                     
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                   <label>Full Name *</label>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <input type="text" id="name" value="{{old('name', "")}}" onkeyup="myName()" name="name" class="form-control" placeholder="Enter Full Name" required/>
                                                    <p id="nameerror" style="color:red;"></p>
                                                </div>
                                            </div>
                                        </div>
                                      <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                   <label>Email *</label>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <input type="text" name="email" id="email" value="{{old('email', "")}}"onkeyup="myEmail();" class="form-control" placeholder="Enter Email" required />
                                                     <p id="emailerror" style="color:red;"></p>
                                                </div>
                                            </div>
                                        </div>
                                      <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                   <label>Mobile *</label>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <input type="text" name="phone_no" value="{{old('phone_no', "")}}" onkeyup="myMobile()" id="mobile" class="form-control" placeholder="Enter Mobile Number" required maxlength="10" />
                                                     <p id="mobileerror" style="color:red;"></p>
                                                </div>
                                            </div>
                                        </div>
                                     <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                   <label>Password *</label>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <input type="text" name="password" value="{{old('password', "")}}" id="txtPassword" class="form-control" placeholder="Enter Password" required />
                                                     <p id="passworderror" style="color:red;"></p>
                                                </div>
                                            </div>
                                        </div>
                                       <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                   <label>Re Password *</label>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <input type="text" name="cpassword" value="{{old('cpassword', "")}}" onkeyup="myPassword()" id="txtConfirmPassword" class="form-control" placeholder="ReEnter Password" required />
                                                     <p id="repassworderror" style="color:red;"></p>
                                                </div>
                                            </div>
                                        </div>
									</div>
								</div>
								
							<div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo1"><span class="glyphicon glyphicon-th-list">
                                        </span>RealAuto Plans</a>
                                    </h4>
                                </div>
								
								<div id="collapseTwo1" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row mb-3">
												  <label for="inputEmail3" class="col-3 col-form-label">Plans<span class="required">*</span></label>
													 <div class="col-9">
													<select name="user-plans" onchange="changeUserPlan()" id="user_plans" class="form-control custom-select mb-2" required>
													    <option value="0" >Select Plan</option>
														<option value="1" >15 Days</option>
														<option value="2">1 month</option>
														<option value="3" >3 Months</option>
														<option value="4">6 Months</option>
														<option value="5">3 Days (Trial Plan)</option>
														</select>
													</div>
                                                </div>
                                                <div class="form-group row mb-3">
												  <label for="inputEmail3" class="col-3 col-form-label">Start Date / End Date<span class="required">*</span></label>
													<div class="col-4">
														<input type="date" name="start_date" id="start_date" value=""  onchange="setEndDate()" name="start_date" class="form-control"  />
													</div>
													<div class="col-4">
														<input type="date" id="end_date" name="end_date" value="" class="form-control" placeholder="End Date" readonly />
													</div>												
												</div>
											</div>
										</div>
									</div>
								</div>
								
                            </div>
							
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><span class="glyphicon glyphicon-th-list">
                                        </span>WhatsApp API</a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                  <select name="whatsapp-plans" class="form-control custom-select mb-2" >
													<option value="0" >Select Plan</option>
													<option value="1" >3 Day Trial</option>
													<option value="2" >1 Month</option>
													<option value="3" >3 Months</option>
													<option value="4" >1 Year</option>
													<option value="5" >Lifetime</option>
												</select>
												Do you want to change the WhatsApp API plan? <input type="radio" name="isChangePlan" value="1" >Yes <input type="radio" name="isChangePlan" checked value="0">No 
                                                </div>
                                                 <div class="form-group">
                                                    <input type="text" name="whatsapp_api_key" value="{{old('whatsapp_api_key', "")}}" class="form-control" placeholder="Whatsapp Api"  />
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" name="whatsapp_username" value="{{old('whatsapp_username', "")}}" class="form-control" placeholder="Whatsapp Username"  />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree"><span class="glyphicon glyphicon-th-list">
                                        </span>Email Api</a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="text" name="email_api_key" value="{{old('email_api_key', "")}}" class="form-control" placeholder="Email.."  />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapsefour"><span class="glyphicon glyphicon-th-list">
                                        </span>SMS Api</a>
                                    </h4>
                                </div>
                                <div id="collapsefour" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="text" name="sms_api_key" value="{{old('sms_api_key', "")}}" class="form-control" placeholder="Sms Api"  />
                                                </div>
                                                <div class="form-group">
                                                    <input type="text" name="sms_from_name" value="{{old('sms_from_name', "")}}" class="form-control" placeholder="Sms Username"  />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
							<div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive"><span class="glyphicon glyphicon-th-list">
                                        </span>Campaigns</a>
                                    </h4>
                                </div>
								
								<div id="collapseFive" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row mb-3">
												  <label for="inputEmail3" class="col-3 col-form-label">Select Campaign<span class="required">*</span></label>
													<div class="col-9">
														<select name="campaign"  id="campaign" class="form-control custom-select mb-2" required>
															<option value="0" >Default</option>
															<?php 
															foreach($campaigns as $key => $value) {?>
																<option value="{{$value->id}}" >{{$value->campaigns_name}}</option>
															<?php
															}
															?>
														</select>
													</div>
                                                </div>
                                                
											</div>
										</div>
									</div>
								</div>
								
                            </div>
                            <br />
                            <div class="row" style="float: right;margin-right: 14px;">
                            <div class="">
                                <div class="form-group" >
                                   <button class="btn btn-info" type="submit" name="submit">Submit</button>
                                    <a href="{{ route('manage-admins') }}"><button type="button" class="btn btn-success">Back</button></a>
                                </div>
                            </div>
                        </div>
                        </div>
                        </form>
                    </div>
                </div>
		    </div>
         </div>  
          <!-- Footer Start -->
      
        <!-- end Footer --> 
	</div>				

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->
<script>
//const name = document.getElementById('name').value;
function myName(){
   var name = document.getElementById('name').value;
   var namevalue = name.trim()
 
   if (namevalue.length > 0) {
        document.getElementById('nameerror').innerHTML = "";
        return true;
   } else {
    //alert("Enter Full Name")
        document.getElementById('nameerror').innerHTML = "Please Enter Your Full Name.";
        return false;
   }
}    

function myEmail(){
   var email = document.getElementById('email').value;
   var emailvalue = email.trim()

   if (emailvalue.length > 0) {
    document.getElementById('eamilerror').innerHTML = "";
    return true;
   } else {
    //alert("Enter Full Name")
        document.getElementById('emailerror').innerHTML = "Please Enter Your Valid Email.";
        return false;
   }
}    

function myMobile(){
   var mobile = document.getElementById('mobile').value;
  // alert(mobile)
   var mobilevalue = mobile.trim()

   if (mobilevalue.length > 0) {
    document.getElementById('mobileerror').innerHTML = "";
    return true;
   } else {
    //alert("Enter Full Name")
        document.getElementById('mobileerror').innerHTML = "Please Enter Your Mobile Number.";
        return false;
   }
}    
//password

 function myPassword() {
    var password = document.getElementById("txtPassword").value;
    var confirmPassword = document.getElementById("txtConfirmPassword").value;
    if (password != confirmPassword) {
        //alert("Passwords do not match.");
        document.getElementById('repassworderror').innerHTML = "Re-Password Don't Match.";
        return false;
    }
    document.getElementById('repassworderror').innerHTML = "";
    
    return true;
}

function validateForm() {
    if(myName() && myMobile()){
        return true;
    }
    return false;
}

function changeUserPlan() {
	
	if(document.getElementById("start_date").value != "") {
		setEndDate()
	} else {
		alert('select start date first')
	}
}
	
	
function setEndDate() {
	var plan = document.getElementById("user_plans").value;
	
	if (plan > 0){
		var days = 0;
			if (plan == "1") {
				days = 15
			} else if (plan == "2") {
				days = 90
			} else if (plan == "3") {
				days = 180
			} else if (plan == "4") {
				days = 365;
			} else if (plan == "5") {
				days = 3;
			} else {
				days = 0;
			}
		var startDate = document.getElementById("start_date").value;
		//alert(startDate)
		const newDate = startDate.split("-");
		//alert(newDate)
		var year = newDate[0];
		
		var mon = newDate[1];
		var date1 = newDate[2];

		const date = new Date(year,mon-1,date1);

		date.setDate(date.getDate() + days);
		document.getElementById("end_date").value = date.toISOString().slice(0, 10);
	} else {
		alert("Please select user plan")
	}
//alert(date.toISOString().slice(0, 10))
//alert(date)
}

</script>
@endsection
