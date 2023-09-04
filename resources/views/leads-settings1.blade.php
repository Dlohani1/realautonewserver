@extends('layouts.app')
@section('title', 'Lead Settings')
@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script src="{{ asset('/assets/js/multiselect.js') }}"></script>
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->
<style>
/*
.form-group input[type="checkbox"] {
    display: none;
}

.form-group input[type="checkbox"] + .btn-group > label span {
    width: 20px;
}

.form-group input[type="checkbox"] + .btn-group > label span:first-child {
    display: none;
}
.form-group input[type="checkbox"] + .btn-group > label span:last-child {
    display: inline-block;   
}

.form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
    display: inline-block;
}
.form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
    display: none;   
}
*/
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
                                <li class="breadcrumb-item active" aria-current="page">Lead Settings</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Lead Settings</h4>
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

                                <form action="<?php echo url('/auto-lead-assign'); ?>" method="post" name="save-admin" class="form-horizontal">
                                    @csrf
									<input type="hidden" name = "staffPriority" style="display:none" id="staffPriority" />
									<div class="container">
									<div class="row">
									<div class="col-md-6">
										<h3>Select Projects</h3><hr />
										<div style="overflow-y: scroll; height:200px;">
										<?php $i = 0; ?>
										@foreach ($projectList as $project)
										<div class="[ form-group ]"  >
            
            <div class="[ btn-group ]" style="left:30px"> 
                <label for="fancy-checkbox-default" class="[ btn btn-default ]">
				<input type="checkbox" value={{$project->id}} class="projects" name="project_lists[]" id="project{{++$i}}" autocomplete="off" />
                  <!--  <span class="[ glyphicon glyphicon-ok ]"></span> -->
                   
                </label>
                <label for="fancy-checkbox-default" class="[ btn btn-default active ]">
					{{ucwords($project->project_name)}}
                </label>
            </div>
        </div>
		@endforeach
		</div>
		</div>
		<div class="col-md-6">
        <h3>Select Staff</h3><hr />
		<div style="overflow-y: scroll; height:200px;width:400px">
		<?php $j=0; ?>
		@foreach ($staffList as $rowstaff)
        <div class="[ form-group ]" >
            
            <div class="[ btn-group ]"  style="left:30px">
                <label for="fancy-checkbox-default-custom-icons" class="[ btn btn-default ]">
                     <input type="checkbox" class="staffs" value="{{$rowstaff->id}}" name="staff_list[]" id="staff{{++$j}}" autocomplete="off" />
					 <!-- <span class="[ glyphicon glyphicon-ok ]"></span>
					
					<span class="[ glyphicon glyphicon-plus ]"></span>
                    <span class="[ glyphicon glyphicon-minus ]"></span>
					-->
                </label>
                <label for="fancy-checkbox-default-custom-icons" class="[ btn btn-default active ]">
				{{$rowstaff->name}}
                </label>
            </div>
        </div>
		@endforeach
		</div></div>
    </div>
	</div>
                                <!--    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">Select Projects <span class="required">*</span></label>
                                        <div class="col-9">
											<select name="project_id" class="form-control custom-select mb-2" required>
												<option value="">Select Project</option>
												@foreach ($projectList as $rowstaff)
												<option value="{{ $rowstaff->id }}">{{ ucwords($rowstaff->project_name) }}</option>
												@endforeach
											</select>
										</div>
                                    </div>
                                   
									<div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">Select Executives <span class="required">*</span></label>
                                        <div class="col-9">
											<div class="container" style="margin-top: 20px;" id="multiselectlist">
												<div class="form-group col-sm-3">
													<label>Executives List</label>
													<select name="staff_id" class="form-control custom-select mb-2"  multiple 
id="multivalfrom" size="8">
														
														@foreach ($staffList as $rowstaff)
														<option value="{{ucwords($rowstaff->name).' | Id -'.$rowstaff->id}}">{{ 
ucwords($rowstaff->name) }}</option>
														@endforeach
													</select>
												</div>
												<div class="col-sm-1">
													<div class="btn-group-vertical w-100" style="width:100%">
														<button type="button" class="btn btn-default col-sm-12 btn-sm" 
