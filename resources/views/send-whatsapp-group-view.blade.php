@extends('layouts.app')
@section('title', 'Send WhatsApp Broadcast')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropbox.js/10.23.0/Dropbox-sdk.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
<style>

.multiselect_block {
  position: relative;
  width: 100%;
}
.field_select {
  position: absolute;
  top: calc(100% - 2px);
  left: 0;
  width: 100%;

  border: 2px solid #cdd6f3;
  border-bottom-right-radius: 2px;
  border-bottom-left-radius: 2px;
  box-sizing: border-box;
  outline-color: #cdd6f3;
  z-index: 6;
}

.field_select[multiple] {
  overflow-y: auto;
}

.field_select option {
  display: block;
  padding: 8px 16px;
  width: calc(370px - 32px);
  cursor: pointer;
  &:checked {
    background-color: #eceff3;
  }
  &:hover {
    background-color: #d5e8fb;
  }
}

.field_multiselect button {
  position: relative;
  padding: 7px 34px 7px 8px;
  background: #ebe4fb;
  border-radius: 4px;
  margin-right: 9px;
  margin-bottom: 10px;

  &:hover,
  &:focus {
    background-color: #dbd1ee;
  }
  &:after {
    content: "";
    position: absolute;
    top: 7px;
    right: 10px;
    width: 16px;
    height: 16px;
    background: url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' 
xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M19.4958 6.49499C19.7691 6.22162 
19.7691 5.7784 19.4958 5.50504C19.2224 5.23167 18.7792 5.23167 18.5058 5.50504L12.5008 11.5101L6.49576 5.50504C6.22239 5.23167 
5.77917 5.23167 5.50581 5.50504C5.23244 5.7784 5.23244 6.22162 5.50581 6.49499L11.5108 12.5L5.50581 18.505C5.23244 18.7784 
5.23244 19.2216 5.50581 19.495C5.77917 19.7684 6.22239 19.7684 6.49576 19.495L12.5008 13.49L18.5058 19.495C18.7792 19.7684 
19.2224 19.7684 19.4958 19.495C19.7691 19.2216 19.7691 18.7784 19.4958 18.505L13.4907 12.5L19.4958 6.49499Z' 
fill='%234F5588'/%3E%3C/svg%3E")
      50% 50% no-repeat;
    background-size: contain;
  }
}

.multiselect_label {
  position: absolute;
  top: 1px;
  left: 2px;
  width: 100%;
  height: 44px;
  cursor: pointer;
  //background-color: #f00;  /// for test
  z-index: 3;
}

.field_select {
  display: none; ////
}

input.multiselect_checkbox {
  position: absolute;
  right: 0;
  top: 0;
  width: 40px;
  height: 40px;
  border: none;
  opacity: 0;
}

.multiselect_checkbox:checked ~ .field_select {
  display: block;
}

.multiselect_checkbox:checked ~ .multiselect_label {
  width: 40px;
  left: initial;
  right: 4px;
  background: #ffffff
    url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' 
xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' clip-rule='evenodd' d='M19.4958 6.49499C19.7691 6.22162 
19.7691 5.7784 19.4958 5.50504C19.2224 5.23167 18.7792 5.23167 18.5058 5.50504L12.5008 11.5101L6.49576 5.50504C6.22239 5.23167 
5.77917 5.23167 5.50581 5.50504C5.23244 5.7784 5.23244 6.22162 5.50581 6.49499L11.5108 12.5L5.50581 18.505C5.23244 18.7784 
5.23244 19.2216 5.50581 19.495C5.77917 19.7684 6.22239 19.7684 6.49576 19.495L12.5008 13.49L18.5058 19.495C18.7792 19.7684 
19.2224 19.7684 19.4958 19.495C19.7691 19.2216 19.7691 18.7784 19.4958 18.505L13.4907 12.5L19.4958 6.49499Z' 
fill='%234F5588'/%3E%3C/svg%3E")
    50% 50% no-repeat;
}

