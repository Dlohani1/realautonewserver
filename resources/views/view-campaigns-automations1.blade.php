@extends('layouts.app')
@section('title', 'Campaign Automation Details')
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

.no-after:after{content:none;}

.onoffswitch {
    position: relative; width: 86px;
    -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
}
.onoffswitch-checkbox {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
.onoffswitch-label {
    display: block; overflow: hidden; cursor: pointer;
    border: 2px solid #999999; border-radius: 50px;
}
.onoffswitch-inner {
    display: block; width: 200%; margin-left: -100%;
    transition: margin 0.3s ease-in 0s;
}
.onoffswitch-inner:before, .onoffswitch-inner:after {
    display: block; float: left; width: 50%; height: 24px; padding: 0; line-height: 24px;
    font-size: 18px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
    box-sizing: border-box;
}
.onoffswitch-inner:before {
    content: "ON";
    padding-left: 12px;
    background-color: #34A7C1; 
	color: #FFFFFF;
}

.onoffswitch-inner:after {
    content: "OFF";
    padding-right: 12px;
    background-color: #EEEEEE; color: #999999;
    text-align: right;
}

.onoffswitch-switch {
    display: block; width: 31px; margin: -3.5px;
    background: #FFFFFF;
    position: absolute; top: 0; bottom: 0;
    right: 58px;
    border: 2px solid #999999; border-radius: 50px;
    transition: all 0.3s ease-in 0s; 
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
    margin-left: 0;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
    right: 0px; 
}


</style>

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <!-- ADD EVENT  modal content -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel" style="margin: 14px;"><i data-feather="plus" class="icons-sm"></i> Add New Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mb_">
                    <div class="row">
                        <div class="col-sm-4 text-center">
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
								
                                    <!-- <a href="{{ url('email-automation/'.Request::segment(2) ) }}"><i data-feather="mail"></i>
                                        <h5>E-mail</h5>
                                    </a> -->
									<?php
										$userId = Auth::user()->id;
										if ($userId == "13") { ?>
											<a href="{{ url('email-automation/'.Request::segment(2) ) }}"><i data-feather="mail"></i>
												<h5>E-mail</h5>
											</a>
										<?php } else {?> 
											
											<a href="javascript:void(0)"><i data-feather="mail"></i>
												<h5>E-mail</h5>
											</a>
										<?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 text-center">
                            <div class="card">
                                <div class="card-body" style="padding: 20px 10px;">
                                    <a href="{{ url('whatsapp-automation/'.Request::segment(2) ) }}"><i data-feather="message-circle"></i>
                                        <h5>Whatsaapp</h5>
                                    </a>
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
                        <nav aria-label="breadcrumb" class="float-right mt-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                 <li class="breadcrumb-item"><a href="<?php echo url('/automation-master'); ?>"> Manage Campaigns</a></li>
								<li class="breadcrumb-item active" aria-current="page">Manage Events</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Manage Events</h4>
                    </div>
                
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>
                                    <i data-feather="chevron-right" class="icons-sm"></i> {{ $campaignname->campaigns_name }}
                                    <a href="#" data-toggle="modal" data-target="#myModal"
                                        class="btn btn-primary btn-sm float-right">
                                        <i data-feather="plus" class="icons-sm"></i> Add New Event
                                    </a>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-lg-12">
                        <div class="left-timeline pl-4">
                            @if(Session::has('message'))
                                <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{!! session('message') !!}</strong>
                                </div>
                            @endif
                            <ul class="list-unstyled events">
								<?php $sl = 0; ?>
                                @foreach ($automationseries as $data)
								<?php $sl++; ?>
                                <li class="event-list">
                                    <div>
                                        <div class="media">
                                            <div class="event-date text-center mr-4">
                                                <div class=" avatar-sm rounded-circle bg-soft-primary">
                                                    <span
                                                        class="font-size-16 avatar-title text-primary font-weight-semibold">
                                                        !!
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <div class="card ">
                                                    <div class="card-body">
													 <?php
														$message = $data->message;
														if ($data->automation_type == 2) {
															$dataMsg = explode(":>",$data->message);
															$message = isset($dataMsg[1]) ? $dataMsg[1] : $data->message;
														}
														?>
                                                        <h5 class="mt-0">
                                                            @if($data->automation_type == 1)
                                                            <i class="uil uil-mobile-android-alt"></i>
                                                            @elseif ($data->automation_type == 2)
                                                            <i class="uil uil-fast-mail-alt"></i>
                                                            @else
                                                            <i class="uil uil-whatsapp"></i>
                                                            @endif
                                                            {{ $data->series_name }}</h5>
                                                        <p class="text-muted">{{ $message }}</p>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="row">
                                                            <div class="col-md-9 mt-2">
                                                                @if($data->automation_type == 1)
                                                                    <a href="{{ url('edit-sms-automation/'.$data->id) }}" class="card-link"><i class="uil uil-pen"></i> Edit</a>
                                                                @elseif ($data->automation_type == 2)
                                                                    <a href="{{ url('edit-email-automation/'.$data->id) }}" class="card-link"><i class="uil uil-pen"></i> Edit</a>
                                                                @else
                                                                    <a href="{{ url('edit-whatsapp-automation/'.$data->id) }}" class="card-link"><i class="uil uil-pen"></i> Edit</a>
                                                                @endif

                                                                <!-- <a href="#" class="card-link"><i class="uil uil-trash-alt"></i>Remove</a> -->&nbsp;&nbsp;&nbsp;

                                                                @if($data->automation_type == 1)
                                                                    <a href="{{ url('copy-sms-automation-campaign/' . $data->id) }}"><i data-feather="copy" class="icons-sm"></i></a>
                                                                @elseif ($data->automation_type == 2)
                                                                    <a href="{{ url('copy-email-automation-campaign/' . $data->id) }}"><i data-feather="copy" class="icons-sm"></i></a>
                                                                @else
                                                                    <a href="{{ url('copy-whatsapp-automation-campaign/' . $data->id) }}"><i data-feather="copy" class="icons-sm"></i></a>
                                                                @endif
																<?php 
																$cronEvent = 0;
																if(!empty($data->delivery_day) || $data->delivery_day != 0 || $data->delivery_time != 0) {
																	$cronEvent = 1;
																}
																 ?>
																<div class="onoffswitch" style="margin-top:10px">
																	<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch{{$sl}}" tabindex="0" onclick="deactivateEvent('myonoffswitch{{$sl}}',{{$data->id.','.$data->is_active.','.($data->is_active == 0 ? '1':'0').','.$cronEvent}})" {{$data->is_active == 1 ? "checked":""}} >
																	<label class="onoffswitch-label" for="myonoffswitch{{$sl}}">
																	<span class="onoffswitch-inner"></span>
																	<span class="onoffswitch-switch"></span>
																	</label>
																</div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                
                                                                @if(!empty($data->delivery_day) || $data->delivery_day != 0 || $data->delivery_time != 0)
                                                                    <?php  if ($data->delivery_day) { switch($data->delivery_day) { case 1 : $sup = "st"; break; case 2: $sup = "nd"; break; case 3: $sup = "rd"; break; default : $sup = "th";} echo "$data->delivery_day<sup>$sup</sup>"; } else {echo "Same ";}?> Day | Time {{ date('h:i A', strtotime($data->delivery_time)) }}
                                                                @else
                                                                    Immediate
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                                <li><div class="ad_sp"><a href="#myModal" data-toggle="modal" data-target="#myModal" class="modal-title"><i data-feather="plus-circle" class="icon-dual"></i><span>Add Events</span></a></div></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end row -->


                <!-- end row -->
            </div> <!-- container-fluid -->
        </div> <!-- content -->

@endsection

@push('scripts')
<script>
	$('.onoffswitch-inner').on('change', function(e) {

  
		let checked = $(this).is(':checked');

		let changed = confirm("Are you sure you want to change this account?");

		if (!changed) { 

			return checked ? $(this).prop('checked', false) : $(this).prop('checked', true);
		}
	});
	
	function deactivateEvent(id,series_id,is_active,status, isCronEvent) {
		//alert(status)
		
		if(status == "1") {
			var stop = confirm("Do you really want to on this event ??");
		} else {
			var stop = confirm("Do you really want to off this event ??");
		}
		
		if (stop == true) {
			
			var formData = {_token: "{{ csrf_token() }}",seriesId:series_id,status:status,isCronEvent:isCronEvent}; //Array 
			var url = "{{ url('deactivate-event')}}";
			$.ajax({
				url : url,
				type: "POST",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{
					console.log('dd',data)
					//data - response from server
					window.location.reload()
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
			 
				}
			});
			
		} else {
			var toggle = false;
			
			if (status == "0") {
				toggle = true;
			}

			return stop ? $("#"+id).prop('checked', toggle) : $("#"+id).prop('checked', toggle);
			
		}
			
	}
</script>
@endpush
