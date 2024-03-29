@extends('layouts.app')
@section('title', 'Leads Followup')
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
.received_withd_msg { width: 57%;}
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
    margin-top: -11px;
    background-color: black;
    width: 20px;
    height: 20px;
    border-radius: 50%;
	margin-right: -9px;
}
.modal-header .close span {
    margin-top: -11px;
    position: absolute;
    color: white;
    margin-left: -6px;
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
                                <li class="breadcrumb-item active" aria-current="page">Leads Followup</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Leads Followup</h4>
                    </div>
                </div>
				
         
                <div class="row">
                    <div class="col-12">
               
						
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

						       <td>
							   <label for="search" style="display:block">Search From Date : </label>
							   <input  id="leads-date" name="followup_date" type="date" onchange="searchDate()" />
							   
							   <button onclick="myClear();"> Clear </button>
							   </td>
                                <div class="table-responsive">
                                     <table class="table table-bordered " id="example">
                                        <thead>
                                            <tr>
                                                <th>Sl.no</th>
												<th>Project</th>
												<th>Name</th>
												<th>Mobile No</th>
												<th>Date</th>
												<th>Time</th>
												<th>Status</th>
												<th>Action</th>
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

<div id="myModal" class="modal fade" role="dialog">
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
     <!--     <button class="btn btn-primary call" type="button"><i class="fa fa-phone"></i></button> &nbsp; 
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
          <div class="msg_history" id="msgHistory" >
        
        </div>
			<div class="type_msg">
				<div class="input_msg_write" style="padding-top:10px">
					<fieldset>
					 <legend>Follow Ups:</legend>
					<div class="row">
						<div class="col-md-4 form-group">					
							<label class="control-label" for="date">Status</label>
							<!--<select id="lead-status">
								<option value="0">Attended</option>
								<option value="1">Not Interested</option>
								<option value="2">Not Reachable</option>
								
							</select>-->					
					<select id="lead-status"   class="custom-select">
								<option value="0">In Progress</option>
								<!--<option value="1">Hot</option>-->
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
							<input type="date" id="followup-date" name="followup-date"   class="custom-select">					
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
					<button id="save-followup" class="btn btn-primary" type="button" onclick="setComment()">Submit</button>
                    </div>
					</fieldset>
				</div>
			</div>
        </div>
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
 

	function refreshLeads() {
			var t = $('#example').DataTable( {
 
			//bLengthChange: false,
			//searching: false,
			processing: true,
			serverSide: true,
			 destroy: true,

			ajax: "{{url('followup-assigned-retrival')}}",
			columns: [
				{  data: null },
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

				{  data: "mobile_no" },
				{  data: null,
                                render: function(data, type, row, meta) {
                                const monthNames = ["Jan", "Feb", "March", "April", "May", "June","July", "Aug", "Sept", "Oct", "Nov", "Dec"];
                                                const d = new Date(data.followup_date);
                                                var month = monthNames[d.getMonth()];
                                                var dateN = d.getDate();
                                                var dateY = d.getFullYear();

var da = new Date(data.followup_date+"T"+data.followup_time)

console.log(da.toLocaleString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }))
//console.log( da.toLocaleString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true }))

                                                return dateN+" "+month+","+dateY;
}},

			//	{  data: "followup_time"},
 
{  data: null,
                                render: function(data, type, row, meta) {
                                //const monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "Oct", "Nov", "Dec"];
                                                const d = new Date(data.followup_time);
                                                //var month = monthNames[d.getMonth()];
                                                //var dateN = d.g
//var d = new Date(date.followup_date."T".date.followup_time);

//return d.toLocaleString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })
var da = new Date(data.followup_date+"T"+data.followup_time)

return da.toLocaleString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })

}},



				{  data: null,
					render: function(data, type, row, meta) {
						$status = "<span style='color:red'> Pending </span>";
							if (data.status == "1" || data.status == "2" || data.status == "3") {
								$status = "<span style='color:green'> Attended </span>";
							}
						return $status	
					}
				},
				{  data: null,
					render: function(data, type, row, meta) {
						
						return '<button type="button" class="btn-sm" data-toggle="modal" data-target="#myModal" onclick="setleadId('+data.lead_id+',true)">Add/Reschedule </button>'
					}
				}
			],
			colReorder: true,
			
		});
		
		t.on( 'draw.dt', function () {
		var PageInfo = $('#example').DataTable().page.info();
if (PageInfo.page > 0 ) {
$('#example').DataTable().stateSave = true

}

			 t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			} );
		} );
		$('#example tbody').on( 'click', 'tr', function () {
			//$(this).toggleClass('selected');
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

}


$(document).ready(function() {

refreshLeads();
	});
		
 
function setleadId(leadId, getComment) {
	//alert(leadId);
document.getElementById("save-followup").disabled = false
			document.getElementById("leadId").value = leadId;
			document.getElementById("loader").style.display="block";
			if (getComment) {
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


						var userId = "<?php echo Auth::user()->id; ?>";
						
						document.getElementById("lead-status").value = leadStatus;

						var bodyData = '';
						var i=1;
						$.each(resultData,function(index,row){
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
								//console.log(initials);
							bodyData+= "<div class='incoming_msg'><div class='incoming_msg_img'> <p data-letters='"+initials+"'></p></div><div class='received_msg'><div class='received_withd_msg'><p>"+row.comments+"</p><span class='time_date'>"+readable_time +"  |  "+readable_date+"</span></div></div></div>";
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
		}
		
		function setComment() {
			var leadId = document.getElementById("leadId").value
			var comment = document.getElementById("comment").value
			var followupDate = document.getElementById("followup-date").value
			var followupTime = document.getElementById("followup-time").value
			var leadStatus = document.getElementById("lead-status").value


			if (leadStatus == "0" || leadStatus == "1") {
                                if (followupDate.length == 0 || followupTime.length == 0) {
                                        alert("Please provide follow up date and time")
                                        return false;
                                }
                        }
document.getElementById("save-followup").disabled = true;

			var formData = {_token: "{{ csrf_token() }}",source:'followup',leadId:leadId,comment:comment,leadStatus:leadStatus,followupDate:followupDate,followupTime:followupTime}; //Array 
			$.ajax({
				url : "add-lead-comments",
				type: "POST",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{
					$('#myModal').modal('hide');
                      
                     
                   swal("Success!","Comment Added Successfully!", "success");
                    document.getElementById("comment").value = ""
                  
                   $('#closebtn').click();

refreshLeads();

					console.log('data')
					//data - response from server
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
			 
				}
			});
		}	
		
		function setFollowUp() {
			var leadId = document.getElementById("leadId").value
			var followUpDate = document.getElementById("followup-date").value
			var followUpTime = document.getElementById("followup-time").value
			var comments = document.getElementById("additional-comments").value
			var status = document.getElementById("lead-status").value
			var formData = {_token: "{{ csrf_token() }}",leadId:leadId,followUpDate:followUpDate,followUpTime:followUpTime,comments:comments,status:status}; //Array 
			$.ajax({
				url : "add-lead-followUp",
				type: "POST",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{
					$('#myModal1').modal('hide');
                      
                     
                   swal("Success!","Comment Added Successfully!", "success");
                    document.getElementById("comment").value = ""
                  
                   $('#closebtn1').click();
					console.log('data')
					//data - response from server
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
			 
				}
			});
		}
// serach end date 
function searchDate()
{
	var leaddate = document.getElementById('leads-date').value;
   //// alert(leaddate);
 let list=new Array;	
 $(document).ready(function() {
		var t = $('#example').DataTable( {
     
        processing: true,
		serverSide: true,
	 	destroy: true,
		ajax: {
			url: "end-dateretrival",
			data: function ( d ) {
				return $.extend( {}, d, {
					"followup_date":leaddate
				});
			}
		 },
		columns: [
			{  data: null },
			{  data: "project_name"},
			{  data: "name" },
			{  data: "mobile_no" },
			{  data: null,
                                render: function(data, type, row, meta) {
				const monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "Oct", "Nov", "Dec"];
						const d = new Date(data.followup_date); 
						var month = monthNames[d.getMonth()];
						var dateN = d.getDate();
						var dateY = d.getFullYear();
						return dateN+" "+month+","+dateY;
}},
			//{  data: "followup_time"},
{  data: null,
                                render: function(data, type, row, meta) {
                                //const monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "Oct", "Nov", "Dec"];
                                                const d = new Date(data.followup_time);
                                                //var month = monthNames[d.getMonth()];
                                                //var dateN = d.g
//var d = new Date(date.followup_date."T".date.followup_time);

//return d.toLocaleString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })
var da = new Date(data.followup_date+"T"+data.followup_time)

return da.toLocaleString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })

}},

			{  data: null,
				render: function(data, type, row, meta) {
					$status = "<span style='color:red'> Pending </span>";
						if (data.status == "1" || data.status == "2" || data.status == "3") {
							$status = "<span style='color:green'> Attended </span>";
						}
					return $status	
				}
			},
			{  data: null,
				render: function(data, type, row, meta) {
					
					return '<button type="button" class="btn-sm" data-toggle="modal" data-target="#myModal" onclick="setleadId('+data.lead_id+',true)">Add/Reschedule </button>'
				}
			}
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
		//$(this).toggleClass('selected');
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
}		
//clear
function myClear(){	
	location.reload();
}
</script>
@endsection
