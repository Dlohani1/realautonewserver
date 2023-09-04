@extends('layouts.app')
@section('title', 'Lead Settings')
@section('content')

<style>

.view {
  /*margin: auto;*/
  width: 100%;
  background-color: white;
  max-width:50%;
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
  <div class="content-page">
        <div class="content">
            <!-- Start Content--> 
            <div class="container-fluid">
			<!-- start -->
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
                    </div>
					
						<div class="row">
						<!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Whatsapp QR Code Scanner</h6>
                                    <!--<div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>-->
                                </div>
                                <!-- Card Body -->
								<div class="card-body">
                                    <!--<div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Direct
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Social
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Referral
                                        </span>
                                    </div>-->
									<h2 class="mb-0">
										<img id = "whatsapp-scanner" src="{{ whatsappscanner() }}" width='160' height='160'>
									</h2>
                                </div>
								
                            </div>
                        </div>
						<!-- Content Column -->
                        <div class="col-lg-8 mb-4">
						
						<div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Leads</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_leds }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						 <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Uploaded Leads</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $uploads_leds }}</div>
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
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Facebook Leads</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $facebook_leds }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
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
                                                                                        //dd($bal);
                                                                                        //echo $smsbal; die;
                                            //json_decode($credit_balance);
                                        ?>
                                            
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ @$smsbal }}</div>
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
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Whatsapp Credit</div>
												
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ whatsappcounter() }}</div>
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
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Email Credit</div>
												<?php

					   
						$api_key = Auth::user()->email_api_key;
						$bal = 0;
						
						if (null !== $api_key) {

							$api_url = "https://api.elasticemail.com/v2/account/load?apikey=".$api_key;

							//Submit to server
							//uncomment below
							//$credit_balance = file_get_contents($api_url);

							//$bal= json_decode($credit_balance);
							//uncomment above
						$bal = null;
						}
						//dd($bal);
						//echo $smsbal; die;
						//json_decode($credit_balance);
					?>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ null !== @$bal->data->emailcredits ? $bal->data->emailcredits : 0}}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						
						 
					</div>
					
                            <!-- Project Card Example -->
                           <!-- <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                                </div>
				<div class="card-body">

				<div id="funnel"></div>
				</div>
                                <!-- <div class="card-body">
                                    <h4 class="small font-weight-bold">Server Migration <span
                                            class="float-right">20%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Sales Tracking <span
                                            class="float-right">40%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Customer Database <span
                                            class="float-right">60%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: 60%"
                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Payout Details <span
                                            class="float-right">80%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Account Setup <span
                                            class="float-right">Complete!</span></h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>  -->                         
                        </div>
						
					</div>
						
					

                    
                    <!-- Content Row -->
					
					<div class="row">
						<!-- Content Column -->
                        <div class="col-xl-4 col-lg-4 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Leads Overview ( Total - {{$total_leds}})</h6>
                                </div>
