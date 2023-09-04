@extends('layouts.app')
@section('title', 'Leads Assigned')
@section('content')

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet" />
<style>
.container{max-width:1170px; margin:auto;}
img{ max-width:100%;}
.inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 40%; border-right:1px solid #c4c4c4;
}
.inbox_msg {
  border: 1px solid #c4c4c4;
  clear: both;
  overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 60%;
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
  color: #05728f;
  font-size: 21px;
  margin: auto;
}
.srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
}
.inbox_chat { height: 550px; overflow-y: scroll;}

.active_chat{ background:#ebebeb;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
  padding-left: 20px;
 }
 .received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 100%;}
.mesgs {
  float: left;
  padding: 30px 15px 0 25px;
  width: 100%;
}

 .sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  /*border: medium none;*/
  color: #4c4c4c;
  font-size: 15px;
  /*min-height: 48px;*/
  width: 100%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 300px;
  overflow-y: auto;
}

[data-letters]:before {
  content:attr(data-letters);
  display:inline-block;
  font-size:1em;
  width:2.5em;
  height:2.5em;
  line-height:2.5em;
  text-align:center;
  border-radius:50%;
  background:plum;
  vertical-align:middle;
  margin-right:1em;
  color:white;
  }

.nav-pills .nav-link {
	background-color: transparent;
	color: #6c757d;
	padding:10px
}
.write_msg{
	border: 1px solid #e2e7f1;
    border-radius: 0.3rem;
	width:100%;
}
.modal-content {
    margin-top: 0;
}
.call{background-color: #ff2009;
    border-radius: 50%;
    padding: 5px 9px;
    border: 0;
}
.whap{background-color: #02cf5f;
    border-radius: 50%;
    padding: 5px 9px;
    border: 0;
}
.mail {
    background-color: #5369f8;
    border-radius: 50%;
    padding: 5px 9px;
    border: 0;
}

.modal-header {
    padding: 0;
    border-bottom: 0px solid #e5e5e5;
    min-height: 0;
	display: inherit;
}
.modal-header .close {
    margin-top: 5px;
    background-color: black;
    width: 20px;
    height: 20px;
    border-radius: 50%;
	margin-right: 11px;
}
.modal-header .close span {
    margin-top: -11px;
    position: absolute;
    color: white;
    margin-left: -6px;
}
.nav-pills .nav-link {
    background-color: transparent;
    color: #6c757d;
    padding: 4px 6px;
    margin-bottom: 5px;
}