.multiselect_checkbox:checked ~ .field_multiselect_help {
  opacity: 1;
}

@media (max-width: 400px) {
  .field_multiselect_help {
    display: none; //// We don't need it on mobile
  }
}

</style>

<meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .ad_sp {
            position: absolute;
            bottom: -5px;
            left: 25px;
        }

        .ad_sp span {
            margin-left: 20px;
            font-size: 15px;
            color: #5369f8;
            text-transform: uppercase;
            font-weight: 600;
            position: absolute;
            width: 200px;
            top: 1px;
        }

        .icons-sm {
            width: 16px;
            height: 16px;
        }

        .mb_ .feather {
            color: red;
        }

        .message-wrap {
            height: 190px;
            position: relative;
        }

        .avatar_img {
            width: 48px;
            height: 48px;
            line-height: 48px;
            text-align: center;
            border-radius: 50%;
        }

        .hl_edit-event-modal-preview-inner {
            background-color: #fff;
            height: 370px;
        }

        .hl_edit-event-modal-preview {
            min-width: 100%;
            width: 100%;
            height: 500px;
            background-color: #d2dce2;
            border-radius: 50px;
            padding: 80px 18px;
            margin-bottom: 20px;
        }

        .hl_sms-preview-header {
            height: 50px;
            width: 100%;
            border-bottom: 2px solid #f2f7fa;
            padding: 5px 15px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .hl_sms-preview-body {
            -webkit-box-flex: 1;
            -ms-flex: 1 0 0px;
            flex: 1 0 0;
            padding: 30px 10px;
        }

        .hl_sms-preview-footer {
            height: 50px;
            width: 100%;
            border-top: 2px solid #f2f7fa;
            padding: 5px 15px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            color: #afb8bc;
        }

        .hl_sms-preview-body .message-bubble {
            background-color: #2a3135;
            color: #fff;
            max-width: 70%;
            border-radius: 10px;
            overflow: hidden;
            line-height: 1.3;
            font-size: .9375rem;
            padding: 10px;
            position: absolute;
            top: 0px;
            right: 0px;
        }
			/* Chrome, Safari, Edge, Opera */
		input::-webkit-outer-spin-button,
		input::-webkit-inner-spin-button {
		  -webkit-appearance: none;
		  margin: 0;
		}

		/* Firefox */
		input[type=number] {
		  -moz-appearance: textfield;
		}
    </style>

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
                         <li class="breadcrumb-item"><a href="{{ url()->previous() }}"> Manage Events </a></li>
                                <li class="breadcrumb-item active" aria-current="page">Send WhatsApp Broadcast</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Send WhatsApp Broadcast</h4>
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
                                        <button type="button" class="close" data-dismiss="alert" 
aria-hidden="true">×</button>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form id="myForm" action="#" method="POST" name="save_email_automation" 
enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="hidden" name="campaignsid" value="{{ Request::segment(2) }}">
                                            <div class="form-group" style="display: none;"> 
                                                <input type="text" name="series_name" class="form-control" placeholder="Name 
of the event" autocomplete="off">
                                            </div>

                                            <div class="form-group" >
                                                <select id="delivery_type" name="delivery_type" data-plugin="customselect" 
class="form-control" onchange="getAfterValue(this.value)">
                                                    <option value="0">Immediately</option>
                                                    <option value="1">Schedule</option>
                                                </select>
                                            </div>

                                            <div class="row" id="show_delivery_day_time" style="display: none;" >
                                                <div class="form-group col-6">
                                                    <input type="date" id="delivery_date" name="delivery_date" min="0" 
class="form-control"  autocomplete="off" >
                                                </div>
                                                <div class="form-group col-6">
                                                    <input type="time" id="delivery_time" name="delivery_time" 
class="form-control bs-timepicker"  autocomplete="off" >
                                                </div>
                                            </div>

                                            

<div class="form-group">
<select onchange="test()" id = "receivers" name="receivers" data-plugin="customselect" class="form-control">
<option value="0">Select Contacts</option>
<option value="1">Mobile #s </option>
<option value="2">WhatsApp Groups </option>
</select>
</div> 
                                            

<div class="form-group">
    <input class="form-control"" type="text" id="mobnos" style="display:none" placeholder="Enter mobile #s without country codes ie. +91 or +1 and 
separated by commas" />
</div>
<div class="form-group" id ="wpgroups" style="display:none" >
<p>Select WhatsApp Groups</p>
        
                    <select class="form-control" id="choices-multiple-remove-button" placeholder="" multiple>
                    @if(!empty($whatsappgrp))
                    @foreach($whatsappgrp as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                    @endif
                    </select>
                
        </div>
    

<!-- <div class="multiselect_block form-group" id ="wpgroups" style="display:none" >
                <label for="select-1" class="field_multiselect">Select Whatsapp Group</label>

                <input id="checkbox-1" class="multiselect_checkbox" type="checkbox">
                <label for="checkbox-1" class="multiselect_label"></label>

                <select id="select-1" class="field_select" name="technology" multiple style="@media (min-width: 768px) { 
height: calc(4 * 38px)}" class="form-control"  autocomplete="off">
                  
                 @if(!empty($whatsappgrp))
                                                        @foreach($whatsappgrp as $row)
                                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                        @endforeach
                                                    @endif
                </select>
                <span class="field_multiselect_help">You can select several items by pressing <b>Ctrl(or 
Command)+Element</b></span>
              </div> -->

                                            <div class="card border border-primary">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <!--<select name="txt_full_name" class="form-control col-6" 
id="txt_full_name" onchange="Put_full_name(this.value)">
                                                            <option value="">Custom Values</option>
                                                            <option value="custom_full_name">Full Name</option>
                                                        </select> -->
														<!-- <a 
style="padding:10px" href="#" onclick="insertAtCaret('message', '{Full Name}');return false;">Add Full Name </a> -->
                                                        <strong>Enter Message Here </strong>
                                                    </div>
                                                </div>
                                                <div >
                                                    <textarea name="message" rows="8" style="width:100%;" id="message" 
required onkeyup="Msg_show_textarea()"></textarea>
                                                </div>
                                                <div class="card-footer">Characters: <span id="charcount">0</span> </div>
                                            </div>

                                            <div class="form-group row mt-3">
                                                
												<label class="col-lg-3 
col-form-label" for="example-fileinput">Attach File</label>
                                                <div class="col-lg-9">
                                                    {{--<input type="file" name="image1" class="form-control" id="imgInp"> 
--}}
							<input type="hidden" name="dropbox_link" class="form-control" 
id="attachment-dropbox">

                                                    <input type="hidden" name="image_link" class="form-control" 
id="attachment">
													<input type="file" 
name="image_link1" class = "form-control" id="attachment1" onchange="storeFile(this)" />
													<!--<button 
type="button" onclick="storeFile()">Upload</button> -->
													<span 
style="color:blue"> *Supported files are jpeg,png,doc,excel,pdf. 2MB Limit </span>
													<br/><span 
id="uploadMsg" style="color:green"></span>
                                                </div>
												
                                            </div> 
                                            <button id="save-msg" type="button" class="btn btn-primary" 
onclick="testWhatsapp()" >SEND</button>
                                            <a href="{{ url()->previous() }}"><button type="button" class="btn 
btn-success">Back</button></a>
                                        </div>
                                    </form>

                                    <div class="col-4">
                                        <div class="hl_edit-event-modal-preview">
                                            <div class="hl_edit-event-modal-preview-inner">
                                                <div class="hl_sms-preview-header"><i data-feather="menu"></i></div>
                                                <div class="hl_sms-preview-body" style="overflow-y: auto;">
                                                    <div class="message-wrap">
                                                        <div class="--sm avatar">
                                                            <div class="avatar_img" style="background-color: rgb(189, 160, 
117);"> {{ substr(Auth::user()->name,0,2) }} </div>
                                                            <div class="message-bubble" style="background-color: rgb(24, 139, 
