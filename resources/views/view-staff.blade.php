@extends('layouts.app')
@section('title', 'Admin User Management')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                                <li class="breadcrumb-item active" aria-current="page">Manage Staff</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Staff List</h4>
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
                                        <button class="btn btn-primary" id="btn-new-event" onclick="window.location.href='<?php echo url('/add-staff'); ?>'"><i class="uil-plus 
mr-1"></i>Add Staff</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                            <?php if(!$staffList->isEmpty()) { // $data is not empty ?>
                                <table id="idsssss" class="table dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
											<th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach($staffList as $row){
												$status = "<span style='color:green'>Active</span>";
												$btnValue = "De Activate";
												if ($row->status == "2") {
													$status = "<span style='color:red'>InActive</span>";	
													$btnValue = "Activate";
												}
											?>
                                            <tr>
                                                <td><?php echo $row->name; ?></td>
                                                <td><?php echo $row->email; ?></td>
                                                <td><?php echo $row->phone_no; ?></td>
												<td><?php echo $status; ?></td>
                                                <td>
                                                <div class="icon-item">
                                                    <a href="{{ url('/edit-staff/'.$row->id) }}" title="Edit Staff">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 
2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                    </a>
                                                    <!--<a href="javascript:void(0)" onclick="deleteStaff({{$row->id}})" title="Deactivate">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" 
x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg>
                                                    </a>-->
													<button type="button" onclick="deleteStaff({{$row->id}})">{{$btnValue}}</button>
                                                </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <?php }else { // $data is empty?>
                                    <table id="idsssss" class="table dt-responsive nowrap">
                                        <tr>
                                            <th colspan=4 style="text-align:center;"> No Record Found </th>
                                        </tr>
                                    </table>
                                <?php } ?>

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

function deleteStaff(userId) {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	var sure = confirm("Do you really want to delete?")
	//alert(userId)
	var form_data = new FormData();
	form_data.append('userId', userId);
	
	if (sure) {
		
		//var formData = {_token: "{{ csrf_token() }}",userId:userId}; //Array 
		console.log(form_data);
		$.ajax({
			type:'POST',
			url: "{{ url('delete-staff')}}",
			data: form_data,
			cache:false,
			contentType: false,
			processData: false,
			success: (data) => {
				alert(data)
				window.location.reload()
			},
			error: function(data){
				console.log(data);
			}
		});
		alert(sure)
	} else {
		alert(sure)
	}
}
</script>
@endsection
