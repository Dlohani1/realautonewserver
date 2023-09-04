@extends('layouts.app')
@section('title', 'All Leads')
@section('content')

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- ajxa table get data  -->
 <meta name="csrf-token" content="{{ csrf_token() }}">
<style>
	.custom-table{border-collapse:collapse;width:100%;border:solid 1px #c0c0c0;font-family:open sans;font-size:14px}
	.custom-table th,.custom-table td{text-align:left;padding:8px;border:solid 1px #c0c0c0}
	.custom-table th{color:#000080}
	.custom-table tr:nth-child(odd){background-color:#f7f7ff}
	.custom-table>thead>tr{background-color:#dde8f7!important}
	.tbtn{border:0;outline:0;background-color:transparent;font-size:13px;cursor:pointer}
	.toggler{display:none}
	.toggler1{display:table-row;}
	.custom-table a{color: #0033cc;}
	.custom-table a:hover{color: #f00;}
	.page-header{background-color: #eee;}
			
			
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

table {
    border-collapse: collapse;
}

td {
    position: relative;
    padding: 5px 10px;
}

tr.strikeout td:before {
    content: " ";
    position: absolute;
    top: 50%;
    left: 0;
    border-bottom: 1px solid #111;
    width: 100%;
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
                                <li class="breadcrumb-item active" aria-current="page">Manage Leads</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">Leads Tables</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" onclick="window.location.href='<?php echo url('/add-import-leads'); ?>'"> Import 
Lead <i class="uil-plus mr-1"></i></button>
                                <button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" onclick="window.location.href='<?php echo url('/add-leads'); ?>'"> Add Lead <i 
class="uil-plus mr-1"></i></button>
                                 <button style="display:none" class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" onclick="sendImmediate()"> Send Immediate </button>
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

<?php 

if (count($cautomation) > 0 ) { ?>
<h3> Campaign w.e.f :  {{date("jS F, Y", strtotime($cautomation[0]['campaign_activated_on']))." at ".date('h:i a ', strtotime($cautomation[0]['campaign_activated_on']))}}</h3>

								<table class="custom-table">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Attachment</th>
                    <th>Delivery On</th>
                    <th>Status</th>
                    <!--<th>Group Name</th>
                    <th>Host Id</th>
                    <th>Browser Type</th>
                    <th>Performance Score</th>
                    <th>Accessibility Score</th>
                    <th>PWA Score</th>
                    <th>Best Practice Score</th>
                    <th>SEO Score</th>
                    <th>Frst Contntful Paint</th>
                    <th>Frst Meaningful Paint</th>
                    <th>TTI</th>
                    <th>Speed Index</th>
                    <th>Frst CPU Idle</th>
                    <th>Estimated Input Latency</th> -->
                </tr>
            </thead>
			<?php
			$previousEventId = 0;
			foreach($cautomation as $key => $value) {
				$newEventId = $value->automation_messages_id;
				?>
            <tbody>
				<?php
				if ($newEventId != $previousEventId) { ?>
                <tr>
                    <td colspan="20" class="page-header"><button type="button" class="tbtn" style="font-size:16px"><i class="fa fa-plus-circle fa-minus-circle"></i>   
{{strtoupper($value->series_name)}}</button>  Last Updated On : {{date("jS F, Y", strtotime($value->updated_at))." at ".date('h:i a ', strtotime($value->updated_at))}}</td>
                </tr>
				<?php } $previousEventId = $newEventId; ?>
               <tr class="toggler toggler1">
                   <td rowspan="2"></td>
                    <td><?php if ($value->automation_type == "1") { echo "SMS";} else if  ($value->automation_type == "3") { echo "Whatsapp";} else {echo "Email";}?></td>
                    <td>{{$value->message}}</td>
                    <td><?php echo null !== $value->image ? "Yes" : "No";?></td>
                    <td>{{date("jS F, Y", strtotime($value->delivery_date_time))." at ".date('h:i a ', strtotime($value->delivery_date_time))}}</td>
                    <td><?php echo $value->is_delivered == "1" ? "<span style='color:green'>Delivered</span>" : ($value->is_cancelled != "1" ? "<span style='color:grey'>Not Delivered</span>" : "<span style='color:red'>Cancelled due to ".$value->failure_reason."</span>");?></td>
                    <!--<td>0</td>
                    <td>0</td>
                    <td>12</td>
                    <td>23</td>
                    <td>67</td>
                    <td>100</td>
                    <td>4420</td>
                    <td>4420</td>
                    <td>15360</td>
                    <td>16150</td>
                    <td>21750</td>
                    <td>291</td>
                    <td>0</td>
                    <td>0</td>-->
                </tr>
            
               
                
            </tbody>
			<?php } ?>

<!--			
		<tbody>
                <tr>
                    <td colspan="20" class="page-header"><button type="button" class="tbtn"><i class="fa fa-plus-circle"></i>    P_index 2</button></td>
                </tr>
                <tr class="toggler">
                    <td rowspan="11"></td>
                    <td><a href="#">lighthouse+0_0_0_0_0_0_0_0_0.html</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>12</td>
                    <td>23</td>
                    <td>67</td>
                    <td>100</td>
                    <td>4420</td>
                    <td>4420</td>
                    <td>15360</td>
                    <td>16150</td>
                    <td>21750</td>
                    <td>291</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
                
                <tr class="toggler">
                    <td><a href="#">lighthouse+0_0_0_0_0_0_0_0_0.html</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>12</td>
                    <td>23</td>
                    <td>67</td>
                    <td>100</td>
                    <td>4420</td>
                    <td>4420</td>
                    <td>15360</td>
                    <td>16150</td>
                    <td>21750</td>
                    <td>291</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td colspan="20" class="page-header"><button type="button" class="tbtn"><i class="fa fa-plus-circle"></i>   P_index 3</button></td>
                </tr>
                <tr class="toggler">
                    <td rowspan="5"></td>
                    <td><a href="#">lighthouse+0_0_0_0_0_0_0_0_0.html</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>12</td>
                    <td>23</td>
                    <td>67</td>
                    <td>100</td>
                    <td>4420</td>
                    <td>4420</td>
                    <td>15360</td>
                    <td>16150</td>
                    <td>21750</td>
                    <td>291</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
               
                <tr class="toggler">
                    <td><a href="#">lighthouse+0_0_0_0_0_0_0_0_0.html</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>12</td>
                    <td>23</td>
                    <td>67</td>
                    <td>100</td>
                    <td>4420</td>
                    <td>4420</td>
                    <td>15360</td>
                    <td>16150</td>
                    <td>21750</td>
                    <td>291</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <td colspan="20" class="page-header"><button type="button" class="tbtn"><i class="fa fa-plus-circle"></i>   P_index 4</button></td>
                </tr>
               <tr class="toggler">
                   <td rowspan="2"></td>
                    <td><a href="#">lighthouse+0_0_0_0_0_0_0_0_0.html</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>12</td>
                    <td>23</td>
                    <td>67</td>
                    <td>100</td>
                    <td>4420</td>
                    <td>4420</td>
                    <td>15360</td>
                    <td>16150</td>
                    <td>21750</td>
                    <td>291</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
               <tr class="toggler">
                    <td><a href="#">lighthouse+0_0_0_0_0_0_0_0_0.html</a></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>12</td>
                    <td>23</td>
                    <td>67</td>
                    <td>100</td>
                    <td>4420</td>
                    <td>4420</td>
                    <td>15360</td>
                    <td>16150</td>
                    <td>21750</td>
                    <td>291</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
            </tbody>
			-->

        </table>
<?php  } ?>                                     <!--<table class="table table-bordered " id="example">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Campaigns</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile No</th>
                                                <th>Project</th>
                                                <th>Segment</th>
												<th>Status</th>
                                                <th width="100px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table> -->
                                </div>

                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
                <!-- end row-->
            </div> <!-- container-fluid -->
        </div> <!-- content -->

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
<script type="text/javascript">
  /*
  $(function () {
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('leads-master') }}",
        columns: [

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'campaigns_name', name: 'campaigns_name'},
            {data: 'name', name: 'name'},
            {data: 'mail_id', name: 'mail_id'},
            {data: 'mobile_no', name: 'mobile_no'},
            {data: 'project_name', name: 'project_name'},
            {data: 'segment_name', name: 'segment_name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });


	$(document).ready(function() {
 $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
		var t = $('#example').DataTable( {
			bLengthChange: false,
			searching: false,
			processing: true,
			serverSide: true,
			ajax: "{{url('leads-retrival')}}",
			
			columns: [
				{ data: null},
				{ data: "campaigns_name" },
				{ data: "name" },
				{ data: "mail_id" },
				{ data: "mobile_no" },
				{ data: "project_name" },
				{ data: "segment_name" },
				{data:"status",
					render: function(data, type, row, meta) {
						var status = '<span style ="color:green">Active</span>';
						if (data.status == "0") {
							var status = '<span style ="color:red">In Active</span>';
						}
						return status;
						}
				},
				{  data: null,
					render: function(data, type, row, meta) {
						return ' <a href="edit-leads/'+data.id+'" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" 
fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 
0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><a href="delete-leads/'+data.id+'" onclick="return deleteConfirm()"><svg 
xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" 
y2="15"></line></svg></a>&nbsp;<a title="View Details" href="view-reports/'+data.id+'" "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" 
stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle 
cx="12" cy="12" r="3"></circle></svg></a>';
					} 
				}
			],
			colReorder: true,
			
		});
		
		t.on( 'order.dt search.dt draw.dt', function () {
			t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
	
		t.on( 'draw.dt', function () {
		var PageInfo = $('#example').DataTable().page.info();
			 t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			} );
		} );
	});
	*/	
$(document).ready(function () {
	$(".tbtn").click(function () {
		$(this).parents(".custom-table").find(".toggler1").removeClass("toggler1");
		$(this).parents("tbody").find(".toggler").addClass("toggler1");
		$(this).parents(".custom-table").find(".fa-minus-circle").removeClass("fa-minus-circle");
		$(this).parents("tbody").find(".fa-plus-circle").addClass("fa-minus-circle");
	});
});
  function deleteConfirm() {
    var isSure = confirm("Do you really want to delete this lead ??");
    
    if (isSure) {       
    
        //var isVerySure = prompt("This will permanently delete the lead. Type YES to proceed");

        //if (isVerySure == "YES") {
            return true;
        //}
    }

    return false;
}

</script>

@endsection
