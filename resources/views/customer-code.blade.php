@extends('layouts.app')
@section('title', 'Generate Customer Code')
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
                                <li class="breadcrumb-item active" aria-current="page">Generate Customer Code</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Generate Code</h4>
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

								Your Unique Identification Code is : <span class="text-success">{{$admin_code}}</span>
                                <form action="<?php echo url('/save-code'); ?>" method="post" name="save_campaign_automation" class="form-horizontal" onsubmit="return validateForm()">
                                    @csrf

                                    <div class="form-group row mb-3" {{ $errors->has('campaigns_name')? 'has-error':'' }}>
                                        <label for="inputEmail3" class="col-3 col-form-label">Unique Identification Code <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="code" id="code" class="form-control" readonly placeholder="Generate Code" value="{{ old('campaigns_name') }}">
                                            <span class="text-danger">{{ $errors->first('campaigns_name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-info">Submit</button>
											<button type="button" class="btn btn-primary" onclick="generateCode()">Generate</button>
                                            <a href="{{ route('automation-master') }}"><button type="button" class="btn btn-success">Back</button></a>
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

@endsection
<script>
function validateForm() {
	if (document.getElementById('code').value.length > 0) {
		return true
	} else {
		alert("Generate Code !!")
	}
	return false;
	
}

function generateCode() {
	var dt = new Date().getTime();
	var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
		var r = (dt + Math.random()*16)%16 | 0;
		dt = Math.floor(dt/16);
		
		return (c=='x' ? r :(r&0x3|0x8)).toString(16);
	});
	
	document.getElementById('code').value = uuid;
}

</script>
