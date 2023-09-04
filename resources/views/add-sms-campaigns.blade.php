@extends('layouts.app')
@section('title', 'Add New Bulk SMS Campaigns')
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
                                <li class="breadcrumb-item"><a href="<?php echo url('/'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('bulk_sms_master') }}"> Manage SMS Campaign </a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Bulk SMS Campaign</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Create Bulk SMS Campaign</h4>
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

                                <form action="{{ route('save_sms_campaign') }}" method="post" name="save_campaign_automation" class="form-horizontal">
                                    @csrf

                                    <div class="form-group row mb-3 {{ $errors->has('campaigns_name')? 'has-error':'' }}">
                                        <label for="inputEmail3" class="col-3 col-form-label">Bulk SMS Campaign Name <span class="required">*</span></label>
                                        <div class="col-6">
                                            <input type="text" name="campaigns_name" class="form-control" placeholder="Bulk SMS Campaign Name">
                                        </div>
                                        <span class="text-danger">{{ $errors->first('campaigns_name') }}</span>
                                    </div>

                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-info">Submit</button>
                                            <a href="{{ route('bulk_sms_master') }}"><button type="button" class="btn btn-success">Back</button></a>
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
