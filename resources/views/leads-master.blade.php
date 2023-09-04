@extends('layouts.app')
@section('title', 'All Leads')
@section('content')

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
.blur {
 text-shadow: 1px 1px 5px #aaa;
 color: transparent;
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
                                <li class="breadcrumb-item active" aria-current="page">Manage Leads</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Manage Leads</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" onclick="window.location.href='<?php echo url('/add-import-leads'); ?>'"> Import Leads <i class="uil-plus mr-1"></i></button>
                                <button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" onclick="window.location.href='<?php echo url('/add-leads'); ?>'"> Add a Lead <i class="uil-plus mr-1"></i></button>
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
                                    <table id="basic-datatable" class="table">
                                        <thead>
                                        <tr>
                                            <th>Sl No</th>
                                            <th>Campaigns</th>
                                            <th>Name</th>
                                            <th>Mobile No</th>
                                            <th>Email</th>
											<th>Project</th>
                                            <th>Segment</th>                                            
                                            <!--<th>Actions</th>-->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $sl=0;
                                            foreach ($leaddata as $row){

                                                $sl++;

                                        ?>
                                        <tr class=<?php echo !in_array($row->id,$leads) ? "blur":"";?> title="Buy Premium plan to see">
                                            <td>{{ $sl }}</td>
                                            <td>{{ GetCampaignsName($row->campaigns_id) }}  
												<!--<div class="onoffswitch">
													<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch{{$sl}}" tabindex="0" onclick="deactivateCron('myonoffswitch{{$sl}}',{{$row->id.','.$row->campaigns_id.','.($row->is_cron_disabled == 0 ? '1':'0')}})" {{$row->is_cron_disabled == 0 ? "checked":""}} >
													<label class="onoffswitch-label" for="myonoffswitch{{$sl}}">
													<span class="onoffswitch-inner"></span>
													<span class="onoffswitch-switch"></span>
													</label>
												</div> --> 
												  
											</td>
											<td>{{ $row->name }}</td>
                                            <td>{{ $row->mobile_no }}</td>
                                            <td>{{ $row->mail_id }}</td>
                                            <td>{{ $row->project_name }}</td>
                                            <td>{{ $row->segment_name }}</td>
					    <!--
                                            <td>
                                                <div class="icon-item">
                                                    <a href="{{ url('/edit-leads/'.$row->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                    </a>
                                                    <a href="{{ url('/delete-leads/'.$row->id) }}" onclick="return deleteConfirm()">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg>
                                                    </a>
                                                </div>
                                            </td> -->
                                        </tr>
                                        <?php } ?>
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
	<script>
	function deactivateCron(id,lead_id,campaigns_id,isStopped) {
		//alert(lead_id)
		//alert(campaigns_id)
		//alert(isStopped)
		//alert(document.getElementById(id).checked)
		if(isStopped == "1") {
			var stop = confirm("Do you really want to off the running campaign ??");
		} else {
			var stop = confirm("Do you really want to on the stopped campaign ??");
		}
		
		if (stop == true) {
    
			var formData = {_token: "{{ csrf_token() }}",leadId:lead_id,campaignId:campaigns_id,isStopped:isStopped}; //Array 
				
			$.ajax({
				url : "deactivate-cron",
				type: "POST",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{
					console.log('dd',data)
					//data - response from server
					window.location.reload()
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
			 
				}
			});
		} else {
			//window.location.reload();
			//var element = document.getElementsById(id);
			//element.className += "no-after";
			//let color = style.getPropertyValue('background-color')
			/*alert(isStopped)
			*/
			var toggle = false;
			if (isStopped == "1") {
				toggle = true;
			}
			
			return stop ? $("#"+id).prop('checked', toggle) : $("#"+id).prop('checked', toggle);
			
		}
		
			
	}
	
	$('.onoffswitch-inner').on('change', function(e) {

  
		let checked = $(this).is(':checked');

		let changed = confirm("Are you sure you want to change this account?");

		if (!changed) { 

			return checked ? $(this).prop('checked', false) : $(this).prop('checked', true);
		}
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
	
	</script>

@endsection