title="Move All" id="move_all_btn"><i class='fas fa-angle-double-right'></i></button>
														<button type="button" class="btn btn-default col-sm-12 btn-sm" 
title="Move" id="move_btn"><i class='fas fa-angle-right'></i></button>
														<button type="button" class="btn btn-default col-sm-12 btn-sm" 
title="Remove" id="remove_btn"><i class='fas fa-angle-left'></i></button>
														<button type="button" class="btn btn-default col-sm-12 btn-sm" 
title="Remove All" id="remove_all_btn"><i class='fas fa-angle-double-left'></i></button>
													</div>	
												</div>
												<div class="form-group col-sm-3">
												<label>Assigned List</label>
													<select name="user-list[]" multiple class="form-control" id="multivalto" 
size="11" required>
													</select>
												</div>
												<div class="col-sm-1">
													<div class="btn-group-vertical" style="width:100%">
														<button type="button" class="btn btn-default col-sm-12 btn-sm" 
title="Top" id="top_btn"><i class='fas fa-angle-double-up'></i></button>
														<button type="button" class="btn btn-default col-sm-12 btn-sm" 
title="Up" id="up_btn"><i class='fas fa-angle-up'></i></button>
														<button type="button" class="btn btn-default col-sm-12 btn-sm" 
title="Down" id="down_btn"><i class='fas fa-angle-down'></i></button>
														<button type="button" class="btn btn-default col-sm-12 btn-sm" 
title="Bottom" id="bottom_btn"><i class='fas fa-angle-double-down'></i></button>
													</div>
												</div>
											</div>
										</div>
										-->
										
										<div class="form-group mb-0 justify-content-end row">
											<div class="col-9" style="top:10px">
												<button type="submit" class="btn btn-info">Submit</button>
												<!-- <a href="{{ route('manage-admins') }}"><button type="button" class="btn 
btn-success">Back</button></a> -->
											</div>
										</div>
                                </form>
                            </div>  <!-- end card-body -->
                        </div>  <!-- end card -->
	<!--