.add-background {

background-color: red;

}
</style>
    <div class="content-page">
                <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="row page-title">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb" class="float-right mt-1">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Assign Leads</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Assigned Leads Details</h4>
                    </div>
                </div>
				
                
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
				<form action="{{ route('subadmin-download') }}"  method="POST" onsubmit="return validateForm()" >
                   		 @csrf

				  From Date : <input type="date" name="from_date" id="from_date" /> &nbsp; &nbsp;
          			  To Date :&nbsp; <input type="date" name="to_date" id="to_date" /> &nbsp; &nbsp;
          			  <input type="submit" value="Download" />



				</form>
                                </div>
                            </div>
						</div>
					</div>	
							
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
								<!--<button type="button" class="btn btn-info" onclick="setLeadStatus(2)">Close</button>
								<button type="button" class="btn btn-primary" onclick="setLeadStatus(3)">Site Visit</button>
								<button type="button" class="btn btn-danger" onclick="setLeadStatus(4)">Fake Lead</button>
								<button type="button" class="btn btn-warning" onclick="setLeadStatus(6)">Not Interested</button>
								-->
								
  <!-- Nav pills -->
  <ul class="nav nav-pills" role="tablist" style="padding:10px">
    <li class="nav-item">
	    <button type="button" class="nav-link active btn btn-info" data-toggle="pill" onclick="filterData(-1)">All</button>
    </li> &nbsp;&nbsp;
	 <li class="nav-item">
      <button type="button"  class="nav-link btn btn-info" data-toggle="pill" onclick="filterData(1)">Hot</button>
    </li>&nbsp;&nbsp;
    <li class="nav-item">
      <button type="button"  class="nav-link btn btn-info" data-toggle="pill" onclick="filterData(2)">Closed</button>
    </li>&nbsp;&nbsp;
    <li class="nav-item">
      <button type="button"  class="nav-link btn btn-info" data-toggle="pill" onclick="filterData(3)">Site Visit</button>
    </li>&nbsp;&nbsp;
        <li class="nav-item">
      <button type="button"  class="nav-link btn btn-info" data-toggle="pill" onclick="filterData(0)">In Progress</button>
    </li>&nbsp;&nbsp;
 <li class="nav-item">
      <button type="button"  class="nav-link btn btn-info" data-toggle="pill" onclick="filterData(4)">Fake Lead</button>
    </li>&nbsp;&nbsp;

 <li class="nav-item">
      <button type="button"  class="nav-link btn btn-info" data-toggle="pill" onclick="filterData(6)">Not Interested</button>
    </li>&nbsp;&nbsp;

 <li class="nav-item">
      <button type="button"  class="nav-link btn btn-info" data-toggle="pill" onclick="filterData(7)">Wrong No</button>
    </li>&nbsp;&nbsp;
 <li class="nav-item">
      <button type="button"  class="nav-link btn btn-info" data-toggle="pill" onclick="filterData(8)">Not Reachable</button>
    </li>&nbsp;&nbsp;
 <li class="nav-item">
      <button type="button"  class="nav-link btn btn-info" data-toggle="pill" onclick="filterData(9)">Out of Budget</button>
    </li>&nbsp;&nbsp;

 <li class="nav-item">
      <button type="button"  class="nav-link btn btn-info" data-toggle="pill" onclick="filterData(5)">Out of Location</button>
    </li>&nbsp;&nbsp;

	<!--<li class="nav-item" style="padding-left: 40%;">
	<label>Bulk Leads </label>
	<select id="bulk-status" style="width:130px" onchange="setLeadStatus(this.value)" class="custom-select mb-2">
		<option>Select Status</option>
		<option value="0">In Progress</option>
		<option value="1">Hot</option>
		<option value="2">Close</option>
		<option value="3">Site Visit</option>
		<option value="4">Fake Lead</option>
		<option value="5">Out of Location</option>
		<option value="6">Not Interested</option>
		<option value="7">Wrong No</option>
		<option value="8">Not Reachable</option>
	</select>
</li> -->
  </ul>		
 
							</div>
                        </div>
						
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
                                <div class="table-responsive">
                                     <table class="table table-bordered " id="example">
                                        <thead>
                                            <tr>
												<th>Sl.no</th> 
												<th>Assigned On</th>
												<th>Project</th>
												<th>Name</th>
												<th>Mobile</th>
												<th>Email </th>
												<th>Status</th>
												<th>Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
                <!-- end row-->
				
            </div> <!-- container-fluid -->
        </div> <!-- content -->

<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closebtn">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row dis-n-md">
        <div class="col-md-12 p-3 text-center">
       <div class="" style="border-bottom: 1px solid #bbbbbb;
    padding-bottom: 20px;width: 50%;
    margin: 0px auto;">
<!--          <button class="btn btn-primary call" type="button"><i class="fa fa-phone"></i></button> &nbsp; 
          <button class="btn btn-primary whap" type="button"><i class="fa fa-whatsapp"></i></button> &nbsp; 
          <button class="btn btn-primary mail" type="button"><i class="fa fa-envelope-o"></i></button>
