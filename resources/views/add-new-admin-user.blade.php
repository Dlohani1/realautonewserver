@extends('layouts.app')
@section('title', 'Add New Admin User')
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
                                <li class="breadcrumb-item"><a href="<?php echo url('/manage-admin-user'); ?>"> Manage Admins User </a></li>
                                <li class="breadcrumb-item active" aria-current="page">User's</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Add User</h4>
                    </div>
                </div>

                <!-- end row -->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">

                                <?php if(Session::has('message')) { ?>
                                    <div class="alert alert-success"> <?php echo Session::get('message'); ?> </div>
                                <?php } ?>

                                <?php if ($errors->any()){ ?>
                                    <div class="alert alert-danger">
                                        <ul>
                                            <?php foreach ($errors->all() as $error){ ?>
                                                <li><?php echo  $error ; ?></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } ?>

                                <form action="<?php echo url('/save-admin-user'); ?>" method="post" name="save-admin" class="form-horizontal">
                                    @csrf
                                    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">Name <span class="required">*</span></label>
                                        <div class="col-9">
                                            <input type="text" name="name" class="form-control" placeholder="Name" required>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">Email <span class="required">*</span></label>
                                        <div class="col-9">
                                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputEmail3" class="col-3 col-form-label">Mobile <span class="required">*</span></label>
                                        <div class="col-9">
                                            <input type="text" name="phone_no" class="form-control" placeholder="Mobile" required>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputPassword3" class="col-3 col-form-label">Password <span class="required">*</span></label>
                                        <div class="col-9">
                                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label for="inputPassword5" class="col-3 col-form-label">Re Password <span class="required">*</span></label>
                                        <div class="col-9">
                                            <input type="password" name="cpassword" class="form-control" placeholder="Retype Password" required>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-9">
                                            <button type="submit" class="btn btn-info">Submit</button>
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
