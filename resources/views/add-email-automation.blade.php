@extends('layouts.app')
@section('title', 'Add Email Automations')
@section('content')

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
                                <li class="breadcrumb-item"><a href="<?php echo url('/automation-master'); ?>"> Manage Automations </a></li>
                                <li class="breadcrumb-item active" aria-current="page">Create Email Automation</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Create Email Automation</h4>
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

                                <form action="{{ route('save-email-automation') }}" method="POST" name="save_email_automation" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8">
                                            <input type="hidden" name="campaignsid" value="{{ Request::segment(2) }}">
                                            <div class="form-group">
                                                <input type="text" name="series_name" class="form-control" placeholder="Event Name" autocomplete="off" required>
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
                                                     
														<div class="form-group col-6">
															<input type="text" name="subject" class="form-control" placeholder="Enter Subject" autocomplete="off" id="email-subject">
														</div>
														<a style="padding:10px" href="#" onclick="insertAtCaret('message', '{Full Name}');return false;">Add Full Name </a>
                                                    </div>
                                                </div>
                                                <div>
												
												
                                                    <textarea name="message" id="message" rows="8" style="width:100%;" class="summernote" data-plugin-summernote data-plugin-options='{ "height": 400, "codemirror": { "theme": "ambiance" } }' required onkeyup="Msg_show_textarea()"></textarea>
                                                  
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
                                                            <div class="message-bubble" style="background-color: rgb(24, 139, 246); word-break: break-word;">
                                                                <img id="blah" src="#" style="width:100px; height:100px; display:none;" />
                                                                <p id="msgshow"> </p>
                                                            </div>
                                                            <!---->
                                                        </div>
                                                        <!---->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <input type="email" id="test-email" class="form-control mb-2 mr-sm-2" placeholder="Enter Email">
                                            <button type="button" id="sendEmailBtn" class="btn btn-info" onclick="testEmail()"> Send Test</button>
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
        function getAfterValue(valid){
            if(valid == 'scheduled'){
                $("#show_delivery_day_time").show();
            }else{
                $("#show_delivery_day_time").hide();
            }
        }

        function Put_full_name(fval){
            var msg = $("#message").val();
            var fullname = "{Full Name}";

            $("#message").text( msg +' '+  (fullname) );
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
		
		function testEmail() {
			var baseUrl = "<?php echo URL::to('/'); ?>";
			var receiversEmail = document.getElementById("test-email").value;
	
			if (receiversEmail != "") {
				$('#sendEmailBtn').hide();
				$('#processMsg').show();
		
				var msg = document.getElementById("message").value;
				var subject = document.getElementById("email-subject").value;
				//var attachment = baseUrl+"/"+document.getElementById("attachment").value;
				//alert(attachment)
				var attachment = "";
		
				var formData = {_token: "{{ csrf_token() }}",subject:subject,receiversEmail:receiversEmail,msg:msg,attachment:attachment}; //Array 

				$.ajax({
					url : baseUrl+"/send-test-email",
					type: "POST",
					data : formData,
					success: function(data, textStatus, jqXHR)
					{
						$('#sendEmailBtn').show();
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
		
			}	 else {
				alert("Enter test Email ID")
			}
		}
		
		$('textarea').keyup(updateCount);
$('textarea').keydown(updateCount);

function updateCount() {
    var cs = $(this).val().length;
    $('#charcount').text(cs);
}

    </script>
@endpush