246); word-break: break-word;"><img id="blah" src="#" style="width:100px; height:100px; display:none;" /><p id="msgshow">  
</p></div>
                                                            <!---->
                                                        </div>
                                                        <!---->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12" style="display:none">    
											<input type="number" 
class="form-control mb-2 mr-sm-2" placeholder="Enter Phone #" id="mobNo" onkeydown="javascript: return event.keyCode === 8 || 
event.keyCode === 46 ? true : !isNaN(Number(event.key))">
											<button type="button" class="btn 
btn-success" onclick="resetMob()">Clear</button>
                                            <button type="button" class="btn btn-info" onclick="testWhatsapp()" 
id="sendWhatsapp" > Send Test</button>
											<span id="successMsg" 
style="color:green"></span>
											<span id="processMsg" 
class="text-dark" style="display:none">Sending in Progress.. Please wait</span>
                                        </div>
                                    </div>
                                </div>

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
                        <?php echo date('Y'); ?> &copy; RealAuto. All Rights Reserved. Crafted with <i class='uil uil-heart 
text-danger font-size-12'></i> by <a href="https://active-digital.tech/home/" target="_blank">Active Digital Technology</a>
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

                   $('#save-msg').attr('disabled', 'disabled');
});

        function getAfterValue(valid){
            if(valid == '1'){
                $("#show_delivery_day_time").show();
            }else{
                $("#show_delivery_day_time").hide();
            }
        }

        function Put_full_name(fval){

            if(fval !=''){
                var msg = $("#message").val();
                var fullname = "{Full Name}";
                var msg = $("#message").val();
                $("#message").val(msg +' '+  fullname);
                $("#msgshow").text( msg +' '+  fullname );
            }else{
                // $('#message').contains('{Full Name}').remove();​
                // $('#msgshow').contains('{Full Name}').remove();​
            }
        }

        function Msg_show_textarea(){

            var tfullname = $( "#txt_full_name" ).val();
            if(tfullname == "custom_full_name"){
                var msg = $("#message").val();
                var fullname = "{Full Name}";
                $("#msgshow").text( msg );
            }else{
                var msg = $("#message").val();
                $("#msgshow").text(msg);
            }
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah').show();
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#imgInp").change(function() {
            readURL(this);
        });
		
		function insertAtCaret(areaId, text) {
		  var txtarea = document.getElementById(areaId);
		  if (!txtarea) {
			return;
		  }

	  var scrollPos = txtarea.scrollTop;
	  var strPos = 0;
	  var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
		"ff" : (document.selection ? "ie" : false));
	  if (br == "ie") {
		txtarea.focus();
		var range = document.selection.createRange();
		range.moveStart('character', -txtarea.value.length);
		strPos = range.text.length;
	  } else if (br == "ff") {
		strPos = txtarea.selectionStart;
	  }

	  var front = (txtarea.value).substring(0, strPos);
	  var back = (txtarea.value).substring(strPos, txtarea.value.length);
	  txtarea.value = front + text + back;
	  strPos = strPos + text.length;
	  if (br == "ie") {
		txtarea.focus();
		var ieRange = document.selection.createRange();
		ieRange.moveStart('character', -txtarea.value.length);
		ieRange.moveStart('character', strPos);
		ieRange.moveEnd('character', 0);
		ieRange.select();
	  } else if (br == "ff") {
		txtarea.selectionStart = strPos;
		txtarea.selectionEnd = strPos;
		txtarea.focus();
	  }

	  txtarea.scrollTop = scrollPos;
	}