<div class="container">
 <div class="[ col-xs-12 col-sm-6 ]">
        <h3>Select Projects</h3><hr />
		<div style="overflow-y: scroll; height:200px;">
		<?php $i = 0; ?>
		@foreach ($projectList as $project)
        <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-default[]" id="fancy-checkbox-default{{$i}}" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-default" class="[ btn btn-default ]">
                    <span class="[ glyphicon glyphicon-ok ]"></span>
                    <span> </span>
                </label>
                <label for="fancy-checkbox-default" class="[ btn btn-default active ]">
					{{ucwords($project->project_name)}}
                </label>
            </div>
        </div>
		@endforeach
		
        <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-primary" id="fancy-checkbox-primary" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-primary" class="[ btn btn-primary ]">
                    <span class="[ glyphicon glyphicon-ok ]"></span>
                    <span> </span>
                </label>
                <label for="fancy-checkbox-primary" class="[ btn btn-default active ]">
                    Primary Checkbox
                </label>
            </div>
        </div>
        <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-success" id="fancy-checkbox-success" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-success" class="[ btn btn-success ]">
                    <span class="[ glyphicon glyphicon-ok ]"></span>
                    <span> </span>
                </label>
                <label for="fancy-checkbox-success" class="[ btn btn-default active ]">
                    Success Checkbox
                </label>
            </div>
        </div>
        <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-info" id="fancy-checkbox-info" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-info" class="[ btn btn-info ]">
                    <span class="[ glyphicon glyphicon-ok ]"></span>
                    <span> </span>
                </label>
                <label for="fancy-checkbox-info" class="[ btn btn-default active ]">
                    Info Checkbox
                </label>
            </div>
        </div>
        <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-warning" id="fancy-checkbox-warning" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-warning" class="[ btn btn-warning ]">
                    <span class="[ glyphicon glyphicon-ok ]"></span>
                    <span> </span>
                </label>
                <label for="fancy-checkbox-warning" class="[ btn btn-default active ]">
                    Warning Checkbox
                </label>
            </div>
        </div>
        <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-danger" id="fancy-checkbox-danger" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-danger" class="[ btn btn-danger ]">
                    <span class="[ glyphicon glyphicon-ok ]"></span>
                    <span> </span>
                </label>
                <label for="fancy-checkbox-danger" class="[ btn btn-default active ]">
                    Danger Checkbox
                </label>
            </div>
        </div>
         <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-danger" id="fancy-checkbox-danger" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-danger" class="[ btn btn-danger ]">
                    <span class="[ glyphicon glyphicon-ok ]"></span>
                    <span> </span>
                </label>
                <label for="fancy-checkbox-danger" class="[ btn btn-default active ]">
                    Danger Checkbox
                </label>
            </div>
        </div> <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-danger" id="fancy-checkbox-danger" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-danger" class="[ btn btn-danger ]">
                    <span class="[ glyphicon glyphicon-ok ]"></span>
                    <span> </span>
                </label>
                <label for="fancy-checkbox-danger" class="[ btn btn-default active ]">
                    Danger Checkbox
                </label>
            </div>
        </div> <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-danger" id="fancy-checkbox-danger" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-danger" class="[ btn btn-danger ]">
                    <span class="[ glyphicon glyphicon-ok ]"></span>
                    <span> </span>
                </label>
                <label for="fancy-checkbox-danger" class="[ btn btn-default active ]">
                    Danger Checkbox
                </label>
            </div>
        </div>
		
    </div>
	
	</div>
-->
<!--
    <div class="[ col-xs-12 col-sm-6 ]">
        <h3>Select Staff</h3><hr />
		<div style="overflow-y: scroll; height:200px;width:400px">
		<?php $j=0; ?>
		@foreach ($staffList as $rowstaff)
        <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-default-custom-icons[]" id="fancy-checkbox-default-custom-icons{{++$j}}" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-default-custom-icons" class="[ btn btn-default ]">
                    
					
					<span class="[ glyphicon glyphicon-plus ]"></span>
                    <span class="[ glyphicon glyphicon-minus ]"></span>
					
                </label>
                <label for="fancy-checkbox-default-custom-icons" class="[ btn btn-default active ]">
				{{$rowstaff->name}}
                </label>
            </div>
        </div>
		@endforeach
		<!--
        <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-primary-custom-icons" id="fancy-checkbox-primary-custom-icons" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-primary-custom-icons" class="[ btn btn-primary ]">
                    <span class="[ glyphicon glyphicon-plus ]"></span>
                    <span class="[ glyphicon glyphicon-minus ]"></span>
                </label>
                <label for="fancy-checkbox-primary-custom-icons" class="[ btn btn-default active ]">
                    Primary Checkbox
                </label>
            </div>
        </div>
        <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-success-custom-icons" id="fancy-checkbox-success-custom-icons" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-success-custom-icons" class="[ btn btn-success ]">
                    <span class="[ glyphicon glyphicon-plus ]"></span>
                    <span class="[ glyphicon glyphicon-minus ]"></span>
                </label>
                <label for="fancy-checkbox-success-custom-icons" class="[ btn btn-default active ]">
                    Success Checkbox
                </label>
            </div>
        </div>
        <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-info-custom-icons" id="fancy-checkbox-info-custom-icons" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-info-custom-icons" class="[ btn btn-info ]">
                    <span class="[ glyphicon glyphicon-plus ]"></span>
                    <span class="[ glyphicon glyphicon-minus ]"></span>
                </label>
                <label for="fancy-checkbox-info-custom-icons" class="[ btn btn-default active ]">
                    Info Checkbox
                </label>
            </div>
        </div>
        <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-warning-custom-icons" id="fancy-checkbox-warning-custom-icons" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-warning-custom-icons" class="[ btn btn-warning ]">
                    <span class="[ glyphicon glyphicon-plus ]"></span>
                    <span class="[ glyphicon glyphicon-minus ]"></span>
                </label>
                <label for="fancy-checkbox-warning-custom-icons" class="[ btn btn-default active ]">
                    Warning Checkbox
                </label>
            </div>
        </div>
        <div class="[ form-group ]">
            <input type="checkbox" name="fancy-checkbox-danger-custom-icons" id="fancy-checkbox-danger-custom-icons" autocomplete="off" />
            <div class="[ btn-group ]">
                <label for="fancy-checkbox-danger-custom-icons" class="[ btn btn-danger ]">
                    <span class="[ glyphicon glyphicon-plus ]"></span>
                    <span class="[ glyphicon glyphicon-minus ]"></span>
                </label>
                <label for="fancy-checkbox-danger-custom-icons" class="[ btn btn-default active ]">
                    Danger Checkbox
                </label>
            </div>
        </div>
		
    </div></div>
	</div>
