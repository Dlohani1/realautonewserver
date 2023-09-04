@extends('layouts.app')
@section('title', 'Add New Leads')
@section('content')

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">
            <!-- Start Content--> 
            <div class="container-fluid">
                <div class="row page-title">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb" class="float-right mt-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo url('/leads-master'); ?>"> Manage Leads </a></li>
                                <li class="breadcrumb-item active" aria-current="page">Leads</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Create Lead</h4>
                    </div>
                </div>

                <!-- end row -->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">

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

                                <?php if (Session::has('error-message')){ ?>
                                    <div class="alert alert-danger"><?php echo Session::get('error-message'); ?></div>
                                <?php } ?>
								<button onclick="fillForm()" style="display:none"> Fill Form </button>

                                <form id="myForm" action="<?php echo url('/add-leads-post-data'); ?>" method="post" name="add-leads-post-data" class="form-horizontal" onsubmit="return checkDigit()">
                                    @csrf

                                    <div class="form-group row mb-3 {{ $errors->has('name')? 'has-error':'' }}">
                                        <label for="inputEmail3" class="col-3 col-form-label">Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input id="fullname" type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}" onkeyup="myName()" autocomplete="off">
                                            <p id="nameerror" style="color:red;"></p>
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3 {{ $errors->has('mail_id')? 'has-error':'' }}">
                                        <label for="inputEmail3" class="col-3 col-form-label">Email <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input id="email" title="abc@gmail.com" type="email" name="mail_id" class="form-control" placeholder="Email" value="{{ old('mail_id') }}" onkeyup="myEmail()" autocomplete="off">
                                             <p id="emailerror" style="color:red;"></p>
                                            <span class="text-danger">{{ $errors->first('mail_id') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3 {{ $errors->has('mobile_no')? 'has-error':'' }}">
                                        <label for="inputEmail3" class="col-3 col-form-label">Mobile No<span class="required">*</span></label>
                                        <div class="col-6">
                                            <input id="mobno" type="text" name="mobile_no" class="form-control" placeholder="Mobile" value="{{ old('mobile_no') }}" onkeyup="myMobile()" maxlength="10" autocomplete="off">
                                             <span id="mobileerror" style="color:red;"></span>
                                            <span class="text-danger">{{ $errors->first('mobile_no') }}</span>
											<span id="mobnoErr" style="display:none;color:red"></span>

                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputPassword3" class="col-3 col-form-label">Country </label>
                                        <div class="col-6">
                                            <input type="text" name="country" id="country" class="form-control" onchange="myCountry();" placeholder="Country">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">State </label>
                                        <div class="col-6">
                                            <input type="text" name="state" id="state" class="form-control" placeholder="State" >
                                            
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">City </label>
                                        <div class="col-6">
                                            <input type="text" name="city" id="city" class="form-control" placeholder="City" >
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Zipcode </label>
                                        <div class="col-6">
                                            <input type="text" name="zipcode" class="form-control" placeholder="Zipcode" >
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Company </label>
                                        <div class="col-6">
                                            <input type="text" name="company" class="form-control" placeholder="Company" >
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Position </label>
                                        <div class="col-6">
                                            <input type="text" name="position" class="form-control" placeholder="Position" >
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Address1 </label>
                                        <div class="col-6">
                                            <input type="text" name="address1" class="form-control" placeholder="Address1" >
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Address2 </label>
                                        <div class="col-6">
                                            <input type="text" name="address2" class="form-control" placeholder="Address2" >
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3 {{ $errors->has('project_type')? 'has-error':'' }}">
                                        <label for="inputPassword5" class="col-3 col-form-label">Project <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="project_type" id="project" class="custom-select mb-2" onchange="GetProjects(this.value); myProject()">
                                                <option value="">Select Project</option>
                                                <option value="1">Old</option>
                                                <option value="2">New</option>
                                            </select>
                                            <p id="projecterror" style="color:red;"></p>
                                            <span class="text-danger">{{ $errors->first('project_type') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3" id="hideprojects_name" style="display: none;">
                                        <label for="inputPassword5" class="col-3 col-form-label">Project Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="project_name" id="projectnametext" class="form-control" onkeyup="myProjectnametext()" placeholder="Enter New Project Name" autocomplete="off">
                                            <p id="projectnametexterror" style="color:red;"></p>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3" id="hideprojects_list" style="display: none;">
                                        <label for="inputPassword5" class="col-3 col-form-label">Project Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="project_id" id="projectname" class="custom-select mb-2" onchange="myProjectname()">
                                                <option value="">Select Project Name</option>
                                                @foreach($projects as $rowproject)
                                                <option value="{{ $rowproject->id }}">{{ $rowproject->project_name }}</option>
                                                @endforeach
                                            </select>
                                            <p id="projectnameerror" style="color:red;"></p>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3 {{ $errors->has('segment_type')? 'has-error':'' }}">
                                        <label for="inputPassword5" class="col-3 col-form-label">Segment <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="segment_type" id="segment" class="custom-select mb-2" onchange="GetSegment(this.value); mySegment()">
                                                <option value=" ">Select Segment</option>
                                                <option value="1">Old</option>
                                                <option value="2">New</option>
                                            </select>
                                            <p id="segmenterror" style="color:red;"></p>
                                            <span class="text-danger">{{ $errors->first('segment_type') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3 {{ $errors->has('segment_name')? 'has-error':'' }}" id="hidesegment_name" style="display: none;">
                                        <label for="inputPassword5" class="col-3 col-form-label">Segment Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="segment_name" id="segmentnametext" class="form-control" onkeyup="mySegmentnametext()" placeholder="Enter New Segment Name" autocomplete="off">
                                            <p id="segmentnametexterror" style="color:red;"></p>
                                            <span class="text-danger">{{ $errors->first('segment_name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3" id="hidesegment_list" style="display: none;">
                                        <label for="inputPassword5" class="col-3 col-form-label">Segment Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="segment_id" id="segmentname" class="custom-select mb-2" onchange="mySegmentname()">
                                                <option value=" ">Select Segment Name</option>
                                                @foreach($segments as $rowsegments)
                                                <option value="{{ $rowsegments->id }}">{{ $rowsegments->segment_name }}</option>
                                                @endforeach
                                            </select>
                                            <p id="segmentnameerror" style="color:red;"></p>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3 {{ $errors->has('campaign_id')? 'has-error':'' }}">
                                        <label for="inputPassword5" class="col-3 col-form-label">Campaign <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="campaign_id" id="campaigns" class="custom-select mb-2" onchange="myCampaign()">
                                                <option value="">Select Campaign</option>
                                                @foreach($campaigns as $rowcampaign)
                                                <option value="{{ $rowcampaign->id }}">{{ $rowcampaign->campaigns_name }}</option>
                                                @endforeach
                                            </select>
                                            <p id="campaignserror" style="color:red;"></p>
                                            <span class="text-danger">{{ $errors->first('campaign_id') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-6">
                                            <button id="submit"  type="submit" class="btn btn-info">Submit</button>
                                            <a href="{{ route('leads-master') }}"><button type="button" class="btn btn-success">Back</button></a>
                                        </div>
                                    </div>
                                </form>
                            </div>  <!-- end card-body -->
                        </div>  <!-- end card -->
                    </div>  <!-- end col -->
                </div>
                <!-- end row -->
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

@endsection
@push('scripts')
    <script>
		$('#myForm').on('submit', function () {
			if (checkDigit()) {
			   $('#submit').attr('disabled', 'disabled');
			}
		});
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
		
		function fillForm() {
			document.getElementById("fullname").value="Deepak";
			document.getElementById("email").value="dlohani1@gmail.com";
			document.getElementById("mobno").value="8878100065";
		}
        //valaidatio
        function myName(){
            var fullname = document.getElementById("fullname").value;
            var namevalue = fullname.trim()
            if(namevalue.length > 0){
                document.getElementById('nameerror').innerHTML = "";
             $( "#fullname" ).addClass( "is-valid" );
                return true;
            }else{
            $( "#fullname" ).removeClass("is-valid");
			 document.getElementById("fullname").focus()
            document.getElementById('nameerror').innerHTML = "Enter Your Full Name";
            return false;
           }
        }
 //validation
         function myEmail(){
            var email = document.getElementById("email").value;
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            var emailvalue = email.trim()
            if(emailvalue.length > 0){
                if(emailvalue.match(mailformat)){
                document.getElementById('emailerror').innerHTML = "";
             $( "#email" ).addClass( "is-valid" );
                return true;
               }
               else{
                 $( "#email" ).removeClass("is-valid");
				 document.getElementById("email").focus()
                document.getElementById('emailerror').innerHTML = "Enter valid Email";
                return false;
               }
            }else{
            $( "#email" ).removeClass("is-valid");
			document.getElementById("email").focus()
            document.getElementById('emailerror').innerHTML = "Enter valid Email";
            return false;
           }
        }
    
        function myMobile(){
            var mobile = document.getElementById("mobno").value;
            var mobileformate = /^\d{10}$/g;
            var mobilevalue = mobile.trim()
            var firstDigit = mobile[0];
            if(mobilevalue.length > 0){
                if (firstDigit == "0") {
                alert("Mobile No should not start with 0")
                //document.getElementById("mobnoErr").style.display="";
                return false
                 }
                if(mobilevalue.match(mobileformate)){
					document.getElementById('mobileerror').innerHTML = "";
					$( "#mobno" ).addClass( "is-valid" );
					return true;
				} else {
					$( "#mobno" ).removeClass("is-valid");
					document.getElementById("mobno").focus()
					document.getElementById('mobileerror').innerHTML = "Enter valid 10 digit mobile no";
					return false;
				}
            } else {
				$( "#mobno" ).removeClass("is-valid");
				document.getElementById("mobno").focus()
				document.getElementById('mobileerror').innerHTML = "Enter Your Mobile Number";
				return false;
			}
        }
        
		
		
		function myProject(){
			var project = document.getElementById("project").value;
			
			if(project == "1" || project == "2"){
				document.getElementById('projecterror').innerHTML = "";
				$( "#project" ).css("border", "1px solid #43d39e");
				if (project == "1" && !myProjectname()) {
					return false;
				}
				
				if (project == "2" && !myProjectnametext()) {
					return false
				}
				
				return true;
			} else {
				$( "#project" ).css("border", "1px solid red");
				document.getElementById("project").focus()
				document.getElementById('projecterror').innerHTML = "Select Your Project";
				return false;
			}
		}
        
		function mySegment(){
			var segment = document.getElementById("segment").value;
			if(segment == "1" || segment == "2"){
				document.getElementById('segmenterror').innerHTML = "";
				$( "#segment" ).css("border", "1px solid #43d39e");
				if (segment == "1" && !mySegmentname()) {
					return false;
				}
				
				if (segment == "2" && !mySegmentnametext()) {
					return false
				}
				
				return true;
			} else {
				$( "#segment" ).css("border", "1px solid red");
				document.getElementById('segmenterror').innerHTML = "Select Your Segments";
				return false;
           }
        }
         function myCampaign(){
            //alert('hii');
            var campaigns = document.getElementById("campaigns").value;
            //var projetctvalue = mobile.trim()
            if(campaigns == ""){
                //alert('hii')
                 $( "#campaigns" ).css("border", "1px solid red");
            document.getElementById('campaignserror').innerHTML = "Select Your campaigns";
               
                return false;
            }else{
               // alert('bye')
                 document.getElementById('campaignserror').innerHTML = "";
             $( "#campaigns" ).css("border", "1px solid #43d39e");
            return true;
           }
        }
        
		function myProjectname(){
           
            var projectname = document.getElementById("projectname").value;
            var projetctvalue = projectname.trim()
			
            if(projetctvalue == ""){
				$( "#projectname" ).css("border", "1px solid red");
				document.getElementById("projectname").focus()
				document.getElementById('projectnameerror').innerHTML = "Select Your Project Name";
				return false;
            } else {
               
				document.getElementById('projectnameerror').innerHTML = "";
				$( "#projectname" ).css("border", "1px solid #43d39e");
			 
				//if (myProjectnametext()) {
					//return true;
				//} 
				return true;
           }
        }
		
        function mySegmentname(){
            //alert('hii');
            var segmentname = document.getElementById("segmentname").value;
            var segmentnamevalue = segmentname.trim()
            //alert(segmentnamevalue)
            if(segmentnamevalue == ""){
               //alert('hii')
                 $( "#segmentname" ).css("border", "1px solid red");
            document.getElementById('segmentnameerror').innerHTML = "Select Your Segment Name";
               
                return false;
            }else{
               // alert('bye')
                 document.getElementById('segmentnameerror').innerHTML = "";
             $( "#segmentname" ).css("border", "1px solid #43d39e");
            return true;
           }
        }
        function myProjectnametext(){
            var projectnametext = document.getElementById("projectnametext").value;
            var projectnametextvalue = projectnametext.trim()
            if(projectnametextvalue.length > 0){
                document.getElementById('projectnametexterror').innerHTML = "";
             $( "#projectnametext" ).addClass( "is-valid" );
                return true;
            }else{
            $( "#projectnametext" ).removeClass("is-valid");
            document.getElementById('projectnametexterror').innerHTML = "Enter Your Project Name";
            return false;
           }
        }
        function mySegmentnametext(){
            var segmentnametext = document.getElementById("segmentnametext").value;
            var segmentnametextvalue = segmentnametext.trim()
            if(segmentnametextvalue.length > 0){
                document.getElementById('segmentnametexterror').innerHTML = "";
             $( "#segmentnametext" ).addClass( "is-valid" );
                return true;
            }else{
            $( "#segmentnametext" ).removeClass("is-valid");
            document.getElementById('segmentnametexterror').innerHTML = "Enter Your Segment Name";
            return false;
           }
        }
        function checkDigit() {
            if(myName() && myEmail() && myMobile() && myProject() && mySegment() && myCampaign() ){
              return true;
            }
            return false;
            }
      //Country code set 
    </script>

@endpush