@extends('layouts.app')
@section('title', 'Users Assign')
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
                                <li class="breadcrumb-item active" aria-current="page">Assign Users</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Assign Users</h4>
                    </div>
                </div>
				
                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
						          <form action="{{ route('users-assigned-sells') }}" method="POST">
                      @csrf

                    <div class="form-group row mb-3">
                        <label for="inputPassword5" class="col-3 col-form-label">Sells <span class="required">*</span></label>
                        <div class="col-6">
                          <select name="sells_id" class="custom-select mb-2" required>
                              <option value="">Select Team</option>
                              @foreach ($sellsdata as $rowssells)
                              <option value="{{ $rowssells->id }}">{{ ucwords($rowssells->name) }}</option>
                              @endforeach
                          </select> 
                            <input id="users-ids" name="users-ids" type="hidden" /> 
								        
                      </div>
                      <div class="col-3">
                      <button type="submit" class="btn btn-info">Submit</button>
                      </div>
                    </div>
									</form>
                </div>
              </div>
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
                                <div class="table-responsive">
                                     <table class="table table-bordered " id="example">
                                        <thead>
                                            <tr>
                                              <th>Sl.no</th> 
                                              <th>Name</th>
                                              <th>Mobile</th>
                                              <td>Email</td>
                                              <th>Status</th>
                                              <th>Assignee</th>
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
        <button type="button" class="close" data-dismiss="modal" style="font-size: 20px;">&times;</button>
      </div>
      <div class="modal-body">
	  <input type="hidden" id="adminid" />
		<div id="loader">
		<img src="https://files.readme.io/7802b3a-newprogress.gif" />
		<p style="padding-left:10px"> Loading Comments...</p>
		</div>
		<div class="mesgs">
          <div class="msg_history" id="msgHistory">

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
						
						<div class="col-md-3   form-group">
							<label class="control-label" for="date">Time</label>
							<input type="time" id="followup-time" name="followup-time" style="min-height:0px">					
						</div>
						
					</div>
					</fieldset>
					<fieldset>
					 <legend>Comments:</legend>
					<textarea class="write_msg" placeholder="Type a message"  rows="5" style="width:360px;resize:none;" id="comment"></textarea>
			
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
                        <?php echo date('Y'); ?> &copy; RealAuto. All Rights Reserved. Crafted with <i class='uil uil-heart text-danger font-size-12'></i> by <a href="https://active-digital.tech/home/" target="_blank">Active Digital Technology</a>
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
      processing: true,
      serverSide: true,
      ajax: "{{url('users-assigned-retrival')}}",
       
      columns: [
        { data: null },
        { data: "name" },
        { data: "email" },
        { data: "phone_no" },
        { data: null,
          render: function(data, type, row, meta) {
            var status ="<span style='color:red'>In Active</span>";
            
            
            if (data.status == "1") {
              status = "<span style='color:green'>Active</span>"
              
            }
            return  status
          }  
        },
        
        {  data: null,
          render: function(data, type, row, meta) {
            return undefined == data.assignee ?  "Not Assigned" : data.assignee 
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
      $(this).toggleClass('selected');
      var id = t.row( this ).data().id
      //console.log('id',id)
      if (list.includes(id)) {
        list.pop(id)
      } else { 
        list.push(id);
      }
      document.getElementById("users-ids").value = list;
      console.log(list)
    });
  });
	</script>

@endsection
