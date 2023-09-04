@extends('layouts.app')
@section('title', 'Edit Leads')
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
                                <li class="breadcrumb-item active" aria-current="page">Edit Leads</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Edit Leads</h4>
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

                                <form action="<?php echo url('/edit-leads-post/'.$leadsdata->id); ?>" method="post" name="add-leads-post-data" class="form-horizontal">
                                    @csrf
                                    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="name" class="form-control" placeholder="Name" required value="{{ @$leadsdata->name }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">Email <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="email" name="mail_id" class="form-control" placeholder="Email" required value="{{ @$leadsdata->mail_id }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">Mobile No<span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="mobile_no" class="form-control" placeholder="Mobile" required value="{{ @$leadsdata->mobile_no }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputPassword3" class="col-3 col-form-label">Country </label>
                                        <div class="col-6">
                                            <input type="text" name="country" class="form-control" placeholder="Country"  value="{{ @$leadsdata->country }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">State </label>
                                        <div class="col-6">
                                            <input type="text" name="state" class="form-control" placeholder="State"  value="{{ @$leadsdata->state }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">City </label>
                                        <div class="col-6">
                                            <input type="text" name="city" class="form-control" placeholder="City"  value="{{ @$leadsdata->city }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Zipcode </label>
                                        <div class="col-6">
                                            <input type="text" name="zipcode" class="form-control" placeholder="Zipcode"  value="{{ @$leadsdata->zipcode }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Company </label>
                                        <div class="col-6">
                                            <input type="text" name="company" class="form-control" placeholder="Company"  value="{{ @$leadsdata->company }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Position </label>
                                        <div class="col-6">
                                            <input type="text" name="position" class="form-control" placeholder="Position"  value="{{ @$leadsdata->position }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Address1 </label>
                                        <div class="col-6">
                                            <input type="text" name="address1" class="form-control" placeholder="Address1"  value="{{ @$leadsdata->address1 }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Address2 </label>
                                        <div class="col-6">
                                            <input type="text" name="address2" class="form-control" placeholder="Address2" value="{{ @$leadsdata->address2 }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Project <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="project_type" class="custom-select mb-2" onchange="GetProjects(this.value)" required>
                                                <option value="">Select Project</option>
                                                <option value="1" @if ($leadsdata->project_type == 1) selected @endif>Old</option>
                                                <option value="2" @if ($leadsdata->project_type == 2) selected @endif>New</option>
                                            </select>
                                        </div>
                                    </div>
                                    @if($leadsdata->project_type == 1)
                                    <div class="form-group row mb-3" id="hideprojects_name" style="display: none;"  >
                                        <label for="inputPassword5" class="col-3 col-form-label">Project Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="project_name" class="form-control" placeholder="Enter New Project Name">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3" id="hideprojects_list">
                                        <label for="inputPassword5" class="col-3 col-form-label">Project Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="project_id" class="custom-select mb-2" >
                                                <option value="">Select Project Name</option>
                                                @foreach($projects as $rowproject)
                                                <option value="{{ $rowproject->id }}" @if($rowproject->id == $leadsdata->project_id) selected @endif>{{ $rowproject->project_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group row mb-3" id="hideprojects_name"  >
                                        <label for="inputPassword5" class="col-3 col-form-label">Project Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="project_name" class="form-control" placeholder="Enter New Project Name" value="{{ $leadsdata->project_name }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3" id="hideprojects_list"  style="display: none;" >
                                        <label for="inputPassword5" class="col-3 col-form-label">Project Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="project_id" class="custom-select mb-2" >
                                                <option value="">Select Project Name</option>
                                                @foreach($projects as $rowproject)
                                                <option value="{{ $rowproject->id }}" @if($rowproject->id == $leadsdata->project_id) selected @endif>{{ $rowproject->project_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Segment <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="segment_type" class="custom-select mb-2" onchange="GetSegment(this.value)">
                                                <option value="">Select Segment</option>
                                                <option value="1"  @if ($leadsdata->segment_type == 1) selected @endif>Old</option>
                                                <option value="2"  @if ($leadsdata->segment_type == 2) selected @endif>New</option>
                                            </select>
                                        </div>
                                    </div>
                                    @if($leadsdata->segment_type == 1)
                                    <div class="form-group row mb-3" id="hidesegment_name"  style="display: none;" >
                                        <label for="inputPassword5" class="col-3 col-form-label">Segment Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="segment_name" class="form-control" placeholder="Enter New Segment Name" value="">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3" id="hidesegment_list" >
                                        <label for="inputPassword5" class="col-3 col-form-label">Segment Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="segment_id" class="custom-select mb-2" >
                                                <option value="">Select Segment Name</option>
                                                @foreach($segments as $rowsegments)
                                                <option value="{{ $rowsegments->id }}" @if($rowsegments->id == $leadsdata->segment_id) selected @endif>{{ $rowsegments->segment_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group row mb-3" id="hidesegment_name"   >
                                        <label for="inputPassword5" class="col-3 col-form-label">Segment Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="segment_name" class="form-control" placeholder="Enter New Segment Name" value="{{ $leadsdata->segment_name }}">
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3" id="hidesegment_list" style="display: none;">
                                        <label for="inputPassword5" class="col-3 col-form-label">Segment Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <select name="segment_id" class="custom-select mb-2" >
                                                <option value="">Select Segment Name</option>
                                                @foreach($segments as $rowsegments)
                                                <option value="{{ $rowsegments->id }}" @if($rowsegments->id == $leadsdata->segment_id) selected @endif>{{ $rowsegments->segment_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                   <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Campaign <span class="required">*</span></label>
                                        <div class="col-6">
											<input type="hidden" name="old_campaign_id" value= "{{ $leadsdata->campaigns_id }}" />
                                            <select name="campaign_id" class="custom-select mb-2" required>
                                                <option value="">Select Campaign</option>
                                                @foreach($campaigns as $rowcampaign)
                                                <option value="{{ $rowcampaign->id }}" @if($rowcampaign->id == $leadsdata->campaigns_id) selected @endif>{{ $rowcampaign->campaigns_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-info">Submit</button>
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
    </script>
@endpush
