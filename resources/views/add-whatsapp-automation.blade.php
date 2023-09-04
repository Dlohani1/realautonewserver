@extends('layouts.app')
@section('title', 'Add WhatsApp Automations')
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropbox.js/10.23.0/Dropbox-sdk.min.js"></script>

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
                                <li class="breadcrumb-item active" aria-current="page">Create WhatsApp Automation</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Create WhatsApp Automation</h4>
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

                                <form id="myForm" action="{{ route('save-whatsapp-automation') }}" method="POST" name="save_email_automation" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="hidden" name="campaignsid" value="{{ Request::segment(2) }}">
                                            <div class="form-group">
                                                <input type="text" name="series_name" class="form-control" placeholder="Event Name" autocomplete="off">
                                            </div>

                                            <div class="form-group">
                                                <select name="delivery_type" data-plugin="customselect" class="form-control" onchange="getAfterValue(this.value)">
                                                    <option value="initial">Immediately</option>
                                                    <option value="scheduled">Schedule</option>
                                                </select>
                                            </div>

                                            <div class="row" id="show_delivery_day_time" style="display: none;" >
                                                <div class="form-group col-6">
                                                    <input type="number" name="delivery_day" min="0" class="form-control" placeholder="Delivery Day" autocomplete="off" >
                                                </div>
                                                <div class="form-group col-6">
                                                    <input type="text" name="delivery_time" class="form-control bs-timepicker" placeholder="Delivery Time" autocomplete="off" >
                                                </div>
                                            </div>

                                            <div class="card border border-primary">
                                                <div class="card-header">
                                                    <div class="row">
                                                  
														<a style="padding:10px" href="#" onclick="insertAtCaret('message', '{Full Name}');return false;">Add Full Name </a>
                                                    </div>
                                                </div>
                                                <div >
                                                   
                                                    <div class="card-body" style="min-height: 200px;">
													<div id="unEditable" style="width: fit-content;padding-right:10px;" contenteditable="false" unselectable="off"  ></div> 

													<textarea name="message" id="message" rows="8" style="width:100%;" required onkeyup="Msg_show_textarea()"  maxlength="160"></textarea>
												</div>
                                                </div>
                                                <div class="card-footer">Characters: <span id="charcount">0</span> </div>
                                            </div>

                                            <div class="form-group row mt-3">
                                                
												<label class="col-lg-3 col-form-label" for="example-fileinput">Attach File</label>
                                                <div class="col-lg-9">
                                                    {{--<input type="file" name="image1" class="form-control" id="imgInp"> --}}
							<input type="hidden" name="dropbox_link" class="form-control" id="attachment-dropbox">

                                                    <input type="hidden" name="image_link" class="form-control" id="attachment">
													<input type="file" name="image_link1" class = "form-control" id="attachment1" onchange="storeFile(this)" />
											
													<span style="color:blue"> *Supported files are jpeg,png,doc,excel,pdf. 2MB Limit </span>
													<br/><span id="uploadMsg" style="color:green"></span>
                                                </div>
												
                                            </div> 
                                            <button id="save-msg" type="submit" class="btn btn-primary" >SAVE</button>
                                            <a href="{{ url()->previous() }}"><button type="button" class="btn btn-success">Back</button></a>
                                        </div>
                                    </form>

                                    <div class="col-4">
                                        <div class="hl_edit-event-modal-preview">
                                            <div class="hl_edit-event-modal-preview-inner">
                                                <div class="hl_sms-preview-header"><i data-feather="menu"></i></div>
                                                <div class="hl_sms-preview-body" style="overflow-y: auto;">
                                                    <div class="message-wrap">
                                                        <div class="--sm avatar">
                                                            <div class="avatar_img" style="background-color: rgb(189, 160, 117);"> {{ substr(Auth::user()->name,0,2) }} </div>
                                                            <div class="message-bubble" style="background-color: rgb(24, 139, 246); word-break: break-word;"><img id="blah" src="#" style="width:100px; height:100px; display:none;" /><p id="msgshow">  </p></div>
                                                            <!---->
                                                        </div>
                                                        <!---->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">    
											<input type="number" class="form-control mb-2 mr-sm-2" placeholder="Enter Phone No" id="mobNo" onkeydown="javascript: return event.keyCode === 8 || event.keyCode === 46 ? true : !isNaN(Number(event.key))">
											<button type="button" class="btn btn-success" onclick="resetMob()">Clear</button>
                                            <button type="button" class="btn btn-info" onclick="testWhatsapp()" id="sendWhatsapp" > Send Test</button>
											<span id="successMsg" style="color:green"></span>
											<span id="processMsg" class="text-dark" style="display:none">Sending in Progress.. Please wait</span>
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

                   $('#save-msg').attr('disabled', 'disabled');
});

        function getAfterValue(valid){
            if(valid == 'scheduled'){
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
	var baseUrl = "<?php echo URL::to('/'); ?>";
	var no = document.getElementById("mobNo").value;
	
	
	if (no[0] == 0) {
		alert("Contact No should not start with 0")
	} else if (no.length == 10) {
		$('#sendWhatsapp').hide();
		$('#processMsg').show();
		
		var msg = document.getElementById("message").value;
		
		var attachment = "";
		
		if (document.getElementById("attachment").value.length > 0) {
			attachment = baseUrl+"/"+document.getElementById("attachment").value;
		}
attachment = document.getElementById("attachment").value;
		
		//alert(attachment)
		//var attachment = "";
		
		var formData = {_token: "{{ csrf_token() }}",mobile:no,msg:msg,attachment:attachment}; //Array 

		$.ajax({
			url : baseUrl+"/test-whatsapp-msg",
			type: "POST",
			data : formData,
			success: function(data, textStatus, jqXHR)
			{
				$('#sendWhatsapp').show();
				$('#processMsg').hide();
				$('#successMsg').show();
				$('#successMsg').empty();
				document.getElementById("successMsg").innerHTML=data;
				$('#successMsg').delay(5000).fadeOut('slow');
				//data - response from server
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
		 
			}
		});
		
	} else {
		alert("Enter valid 10 digit contact no")
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
	
//var ACCESS_TOKEN = "VP59QH1WCpcAAAAAAAAAAUSo2kWaGQEti71a7itT8vzWCSTxWSDbZ7opOOnrNwre";

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

</script>
@endpush