function testWhatsapp() {
    var no = document.getElementById("receivers").value
    var delivery_type = document.getElementById("delivery_type").value
    var deliverDate = "";
    var deliverTime = "";

    if (delivery_type == 1) {
        deliverDate = document.getElementById("delivery_date").value;
        deliverTime = document.getElementById("delivery_time").value;
    }

    if (no < 1) {
        
        swal({
                icon: "error",
                text: "Please select Numbers or Groups"
});
        //alert("please select Numbers or Groups")
    } else {
    	var baseUrl = "<?php echo URL::to('/'); ?>";
        var msg = document.getElementById("message").value;
        var attachment = "";
        console.log("wp",wpgroup)
    	
        if (document.getElementById("attachment").value.length > 0) {
        	attachment = baseUrl+"/"+document.getElementById("attachment").value;
        }

        attachment = document.getElementById("attachment").value;
    		
    	//var group = document.getElementById("wp_group_id").value;
    	 
         isGroup = 0
         if (no == 1) {
            group = document.getElementById("mobnos").value;
         }
         if (no == 2) {
            isGroup = 1
            selectedOptions = document.getElementById("choices-multiple-remove-button");
            wpgroup1 = []
            for (let option of selectedOptions) {
                wpgroup.push(option.value);
            }
            group = wpgroup
         }



    	var formData = {_token: "{{ csrf_token() 
}}",isGroup:isGroup,group:group,msg:msg,attachment:attachment,deliveryType:delivery_type,deliverDate:deliverDate,deliverTime:deliverTime}; 

    		$.ajax({
    			url : baseUrl+"/send-whatsapp-to-groups-post",
    			type: "POST",
    			data : formData,
    			success: function(data, textStatus, jqXHR)
    			{
    				console.log(data)
    				//data - response from server
                    var msg = "Messages Sent Successfully!!";

                    if (delivery_type == 1) {
                        msg = "Messages Scheduled Successfully!!"
                    }
                    swal({
                        icon: "success",
                        text: msg
                    });
                    

                   location.replace( baseUrl+"/send-whatsapp-to-groups")
    			},
    			error: function (jqXHR, textStatus, errorThrown)
    			{
    		 
    			}
    		});
        }
}

