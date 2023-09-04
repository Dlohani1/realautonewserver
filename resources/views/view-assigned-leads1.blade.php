@extends('layouts.app')
@section('title', 'Lead Assign')
@section('content')

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
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
  height: 200px;
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
                                <li class="breadcrumb-item active" aria-current="page">Leads</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Assign Leads </h4>
                    </div>
                </div>

					<div class="card">
						<div class="card-body">
						<?php if(!$leaddata->isEmpty()) { $i = 0;// $data is not empty ?>
							<table id="idsssss" class="table dt-responsive">
								<thead>
									<tr>
										<!--<th>#</th>-->
										<th>Sl.no</th>
										<!--<th>Campaigns</th>-->
										<th>Project</th>
										<!--<th>Segment</th>-->
										<th>Name</th>
										<th>Mobile No</th>
										<th>Email</th>
										<th>Comments/Follow</th>
										<th>Status</th>
									</tr>
								</thead>

								<tbody>
									<?php foreach($leaddata as $row){
										$i++;
										$assignedTo = "<strong>Not Assigned</strong>";
										$status = "<span style='color:red'>Not Attended</span>";
										if (in_array($row->id,$attendedLeads)) {
											$status = "<span style='color:green'>Attended</span>";
										}
										?>
										<tr>
											<!--<td><input type="checkbox" name="lead_id[]" value="{{$row->id}}"></td> -->
											<td>{{$i}}</td>
											<!--<td><?php echo GetCampaignsName($row->campaigns_id); ?></td>-->
											<td><?php echo $row->project_name; ?></td>
											<!--<td><?php echo $row->segment_name; ?></td>-->
											<td><?php echo $row->name; ?></td>
											<td><?php echo $row->mobile_no; ?></td>
											<td><?php echo $row->mail_id; ?></td>
											<td><button type="button" class="btn-sm" data-toggle="modal" data-target="#myModal" onclick="setleadId({{$row->id}},true)">Add/View </button></td>
											<td><?php echo $status;?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>

						<?php } else { // $data is empty ?>
							<table id="idsssss" class="table dt-responsive nowrap">
								<tr>
									<th colspan=4 style="text-align:center;"> No Record Found </th>
								</tr>
							</table>
						<?php } ?>

                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div>
                    <!-- end row-->
                </div> <!-- container-fluid -->
            </div> <!-- content -->
			<!--
            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-0 justify-content-end row">
						<div class="col-6">							 
                            <button type="submit" class="btn btn-info">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
			-->
      
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="margin-top: -6%;
    margin-right: -27%;">&times;</button>
        
      </div>
      <div class="modal-body">
		<input type="hidden" id="leadId" />
		<div id="loader">
		<img src="https://files.readme.io/7802b3a-newprogress.gif" />
		<p style="padding-left:10px"> Loading Comments...</p>
		</div>
		<div class="mesgs">
          <div class="msg_history" id="msgHistory" >
            <!--
			<div class="incoming_msg">
              <div class="incoming_msg_img"> 
			  <p data-letters="MN"></p>
			  </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>Test which is a new approach to have all
                    solutions</p>
                  <span class="time_date"> 11:01 AM    |    June 9</span></div>
              </div>
            </div>
            <div class="outgoing_msg">
              <div class="sent_msg">
                <p>Test which is a new approach to have all
                  solutions</p>
                <span class="time_date"> 11:01 AM    |    June 9</span> </div>
            </div>
            <div class="incoming_msg">
              <div class="incoming_msg_img"> <p data-letters="MN"></p> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>Test, which is a new approach to have</p>
                  <span class="time_date"> 11:01 AM    |    Yesterday</span></div>
              </div>
            </div>
            <div class="outgoing_msg">
             <div class="sent_msg">
                <p>Apollo University, Delhi, India Test</p>
                <span class="time_date"> 11:01 AM    |    Today</span> 
			</div>
            </div>
   -->
        </div>
			<div class="type_msg">
				<div class="input_msg_write" style="padding-top:10px">
					<fieldset>
					 <legend>Follow Ups:</legend>
					<div class="row">
						<div class="col-md-4 form-group">					
							<label class="control-label" for="date">Status</label>
							<select id="lead-status">
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
						</div>
						<div class="col-md-5 form-group">
							<label class="control-label" for="date">Date</label>
							<input type="date" id="followup-date" name="followup-date" style="min-height:0px">					
						</div>
						
						<div class="col-md-3 form-group">
							<label class="control-label" for="date">Time</label>
							<input type="time" id="followup-time" name="followup-time" style="min-height:0px">					
						</div>
						
					</div>
					</fieldset>
					<fieldset>
					 <legend>Comments:</legend>
					<!-- <input type="text" class="write_msg" placeholder="Type a message" /> -->
					<textarea class="write_msg" placeholder="Type a message"  rows="5" style="width:360px;resize:none;" id="comment"></textarea>
					<!--<button class="msg_send_btn" type="button" onclick="setComment()"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button> -->
					<button class="btn btn-primary" type="button" onclick="setComment()">Submit</button>
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
	
		function setleadId(leadId,getComment) {
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
						var resultData = resultData.data;
						var userId = "<?php echo Auth::user()->id; ?>";
						
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
			var formData = {_token: "{{ csrf_token() }}",leadId:leadId,comment:comment,leadStatus:leadStatus,followupDate:followupDate,followupTime:followupTime}; //Array 

			$.ajax({
				url : "add-lead-comments",
				type: "POST",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{
					$('#myModal').modal('hide');
					alert("Comment Added")
					window.location.reload();
					console.log('data')
					//data - response from server
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
			 
				}
			});
		}		
		
	</script>

@endsection