-->
<a id="call" href="#"><button class="btn btn-primary call" type="button"><i class="fa fa-phone"></i></button></a> &nbsp; 
<a id="wp" href="#"><button class="btn btn-primary whap" type="button"><i class="fa fa-whatsapp"></i></button></a> &nbsp; 
<a id="mail" href="#"><button class="btn btn-primary mail" type="button"><i class="fa fa-envelope-o"></i></button></a>
         </div>
        </div>
      </div>
	  <input type="hidden" id="leadId" />
		<div id="loader">
		<img src="https://files.readme.io/7802b3a-newprogress.gif" />
		<p style="padding-left:10px"> Loading Comments...</p>
		</div>
		<div class="mesgs">
          <div class="msg_history" id="msgHistory">

          </div>
          <div class="type_msg">
		  <div class="input_msg_write" style="padding-top:10px" >
					<fieldset>
					<legend>Follow Ups:</legend>
					<div class="row">
						<div class="col-md-4 form-group">					
							<label class="control-label" for="date">Status</label>
							<select id="lead-status"  class="custom-select">
								<option value="0">In Progress</option>
								<option value="1">Hot</option>
								<option value="2">Close</option>
								<option value="3">Site Visit</option>
								<option value="4">Fake Lead</option>
								<option value="5">Out of Location</option>
								<option value="6">Not Interested</option>
								<option value="7">Wrong No</option>
								<option value="8">Not Reachable</option>
								<option value="9">Out of Budget</option>

							</select>					
						</div>
						<div class="col-md-4 form-group">
							<label class="control-label" for="date">Date</label>
							<input type="date" id="followup-date" class="custom-select" name="followup-date">					
						</div>
						
						<div class="col-md-4 form-group">
							<label class="control-label" for="date">Time</label>
							<input type="time" id="followup-time" name="followup-time"  class="custom-select">					
						</div>
						
					</div>
					</fieldset>
					<fieldset>
					 <legend>Comments:</legend>
                     <div class="col-md-12 form-group p-0">
					<!-- <input type="text" class="write_msg" placeholder="Type a message" /> -->
					<textarea class="write_msg" placeholder="Type a message"  rows="3" id="comment"></textarea>
                    </div>
					<!--<button class="msg_send_btn" type="button" onclick="setComment()"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button> -->
                    <div class="col-md-12 form-group text-center">
					<button  id="save-followup" class="btn btn-primary" type="button" onclick="setComment()">Submit</button>
                    </div>
					</fieldset>
				</div>
            <!--<div class="input_msg_write" style="padding-top:10px">
              <!-- <input type="text" class="write_msg" placeholder="Type a message" /> -->
			  <!--<textarea class="write_msg" placeholder="Type a message"  onclick="setComment()" rows="5" style="width:360px;resize:none;"></textarea>
              <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
            </div> -->
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

  <!-- Modal -->
  <div class="modal fade" id="downloadModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Download Leads</h4>
        </div>
        <div class="modal-body">
          <form>
          From Date : <input type="date" name="from_date" id="from_date" /> <br/> <br/>
         
          To Date :&nbsp; <input type="date" name="to_date" id="to_date" /> <br/> <br/>
          <input type="submit" value="Download" />
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

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
	
	<script>
