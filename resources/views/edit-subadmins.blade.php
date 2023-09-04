@extends('layouts.app')
@section('title', 'Edit Admin')
@section('content')
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<!--<link href="{{url('/')}}/assets/css/bootstrap.min.admin.css">-->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

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
.col-md-10{
    padding: 6px;
}
.col-md-2{
    padding: 12px;
}
.avatar-sm {
    height: 3.5rem;
    width: 3.5rem;
}
.user-profile .pro-user-name {
     color: #4b4b5a;
}
.user-profile .pro-user-desc {
    font-size: 1rem;
    color: #6c757d;
}
.modal-dialog {
    left: auto;
}
#exampleModal1 .modal-content {
    margin-left: -150px;
}
#videoModal .modal-content {
    margin-left: 0px;
	margin-top: 0% !important;
}
#exampleModal1 .modal-body {
    padding: 4px !important;
}
#videoModal .modal-body {
    padding: 4px 4px 0px 4px !important;
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
                            <li class="breadcrumb-item"><a href="<?php echo url('/manage-admins'); ?>"> Manage Admins User </a></li>
                            <li class="breadcrumb-item active" aria-current="page">User's</li>
                        </ol>
                    </nav>
                    <h4 class="mb-1 mt-0">Edit User</h4>
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
							<?php $adminId = Request::segment(2); ?>
                           <form action="<?php echo url('/edit-admins-post/'.$adminId); ?>" method="post" name="edit-admins-post" class="form-horizontal"> @csrf
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-file">
                                        </span>Create Profile</a>
                                    </h4>
                                </div>
								<?php 
								//echo "<pre>";
								//print_r($admindata); die;
								?>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="panel-body">
										<div class=" row ">
											 <div class="col-md-2">
                                                <label>Status</label>
                                            </div>
                                            <div class="col-md-10">
											<select name="status" class="form-control custom-select mb-2 " required>
											<option value="1" @if($admindata->status == 1) selected @endif>Active</option>
											<option value="2" @if($admindata->status == 2) selected @endif>In-Active</option>
											</select>
											Do you want to send login credentials whatsapp & email ? <input type="radio" 
name="sendLoginDetails" value="1" >Yes <input type="radio" name="sendLoginDetails" checked value="0">No 
											</div>
										</div>

                                        <div class=" row ">
                                             <div class="col-md-2">
                                                <label>Pack</label>
                                            </div>
                                            <div class="col-md-10">
                                            <select name="pack" class="form-control custom-select mb-2 " required>
                                                <option value="1"  @if($admindata->pack == 1) selected @endif>Starter</option>
                                                <option value="2"  @if($admindata->pack == 2) selected @endif>Normal</option>
                                                <option value="3"  @if($admindata->pack == 3) selected @endif>Advanced</option>
                                            </select>
                                           
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-2">
                                                <label>Full Name *</label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" name="name" class="form-control" placeholder="Name" required value="{{ $admindata->name }}">
                                            </div>
                                        </div>
                                      <div class="row">
                                            <div class="col-md-2">
                                                <label>Email *</label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="email" name="email" class="form-control" placeholder="Email" required value="{{ $admindata->email }}" readonly>
                                            </div>
                                        </div>
                                      <div class="row">
                                            <div class="col-md-2">
                                                <label>Mobile *</label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" name="phone_no" class="form-control" placeholder="Mobile" required value="{{ $admindata->phone_no }}">
                                            </div>
                                        </div>
                                    
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="inputPassword5">Change Password <span class="required">*</span></label>  
                                        </div>
                                        <div class="col-md-10">
                                            Yes <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="yesCheck" value="1" > No <input type="radio" 
onclick="javascript:yesnoCheck();" name="yesno" id="noCheck" checked value="2">
                                        </div>
                                    </div>
                                    <div class="row" id="ifYes" style="visibility:hidden">
                                        <div class="col-md-2">
                                            <label>Password *</label>
                                        </div>
                                        <div class="col-md-10">
                                            <input type="text" name="pwd" class="form-control" placeholder="Enter Password"  value="">
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
												  <label for="inputEmail3" class="col-3 col-form-label">Plans<span 
class="required">*</span></label>
													 <div class="col-9">
													<select name="user-plans" onchange="changeUserPlan()" id="user_plans" 
class="form-control custom-select mb-2" required>
													    <option value="0" >Select Plan</option>
														<option value="1" <?php if ($admindata->user_plans_id == "1") {echo 
"selected";} ?> >15 Days</option>
														<option value="2" <?php if ($admindata->user_plans_id == "2") {echo 
"selected";} ?>>1 month</option>
														<option value="3" <?php if ($admindata->user_plans_id == "3") {echo 
"selected";} ?>>3 Months</option>
														<option value="4" <?php if ($admindata->user_plans_id == "4") {echo 
"selected";} ?>>6 Months</option>
														<option value="5" <?php if ($admindata->user_plans_id == "5") {echo 
"selected";} ?>>3 Days (Trial Plan)</option>
													</select>
													</div>
                                                </div>
                                                <div class="form-group row mb-3">
												  <label for="inputEmail3" class="col-3 col-form-label">Start Date / End Date<span 
class="required">*</span></label>
													<div class="col-4">
														<input type="date" name="start_date" id="start_date" 