<div class="card-body"><div id="funnel"></div></div>
                                <!-- <div class="card-body">
                                    <h4 class="small font-weight-bold">Closed Leads<span
                                            class="float-right">{{number_format((float)(($closed_leds/$total_leds)*100), 2, '.','' )}}%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{number_format((float)(($closed_leds/$total_leds)*100), 2, '.','' )}}%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Hot Leads <span
                                            class="float-right">{{number_format((float)(($hot_leds/$total_leds)*100), 2, '.','' )}}%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ number_format((float)(($hot_leds/$total_leds)*100), 2, '.','' )}}%"
                                            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">In Progress <span
                                            class="float-right">{{number_format((float)(($progress_leds/$total_leds)*100), 2, '.','' )}}%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar" role="progressbar" style="width: {{number_format((float)(($progress_leds/$total_leds)*100), 2, '.','' )}}%"
                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <h4 class="small font-weight-bold">Site Visit Done <span
                                            class="float-right">{{number_format((float)(($visited_leds/$total_leds)*100), 2, '.','' )}}%</span></h4>
                                    <div class="progress mb-4">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{number_format((float)(($visited_leds/$total_leds)*100), 2, '.','' )}}%"
                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                            
									 <h4 class="small font-weight-bold">Out of Location<span
                                            class="float-right">{{number_format((float)(($outoflocation_leds/$total_leds)*100), 2, '.','' )}}%</span></h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{number_format((float)(($outoflocation_leds/$total_leds)*100), 2, '.','' 
)}}%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
									 <h4 class="small font-weight-bold">Wrong No<span
                                            class="float-right">{{number_format((float)(($wrongno_leds/$total_leds)*100), 2, '.','' )}}%</span></h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{number_format((float)(($wrongno_leds/$total_leds)*100), 2, '.','' )}}%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
									 <h4 class="small font-weight-bold">Not Reachable<span
                                            class="float-right">{{number_format((float)(($notreachable_leds/$total_leds)*100), 2, '.','' )}}%</span></h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{number_format((float)(($notreachable_leds/$total_leds)*100), 2, '.','' 
)}}%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
									 <h4 class="small font-weight-bold">Not Interested<span
                                            class="float-right">{{number_format((float)(($notinterested_leds/$total_leds)*100), 2, '.','' )}}%</span></h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{number_format((float)(($notinterested_leds/$total_leds)*100), 2, '.','' 
)}}%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
									 <h4 class="small font-weight-bold">Fake Leads<span
                                            class="float-right">{{number_format((float)(($fake_leds/$total_leds)*100), 2, '.','' )}}%</span></h4>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{number_format((float)(($fake_leds/$total_leds)*100), 2, '.','' )}}%"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>	-->
                            </div>                           
                        </div>
						
						<div class="col-xl-8 col-lg-8">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Team Performance</h6>
                                    
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
								<div class="media p-3">
									<div class="media-body">
										<span class="text-muted text-uppercase font-size-12 font-weight-bold">Leads Report</span>
										<div class="view">
											<div class="wrapper">
                                  <?php 
													if (count($leadsReport) > 0) {
														$i = 0;
												?>	<table class="table table-striped ">
													  <thead>
														<tr>
														  <th class="sticky-col first-col">Sl.no</th>
														  <th class="sticky-col second-col">Assignee</th>
														  <th>Leads Assigned</th>
														  <th>Overdue Leads </th>
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
														<td>{{isset($value['assignedLeads']) ? $value['assignedLeads'] : 0}}</td>
														<td>{{isset($value['unattendedLeads']) ? $value['unattendedLeads'] : 0}}</td>
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
												   </div></div></div></div>
                                </div>
                            </div>
                        </div>
						
						
                    </div>
					
                    
                     

				
<!--end-->				
			</div>
		</div>
	</div>
	
	<script>
setInterval("loadScanner()", 10000);


function loadScanner() {
	//var url = "<?php echo URL::to('/'); ?>"
	//document.getElementById("whatsapp-scanner").src=url+"assets/images/dummyqr.png";
	
	$.ajaxSetup({
		headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	})

	var formData = new FormData();

	//formData.append('leadIds', list);

	$.ajax({
		type:'POST',
		url: "{{ url('load-scanner')}}",
		data: formData,
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
							["Total Leads ", <?php echo $total_leds; ?>],
							["Hot Leads ", <?php echo $hot_leds; ?>],
							["In Progress ", <?php echo $progress_leds; ?>],							
							["Closed ", <?php echo $closed_leds; ?>],
							["Out of Location ", <?php echo $outoflocation_leds; ?>],
							["Fake Leads ", <?php echo $fake_leds; ?>],
							["Not Interested ", <?php echo $notinterested_leds; ?>],
							["Not Reachable", <?php echo $notreachable_leds; ?>],							
							["Wrong No ", <?php echo $wrongno_leds; ?>],
							["Visit Done ", <?php echo $visited_leds; ?>],
							
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

@endsection
