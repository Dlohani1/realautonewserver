@extends('layouts.app')
@section('title', 'Campaigns Master')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- ===============================================0=============== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- ajxa table get data  -->
 
<style>
.no-after:after{content:none;}

.onoffswitch {
    position: relative; width: 86px;
    -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
}
.onoffswitch-checkbox {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
.onoffswitch-label {
    display: block; overflow: hidden; cursor: pointer;
    border: 2px solid #999999; border-radius: 50px;
}
.onoffswitch-inner {
    display: block; width: 200%; margin-left: -100%;
    transition: margin 0.3s ease-in 0s;
}
.onoffswitch-inner:before, .onoffswitch-inner:after {
    display: block; float: left; width: 50%; height: 24px; padding: 0; line-height: 24px;
    font-size: 18px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
    box-sizing: border-box;
}
.onoffswitch-inner:before {
    content: "ON";
    padding-left: 12px;
    background-color: #34A7C1; 
	color: #FFFFFF;
}

.onoffswitch-inner:after {
    content: "OFF";
    padding-right: 12px;
    background-color: #EEEEEE; color: #999999;
    text-align: right;
}

.onoffswitch-switch {
    display: block; width: 31px; margin: -3.5px;
    background: #FFFFFF;
    position: absolute; top: 0; bottom: 0;
    right: 58px;
    border: 2px solid #999999; border-radius: 50px;
    transition: all 0.3s ease-in 0s; 
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
    margin-left: 0;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
    right: 0px; 
}

</style>
    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="row page-title">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb" class="float-right mt-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Manage Campaigns</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">My Campaigns</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                             <div class="card-body">
                                <button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" 
onclick="window.location.href='<?php echo url('/add-campaign'); ?>'"> Create Campaign <i class="uil-plus mr-1"></i></button>
<button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event"
onclick="window.location.href='<?php echo url('/add-segment'); ?>'"> Create Segment <i class="uil-plus mr-1"></i></button>
<button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event"
onclick="window.location.href='<?php echo url('/add-project'); ?>'"> Create Project <i class="uil-plus mr-1"></i></button>


                            <?php 
							if (Auth::user()->usertype == 1 ) { ?>
							<button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" onclick="setDefault()"> Set Default <i class="uil-plus mr-1"></i></button>
                            <?php } ?>
							</div>
                        </div>
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
                                <div class="table-responsive">
                                     <table class="table table-bordered " id="example">
                                        <thead>
                                            <tr>
												<th>Sl No</th>
												<th>Name </th>
												<th>Total Events </th>
												<th>Email Events </th>
												<th>WhatsApp Events </th>
												<th>SMS Events </th>
												<th>Status</th>                                         
                                                <th width="100px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

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
<script type="text/javascript">
  
	let list=new Array;

	$(document).ready(function() {

		var t = $('#example').DataTable( {

		processing: true,
		serverSide: true,
		ajax: "{{url('campaigns-retrival')}}",
 
		columns: [
			{ data: null },
			{ data: "campaigns_name" },
			
				{ data: "totalEvents"},
			{ data: "emailEvents" },
			{ data: "whatsappEvents" },
			{ data: "smsEvents" },
			{ data: null,
				render: function(data, type, row, meta) {
					return data.status == "1" ?  '<span style ="color:green">Active</span>' :  '<span style ="color:red">OFF</span>';
				}
			},
			{ data: null,
				render: function(data, type, row, meta) {
					return '<a title="View Campaigns" href="view-campaigns-automations/'+data.id+'" "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>&nbsp;&nbsp;<a href="edit-campaigns/'+data.id+'" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit icons-sm"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><a href="delete-campaigns/'+data.id+'" onclick="return deleteCampaign('+data.id+','+data.totalLeads+')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icons-sm"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>&nbsp;<a title="Copy Campaign" href="copy-campaign/'+data.id+'" "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy icons-sm"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></a>';
				} 
			}
		],
		colReorder: true,
	});
	
		t.on( 'draw.dt', function () {
			var PageInfo = $('#example').DataTable().page.info();
			t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			});
		});

		$('#example tbody').on( 'click', 'tr', function () {
			$(this).toggleClass('selected');
			var id = t.row( this ).data().id
			console.log('id',id)
			if (list.includes(id)) {
				list.pop(id)
			} else { 
			  list.push(id);
			}
			console.log(list)
		});
	});
		
  function deleteConfirm() {
    var isSure = confirm("Do you really want to delete this lead ??");
    
    if (isSure) {       
    
        //var isVerySure = prompt("This will permanently delete the lead. Type YES to proceed");

        //if (isVerySure == "YES") {
            return true;
        //}
    }

    return false;
}

function deleteCampaign(id,totalLeads) {
	
		var isSure = confirm("This Campaign contains "+totalLeads+" active leads. Do you really want to delete ?");

		if (isSure) {
			var isVerySure = prompt("This will permanently delete the campaign and associated leads. Type YES to proceed");
			
			if (isVerySure == "YES") {
				return true;
			}
		}
		
		return false;
}
	

	function bulkDelete() {

		var isSure = confirm("Do you really want to delete all the  selected leads ??");

		if (isSure) {

		if (list.length > 0) {
		//alert(list);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})

		var formData = new FormData();
		
		formData.append('leadIds', list);
		
		$.ajax({
			type:'POST',
			url: "{{ url('delete-bulkleads')}}",
			data: formData,
			cache:false,
			contentType: false,
			processData: false,
			success: (data) => {
				//alert("Leads deleted successfully")
				swal("Success!","Leads Deleted Successfully!", "success");
				setTimeout(function () {
					//alert('Reloading Page');
					location.reload(true);
				}, 2000);				
				//window.location.reload()
			},
			error: function(data){
				console.log(data);
			}
		})
	} else {

		swal("Error!", "Please select Leads!", "info");
		//alert("Please select Leads to Delete")
	}
	}
}

		function bulkStop() {

			var isSure = confirm("Do you really want to Off the  selected leads ??");

			if (isSure) {

				if (list.length > 0) {
					//alert(list);
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					})
			
					var formData = new FormData();

					formData.append('leadIds', list);

					$.ajax({
						type:'POST',
						url: "{{ url('deactivate-cron')}}",
						data: formData,
						cache:false,
						contentType: false,
						processData: false,
						success: (data) => {
							//alert("Leads deleted successfully")
							swal("Success!","Leads Stopped Successfully!", "success");
							setTimeout(function () {
							//alert('Reloading Page');
							location.reload(true);
						}, 2000);
							//window.location.reload()
						},
							error: function(data){
							console.log(data);
						}
					})
				} else {
					swal("Error!", "Please select Leads!", "info");
					//alert("Please select Leads to Delete")
				}
			}
		}

function setDefault() {
	console.log('list',list)
	if (list.length > 0) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})

		var formData = new FormData();

		formData.append('leadIds', list);
	
		$.ajax({
			type:'POST',
			url: "{{ url('set-default-campaign')}}",
			data: formData,
			cache:false,
			contentType: false,
			processData: false,
			success: (data) => {
				//alert("Leads deleted successfully")
				swal("Success!","Campaign Default  Successfully!", "success");
				setTimeout(function () {
				//alert('Reloading Page');
				location.reload(true);
			}, 2000);
				//window.location.reload()
			},
				error: function(data){
				console.log(data);
			}
		})
	}
}
</script>

@endsection