-->






	
						<div class="card">
						<div class="card-body">
						
							<?php
							
							if (count($assignedProjects)>0) { ?>
							<table id="idsssss1" class="table dt-responsive">
								<thead>
									<tr>
										<!--<th>#</th>-->
										<th>Sl.no</th>
										<th>Project Name</th>
										<th>Assignee Names</th>
										
									</tr>
								</thead>

								<tbody>
									<?php
									$i = 0;
									foreach($assignedProjects as $key => $value) {
									//print_r($value); die;
									$i++;

									?>
										<tr>
											<td>{{$i}}</td>
											<td>
											<?php
											foreach($projectList as $project) {
												
												if ($project->id == $value->project_id) {
													echo $project->project_name ;
													break;
												}
											}
											?>
											</td>
											<td>
											<?php
											$userIds = explode("|",$value->executive_ids);
											
											foreach($userIds as $user) {
												
												foreach($staffList as $staff) {
													
													if ($staff->id == $user) {
														
														echo $staff->name.",";
														
														
													}
												}
											}
											?>
											
											</td>
											
										</tr>
									<?php } ?>
								</tbody>
							</table>
								
							<?php } else { ?>
						
							<table id="idsssss" class="table dt-responsive nowrap">
								<tr>
									<th colspan=4 style="text-align:center;"> No Record Found </th>
								</tr>
							</table>
						<?php } ?>

                                </div> <!-- end card body-->
                            </div> <!-- end card -->
							
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
                        <?php echo date('Y'); ?> &copy; Realauto. All Rights Reserved. Crafted with <i class='uil uil-heart text-danger font-size-12'></i> by <a href="#" 
target="_blank">Realauto</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
<script>
    
	$(document).ready(function(){
			assign_btn_action('multiselectlist');
		});
		
	$(".projects").on('click', function() {
		// in the handler, 'this' refers to the box clicked on
		var $box = $(this);
		if ($box.is(":checked")) {
		// the name of the box is retrieved using the .attr() method
		// as it is assumed and expected to be immutable
		var group = "input:checkbox[name='" + $box.attr("name") + "']";
		// the checked state of the group/box on the other hand will change
		// and the current value is retrieved using .prop() method
		$(group).prop("checked", false);
		$box.prop("checked", true);
		} else {
		$box.prop("checked", false);
		}
	});
	let list = [];
	$(".staffs").on('click', function() {
		// in the handler, 'this' refers to the box clicked on
		var $box = $(this);
		
		if ($box.is(":checked")) {
			list.push($box.attr("value"))
		} else {
			const index = list.indexOf($box.attr("value"));
			if (index > -1) {
			  list.splice(index, 1);
			}
		}
		document.getElementById("staffPriority").value = list
	console.log(list)	
	});
	
</script>
    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

@endsection

