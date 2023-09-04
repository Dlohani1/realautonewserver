@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	
	<style>
	


#funnel {
	width: 300px;
	height: 400px;
	margin: 0px auto;
}

.view {
  /*margin: auto;*/
  width: 90%;
  background-color: white;
  max-width:330px;
}

.wrapper {
  position: relative;
  overflow: auto;
 /* border: 1px solid black; */
  white-space: nowrap;
}

.sticky-col {
  position: -webkit-sticky;
  position: sticky;
  background-color: white;
}

.first-col {
  /*width: 100px;
  min-width: 100px;
  max-width: 100px;
  left: 0px;
  */
}

.second-col {
 /* width: 150px;
  min-width: 150px;
  max-width: 150px;
  left: 100px;
  */
  left:0px;
}

</style>

<?php

$notAttended = $pendingLeadsNo > $progressCount ? $pendingLeadsNo - $progressCount : $progressCount - $pendingLeadsNo;

//$notAttended =  $pendingLeadsNo > $attendedLeads ? $pendingLeadsNo - $attendedLeads : $attendedLeads - $pendingLeadsNo ;

//$inProgressLeads = $attendedLeads > $notAttended ? $attendedLeads - $notAttended : $notAttended - $attendedLeads;
 
?>
	
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="row page-title align-items-center">
                    <div class="col-sm-4 col-xl-6">
                        <h4 class="mb-1 mt-0">Dashboard</h4>
                    </div>
                </div>

                    <div class="row"> 
        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
          <div class="card shadow mb-4"> 
            <!-- Card Header - Dropdown -->
            <div
              class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary" style="margin-left: 55px !important;">Whatsapp QR Code Scanner</h6>
             
            </div>
            <!-- Card Body -->
            <div class="card-body"> 
              
              <h2 class="mb-0"> <img id = "whatsapp-scanner" src="{{ whatsappscanner() }}" width='160' height='160' style="margin-left: 61px !important;"> </h2>

  <br/>
              <button type="button" onclick="performAction('logoff')"> Restart </button>  <button type="button" onclick="performAction('reset')"> Log Off </button>

            </div>
          </div>
        </div>
        <!-- Content Column -->
        <div class="col-lg-8 mb-4">
          <div class="row">
            <div class="col-md-6 col-xl-4" >
              <div class="card">
                <div class="card-body p-0" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                  <div class="media p-4" style="padding: 0.5rem!important;">
                    <div class="media-body">
                      <h1 class="text-muted text-uppercase font-size-12 font-weight-bold" style="font-size: 14px;font-size: 14px !important;text-align: center;color:#0f59b5 
!important;">Total Leads Assigned</h1>
                      <p style="font-size: 30px;text-align: center;line-height: 1.2 !important;font-weight: 700;margin-bottom: 7px;" class="total">{{$assigned_leads}}</p>
                       <span style="color:#0c9ead;" class="today"><i class='uil uil-arrow-up'></i> InProgress <span class="mb-0" style="color:#000 !important;font-weight: 600 
!important;font-size: 15px !important;""> {{$progressCount}}</span></span> 
                      <span style="color:#0c9ead;" class="today"><i class='uil uil-arrow-down'></i> Today <span class="mb-0" style="color:#000 !important;font-weight: 600 
!important;font-size: 15px !important;""> {{$todayAssignedLeads}}</span></span> </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-4" >
              <div class="card">
                <div class="card-body p-0" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                  <div class="media p-4" style="padding: 0.5rem!important;">
                    <div class="media-body">
                      <h1 class="text-muted text-uppercase font-size-12 font-weight-bold" style="font-size: 14px;font-size: 14px !important;text-align: center;color: #e96100 
!important;;">Overdue Leads</h1>
                      <p style="font-size: 30px;text-align: center;line-height: 1.2 !important;font-weight: 700;margin-bottom: 7px;" class="total">{{$notAttended}}</p>
                      <span style="color:#1ce1ac;margin-left: 45%;" class="today"><i class='uil uil-arrow-down'></i> Today <span class="mb-0" style="color:#000 !important;font-weight: 
