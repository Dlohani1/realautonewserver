@extends('layouts.app')
@section('title', 'Import Self Leads')
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
                        <h4 class="mb-1 mt-0">Self Lead Import</h4>
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

                                <form id="myForm" action="<?php echo url('/add-import-leads-post-data'); ?>" method="post" name="add-import-leads-post-data" class="form-horizontal" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">Upload Lead (CSV) <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="file" name="leads" class="form-control" onchange="myValidation()" required>
                                            <a href="{{url('/')}}/uploads/leads.csv"><i class="bi bi-download"></i>Download sample csv file</a>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Project <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="project_type" class="custom-select mb-2" onchange="GetProjects(this.value)" required>
                                                <option value="">Select Project</option>
                                                <option value="1">Old</option>
                                                <option value="2">New</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3" id="hideprojects_name" style="display: none;">
                                        <label for="inputPassword5" class="col-3 col-form-label">Project Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="project_name" class="form-control" placeholder="Enter New Project Name">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3" id="hideprojects_list" style="display: none;">
                                        <label for="inputPassword5" class="col-3 col-form-label">Project Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="project_id" class="custom-select mb-2" >
                                                <option value="">Select Project Name</option>
                                                @foreach($projects as $rowproject)
                                                <option value="{{ $rowproject->id }}">{{ $rowproject->project_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Segment <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="segment_type" class="custom-select mb-2" onchange="GetSegment(this.value)">
                                                <option value="">Select Segment</option>
                                                <option value="1">Old</option>
                                                <option value="2">New</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3" id="hidesegment_name" style="display: none;">
                                        <label for="inputPassword5" class="col-3 col-form-label">Segment Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="segment_name" class="form-control" placeholder="Enter New Segment Name" >
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3" id="hidesegment_list" style="display: none;">
                                        <label for="inputPassword5" class="col-3 col-form-label">Segment Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="segment_id" class="custom-select mb-2" >
                                                <option value="">Select Segment Name</option>
                                                @foreach($segments as $rowsegments)
                                                <option value="{{ $rowsegments->id }}">{{ $rowsegments->segment_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Campaign <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="campaign_id" class="custom-select mb-2" required>
                                                <option value="">Select Campaign</option>
                                                @foreach($campaigns as $rowcampaign)
                                                <option value="{{ $rowcampaign->id }}">{{ $rowcampaign->campaigns_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-info" id="submit">Submit</button>
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
                        <?php echo date('Y'); ?> &copy; Realauto. All Rights Reserved. Crafted with <i class='uil uil-heart text-danger font-size-12'></i> by <a href="#" target="_blank">Realauto</a>
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
		$('#submit').attr('disabled', 'disabled');
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
	
	function myValidation(){
		var fileInput = 
        document.getElementById('upload');
         
        var filePath = fileInput.value;
        //alert(filePath)
        // Allowing file type
        var allowedExtensions = /(\.csv)$/i;
          
        if (!allowedExtensions.exec(filePath)) {
            //$("#upload").css("border", "1px solid red");
             document.getElementById('errorupload').innerHTML = "Please upload valid file formats .csv only.";
            alert('Please upload valid file formats .csv only.');
           
            fileInput.value = '';
            return false;
		} 
	}   



	
    </script>
@endpush
