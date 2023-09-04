@extends('layouts.app')
@section('title', 'Import Self Leads')
@section('content')

<style>

.pricing-sect{background-color:#e5e9ea; padding:60px 0px;}

.monthly-package{
	 border-radius:10px; 
	 overflow:hidden;
	 background-color:#FFFFFF;
	 box-shadow: -1px -1px 5px 0px rgba(0,0,0,0.35);
-webkit-box-shadow: -1px -1px 5px 0px rgba(0,0,0,0.35);
-moz-box-shadow: -1px -1px 5px 0px rgba(0,0,0,0.35);
max-width: 425px;
padding-bottom: 30px;
}
.hed{
	background-color:#f47b20;
    color:#FFFFFF;
	text-transform:uppercase;
	padding:15px 0px;
	text-align:center;
	font-size: 22px;
}
ul.monthly-list{ margin:20px 20px; padding:0;}
ul.monthly-list li{ list-style-type:none; padding:6px 0px; color:#000;font-weight: 300;}
ul.monthly-list li i{ color:#f47b20; margin-right:7px;}

.amm-btn{ 
color:#fff;
font-weight: 600;
font-size: 22px; 
padding:10px 50px;
background: rgb(162,34,243);
background: linear-gradient(98deg, rgba(162,34,243,1) 0%, rgba(155,159,248,1) 100%); 
border-radius:40px;
width: max-content;
margin: 0px auto;
}
.hed-credit{
color:#535353;
font-size: 30px;
text-align:center; 
}
.hrd-line {
    width: 75px;
    height: 2px;
    background-color: #C3C3C3;
    margin: 10px auto;
}
.plan-box-1 {
    background-color: #a220f4;
	border-top-right-radius:10px;
	border-top-left-radius:10px;
	padding:15px 10px;
	color:#FFFFFF;
	margin: 35px 0px 0px 0px;
}
.plan-box-2 {
    background-color: #f47b20;
	border-top-right-radius:10px;
	border-top-left-radius:10px;
	padding:15px 10px; 
	color:#FFFFFF;
	margin: 35px 0px 0px 0px;
}

.am-box-left{ border-right:1px solid #FFFFFF;padding: 0px 30px;}
.am-box-left h4{color:#FFFFFF;font-size: 36px; margin-bottom:0px;}
.am-box-left p{color:#FFFFFF;font-size: 20px; margin-bottom:0px; font-weight:300;}
.am-box-right{ padding-left:30px;}
.am-box-right p{color:#FFFFFF;font-size: 22px; margin-bottom:0px; text-align:left; font-weight:300;}

.subscription-plan-sect{padding:50px 0px;}

.hed-plan{color:#584f50;font-size: 30px;font-weight:600;}
.hrd-line-or {
    width: 100px;
    height: 2px;
    background-color: #f47b20;
    margin: 10px auto;
}
.add-on-box{
box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.35);
-webkit-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.35);
-moz-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.35);
max-width: 425px;
border-radius:10px;
    margin-top: 30px;
}
.add-on-box-left{ width:80px;}

.add-on-box-right1{
    background-color: #a220f4;
	border-radius:10px;
	padding:15px;
	color:#FFFFFF;
	overflow:hidden;
	    text-align: left;
}
.add-on-box-right1 h4 {
    color: #FFFFFF;
    border-bottom: 1px solid;
    padding-bottom: 10px;
}
.add-on-box-right1 h4 span{font-size: 16px; font-weight:300;}
.add-on-box-right1 p{color:#FFFFFF; font-weight:300; margin-bottom:0;}
.add-on-box-right1 p i{}

.add-on-box-right2{
    background-color: #f47b20;
	border-radius:10px;
	padding:15px;
	color:#FFFFFF;
	overflow:hidden;
	    text-align: left;
}
.add-on-box-right2 h4 {
    color: #FFFFFF;
    border-bottom: 1px solid;
    padding-bottom: 10px;
}
.add-on-box-right2 h4 span{font-size: 16px; font-weight:300;}
.add-on-box-right2 p{color:#FFFFFF; font-weight:300; margin-bottom:0;}
.add-on-box-right2 p i{}

.bor-r-4{border-right: 4px solid #d7d7d7;}


.checkbox-xl {
    margin-top: -40px;
    margin-left: 25px;
}
.checkbox-xl .custom-control-label::before, 
.checkbox-xl .custom-control-label::after {
  top: 1.2rem;
  width: 2.5rem;
  height: 2.5rem;
}

.checkbox-xl .custom-control-label {
  padding-top: 23px;
  padding-left: 10px;
}

.summary-box{
	box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.35);
-webkit-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.35);
-moz-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.35);
padding:20px;
border-radius:10px;
min-height: 500px;
position:relative;
}
.summary-box h4{ margin-bottom:20px;}
.summary-box p{ font-weight:300;}
.summary-box p span{font-weight: 600;}

.tot-amm {
    position: absolute;
    bottom: 0;
    width: 90%;
    border-top: 1px solid #b3b3b3;
    padding-top: 10px;
}
.tot-amm p{font-weight: 600;}

.btn-pay {
    background-color: #80a90d;
    color: #FFFFFF;
    font-size: 24px;
    padding: 10px 50px;
    border-radius: 10px;
    width: inherit;
    margin-top: 30px;
}
.run {
    background-color: #80a90d;
    color: white;
    font-size: 12px;
    border-radius: 10px;
    width: fit-content;
    padding: 0px 15px;
}
.cance{color: #cf1002; cursor:pointer;font-size: 12px; margin:5px 0px;}

.subscription-plans-list-table{background-color: #e5e5e5;}
.subscription-plans-list-table tr th {
    background-color: #5a585d;
    color: #FFFFFF;
    padding: 10px;
    font-weight: 400;
}
.subscription-plans-list-table tr td{padding: 10px;}









@media (max-width: 767px){


}

@media (max-width: 375px){

}


@media (max-width: 326px){

}


@media (max-width: 320px){

}

#total-amt {
	font-size:10px;
}

#popup { 
  display: none; 
  border: 1px black solid;
  width: 50%; height: auto; 
  top:20px; left:20px;
  background-color: white;
  z-index: 10;
  padding: 2em;
  position: fixed;
 margin-top:10%;
margin-left:26%
}

.darken { background: rgba(0, 0, 0, 0.7); }
/*
#iframe {
border: 0;
width: 80%;
height: 500px;
top: 30px;
left: -48px;
background-color: white;
z-index: 10;
padding: 2em;
position: fixed;
margin-top: 10%;
margin-left: 20%;

}
*/

#iframe {
    border: 0;
    width: 50%;
    height: 500px;
    top: 20px;
    left: 20px;
    background-color: white;
    z-index: 10;
    padding: 2em;
    position: fixed;
    margin-top: 7%;
    margin-left: 26%;
}


@media (max-width:768px){
#iframe {
    border: 0;
    width: 80%;
    height: 500px;
    top: 30px;
    left: -48px;
    background-color: white;
    z-index: 10;
    padding: 2em;
    position: fixed;
    margin-top: 10%;
    margin-left: 20%;
}

}

@media (max-width:767px){
#iframe {
    width: 90%;
    height: 100%;
    padding: 1em;
    margin-top: 15%;
    margin-left: 0;
}

}
</style>

    <div class="content-page" style="overflow:scroll;height:500px" id="page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="row page-title">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb" class="float-right mt-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo url('/leads-master'); ?>"> Manage Leads </a></li>
                                <li class="breadcrumb-item active" aria-current="page">Leads</li>
                            </ol>
                        </nav>
                    </div>
                </div>
				<!--newsection -->
			  <section class="subscription-plan-sect">
    <div class=" container-fluid">
      <div class="row mb-4">
        <div class="col-md-12 text-center mb-4">
          <div class="hed-plan">Subscription Plan</div>
          <div class="hrd-line-or"></div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="monthly-package">
            <div class="hed">Monthly Package</div>
            <ul class="monthly-list">
              <li><i class="fa fa-check"></i> Unlimited User</li>
              <li><i class="fa fa-check"></i> Instant Welcome</li>
              <li><i class="fa fa-check"></i> SMS/Email/Whats-app</li>
              <li><i class="fa fa-check"></i> Inbuilt CRM</li>
              <li><i class="fa fa-check"></i> Facebook Integration</li>
              <li><i class="fa fa-check"></i> Google Integration</li>
              <li><i class="fa fa-check"></i> On-Boarding Call</li>
              <li><i class="fa fa-check"></i> Live Lead Status</li>
              <li><i class="fa fa-check"></i> Instant Lead Notiﬁcation</li>
              <li><i class="fa fa-check"></i> Lead Add & Upload Feature</li>
              <li><i class="fa fa-check"></i> Schedule Whats app Feature ( Must have credits )</li>
              <li><i class="fa fa-check"></i> Schedule SMS Feature</li>
              <li><i class="fa fa-check"></i> Schedule Email Feature</li>
              <li><i class="fa fa-check"></i> Daily Reporting</li>
            </ul>
            <!--<div class="amm-btn"><i class="fa fa-inr"></i> 999</div>-->
          </div>
        </div>
        <div class="col-md-4 text-center bor-r-4 mb-4">
          <div class="hed-credit" style="font-size:25px">Select Add-on</div>
          <div class="hrd-line"></div>
          
          <div class="add-on-box">
            <table class="w-100">
              <tr>
                <td>
                 <div class="add-on-box-left">
                  <div class="form-check custom-checkbox checkbox-xl">
                  <input type="radio" class="custom-control-input" id="checkbox-1" name="add-on" onclick="test(this)">
                  <label class="custom-control-label" for="checkbox-1"></label>
                </div>
                 </div>
                </td>
                <td>
                 <div class="add-on-box-right1">
                   <h4><i class="fa fa-inr"></i> $10 <span>Monthly</span></h4>
                   <p><i class="fa fa-caret-right"></i> 1,000 SMS Credits</p>
                   <p><i class="fa fa-caret-right"></i> 1,000 WhatsApp Credits</p>
                   <p><i class="fa fa-caret-right"></i> 1,000 Email Credits</p>
                 </div>
                </td>
              </tr>
            </table>
          </div>
          
          <div class="add-on-box">
            <table class="w-100">
              <tr>
                <td>
                 <div class="add-on-box-left">
                  <div class="form-check custom-checkbox checkbox-xl">
                  <input type="radio" class="custom-control-input" id="checkbox-2" name="add-on" onclick="test(this)">
                  <label class="custom-control-label" for="checkbox-2"></label>
                </div>
                 </div>
                </td>
                <td>
                 <div class="add-on-box-right2">
                   <h4><i class="fa fa-inr"></i> $27 <span>Monthly</span></h4>
                   <p><i class="fa fa-caret-right"></i> 4,000 SMS Credits</p>
                   <p><i class="fa fa-caret-right"></i> 4,000 WhatsApp Credits</p>
                   <p><i class="fa fa-caret-right"></i> 4,000 Email Credits</p>
                 </div>
                </td>
              </tr>
            </table>
          </div>
          
        </div>
        <div class="col-md-4 text-center">
          <div class="summary-box">
            <h4>SUMMARY</h4>
            <table class="w-100">
              <tr>
                <td><p style=" text-align:left;">Platform Charges</p></td>
                <td><p style="float:right;"><span><i class="fa fa-inr"></i>33/</span>Month</p></td>
              </tr>
              <tr id="add-on-list" style="visibility:hidden">
                <td><p style=" text-align:left;"><i class="fa fa-plus-square-o"></i> Add on </p></td>
                <td><p style="float:right;"><span><i class="fa fa-inr"></i><span id="add-on-value">17/</span></span>month</p></td>
              </tr>
            </table>
            <div class="tot-amm">
              <table class="w-100">
              <tr>
                <td><p style=" text-align:left;"><b>Total</b></p></td>
                <td><p style="float:right;"><span><i class="fa fa-inr"></i><span id="total-value">$33</span></span></p><p id="total-amt"></p></td>
              </tr>
            </table>
            </div>
          </div>
          <button class="btn btn-pay" type="button" onclick="subscribe()">Pay Now</button>
		  <input type="hidden" id="plan" />
        </div>
      </div>
      
     <div class="row mt-5">
        <div class="col-md-12">
           <h4 class="mb-4">Your Subscription Plans</h4>
           <table class="w-100 subscription-plans-list-table">
             <tr>
               <th>Expiration Date</th>
               <th>Particulars</th>
               <th>Status</th>
             </tr>
             <tr>
               <td>dd/mm/yyyy</td>
               <td>Starter Pack (Monthly Pack + $10 Credit Pack)</td>
               <td>
                 <div class="run">Active</div>
                 <div class="cance">Cancel Subscription</div>
               </td>
             </tr>
           </table>
        </div>
      </div>
    </div>
  </section>
  

				<!--end section -->
				
			</div>
		</div>
	


<div id="popup"> 
  
  <iframe id="iframe"></iframe>
  </div>
@endsection


<script>

document.getElementById("total-amt").innerHTML = "<i class='fa fa-inr'></i>"
document.getElementById("total-value").innerHTML = "$33";
document.getElementById("plan").value = "1";

function test(addOn) {
if (addOn.id == "checkbox-1") {

document.getElementById("add-on-value").innerHTML = "17/";
document.getElementById("add-on-list").style.visibility = "visible";

document.getElementById("total-amt").innerHTML = "<i class='fa fa-inr'></i>"
document.getElementById("total-value").innerHTML = "$50";
document.getElementById("plan").value = "2";
}
if (addOn.id == "checkbox-2") {
document.getElementById("add-on-value").innerHTML = "27/";
document.getElementById("add-on-list").style.visibility = "visible";
document.getElementById("total-amt").innerHTML = "<i class='fa fa-inr'></i>"
document.getElementById("total-value").innerHTML = "$60";
document.getElementById("plan").value = "3";
}
}

function subscribe() {
//var plan = document.getElementById("plan").value
//var baseUrl = "<?php echo URL::to('/'); ?>";
//window.open(baseUrl+"/subscribeTo/"+plan);

			$.ajax({
				url : "subscribeTo",
				type: "POST",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{
window.open(data);
//document.getElementById("popup").style.display = "block";
//document.getElementById('iframe').src = data;
/*  document.getElementById('page').className = "darken";
  setTimeout(function() { 
    document.getElementById('page').onclick = function() {
      document.getElementById("popup").style.display = "none";
      document.getElementById('page').className = "";
    }
  }, 100);
			
*/					console.log('data')
					//data - response from server
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
			 
				}
			});
}

</script>