function resetMob() {
	document.getElementById("mobNo").value = "";
}

var len = document.getElementById("message").value.length;
document.getElementById("charcount").textContent = len;
	
$('textarea').keyup(updateCount);
$('textarea').keydown(updateCount);

function updateCount() {
    var cs = $(this).val().length;
    $('#charcount').text(cs);
}

var validExt = ".png, .gif, .jpeg, .jpg, .pdf, .xlsx, .csv, .mp4,.mp3";

function fileExtValidate(fdata) {
 var filePath = fdata.value;
 //alert(filePath)
 //var sFileName = fdata.value;
 //sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase()
 
 var getFileExt = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();
 var pos = validExt.indexOf(getFileExt);
 if(pos < 0) {
 	alert("This file is not allowed, please upload valid file.");
 	return false;
  } else {
  	return true;
  }
}
var maxSize = '8192';  //8MB

function fileSizeValidate(fdata) {
	//alert('size')
	if (fdata.files && fdata.files[0]) {
		var fsize = fdata.files[0].size/1024;
		fsize = Math.trunc(fsize)
		if(fsize > maxSize) {
			 alert('Maximum file size exceed, This file size is: ' + fsize + "KB. Please upload file size less than 4MB");
			 return false;
		} else {
			return true;
		}
	}
}

function storeFile(oInput) { 
  if(fileExtValidate(oInput)) {
	    	 if(fileSizeValidate(oInput)) {
	    	 	uploadFile();
	    	 }	 
	    }   
}		