value="{{$admindata->start_date}}"  onchange="setEndDate()" name="start_date" class="form-control"  />
													</div>
													<div class="col-4">
														<input type="date" id="end_date" name="end_date" 
value="{{$admindata->end_date}}" class="form-control" placeholder="End Date" readonly />
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
                                        </span>Whatsapp </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        
                                          <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row mb-3">
												  <label for="inputEmail3" class="col-3 col-form-label">Whatsapp Plans<span 
class="required">*</span></label>
												<div class="col-9">
                                                  <select name="whatsapp-plans" class="form-control custom-select mb-2" >
													<option value="0" >Select Plan</option>
													<option value="1" <?php if ($admindata->whatsapp_plans_id == "1") {echo 
"selected";} ?>>15 Days</option>
													<option value="2" <?php if ($admindata->whatsapp_plans_id == "2") {echo 
"selected";} ?>>1 Month</option>
													<option value="3" <?php if ($admindata->whatsapp_plans_id == "3") {echo 
"selected";} ?>>3 Months</option>
													<option value="4" <?php if ($admindata->whatsapp_plans_id == "4") {echo 
"selected";} ?>>6 Months</option>
													<option value="5" <?php if ($admindata->whatsapp_plans_id == "5") {echo 
"selected";} ?>>1 Year</option>
												</select>
												
												Do you want to call the Whatsapp Change Plan Api ? <input type="radio" 
name="isChangePlan" value="1" >Yes <input type="radio" name="isChangePlan" checked value="0">No 
												</div>
                                                </div>
                                                 <div class="form-group row mb-3">
												  <label for="inputEmail3" class="col-3 col-form-label">Whatsapp API Key<span 
class="required">*</span></label>
												  <div class="col-9">
                                                    <input type="text" name="whatsapp_api_key" value="{{$admindata->whatsapp_api_key}}" class="form-control" placeholder="Whatsapp Api"  
/>
												</div>
												</div>
                                                <div class="form-group row mb-3">
												 <label for="inputEmail3" class="col-3 col-form-label">Whatsapp API Username<span 
class="required">*</span></label>
												<div class="col-9">
                                                    <input type="text" name="whatsapp_username" value="{{$admindata->whatsapp_username}}" class="form-control" placeholder="Whatsapp 
Username"  />
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
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree"><span class="glyphicon glyphicon-th-list">
                                        </span>Email </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse">
                                    <div class="panel-body">
                                       
                                        <div class="form-group row mb-3" id="email-api-key"	>
                                        <label for="inputEmail3" class="col-3 col-form-label">Email API Key <span class="required">*</span></label>
                                        <div class="col-9">
                                            <input type="text" name="email-api-key" class="form-control" placeholder="API Key"  value="{{ $admindata->email_api_key }}">
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                             <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapsefour"><span class="glyphicon glyphicon-th-list">
                                        </span>SMS </a>
                                    </h4>
                                </div>
                                <div id="collapsefour" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        
                                       
                                        <div class="form-group row mb-3" >
                                            <label for="inputEmail3" class="col-3 col-form-label">SMS API Key<span class="required">*</span></label>
                                            <div class="col-9">
                                                <input type="text" name="sms-api-key" class="form-control" placeholder="API Key"  value="{{ $admindata->sms_api_key }}">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-3">
                                            <label for="inputEmail3" class="col-3 col-form-label">SMS Username<span class="required">*</span></label>
                                            <div class="col-9">
                                                <input type="text" name="sms-username" class="form-control" placeholder="SMS Username"  value="{{ $admindata->sms_from_name }}">
                                            </div>
                                        </div>
                                    </div>

                                    </div>
                                </div>
                                <br />
                                <div class="row" style="float: right;margin-right: 10px;">
                                    <div class="">
                                        <div class="form-group" >
                                           <button type="submit" class="btn btn-info" name="submit">Submit</button>
                                            <a href="{{ route('manage-admins') }}"><button type="button" class="btn btn-success">Back</button></a>
                                        </div>
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
function yesnoCheck() {
    if (document.getElementById('yesCheck').checked) {
        document.getElementById('ifYes').style.visibility = 'visible';
    } else  {
        document.getElementById('ifYes').style.visibility = 'hidden';
    }
}

function showApi(key, value) {
    //alert(key)
    
    if (key == "sms") {
        if(value=="1") {
            $("#sms-api-key").show();
            //document.getElementById("sms-api-key").style.visibility = 'visible';
        } else {
            $("#sms-api-key").hide();
            //document.getElementById("sms-api-key").style.visibility = 'hidden';
        }
    }
    
    if (key == "email") {
        if(value=="1") {
            $("#email-api-key").show();
            //document.getElementById("email-api-key").style.visibility = 'visible';
        } else {
            $("#email-api-key").hide();
            //document.getElementById("email-api-key").style.visibility = 'hidden';
        }
    }
    if (key == "whatsapp") {
        if(value=="1") {
            $("#whatsapp-api-key").show();
            document.getElementById("whatsapp-api-key").style.visibility = 'visible';
        } else {
            $("#whatsapp-api-key").hide();
            document.getElementById("whatsapp-api-key").style.visibility = 'hidden';
        }
    }
	
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
			days = 30
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

    
}
</script>
@endsection

