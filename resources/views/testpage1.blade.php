@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        .view {
            /*margin: auto;*/
            width: 100%;
            background-color: white;
            max-width: 50%;
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
            left: 0px;
        }

    .custom-alert {
      display: none;
      position: absolute;
      top: -40px;
      left: 50%;
      transform: translateX(-50%);
      padding: 5px 10px;
      background-color: #28a745;
      color: white;
      border-radius: 4px;
      z-index: 100;
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
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary" style="margin-left: 55px !important;">Whatsapp
                                    QR Code Scanner</h6>

                            </div>
                            <!-- Card Body -->
                            <div class="card-body">

                                <h2 class="mb-0"> <img id="whatsapp-scanner" src="{{ whatsappscanner() }}" width='160'
                                        height='160' style="margin-left: 61px !important;"> </h2>

                                <br />
                                <button type="button" onclick="performAction('logoff')"> Restart </button> <button
                                    type="button" onclick="performAction('reset')"> Log Off </button>

                            </div>
                        </div>
                    </div>
                    <!-- Content Column -->
                    <div class="col-lg-8 mb-4">
                        <div class="row">
                            <div class="col-md-6 col-xl-4">
                                <div class="card">
                                    <div class="card-body p-0" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                                        <div class="media p-4" style="padding: 0.5rem!important;">
                                            <div class="media-body">
                                                <h1 class="text-muted text-uppercase font-size-12 font-weight-bold"
                                                    style="font-size: 14px;font-size: 14px !important;text-align: center;color: #e96100 !important;">
                                                    Total Leads</h1>
                                                <p style="font-size: 30px;text-align: center;line-height: 1.2 !important;font-weight: 700;margin-bottom: 7px;"
                                                    class="total">{{ $total_leds }}</p>
                                                <span style="color:#0c9ead;margin-left: 45%;" class="today"><i
                                                        class='uil uil-arrow-down'></i> Today <span class="mb-0"
                                                        style="color:#000 !important;font-weight: 600 !important;font-size: 15px !important;"">
                                                        {{ $today_total_leads }}</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="card">
                                    <div class="card-body p-0" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                                        <div class="media p-4" style="padding: 0.5rem!important;">
                                            <div class="media-body">
                                                <h1 class="text-muted text-uppercase font-size-12 font-weight-bold"
                                                    style="font-size: 14px;font-size: 14px !important;text-align: center;color: #02c2ed !important;;">
                                                    Total Upload Leads</h1>
                                                <p style="font-size: 30px;text-align: center;line-height: 1.2 !important;font-weight: 700;margin-bottom: 7px;"
                                                    class="total">{{ $uploads_leds }}</p>
                                                <span style="color:#cf15b8;margin-left: 45%;" class="today"><i
                                                        class='uil uil-arrow-down'></i> Today <span class="mb-0"
                                                        style="color:#000 !important;font-weight: 600 !important;font-size: 15px !important;"">
                                                        {{ $today_upload_leads }}</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <div class="card">
                                    <div class="card-body p-0" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                                        <div class="media p-4" style="padding: 0.5rem!important;">
                                            <div class="media-body">
                                                <h1 class="text-muted text-uppercase font-size-12 font-weight-bold"
                                                    style="font-size: 14px;font-size: 14px !important;text-align: center;color:#0f59b5 !important;;">
                                                    Total FaceBook Leads</h1>
                                                <p style="font-size: 30px;text-align: center;line-height: 1.2 !important;font-weight: 700;margin-bottom: 7px;"
                                                    class="total">{{ $facebook_leds }}</p>
                                                <span style="color:#1ce1ac;margin-left: 45%;" class="today"><i
                                                        class='uil uil-arrow-down'></i> Today <span class="mb-0"
                                                        style="color:#000 !important;font-weight: 600 !important;font-size: 15px !important;">
                                                        {{ $today_fb_leads }}</span></span>
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
                                            <div class="col mr-2" style="text-align:center">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    SMS Credit</div>
                                                <?php
                                                
                                                $api_key = Auth::user()->sms_api_key;
                                                $smsbal = 0;
                                                
                                                if (null !== $api_key) {
                                                    $api_url = 'http://webmsg.smsbharti.com/app/miscapi/' . $api_key . '/getBalance/true/';
                                                
                                                    //Submit to server
                                                    $credit_balance = file_get_contents($api_url);
                                                
                                                    $bal = json_decode($credit_balance);
                                                
                                                    if (null !== $bal) {
                                                        foreach ($bal as $key => $value) {
                                                            if ($value->ROUTE == 'OPremiumAPI') {
                                                                $smsbal = $value->BALANCE;
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                ?>


                                                <div class="mb-0 font-weight-bold text-gray-800"
                                                    style="font-size: 28px !important;height: 23px !important;">
                                                    {{ @$smsbal }}</div>
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
                                            <div class="col mr-2" style="text-align:center">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Whatsapp Credit</div>

                                                <div class="mb-0 font-weight-bold text-gray-800"
                                                    style="font-size: 28px !important;height: 23px !important;">
                                                    {{ whatsappcounter() }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6 col-xl-4">
                                <div class="card">
                                    <div class="card-body p-0"
                                        style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;padding: 9px !important;">
                                        <div class="media p-4" style="padding: 0.5rem!important;">
                                            <div class="media-body">
                                                <h1 class="text-muted text-uppercase font-size-12 font-weight-bold"
                                                    style="font-size: 14px;font-size: 14px !important;text-align:
                center;color: #02c2ed !important;;">
                                                    Website Leads</h1>
                                                <p style="font-size: 30px;text-align: center;line-height: 1.2 !important;font-weight: 700;margin-bottom: 7px;"
                                                    class="total">{{ $website_leds }}</p>
                                                <span style="color:#cf15b8;margin-left: 45%;" class="today"><i
                                                        class='uil uil-arrow-down'></i> Today
                                                    <span class="mb-0"
                                                        style="color:#000 !important;font-weight: 600 !important;font-size: 15px !important;"">{{ $today_website_leads }}</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!--<div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body" style="padding: 17px !important;padding-left: 20px !important;">
                <div class="row no-gutters align-items-center">
                <div class="col mr-2" style="text-align:center">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Email Credit</div>
                <?php
                
                $api_key = Auth::user()->email_api_key;
                $bal = 0;
                
                if (null !== $api_key) {
                    $api_url = 'https://api.elasticemail.com/v2/account/load?apikey=' . $api_key;
                    $bal = null;
                }
                
                ?>

                <div class="h5 mb-0 font-weight-bold text-gray-800" style="font-size: 28px !important;height: 23px !important;">{{ null !== @$bal->data->emailcredits ? $bal->data->emailcredits : 0 }}</div>
                </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>-->
                        </div>
                    </div>
                </div>

                <!-- Content Row -->

                <div class="row">
                    <!-- Content Column -->
                    <div class="col-xl-4 col-lg-4 mb-4">

                        <!-- Project Card Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Leads Overview ( Total -
                                    {{ $total_leds }})</h6>
                            </div>
                            <div class="card-body">
                                <div id="funnel"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Team Performance</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">

                                <span class="text-muted text-uppercase font-size-12 font-weight-bold">Leads Report</span>
                                <div id="team-loader">
                                    <img src="https://i.pinimg.com/originals/d7/34/49/d73449313ecedb997822efecd1ee3eac.gif"
                                        width="100%" height="100%">
                                </div>

                                <div class="wrapper" id="team" style="display:none">

                                    <div class="table-responsive">
                                        <table class="table table-striped " id="itemList">
                                            <thead id="itemHead">
                                                <tr>
                                                    <th class="sticky-col first-col">Sl.no</th>
                                                    <th class="sticky-col second-col">Assignee</th>
                                                    <th>Leads Assigned</th>
                                                    <th>Today Assigned</th>
                                                    <th>Overdue Leads </th>
                                                    <th>Hot Leads </th>
                                                    <th>In Progress</th>
                                                    <th>Closed </th>
                                                    <th>Site Visit</th>
                                                    <th>Fake Lead</th>
                                                    <th>Out of Location</th>
                                                    <th>Not Interested</th>
                                                    <th>Wrong No</th>
                                                    <th>Not Reachable</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
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
                </div>

                <!--end-->
            </div>
        </div>
    </div>
    <script>
        setInterval("loadScanner()", 10000);

          function copyToClipboard() {
      const refLink = document.getElementById("refLink");
      refLink.select();
      document.execCommand("copy");
      
      const copyAlert = document.getElementById("copyAlert");
      copyAlert.style.display = "block";
      setTimeout(function() {
        copyAlert.style.display = "none";
      }, 2000);
    }
        function performAction(action) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            actionShow = "LOGOFF";
            if (action == "logoff") {
                actionShow = "RESTART"
            }
            var sure = confirm("Are you sure want to " + actionShow + " ?");

            if (sure) {

                var formData = new FormData();

                formData.append('action', action);

                $.ajax({
                    type: 'POST',
                    url: "{{ url('whatsappchannel-action') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        console.log(data)

                        swal("Success!", data['message'], "success");

                        //alert("Leads deleted successfully")

                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            }

        }

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
                type: 'POST',
                url: "{{ url('load-scanner') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    console.log(data)
                    document.getElementById("whatsapp-scanner").src = data;
                    //alert("Leads deleted successfully")

                },
                error: function(data) {
                    console.log(data);
                }
            })

        }
    </script>
    <script src="{{ asset('/assets/libs/d3-funnel/d3.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/d3-funnel/d3-funnel.js?1') }}"></script>
    <script>
        $(function() {
            function drawChart() {
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
                        ["Teal", 12000, "#008080"],
                        ["Byzantium", 4000, "#702963"],
                        ["Persimmon", 2500, "#ff634d"],
                        ["Azure", 1500, "#007fff"]
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
        $.ajax({
            type: 'GET',
            url: "{{ url('leads-details') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                console.log('d', data)

                var rows = "";
                var i = 0;

                $.each(data, function() {
                    // console.log(this.name)
                    i++;
                    rows += "<tr><td class='sticky-col first-col'>" + i +
                        "</td><td class='sticky-col second-col'>" + this.name.toUpperCase() +
                        "</td><td>" + this.assignedLeads + "</td><td>" + this.todayAssigned +
                        "</td><td>" + this.unattendedLeads + "</td><td>" + this.hotLeads + "</td><td>" +
                        this.progressLeads + "</td><td>" + this.closedLeads + "</td><td>" + this
                        .siteVisitLeads + "</td><td>" + this.fakeLeads + "</td><td>" + this
                        .outOfLocationLeads + "</td><td>" + this.notInterestedLeads + "</td><td>" + this
                        .wrongNoLeads + "</td><td>" + this.notReachableLeads + "</td></tr>";
                });

                if (i > 0) {
                    $(rows).appendTo("#itemList tbody");
                } else {
                    document.getElementById("itemHead").style.display = "none";
                    var rows1 = "<p> No Records Found </p>";
                    $(rows1).appendTo("#itemList tbody");
                }

                document.getElementById("team-loader").style.display = "none";
                document.getElementById("team").style.display = "block";

            },
            error: function(data) {
                console.log(data);
            }
        })
    </script>
@endsection