function uploadFile() {

	const UPLOAD_FILE_SIZE_LIMIT = 150 * 1024 * 1024;
	

var ACCESS_TOKEN = "Gm2wpFjlAI8AAAAAAAAAAV54kmUSTAZs6JlX7nJxgxh4TInOrBCwb78cbpjpd3tb";

var unique_name = "<?php echo Auth::user()->name;?>";
var messageId = window.location.pathname.split('/')[2];

	var dbx = new Dropbox.Dropbox({ accessToken: ACCESS_TOKEN });
	var fileInput = document.getElementById('file-upload');
	//var file = fileInput.files[0];
	var file = $('#attachment1')[0].files[0];
	dbx.filesUpload({path: '/' + unique_name+"_"+messageId+"_"+file.name, contents: file})
	.then(function(response) {
		document.getElementById("attachment-dropbox").value = unique_name+"_"+messageId+"_"+file.name
		console.log(response);
	
		getDropboxLink();
		})
	.catch(function(error) {
	console.error(error);
	});

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
document.getElementById("save-msg").disabled = true;

var baseUrl = "<?php echo URL::to('/'); ?>";
	var files = $('#attachment1')[0].files;

	// Check file selected or not
	if(files.length > 0 ){
		var formData = new FormData();
		document.getElementById("uploadMsg").innerHTML="File Uploading in progress";
		formData.append('file', files[0]);
		
		$.ajax({
			type:'POST',
			url: "{{ url('store-file')}}",
			data: formData,
			cache:false,
			contentType: false,
			processData: false,
			success: (data) => {
			document.getElementById("save-msg").disabled = false;
				document.getElementById("uploadMsg").innerHTML="File Uploaded Successfully";
				$('#uploadMsg').delay(5000).fadeOut('slow');
				document.getElementById("attachment").value=data.filepath
				
				//alert(document.getElementById("attachment").value)
				console.log('d',data.filepath)
				//this.reset();
				//alert('File has been uploaded successfully');
				//console.log(data);
			},
			error: function(data){
				console.log(data);
			}
		});
}
}

function getDropboxLink() {

	var fileName =  document.getElementById("attachment-dropbox").value

	var formData = new FormData();
 
	formData.append('dropbox_link', fileName);


 		$.ajax({
                        type:'POST',
                        url: "{{ url('get-dropbox-link')}}",
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        success: (data) => {
				document.getElementById("attachment").value=data;
                        	/*
				document.getElementById("save-msg").disabled = false;
                                document.getElementById("uploadMsg").innerHTML="File Uploaded Successfully";
                                $('#uploadMsg').delay(5000).fadeOut('slow');
                                document.getElementById("attachment").value=data.filepath

                                //alert(document.getElementById("attachment").value)
                                console.log('d',data.filepath)
                                //this.reset();
                                //alert('File has been uploaded successfully');
                                */
				console.log(data);
                        },
                        error: function(data){
                                console.log(data);
                        }
                });


}
let wpgroup = [];		
 let multiselect_block = document.querySelectorAll(".multiselect_block");
    multiselect_block.forEach(parent => {
        let label = parent.querySelector(".field_multiselect");
        let select = parent.querySelector(".field_select");
        let text = label.innerHTML;
        select.addEventListener("change", function(element) {
            let selectedOptions = this.selectedOptions;
           
            label.innerHTML = "";
            wpgroup = []
            for (let option of selectedOptions) {
                wpgroup.push(option.value)
                let button = document.createElement("button");
                button.type = "button";
                button.className = "btn_multiselect";
                button.textContent = option.text;
               
                button.onclick = _ => {
                    option.selected = false;
                   
                    wpgroup.pop(option.value)
                    if (!select.selectedOptions.length) {
                        label.innerHTML = text
                         
                    }
                };
                label.append(button);
            }
        })
    })

function test() {
    var no = document.getElementById("receivers").value

    if (no == 1) {
        document.getElementById("mobnos").style.display = "block" 

        document.getElementById("wpgroups").style.display = "none" 
    }

    if (no == 2) {
        document.getElementById("wpgroups").style.display = "block" 
        document.getElementById("mobnos").style.display = "none" 
    }
}

</script>

<script><!--For Multiselect dropdown -->
  $(document).ready(function(){
var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
removeItemButton: true,
//maxItemCount:5,
//searchResultLimit:5,
//renderChoiceLimit:5
});
});
</script>

@endpush