let list=new Array;	
 $(document).ready(function() {

			var t = $('#example').DataTable( {

 
			//bLengthChange: false,
			//searching: false,
			processing: true,
			serverSide: true,
			ajax: "{{url('assigned-leads-retrival')}}",
			columns: [
				{  data: null },
				{  data: null,
					render: function(data, type, row, meta) {
						//console.log('leads',data.lead_assigned_on)
if (null != data.lead_assigned_on) {

const assignedDateTime = data.lead_assigned_on.split(" ");
var assignedDate = assignedDateTime[0];
var assignedTime = assignedDateTime[1];

  const monthNames = ["Jan", "Feb", "March", "April", "May", "June","July", "Aug", "Sept", "Oct", "Nov", "Dec"];
                                                const d = new Date(assignedDate);
                                                var month = monthNames[d.getMonth()];
                                                var dateN = d.getDate();
                                                var dateY = d.getFullYear(); 
var adate = dateN+" "+month+","+dateY;

var da = new Date(assignedDate+"T"+assignedTime)

var atime = da.toLocaleString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })


                                                return adate+" at "+atime;
} else {
						return  data.lead_assigned_on
}
					}
				},
				{  data: "project_name"},
				                                { data: null,
                                        render: function(data, type, row, meta) {

                                                if (data.source == "Facebook") {
                                                    return data.name+' <img src="https://www.realauto.in/assets/images/fb.png"  width="20px" height="20px" />'
                                                } else if (data.source == "Google" ) {
                                                    return data.name+' <img src="https://www.realauto.in/assets/images/google-icon.png" width="30px" height="30px" />'
                                                }  else if (data.source == "GoogleAds_PPC") {
                                                    return data.name+' <img src="https://www.realauto.in/assets/images/googleppc.png" width="30px" height="30px" />'
                                                } else if (data.source == "FacebookTraffic") {
                                                    return data.name+' <img src="https://www.realauto.in/assets/images/fbtraffic.png" width="30px" height="30px" />'
                                                } else if (data.source == "Organic" || data.source == "Form HTML") {
                                                    return data.name+' <img src="https://www.realauto.in/assets/images/organic.png" width="30px" height="30px" />'
                                                } else {
                                                   return data.name
                                                }

                                        }
                                },

				                               

                         { data: null,
                                        render: function(data, type, row, meta) {
                                              return  data.mobile_no
                                        }
                                },
 { data: null,
                                        render: function(data, type, row, meta) {
                                              return data.mail_id 
                                        }
                                },


				{  data: null,
					render: function(data, type, row, meta) {
						
						var status = "<span style='color:red'>Not Attended</span>";
						
						if ((undefined !== data.isAttended && data.isAttended == "1") || data.lead_status != "0") {
							switch(data.lead_status) {
								case "1" : status = "<span style='color:red'>Hot</span>"
											break;
								case "2" : status = "<span style='color:orange'>Closed</span>"
										   break;
								case "3" : status = "<span style='color:green'>Site Visit</span>"
										   break;
								case "4" : status = "<span style='color:green'>Fake </span>"
										   break;
								  case "5" : status = "<span style='color:red'>Out of Location </span>"
                                                                           break;

                                                        case "6" : status = "<span style='color:green'>Not Interested</span>"
                                                                           break;

                                                        case "7" : status = "<span style='color:red'>Wrong No</span>"
                                                                           break;

                                                        case "8" : status = "<span style='color:red'>Not Reachable</span>"
                                                                           break;

							 case "9" : status = "<span style='color:red'>Out of Budget</span>"
                                                                           break;

								default : status = "<span style='color:blue'>In Progress</span>"
							}
						}
						return  status
					}
				},
				
				{  data: null,
					render: function(data, type, row, meta) {
						return '<button type="button" class="btn-sm" data-toggle="modal" data-target="#myModal2"  onclick="setleadId('+data.id+')" >Add/View </button>'
					}
				},
				
			],
			colReorder: true,
			
		});
		
		t.on( 'draw.dt', function () {
		var PageInfo = $('#example').DataTable().page.info();
			 t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			} );
		} );

		$('#example tbody').on( 'click', 'tr', function () {
			$(this).toggleClass('selected');

			var id = t.row( this ).data().id
			//console.log('id',id)
/*
			var mobno =  t.row( this ).data().mobile_no

                        var mailId =  t.row( this ).data().mail_id

                        document.getElementById("call").href = 'tel: +91'+mobno


                        document.getElementById("wp").href = 'https://api.whatsapp.com/send?phone=+91'+mobno


                        document.getElementById("mail").href = 'mailto:'+mailId

*/
			if (list.includes(id)) {
				list.pop(id)
			} else { 
				list.push(id);
			}
			document.getElementById("lead-ids").value = list;
			console.log(list)
		});
	});
		
   function filterData(status) {
		var t = $('#example').DataTable( {

 "createdRow": function( row, data, dataIndex ) {
   if ( data.lead_status == "1" ) {

    // $(row).addClass( 'add-background' );
//$( row ).find('td:eq(4)').attr('title','upgrade account to view').addClass('blur');
//$( row ).find('td:eq(5)').attr('title','upgrade account to view').addClass('blur');

    }
  },
      
		//bLengthChange: false,
		//searching: false,
		processing: true,
		serverSide: true,
		destroy: true,
		destroy: true,
		ajax: {
			url: "assigned-leads-retrival",
			data: function ( d ) {
				return $.extend( {}, d, {
					"status": status
				});
			}
		 },
		columns: [
			{  data: null },
			
					  {  data: null,
                                        render: function(data, type, row, meta) {
                                                //console.log('leads',data.lead_assigned_on)
if (null != data.lead_assigned_on) {

const assignedDateTime = data.lead_assigned_on.split(" ");
var assignedDate = assignedDateTime[0];
var assignedTime = assignedDateTime[1];

  const monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "Sept", "Oct", "Nov", "Dec"];
                                                const d = new Date(assignedDate);
                                                var month = monthNames[d.getMonth()];
                                                var dateN = d.getDate();
                                                var dateY = d.getFullYear();
var adate = dateN+" "+month+","+dateY;

var da = new Date(assignedDate+"T"+assignedTime)

var atime = da.toLocaleString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })


                                                return adate+" at "+atime;

                                                
return  data.lead_assigned_on
} else {
return  data.lead_assigned_on

}
                                        }
                        

				
			},
			{  data: "project_name"},
			{  data: "name" },
			{  data: "mobile_no" },
			{data : "mail_id"},
			{  data: null,
				render: function(data, type, row, meta) {
					
					var status = "<span style='color:red'>Not Attended</span>";
					if ((undefined !== data.isAttended && data.isAttended == "1") || data.lead_status != "0") {
						switch(data.lead_status) {

							case "1" : status = "<span style='color:red'>Hot</span>"
										break;
							case "2" : status = "<span style='color:orange'>Closed</span>"
									   break;
							case "3" : status = "<span style='color:green'>Site Visit</span>"
									   break;
							case "4" : status = "<span style='color:green'>Fake </span>"
									   break;
							case "5" : status = "<span style='color:red'>Out of Location </span>"
                                                                           break;

							case "6" : status = "<span style='color:green'>Not Interested</span>"
									   break;

 							case "7" : status = "<span style='color:red'>Wrong No</span>"
                                                                           break;

  							case "8" : status = "<span style='color:red'>Not Reachable</span>"
                                                                           break;
							 case "9" : status = "<span style='color:red'>Out of Budget</span>"
                                                                           break;

							default : status = "<span style='color:blue'>In Progress</span>"
						}
					}
					return  status
				}
			},
			{  data: null,
				render: function(data, type, row, meta) {
					return '<button type="button" class="btn-sm" data-toggle="modal" data-target="#myModal2"  onclick="setleadId('+data.id+')" >Add/View </button>'
				}
			},
			
		],
		colReorder: true,
		
	});
		
	t.on( 'draw.dt', function () {
	var PageInfo = $('#example').DataTable().page.info();
		 t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
			cell.innerHTML = i + 1 + PageInfo.start;
		} );
	} );

	$('#example tbody').on( 'click', 'tr', function () {
		$(this).toggleClass('selected');

		var id = t.row( this ).data().id
		console.log('id',id)
/*
		  	var mobno =  t.row( this ).data().mobile_no

                        var mailId =  t.row( this ).data().mail_id

                        document.getElementById("call").href = 'tel: +91'+mobno


                        document.getElementById("wp").href = 'https://api.whatsapp.com/send?phone=+91'+mobno


                        document.getElementById("mail").href = 'mailto:'+mailId


*/


		if (list.includes(id)) {
			list.pop(id)
		} else { 
			list.push(id);
		}
		document.getElementById("lead-ids").value = list;
		console.log(list)
	});
 }
 
	function setleadId(leadId) {
document.getElementById("save-followup").disabled = false
		
document.getElementById("leadId").value = leadId;
		document.getElementById("loader").style.display="block";
		$("#msgHistory").empty();
		var formData = {_token: "{{ csrf_token() }}",leadId:leadId}; //Array
		$.ajax({
			url : "get-lead-comments",
			type: "POST",
			data : formData,
			success: function(dataResult, textStatus, jqXHR)
			{
				document.getElementById("loader").style.display="none";
				var resultData = JSON.parse(dataResult)
				var leadStatus = resultData.status;

				var leadContact = resultData.leadContact
				var leadEmail = resultData.leadEmail


				var resultData = resultData.data;




				document.getElementById("call").href = 'tel: +91'+leadContact


                        	document.getElementById("wp").href = 'https://api.whatsapp.com/send?phone=+91'+leadContact


                        	document.getElementById("mail").href = 'mailto:'+leadEmail



				document.getElementById("lead-status").value = leadStatus;
				var userId = "<?php echo Auth::user()->id; ?>";
				
				var bodyData = '';
				var i=1;
				$.each(resultData,function(index,row){
					console.log('a',row)
					var comment_date = row.updated_at;
					
					var readable_date = new Date(comment_date).toDateString();
					const today = new Date().getDate();
					
					if (today == new Date(comment_date).getDate()) {
						var readable_date = "Today"
					}
					
					var hour = new Date(comment_date).getHours();
					var min = new Date(comment_date).getMinutes();

					if (min<10) {
						min = "0"+min;
					}
					var timeStr = "AM";
					if (hour > 11) {
						timeStr = "PM";
					}

					var readable_time = hour +":"+min+" "+timeStr;
					//alert(readable_time)
					if(row.user_id == userId) {
						var name= "<?php echo Auth::user()->name; ?>"
						let rgx = new RegExp(/(\p{L}{1})\p{L}+/, 'gu');

						let initials = [...name.matchAll(rgx)] || [];

						initials = (
						  (initials.shift()?.[1] || '') + (initials.pop()?.[1] || '')
						).toUpperCase();
						
						var leadStatus = "In Progres";
						
						if (row.status == "1") {
							leadStatus = "Hot";
						} else if (row.status == "2") {
							leadStatus = "Close";
						} else if (row.status == "3") {
							leadStatus = "Site Visit";
						} else if (row.status == "4") {
							leadStatus = "Fake";
						} else if (row.status == "5") {
      							leadStatus = "Out of Location";
 						} else if (row.status == "6") {
      							leadStatus = "Not Interested";
 						} else if (row.status == "7") {
      							leadStatus = "Wrong No";
						} else if (row.status == "8") {
      							leadStatus = "Not Reachable";
 						} else if (row.status == "9") {
                                                        leadStatus = "Out of Budget";
                                                }


						//console.log(initials);
					bodyData+= "<div class='incoming_msg'><div class='incoming_msg_img'> <p data-letters='"+initials+"'></p></div><div class='received_msg'><div class='received_withd_msg'><p>"+row.comments+"</p><span class='time_date'>"+readable_time +"  |  "+readable_date+"  |  "+leadStatus+"</span></div></div></div>";
					} else {
						bodyData+= "<div class='outgoing_msg'><p data-letters='AB' style='float: right;padding-left: 10px;'></p><div class='sent_msg'><p>"+row.comments+"</p><span class='time_date'> "+readable_time +"  |  "+readable_date+" </span></div></div>";			
					}
				})
			$("#msgHistory").append(bodyData);
				//console.log('data')
				//data - response from server
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
		 
			}
		});
		
	}
		

		function setComment() {
			var leadId = document.getElementById("leadId").value
			var comment = document.getElementById("comment").value
			var followupDate = document.getElementById("followup-date").value
			var followupTime = document.getElementById("followup-time").value
			var leadStatus = document.getElementById("lead-status").value
			var formData = {_token: "{{ csrf_token() }}",leadId:leadId,comment:comment,leadStatus:leadStatus,followupDate:followupDate,followupTime:followupTime}; //Array 
			
			if (leadStatus == "0" || leadStatus == "1") {
				if (followupDate.length == 0 || followupTime.length == 0) {
					alert("Please provide follow up date and time")
					return false;
				}
			}

document.getElementById("save-followup").disabled = true;

			$.ajax({
				url : "add-lead-comments",
				type: "POST",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{
					

                                        $('#myModal2').modal('hide');
                      
                     
                   swal("Success!","Comment Added Successfully!", "success");
                    document.getElementById("comment").value = ""
                  
                   $('#closebtn').click();

					console.log('data')
					//data - response from server
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
			 
				}
			});
		}		
		
		function setLeadStatus(status) {
			var leadIds = list
			if (leadIds.length == 0) {
				alert('Please select Leads')
				return false;
			}
			var formData = {_token: "{{ csrf_token() }}",leadIds:leadIds,status:status};
			var leadStatus = "In Progress";
			if (status == "1") {
				leadStatus = "Hot";
			} else if (status == "2") {
				leadStatus = "Close";
			} else if (status == "3") {
				leadStatus = "Site Visit";
			} else if (status == "4") {
				leadStatus = "Fake";
			} else if (status == "6") {
				leadStatus = "Not Interested";
			} else if (status == "9") {
                                leadStatus = "Out of Budget";
                        }


			var ifYes = confirm("Do you really want to change the lead status to "+ leadStatus)

			if (ifYes) {

				$.ajax({
					url : "set-lead-status",
					type: "POST",
					data : formData,
					success: function(data, textStatus, jqXHR)
					{
						
						alert("Lead Status Set to "+leadStatus)
						window.location.reload();
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
				 
					}
				});
			}
		}

function validateForm() {

var valueDate1 = document.getElementById('from_date').value;

var valueDate2 = document.getElementById('to_date').value;

if (!valueDate1 || !valueDate2) {

alert("Please enter valid Date")
return false;

} else if (valueDate1 > valueDate2) {

alert("From Date should be less then To Date")
return false
}
}

	</script>

@endsection
