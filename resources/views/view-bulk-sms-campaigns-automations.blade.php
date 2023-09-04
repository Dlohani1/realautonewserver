@extends('layouts.app')
@section('title', 'Bulk SMS Automation Campaign List')
@section('content')

    <style>
        .ad_sp {
            position: absolute;
            bottom: -5px;
            left: 25px;
        }
        .ad_sp span {
            margin-left: 20px;
            font-size: 15px;
            color: #5369f8;
            text-transform: uppercase;
            font-weight: 600;
            position: absolute;
            width: 200px;
            top: 1px;
        }
        .modal {
            top: 20%;
        }
    </style>

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <!-- ADD EVENT  modal content -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel" style="margin: 14px;"><i data-feather="plus" class="icons-sm"></i> Add New SMS Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mb_">
                    <div class="row">
                        <?php /* ?><div class="col-sm-4 text-center">
                            <div class="card">
                                <div class="card-body" style="cursor: pointer;">
                                   <a href="{{ url('sms-automation/'.Request::segment(2)) }}"><i data-feather="smartphone"></i>
                                        <h5>SMS</h5>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <a href="{{ url('bulk-email-automation/'.Request::segment(2) ) }}"><i data-feather="mail"></i><h5>E-mail</h5></a>
                                </div>
                            </div>
                        </div><?php */ ?>
                        <div class="col-sm-4 text-center">
                            <div class="card">
                                <div class="card-body" style="padding: 20px 10px;">
                                    <a href="{{ url('bulk-whatsapp-automation/'.Request::segment(2) ) }}"><i data-feather="message-circle"></i><h5>WhatsApp</h5></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="row page-title">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>
                                    <i data-feather="chevron-right" class="icons-sm"></i> {{ $campaignname->sms_campaigns_name }} (Total SMS Fired {{ $smscount }})
                                    <a href="#" data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm float-right">
                                        <i data-feather="plus" class="icons-sm"></i> Add New SMS Event
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div> <!-- container-fluid -->
        </div> <!-- content -->
    </div>

@endsection