600 !important;font-size: 15px !important;"> {{0}}</span></span> </div>
                  </div>
                </div>
              </div>
            </div> 
            <div class="col-md-6 col-xl-4" >
              <div class="card">
                <div class="card-body p-0" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                  <div class="media p-4" style="padding: 0.5rem!important;">
                    <div class="media-body">
                      <h1 class="text-muted text-uppercase font-size-12 font-weight-bold" style="font-size: 14px;font-size: 14px !important;text-align: center;color: #02c2ed 
!important;;">Today Followups</h1>
                      <p style="font-size: 30px;text-align: center;line-height: 1.2 !important;font-weight: 700;margin-bottom: 7px;" class="total">{{$todayFollowup}}</p>
                      <span style="color:#cf15b8;margin-left: 45%;" class="today"><i class='uil uil-arrow-down'></i> Overdue <span class="mb-0" style="color:#000 
!important;font-weight: 600 !important;font-size: 15px !important;""> {{0}}</span></span> 

                      </div>
                  </div>
                </div>
              </div>
            </div>
      
            </div>
            <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body" style="padding: 17px;padding-left: 20px;">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2"  style="text-align:center">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                SMS Credit</div>
                            <?php

                            $api_key = Auth::user()->sms_api_key;
                            $smsbal = 0;
                            
                            if (null !== $api_key) {

                                $api_url = "http://webmsg.smsbharti.com/app/miscapi/".$api_key."/getBalance/true/";

                                //Submit to server
                                $credit_balance = file_get_contents($api_url);

                                $bal= json_decode($credit_balance);


                                if (null !== $bal) {
                                        foreach ($bal as $key => $value) {
                                            if ($value->ROUTE == "OPremiumAPI") {
                                                    $smsbal = $value->BALANCE;
                                            }
                                        }
                                    }
                                }
                                                                                        
                                        ?>

                                            
                                            <div class="mb-0 font-weight-bold text-gray-800" style="font-size: 28px !important;height: 23px !important;">{{ @$smsbal }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body" style="padding: 17px !important;padding-left: 20px !important;">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2"  style="text-align:center">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Whatsapp Credit</div>
                                                
                                            <div class="mb-0 font-weight-bold text-gray-800" style="font-size: 28px !important;height: 23px !important;">{{ whatsappcounter() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    

          <div class="col-md-6 col-xl-4" >
                <div class="card">
                <div class="card-body p-0" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;padding: 9px !important;">
                <div class="media p-4" style="padding: 0.5rem!important;">
                <div class="media-body">
                <h1 class="text-muted text-uppercase font-size-12 font-weight-bold" style="font-size: 14px;font-size: 14px !important;text-align:
                center;color: #02c2ed !important;;">Leads Added </h1><p style="font-size: 30px;text-align: center;line-height: 1.2 !important;font-weight: 700;margin-bottom: 7px;"
                class="total">{{0}}</p>
                <span style="color:#cf15b8;margin-left: 45%;" class="today"><i class='uil uil-arrow-down'></i> Today
                <span class="mb-0" style="color:#000 !important;font-weight: 600 !important;font-size: 15px !important;"">{{0}}</span></span>
                </div>
            </div>
        </div>
    </div>
  </div>

				@if (Auth::user()->usertype == 1)
					
                <div class="row">
                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold"> Total Users</span>
                                        <h2 class="mb-0">{{ $total_users }}</h2>
                                    </div>
                                    {{--<div class="align-self-center">
                                        <div id="today-revenue-chart" class="apex-charts"></div>
                                        <span class="text-success font-weight-bold font-size-13">
                                            <i class='uil uil-arrow-up'></i> 10.21%
                                        </span>
                                    </div>--}}
                                </div>
                            </div>
                        </div> 
                    </div>
					<div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold"> Trial Users</span>
                                        <h2 class="mb-0">{{ $trialUsers }}</h2>
                                    </div>
                                    {{--<div class="align-self-center">
                                        <div id="today-revenue-chart" class="apex-charts"></div>
                                        <span class="text-success font-weight-bold font-size-13">
                                            <i class='uil uil-arrow-up'></i> 10.21%
                                        </span>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold"> Active Users</span>
                                        <h2 class="mb-0">{{ $activeUsers }}</h2>
                                    </div>
                                    {{--<div class="align-self-center">
                                        <div id="today-revenue-chart" class="apex-charts"></div>
                                        <span class="text-success font-weight-bold font-size-13">
                                            <i class='uil uil-arrow-up'></i> 10.21%
                                        </span>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
				@endif
               
				
                @if (Auth::user()->usertype == 2)
                <div class="row">

                   <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="col-md-6 col-xl-6" style="border-right:1px solid black;">
                                   <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Total Leads</span>
                                        <h2 class="mb-0">{{ $total_leds }}</h2>
                                    </div>
                                    </div>
                                    <div class="col-md-6 col-xl-6">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Today Leads</span>
                                        <h2 class="mb-0">{{0  }}</h2>
                                    </div>
                                    </div>
                                <!--    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Total Leads</span>
                                        <h2 class="mb-0">{{ $total_leds }}</h2>
                                    </div> -->
                                    {{--<div class="align-self-center">
                                        <div id="today-revenue-chart" class="apex-charts"></div>
                                        <span class="text-success font-weight-bold font-size-13">
                                            <i class='uil uil-arrow-up'></i> 10.21%
                                        </span>
                                    </div>--}}
                                </div>
                            </div>
                        </div> 

                    </div>

                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
<div class="col-md-6 col-xl-6" style="border-right:1px solid black;">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Uploaded Leads</span>
                                        <h2 class="mb-0">{{ $uploads_leds }}</h2>
                                    </div>
                                    </div>
                                    <div class="col-md-6 col-xl-6">
                                        <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Today Leads</span>
                                        <h2 class="mb-0">{{ $today_upload_leads  }}</h2>
                                    </div>
                                    </div>
                                    <!--<div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Uploaded Leads</span>
                                        <h2 class="mb-0">{{ $today_upload_leads }}</h2>
                                    </div>-->
                                    {{--<div class="align-self-center">
                                        <div id="today-product-sold-chart" class="apex-charts"></div>
                                        <span class="text-danger font-weight-bold font-size-13"><i
                                                class='uil uil-arrow-down'></i> 5.05%</span>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>

					<div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">

				     <div class="col-md-6 col-xl-6" style="border-right:1px solid black;">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Facebook leads</span>
                                        <h2 class="mb-0">{{ $facebook_leds }}</h2>
                                    </div>
                                    </div>
                                    <div class="col-md-6 col-xl-6">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Today  leads</span>
                                        <h2 class="mb-0">{{ $today_fb_leads }}</h2>
                                    </div>
                                    </div>

                                    <!--<div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Facebook leads</span>
                                        <h2 class="mb-0">{{ $facebook_leds }}</h2>
                                    </div>-->
                                    {{--<div class="align-self-center">
                                        <div id="today-new-customer-chart" class="apex-charts"></div>
                                        <span class="text-success font-weight-bold font-size-13"><i
                                                class='uil uil-arrow-up'></i> 25.16%</span>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">
                                        <?php
                                            $api_key = Auth::user()->sms_api_key;
											$smsbal = 0;
                                            if (null !== $api_key) {
												
											$api_url = "http://webmsg.smsbharti.com/app/miscapi/".$api_key."/getBalance/true/";

                                            //Submit to server
                                            $credit_balance = file_get_contents($api_url);
											
                                            $bal= json_decode($credit_balance);
											
											
											if (null !== $bal) {
												foreach ($bal as $key => $value) {
													if ($value->ROUTE == "OPremiumAPI") {
														$smsbal = $value->BALANCE;
													}
												}
											}
											}
											//dd($bal);
											//echo $smsbal; die;
                                            //json_decode($credit_balance);
                                        ?>
                                            SMS Credit</span>
                                        <!--<h2 class="mb-0">{{ @$bal[3]->BALANCE }}</h2>-->
										<h2 class="mb-0">{{ @$smsbal }}</h2>
                                    </div>
                                    {{--<div class="align-self-center">
                                        <div id="today-new-customer-chart" class="apex-charts"></div>
                                        <span class="text-success font-weight-bold font-size-13"><i
                                                class='uil uil-arrow-up'></i> 25.16%</span>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>

					<?php

					   
						$api_key = Auth::user()->email_api_key;
						$bal = 0;
						
						if (null !== $api_key) {

							$api_url = "https://api.elasticemail.com/v2/account/load?apikey=".$api_key;

							//Submit to server
							$credit_balance = file_get_contents($api_url);

							$bal= json_decode($credit_balance);
						}
						//dd($bal);
						//echo $smsbal; die;
						//json_decode($credit_balance);
					?>

                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Email Credit</span>
                                       <h2 class="mb-0">{{ null !== @$bal->data->emailcredits ? $bal->data->emailcredits : 0}}</h2>
                                    </div>
                                    {{--<div class="align-self-center">
                                        <div id="today-new-customer-chart" class="apex-charts"></div>
                                        <span class="text-success font-weight-bold font-size-13"><i
                                                class='uil uil-arrow-up'></i> 25.16%</span>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-4">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="media p-3">
                                    <div class="media-body">
                                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Whats App Credit</span>
                                        <h2 class="mb-0">{{ whatsappcounter() }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					
					<div class="col-md-6 col-xl-4">
						<div class="card">
							<div class="card-body p-0">
								<div class="media p-3">
									<div class="media-body">
									<button type="button" onclick="loadScanner()" style="display:none">Refresh </button>
										<span class="text-muted text-uppercase font-size-12 font-weight-bold">Whats App QR Code Scanner</span>
										<h2 class="mb-0">
											
												<img id = "whatsapp-scanner" src="{{ whatsappscanner() }}" width='160' height='160'>
										</h2>
										<?php /* ?><h2 class="mb-0">
											<img src="{{ whatsappscanner() }}" width='160' height='160'>
										</h2><?php */ ?>
									</div>
								</div>
							</div>
						</div>
					</div>


					<div class="col-md-6 col-xl-4">
						<div class="card">
							<div class="card-body p-0">
								<div class="media">
									<div class="media-body">
										<span class="text-muted text-uppercase font-size-12 font-weight-bold">Team Performance</span>
										<div class="view">
											<div class="wrapper">
                                            <div class="table-responsive">
												<?php 
													if (count($leadsReport) > 0) {
														$i = 0;
												?>	<table class="table table-striped ">
													  <thead>
														<tr>
														  <th class="sticky-col first-col">Sl.no</th>
														  <th class="sticky-col second-col">Assignee</th>
														  <th>Leads Assigned</th>
														  <th>Active Leads </th>
														  <th>Hot Leads </th>
														  <th>In Progress</th>
														  <th>Closed </th>
														  <th>Visit Done</th>
														  <th>Fake Lead</th>
														  <th>Out of Location</th>
														  <th>Not Interested</th>
														  <th>Wrong No</th>
														  <th>Not Reachable</th>
														</tr>
													  </thead>
													  <tbody>
												  <?php 
												   foreach($leadsReport as $key => $value) {
													   
												   ?>
													<tr>
														<td class="sticky-col first-col">{{++$i}}</td>
														<td class="sticky-col second-col">{{ucwords($value['name'])}}</td>
														<td>{{isset($value['assignedLeads']) ? $value['assignedLeads'] : 
0}}</td>
														<td>{{isset($value['notActiveLeads']) ? $value['assignedLeads'] : 
0}}</td>
														<td>{{isset($value['hotLeads']) ? $value['hotLeads'] : 0}}</td>
														<td>{{isset($value['progressLeads']) ? $value['progressLeads'] : 
0}}</td>
														<td>{{isset($value['closedLeads']) ? $value['closedLeads'] : 0}}</td>
														<td>{{isset($value['siteVisitLeads']) ? $value['siteVisitLeads'] : 
0}}</td>
														<td>{{isset($value['fakeLeads']) ? $value['fakeLeads'] : 0}}</td>
														<td>{{isset($value['outOfLocationLeads']) ? $value['outOfLocationLeads'] 
: 0}}</td>
														<td>{{isset($value['notInterestedLeads']) ? $value['notInterestedLeads'] 
: 0}}</td>
														<td>{{isset($value['wrongNoLeads']) ? $value['wrongNoLeads'] : 0}}</td>
														<td>{{isset($value['notReachableLeads']) ? $value['notReachableLeads'] : 
0}}</td>
													</tr>
												   <?php } ?>
												  
												  </tbody>
												</table>
                                                
												<?php
												} else {
													  echo "No Records Found";
												  }
												   ?>
                                                   </div>
											    </div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Share the App</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">

                                <span class="text-muted text-uppercase font-size-12 font-weight-bold my-5">Spread the word</span>
                                
                               
        
                            <p class="mt-3 mb-3">Copy the link or scan the QR code to share the app with your friends:</p>
                           
                           <div class="input-group mb-3">
                              <input type="text" id="refLink" class="form-control" value="http://realauto.in?ref={{Auth::user()->referral_code}}" readonly>
                              <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard()">Copy</button>
                              <div class="custom-alert" id="copyAlert">Copied Successfully!</div>
                            </div>
                             <div class="mt-3 mb-2">
                                 <a href="https://www.facebook.com/sharer/sharer.php?u=https://realauto.in?ref={{Auth::user()->referral_code}}" class="btn btn-primary btn-sm p2" target="_blank">
                                    <i class="uil uil-facebook share-icons"></i>
                                  </a>
                                  <a href="https://twitter.com/intent/tweet?url=https://realauto.in?ref={{Auth::user()->referral_code}}" class="btn btn-primary  btn-sm p2" target="_blank">
                                    <i class="uil uil-twitter-alt share-icons"></i>
                                  </a>
                                  <a href="https://www.linkedin.com/shareArticle?mini=true&url=https://realauto.in?ref={{Auth::user()->referral_code}}" class="btn btn-primary  btn-sm p2" target="_blank">
                                    <i class="uil uil-linkedin share-icons"></i>
                                  </a>
                                  <a href="https://telegram.me/share/url?url=https://realauto.in?ref={{Auth::user()->referral_code}}" class="btn btn-primary btn-sm p2" target="_blank">
                                    <i class="uil uil-telegram-alt share-icons"></i>
                                  </a>
                                  <a href="https://www.instagram.com/share?url=https://realauto.in?ref={{Auth::user()->referral_code}}" class="btn btn-primary btn-sm p2" target="_blank">
                                    <i class="uil uil-instagram share-icons"></i>
                                  </a>
                                </div>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?data=http://realauto.in?ref={{Auth::user()->referral_code}}" alt="QR Code" class="img-fluid">
      
                            </div>
                        </div>
						</div>
			
						<div class="col-md-6 col-xl-4">
							<div class="card">
								<div class="card-body p-0">
									<div class="media p-3">
										<div class="media-body">
<span class="text-muted text-uppercase font-size-12 font-weight-bold">Leads Overview</span>

											<div id="funnel"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

@endif
</div>

<div class="row">
<div class="col-xl-8 col-lg-8 mb-4"> 
          
          <!-- Project Card Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Leads Overview </h6>
            </div>
            <div class="card-body">
              <div id="funnel"></div>
            </div>
          </div>
        </div>
</div>


</div> <!-- content -->
   
<script>

function performAction(action) {

    $.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  })

  actionShow = "LOGOFF";
  if (action == "logoff") {
    actionShow =  "RESTART"
  }
  var sure = confirm("Are you sure want to "+ actionShow + " ?" );
  
  if (sure) {

    var formData = new FormData();

    formData.append('action', action);

    $.ajax({
      type:'POST',
      url: "{{ url('whatsappchannel-action')}}",
      data: formData,
      cache:false,
      contentType: false,
      processData: false,
      success: (data) => {
        console.log(data)

swal("Success!",data['message'], "success");
        
        //alert("Leads deleted successfully")
        
      },
      error: function(data){
          console.log(data);
      }
    })
  }
  
}

$(document).ready(function(){
  $("#myModal").modal("show");
  
})
function showVideo() {
	$("#myModal").modal("show");
}
    var todayDate = new Date();
    var month = todayDate.getMonth() +1; 
    var year = todayDate.getUTCFullYear(); 
    var tdate = todayDate.getDate(); 
    if(month < 10){
    month = "0" + month 
    }
    if(tdate < 10){
    tdate = "0" + tdate;
    }
    var maxDate = year + "-" + month + "-" + tdate;
  //  document.getElementById("demo").setAttribute("min", maxDate);
    console.log(maxDate);
    //time

    var time = new Date();
    var houre = time.getHours();
    var minutes = time.getMinutes();
    var second = time.getSeconds();
    var td = houre + ':' + minutes;
    function  myTime(){
    var x = document.getElementById('daytime').value;
    //alert(x);
    if(x >= td ){
       return true;
    }
    else{
        alert('please select future time');
        return false;
    }
    }
    console.log(time)
;

    function validatetime() {
        if(myTime()){
            return true;
        }
        return false;
    }
	
</script>
<script src="{{ asset('/assets/libs/d3-funnel/d3.min.js') }}"></script>
<script src="{{ asset('/assets/libs/d3-funnel/d3-funnel.js?1') }}"></script>
		<script>
			$(function() {
				function drawChart()
				{
					var index = "curved";

					var data = [];
					if (index !== "color") {
						data = [
							["Total Leads ", <?php echo $assigned_leads; ?>],
							["Not Attended ", <?php echo $notAttended; ?>],						
							["Hot Leads ", <?php echo $hotLeadsNo; ?>],
							["In Progress ", <?php echo $progressCount  ; ?>],							
							["Closed ", <?php echo $closedLeadsNo; ?>],
							["Out of Location ", <?php echo $outOfLocationLeadsNo; ?>],
							["Fake Leads ", <?php echo $fakeLeadsNo; ?>],
							["Not Interested ", <?php echo $notInterestedLeadsNo; ?>],
							["Not Reachable", <?php echo $notReachableLeadsNo; ?>],							
							["Wrong No ", <?php echo $wrongNoLeadsNo; ?>],
							["Site Visit  ", <?php echo $siteVisitNo; ?>],
							
						];
					} else {
						data = [
							["Teal",      12000, "#008080"],
							["Byzantium", 4000,  "#702963"],
							["Persimmon", 2500,  "#ff634d"],
							["Azure",     1500,  "#007fff"]
						];
					}

					var options = {
						"basic": {},
						"curved": {
							isCurved: true
						},
						"pinch": {
							bottomPinch: 1
						},
						"gradient": {
							fillType: "gradient"
						},
						"inverted": {
							isInverted: true
						},
						"hover": {
							hoverEffects: true
						},
						"dynamic": {
							dynamicArea: true
						},
						"label": {
							label: {
								fontSize: "16px"
							}
						},
						"color": {},
						"works": {
							isCurved: true,
							bottomPinch: 2,
							fillType: "gradient",
							hoverEffects: false,
							dynamicArea: false
						}
					};

					var chart = new D3Funnel("#funnel");

					// Reverse the dataset if the isInverted option is present
					// Keep in mind that because the larger component has shorter
					// width, it must compensate with a much larger height!
					//if ("isInverted" in options[index]) {
					//	chart.draw(data.reverse(), options[index]);
					// Otherwise, just use the regular data
					//} else {
						chart.draw(data, options[index]);
					//}
				}

				drawChart();
				$("#picker").change(function() {
					drawChart();
				});
			});
		</script>


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
<script>
setInterval("loadScanner()", 10000);


function loadScanner() {
	//var url = "<?php echo URL::to('/'); ?>"
	//document.getElementById("whatsapp-scanner").src=url+"assets/images/dummyqr.png";
/*	
	$.ajaxSetup({
		headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	})
*/
	var formData = new FormData();

	//formData.append('_token', {{ csrf_token() }});

	$.ajax({
		type:'POST',
		url: "{{ url('load-scanner')}}",
		data: formData,
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		cache:false,
		contentType: false,
		processData: false,
		success: (data) => {
			console.log(data)
			document.getElementById("whatsapp-scanner").src=data;
			//alert("Leads deleted successfully")
			
		},
		error: function(data){
				console.log(data);
		}
	})
	
}
</script>
@endsection

