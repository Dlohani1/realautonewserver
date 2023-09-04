@extends('layouts.app')
@section('title', 'Add New Segment')
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
                                <li class="breadcrumb-item"><a href="<?php echo url('/automation-master'); ?>"> Manage Campaign </a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Segment</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Add Segment</h4>
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

                                <form action="<?php echo url('/save-new-segment'); ?>" method="post" name="save_campaign_automation" class="form-horizontal">
                                    @csrf

                                    <div class="form-group row mb-3" {{ $errors->has('segment_name')? 'has-error':'' }}>
                                        <label for="inputEmail3" class="col-3 col-form-label">Segment Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input id="segment_name" type="text" name="segment_name" class="form-control" placeholder="New Segment Name" value="{{ old('segment_name') }}" onkeyup ="setField()"> 
						<input id="is_update" style="display:none" type="text" name="is_update" value="0" />
						<input id="segment_id" style="display:none" type="text" name="segment_id" value="0" />

                                            <span class="text-danger">{{ $errors->first('segment_name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-info" id="btn-submit">Submit</button>
                                            <a href="{{ route('automation-master') }}"><button type="button" class="btn btn-success">Back</button></a>
                                        </div>
                                    </div>

                                </form>
                            </div>  <!-- end card-body -->
                        </div>  <!-- end card -->
                    </div>  <!-- end col -->
                </div>



                <!-- end row -->




				<div class="table-responsive">
                                    <table id="basic-datatable" class="table">
                                        <thead>
                                        <tr>
                                            <th>Sl No</th>
                                            
                                            <th>Segement Name</th>
                                            <th> Total Leads</th>                        
                                           <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $sl=0;
                                            foreach ($segments as $row){

                                                $sl++;
                                        ?>
                                        <tr>
                                            <td>{{ $sl }}</td>
                                            
                                            <td>{{ $row->segment_name }}</td>


                                            <td>{{ $row->leads }}</td>
                                           
                                           <td>
                                                <div class="icon-item">
                                                    <a href="javascript:void(0)" onclick  = "test('{{$row->segment_name}}','{{$row->id}}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 
2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                    </a>
                                           <!--         <a href="{{ url('/delete-leads/'.$row->id) }}" onclick="return deleteConfirm()">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" 
stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" 
x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg>
                                                    </a> -->
                                                </div>
                                            </td> 
                                        </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
            </div> <!-- container-fluid -->
        </div> <!-- content -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?php echo date('Y'); ?> &copy; RealAuto. All Rights Reserved. Crafted with <i class='uil uil-heart text-danger font-size-12'></i> by <a href="https://active-digital.tech/home/" target="_blank">Active Digital Technology</a></a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
<script>

function setField() {
console.log('test',document.getElementById("segment_name").value.trim().length )
document.getElementById("segment_name").focus(); 
$(window).scrollTop($('#segment_name').position().top);
if (document.getElementById("segment_name").value.trim() == "") {
//document.getElementById("is_update").value= 0;
//document.getElementById("btn-submit").innerHTML = "Submit"

//alert(document.getElementById("is_update").value)
}

}

function test(segment,id) {

document.getElementById("segment_name").value = segment

document.getElementById("segment_id").value = id

document.getElementById("is_update").value= 1;

document.getElementById("btn-submit").innerHTML = "Update"
//alert(document.getElementById("is_update").value)
}
</script>
    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

@endsection

