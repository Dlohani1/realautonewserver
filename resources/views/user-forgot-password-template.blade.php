@extends('layouts.app')
@section('title', 'User Forgot Password')
@section('content')

    <!-- page header section ending here -->
    <section class="page-header padding-tb page-header-bg-1">
        <div class="container">
            <div class="page-header-item d-flex align-items-center justify-content-center">
                <div class="post-content">
                    <h3><i class="fa fa-user"></i> Forgot Password</h3>
                </div>
            </div>
        </div>
    </section>
    <!-- page header section ending here -->

    <!-- contact us section start here -->
    <div class="container login_">
        <div class="row">
            <div class="col-md-4 mx-auto">
                <div class="myform form ">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <?php if(Session::has('success')) { ?>
                        <div class="alert alert-success"> <?php echo Session::get('success'); ?> </div>
                    <?php } ?>

                    <?php if(Session::has('error')) { ?>
                        <div class="alert alert-danger"> <?php echo Session::get('error'); ?> </div>
                    <?php } ?>

                    <form action="{{ route('user_forgot_password') }}" method="post" name="fotgotPass">
                        @csrf
                        <div class="form-group">
                            <input type="email" name="email"  class="form-control my-input" id="email" placeholder="Email Address">
                        </div>
                        <div class="text-center ">
                            <input type="submit" name="submit" value="SUBMIT" class="btn btn-primary"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- contact us section ending here -->

@endsection
