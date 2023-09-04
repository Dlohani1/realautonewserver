@extends('layouts.app')
@section('title', 'Lists Of All Admin')
@section('content')

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->
<style>
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
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 83px;
  height: 28px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ca2222;
  -webkit-transition: .4s;
  transition: .4s;
   border-radius: 34px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 22px;
  width:22px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #2ab934;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(55px);
}

/*------ ADDED CSS ---------*/
.slider:after
{
 content:'OFF';
 color: white;
 display: block;
 position: absolute;
 transform: translate(-50%,-50%);
 top: 50%;
 left: 50%;
 font-size: 10px;
 font-family: Verdana, sans-serif;
}

input:checked + .slider:after
{  
  content:'ON';
}
.write_msg {
    border: 1px solid #5369f8;
    border-radius: 0.3rem;
    width: 100%;
    height: 40px;
    background-color: #f3f4f7;
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
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">@lang('Dashboard')</a></li>
                                <li class="breadcrumb-item active" aria-current="page">@lang('Manage Admins')</li>
                            </ol>
                        </nav>
                        <h4 class="mb-1 mt-0">@lang('Admins List')</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">

                          <!-- Nav pills -->
 

                            <div class="card-body">
							                  @if(Session::has('message'))
                                    <div class="alert alert-success alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{!! session('message') !!}</strong>
                                    </div>
                                @endif
                                <div class="row align-items-center">
  <!-- Nav pills -->
  <ul class="nav nav-pills" role="tablist" style="padding:10px">
    <li class="nav-item">
      <button type="button" class="nav-link active btn btn-primary" data-toggle="pill" onclick="showData(0)">@lang('All')</button>
    </li> &nbsp;&nbsp;
    <li class="nav-item">
      <button type="button"  class="nav-link btn btn-primary" data-toggle="pill" onclick="showData(1)">@lang('Running')</button>
    </li>&nbsp;&nbsp;
   <!-- <li class="nav-item">
      <button type="button"  class="nav-link btn btn-primary" data-toggle="pill" onclick="showData(2)">@lang('Applied')</button>
    </li>&nbsp;&nbsp; -->
     <li class="nav-item">
      <button type="button"  class="nav-link btn btn-primary" data-toggle="pill" onclick="showData(3)">@lang('Expire Pack')</button>
    </li>&nbsp;&nbsp;

<li class="nav-item">
      <button type="button"  class="nav-link btn btn-primary" data-toggle="pill" onclick="showData(4)">@lang('Referral Users')</button>
    </li>&nbsp;&nbsp;

     <li class="nav-item">
      <button type="button"  class="nav-link btn btn-primary" data-toggle="pill" id="button">&nbsp;&nbsp; @lang('Plan EndDate Search') &nbsp;&nbsp;</button>
      <div id="date" style="display: none;">
      From: <input type="date" name="end_date_first" id="start_date" class="nav-link btn btn-primary" >
      To: <input type="date" name="end_date" id="end_date" class="nav-link btn btn-primary">
    	<button type="button" onclick="showData(0)">Search</button> </div>
	</li>&nbsp;&nbsp;
    <!-- <li class="nav-item">
      <button type="button"  class="nav-link btn btn-primary" data-toggle="pill" onclick="window.print()">@lang('Print')</button>
    </li>&nbsp;&nbsp; -->
	<li class="nav-item">
	<!--<label for="user-plans"> Plans </label>-->
    	<select onchange="filterData()" id="user-plans" class="write_msg">
        <option> Plans </option>
		<option value="0"> All </option>
		<option value="5"> Trial </option>
		<option value="1"> 15 Days</option>
		<option value="2"> 1 Month </option>
		<option value="3"> 3 Months</option>
		<option value="4"> 6 Months</option>
	</select>
	</li>&nbsp;&nbsp;
     <li class="nav-item">
      <button type="button"  class="nav-link btn btn-primary" data-toggle="pill" onclick="location.reload()">@lang('Clear')</button>
    </li>
    <!-- <li class="nav-item" style="padding-left: 40%;">
    <label>Plan EndDate Search </label>
    ----------------------text
    </li> -->
  </ul>   
	<div class="col text-right">
		<button class="btn btn-primary" id="btn-new-event" onclick="window.location.href='<?php echo url('/add-new-admin'); ?>'"><i class="uil-plus mr-1"></i>@lang('Add New Admin')</button>
	</div>
</div>
</div>
</div>

                        <div class="card">
                            <div class="card-body">
						                       	<div class="table-responsive">
                                  		<!-- <table> 
                                       <tr>
                                         <td>
                                    		 	<select onchange="showData()" id="status-val" style="height: 27px;margin-top: -2px;">
                                    				<option value="0">-- Search Status --</option>
                                    				<option value="1">Running</option>
                                    				<option value="2">Applied</option>
                                    			</select>
                                         </td>

                                         <td><label>Plan EndDate Search: </label>
                                         	  <input type="date" name="end_date" id="end_date" onchange="myEndDate();">
                                            <button onclick="window.print()" style="background-color: #5369f8;color:#fff;border-radius: 4px;">Print</button>  
                                            <button onclick="location.reload()" style="background-color: #5369f8;color:#fff;border-radius: 4px;">Clear</button>
                                         </td>
                                       </tr>
                                     </table> -->
                                     <br/>
									 
                                     <table class="table table-bordered " id="example">
                                       
                                            
                                        <thead>
                                            <tr>
						
                                                
												<th>@lang('No')</th>
												<th>@lang('Full_Name')</th>
												<th>@lang('Applied_Date')</th>
												<th>@lang('Email')</th>
												<th>@lang('Mobile')</th>
												<th>@lang('Referral Code')</th>
												<th>@lang('Plan')</th>
												<th>@lang('Plan Start_Date') </th>
												<th>@lang('Plan End_Date') </th>
												<th>@lang('Users_Status')</th>
												<th>@lang('Action')</th>
												<th>@lang('Change Status')</th>
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

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?php echo date('Y'); ?> &copy; @lang('Realauto. All Rights Reserved. Crafted with') <i class='uil uil-heart text-danger font-size-12'></i> by <a href="#" target="_blank">@lang('Realauto')</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

    @push('scripts')
        <script>
            var InActive = 'InActive';
            var Active   = 'Active';

            function active_inactive_user(id,status){
				
                $.ajax({
                    type: "post",
                    url: '{{ route('active_inactive_user') }}',
                    data: {
                        _token: '<?php echo csrf_token();?>',
                        id: id,
                        status:status
                    },
                    success: function (data) {
					        	window.location.reload()
                    }
                });
            }


            function deleteAdmin(){
                
                var isSure = confirm("Do you really want to delete ?");
                
                if (isSure) {
                    var isVerySure = prompt("This will permanently delete the Admin. Type YES to proceed");
                    if (isVerySure == "YES") {
                        return true;
                    }
                }
              
              return false;
            }
			
			
			let list=new Array;


	$(document).ready(function() {

		var t = $('#example').DataTable( {

			processing: true,
			serverSide: true,
			destroy: true,
			ajax: "{{url('admins-retrival')}}",
			 
			columns: [
				{ data: null },
				
				 { data: null,

          render: function(data, type, row, meta) {

	var referredBy = "";
			if (undefined !== data.referredBy) {
				referredBy = "<br/><span style='color:blue'>ReferredBy:</span>"+data.referredBy;
			}

          return data.status == "1" ?  '<img src="https://icon2.cleanpng.com/20171221/lle/success-png-image-5a3c42bdc288a6.4170951415138986857968.jpg" width="15px" height="15px;margin-left:5px;" />' +titleCase(data.name)+referredBy : data.status == "2" ? '<img src="https://icon-library.com/images/inactive-icon/inactive-icon-8.jpg" width="15" height="15">' +titleCase(data.name)+referredBy : titleCase(data.name)+referredBy;
         }

         },

        { data: null,
            render: function(data, type, row, meta) {
				const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
				if (undefined  !== data.created_at) {
					const d = new Date(data.created_at); 
					var month = monthNames[d.getMonth()];
					var dateN = ("0" + (d.getDate())).slice(-2);
					var dateY = d.getFullYear();
					console.log(d)
					console.log(data.created_at)
					return dateN+" "+month+","+dateY;
				} else {
					return "";
				}
            }
          },

				{ data: "email" },

    
				{ data: null,
					render: function(data, type, row, meta) {
						var creditPoint = "<br/><span style='color:blue'>Credit Point:</span> NA<br/>";
						if(data.creditPoint != 0) {
							creditPoint = "<br/><span style='color:blue'>Credit Point:</span>"+data.creditPoint+"</br>";
						}
						var activeUpto = "<br/><span style='color:red'>Active:</span> NA";
						if(data.activeUpto != 0) {
							activeUpto = "<br/><span style='color:red'>Active:</span>"+data.activeUpto;
						}
						return data.phone_no+ "<br/>"+ creditPoint+ activeUpto;
					}
				},

				{ data: "referral_code" },
				{ data: null,
					render: function(data, type, row, meta) {
						var plan = "NA"
						switch(data.user_plans_id) {
							case 1 : plan = "15 Days"; break;
							case 2 : plan = "1 Month"; break;
							case 3 : plan = "3 Months"; break;
							case 4 : plan = "6 Months"; break;
							case 5 : plan = "Trial (3 Days)"; break;
						}
						var pack = "<span class='text-primary'>Normal</span>"
						
						if (data.pack == "1") {
	
							pack= "<span class='text-success'>Starter</span>"
						}							
						return  plan+" | "+pack
					}
				},

				{ data: null,
					render: function(data, type, row, meta) {
					if(!data.start_date){
						var enddate = "<p style='color:blue;'>Not Start Date</p>";
					} else {
						const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
						const d = new Date(data.start_date); 
						var month = monthNames[d.getMonth()];
						var dateN = ("0" + (d.getDate())).slice(-2);
						var dateY = d.getFullYear();
						var enddate = dateN+" "+month+","+dateY;
					}
					return  enddate
				}
			},

			{ data: null,
            render: function(data, type, row, meta) {
              if(!data.end_date){
                var enddate = "<p style='color:blue;'>Not End Date</p>";
              }
              else{
              const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
              const d = new Date(data.end_date); 
              var month = monthNames[d.getMonth()];
              var dateN = ("0" + (d.getDate())).slice(-2);
              var dateY = d.getFullYear();
              var enddate = dateN+" "+month+","+dateY;
              }
             return  enddate
            }
          },
        // { data: "end_date"},

				{ data: null,
					render: function(data, type, row, meta) {
	          var todayDate = new Date();
            var month = ("0" + (todayDate.getMonth() + 1)).slice(-2);
            var year = todayDate.getUTCFullYear(); 
            var tdate = ("0" + (todayDate.getDate())).slice(-2); 
            var maxDate = year + "-" + month + "-" + tdate;
            if(data.end_date <= maxDate)
            {
              status = "<span style='color:red'>Expire Pack</span>";
            }
						else if (data.status == "1") {
							status = "<span style='color:green'>Running</span>";
						}

            else{
              status ="<span style='color:blue;font-weight:500'>Applied</span>";
            }
						return  status
					}	 
				},
				
				{ data: null,
					render: function(data, type, row, meta) {
						return '  <a href="manage-settings/'+data.id+'" title="settings"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> </a> <a href="manage-subadmins/'+data.id+'" title="view subadmins"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> </a> <a href="edit-admins/'+data.id+'" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><a href="delete-admins/'+data.id+'" onclick="return deleteAdmin()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg></a>';
					} 
				},

                
        { data: null,
					render: function(data, type, row, meta) {
						var status ='<label class="switch" onchange="myActive('+data.id+');"><input type="hidden" name="status" id="active" value="1"><input type="checkbox"><span class="slider round"></span></label>';
						
						if (data.status == "1") {
							status = '<label class="switch" onchange="myStatus('+data.id+');"><input type="hidden" name="status" id="status" value="2"><input type="checkbox" checked><span class="slider round"></span></label>';
						}
						return  status
					}	 
				}
			],
			colReorder: true,
			
		});

		/*
		t.on( 'order.dt search.dt ', function () {
			t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		*/
		t.on( 'draw.dt', function () {
		var PageInfo = $('#example').DataTable().page.info();
			 t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			} );
		} );

$('#example tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');

//var id = t.row( this ).id();

var id = t.row( this ).data().id
console.log('id',id)
if (list.includes(id)) {
list.pop(id)
} else { 
  list.push(id);
}
console.log(list)
    } );


	});
	
	function showData(statuss) {
    var startDate = document.getElementById("start_date").value;
    var endDate = document.getElementById("end_date").value;
if (statuss < 1) {
if(startDate.length == 0) {
 startDate = startDate.length
}
if(endDate.length == 0) {
 endDate = endDate.length
}
} else {startDate = startDate.length; endDate = endDate.length;}


	//	var val = document.getElementById("status-val").value;
		var t = $('#example').DataTable( {
			//bLengthChange: false,
			//searching: false,

			processing: true,
			serverSide: true,
			destroy: true,
				"ajax": {
				url: "{{url('admins-retrival')}}",
				"data": function ( d ) {
							return $.extend( {}, d, {
								"user-status": statuss,
								"startDate" : startDate,
								"endDate" : endDate
							});
						}
			 },
  
			columns: [
				{ data: null },
				
				 { data: null,

          render: function(data, type, row, meta) {

          var referredBy = "";
			if (undefined !== data.referredBy) {
				referredBy = "<br/><span style='color:blue'>ReferredBy:</span>"+data.referredBy;
			}

          return data.status == "1" ?  '<img src="https://icon2.cleanpng.com/20171221/lle/success-png-image-5a3c42bdc288a6.4170951415138986857968.jpg" width="15px" height="15px;margin-left:5px;" />' +data.name+referredBy : data.status == "2" ? '<img src="https://icon-library.com/images/inactive-icon/inactive-icon-8.jpg" width="15" height="15">' +data.name+referredBy : data.name+referredBy;
         }

         },

        { data: null,
            render: function(data, type, row, meta) {
				const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
				if (undefined  !== data.created_at) {
					const d = new Date(data.created_at); 
					var month = monthNames[d.getMonth()];
					var dateN = ("0" + (d.getDate())).slice(-2);
					var dateY = d.getFullYear();
					console.log(d)
					console.log(data.created_at)
					return dateN+" "+month+","+dateY;
				} else {
					return "";
				}
            }
          },

				{ data: "email" },

    
				{ data: null,
					render: function(data, type, row, meta) {
						var creditPoint = "<br/><span style='color:blue'>Credit Point:</span> NA<br/>";
						if(data.creditPoint != 0) {
							creditPoint = "<br/><span style='color:blue'>Credit Point:</span>"+data.creditPoint+"</br>";
						}
						var activeUpto = "<br/><span style='color:red'>Active:</span> NA";
						if(data.activeUpto != 0) {
							activeUpto = "<br/><span style='color:red'>Active:</span>"+data.activeUpto;
						}
						return data.phone_no+ "<br/>"+ creditPoint+ activeUpto;
					}
				},

				{ data: "referral_code" },
				{ data: null,
					render: function(data, type, row, meta) {
						var plan = "NA"
						switch(data.user_plans_id) {
							case 1 : plan = "15 Days"; break;
							case 2 : plan = "1 Month"; break;
							case 3 : plan = "3 Months"; break;
							case 4 : plan = "6 Months"; break;
							case 5 : plan = "Trial (3 Days)"; break;
						}
						return  plan
					}
				},

				{ data: null,
					render: function(data, type, row, meta) {
					if(!data.start_date){
						var enddate = "<p style='color:blue;'>Not Start Date</p>";
					} else {
						const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
						const d = new Date(data.start_date); 
						var month = monthNames[d.getMonth()];
						var dateN = ("0" + (d.getDate())).slice(-2);
						var dateY = d.getFullYear();
						var enddate = dateN+" "+month+","+dateY;
					}
					return  enddate
				}
			},

			{ data: null,
            render: function(data, type, row, meta) {
              if(!data.end_date){
                var enddate = "<p style='color:blue;'>Not End Date</p>";
              }
              else{
              const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
              const d = new Date(data.end_date); 
              var month = monthNames[d.getMonth()];
              var dateN = ("0" + (d.getDate())).slice(-2);
              var dateY = d.getFullYear();
              var enddate = dateN+" "+month+","+dateY;
              }
             return  enddate
            }
          },
        // { data: "end_date"},

				{ data: null,
					render: function(data, type, row, meta) {
	          var todayDate = new Date();
            var month = ("0" + (todayDate.getMonth() + 1)).slice(-2);
            var year = todayDate.getUTCFullYear(); 
            var tdate = ("0" + (todayDate.getDate())).slice(-2); 
            var maxDate = year + "-" + month + "-" + tdate;
            if(data.end_date <= maxDate)
            {
              status = "<span style='color:red'>Expire Pack</span>";
            }
						else if (data.status == "1") {
							status = "<span style='color:green'>Running</span>";
						}

            else{
              status ="<span style='color:blue;font-weight:500'>Applied</span>";
            }
						return  status
					}	 
				},
				
				{ data: null,
					render: function(data, type, row, meta) {
						return ' <a href="edit-admins/'+data.id+'" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><a href="delete-admins/'+data.id+'" onclick="return deleteAdmin()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg></a>';
					} 
				},

                
        { data: null,
					render: function(data, type, row, meta) {
						var status ='<label class="switch" onchange="myActive('+data.id+');"><input type="hidden" name="status" id="active" value="1"><input type="checkbox"><span class="slider round"></span></label>';
						
						if (data.status == "1") {
							status = '<label class="switch" onchange="myStatus('+data.id+');"><input type="hidden" name="status" id="status" value="2"><input type="checkbox" checked><span class="slider round"></span></label>';
						}
						return  status
					}	 
				}
			],
			colReorder: true,
			
		});

		/*
		t.on( 'order.dt search.dt ', function () {
			t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		*/
		t.on( 'draw.dt', function () {
		var PageInfo = $('#example').DataTable().page.info();
			 t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
				cell.innerHTML = i + 1 + PageInfo.start;
			} );
		} );

$('#example tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');

//var id = t.row( this ).id();

		var id = t.row( this ).data().id
		console.log('id',id)
		if (list.includes(id)) {
		list.pop(id)
		} else { 
		  list.push(id);
		}
		console.log(list)
    });
	}


	
function myEndDate() {
    var startDate = document.getElementById("end_date").value;
    var endDate = document.getElementById("start_date").value;

if(startDate.length == 0) {
startDate = startDate.length
}
if(endDate.length == 0) {
endDate = startDate.length
}



		var t = $('#example').DataTable( {
			processing: true,
			serverSide: true,
			 destroy: true,
				"ajax": {
				 url : "{{url('admins-retrival')}}",
				data : function ( d ) {
					return $.extend( {}, d, {
						
					        "startDate": startDate,
						"endDate" : endDate
					});
				}
			 },

			columns: [
				{ data: null },		
			  { data: null,

          render: function(data, type, row, meta) {

          return data.status == "1" ?  '<img src="https://icon2.cleanpng.com/20171221/lle/success-png-image-5a3c42bdc288a6.4170951415138986857968.jpg" width="15px" height="15px;margin-left:5px;" />' +data.name : data.status == "2" ? '<img src="https://icon-library.com/images/inactive-icon/inactive-icon-8.jpg" width="15" height="15">' +data.name : data.name;
         }

         },

        { data: null,
          render: function(data, type, row, meta) {
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
if (undefined  !== data.created_at) {

            const d = new Date(data.created_at); 
            var month = monthNames[d.getMonth()];
            var dateN = d.getDate();
            var dateY = d.getFullYear();
            return dateN+" "+month+","+dateY;
} else {

return "";
}
          }
        },

				{ data: "email" },

				{ data: "phone_no" },
				{ data: "referral_code" },
				{ data: null,
            render: function(data, type, row, meta) {
              if(!data.start_date){
                var enddate = "<p style='color:blue;'>Not Start Date</p>";
              }
              else{
              const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
              const d = new Date(data.start_date); 
              var month = monthNames[d.getMonth()];
              var dateN = ("0" + (d.getDate())).slice(-2);
              var dateY = d.getFullYear();
              var enddate = dateN+" "+month+","+dateY;
              }
             return  enddate
            }
          },

         { data: null,
            render: function(data, type, row, meta) {
              if(!data.end_date){
                var enddate = "<p style='color:blue;'>Not End Date</p>";
              }
              else{
              const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
              const d = new Date(data.end_date); 
              var month = monthNames[d.getMonth()];
              var dateN = ("0" + (d.getDate())).slice(-2);
              var dateY = d.getFullYear();
              var enddate = dateN+" "+month+","+dateY;
              }
             return  enddate
            }
          },

		  { data: null,
            render: function(data, type, row, meta) {
              var todayDate = new Date();
              var month = ("0" + (todayDate.getMonth() + 1)).slice(-2);
              var year = todayDate.getUTCFullYear(); 
              var tdate = ("0" + (todayDate.getDate())).slice(-2);
              var maxDate = year + "-" + month + "-" + tdate;
              if(data.end_date <= maxDate)
              {
                status = "<span style='color:red'>Expire Pack</span>";
              }
              else if (data.status == "1") {
                status = "<span style='color:green'>Running</span>";
              }
           
              else{
                status ="<span style='color:blue;font-weight:500'>Applied</span>";
              }
              return  status
            }  
          },
	
				{ data: null,
					render: function(data, type, row, meta) 
					{
						return ' <a href="edit-admins/'+data.id+'" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><a href="delete-admins/'+data.id+'" onclick="return deleteAdmin()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg></a>';
					} 
				},

				  { data: null,
					render: function(data, type, row, meta) {
						var status ='<label class="switch" onchange="myActive('+data.id+');"><input type="hidden" name="status" id="active" value="1"><input type="checkbox"><span class="slider round"></span></label>';
						
						if (data.status == "1") {
							status = '<label class="switch" onchange="myStatus('+data.id+');"><input type="hidden" name="status" id="status" value="2"><input type="checkbox" checked><span class="slider round"></span></label>';
						}
						return  status
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
		console.log('id',id)
		if (list.includes(id)) {
		list.pop(id)
		} else { 
		  list.push(id);
		}
		console.log(list)
    });


	}

	//Active and inactive
	
	function myActive(id){
        var status = document.getElementById('active').value;
       
        $.ajax({
             method: "POST",
             url: "admin-status/"+id,
             
             data: {
                    _token: '<?php echo csrf_token();?>',
                    id: id,
                    status:status
                },
            success: function (data) {
                    alert(data);
                    window.location.reload()
                }    
        });
    }

    function myStatus(id){
        var status = document.getElementById('status').value;
       
        $.ajax({
             method: "POST",
             url: "admin-status/"+id,
             
             data: {
                    _token: '<?php echo csrf_token();?>',
                    id: id,
                    status:status
                },
            success: function (data) {
                    alert(data);
                    window.location.reload()
                }    
        });
    }
//skv 
    $("#button").click(function(){
    $("#date").toggle();
    });
 //skv  Expired plans
  function ExpireShowData() {
  var todayDate = new Date();
  var month = ("0" + (todayDate.getMonth() + 1)).slice(-2);
  var year = todayDate.getUTCFullYear(); 
  var tdate = ("0" + (todayDate.getDate())).slice(-2); 
  var maxDate = year + "-" + month + "-" + tdate;

    var t = $('#example').DataTable( {
      processing: true,
      serverSide: true,
      destroy: true,
        "ajax": {
        url: "expire-date-search",
        "data": function ( d ) {
          return $.extend( {}, d, {
            "user-status": maxDate,
          });
        }
       },

      columns: [
        { data: null },   
        { data: null,

          render: function(data, type, row, meta) {

          return data.status == "1" ?  '<img src="https://icon2.cleanpng.com/20171221/lle/success-png-image-5a3c42bdc288a6.4170951415138986857968.jpg" width="15px" height="15px;margin-left:5px;" />' +data.name : data.status == "2" ? '<img src="https://icon-library.com/images/inactive-icon/inactive-icon-8.jpg" width="15" height="15">' +data.name : data.name;
         }

         },

        { data: null,
          render: function(data, type, row, meta) {
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        if (undefined  !== data.created_at) {
    
	const d = new Date(data.created_at); 
            var month = monthNames[d.getMonth()];
            var dateN = d.getDate();
            var dateY = d.getFullYear();
            return dateN+" "+month+","+dateY;
} else {
return "";
}
          }
        },

        { data: "email" },

        { data: "phone_no" },
	{ data: "referral_code" },
        { data: null,
            render: function(data, type, row, meta) {
              if(!data.start_date){
                var enddate = "<p style='color:blue;'>Not Start Date</p>";
              }
              else{
              const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
              const d = new Date(data.start_date); 
              var month = monthNames[d.getMonth()];
              var dateN = ("0" + (d.getDate())).slice(-2);
              var dateY = d.getFullYear();
              var enddate = dateN+" "+month+","+dateY;
              }
             return  enddate
            }
          },

         { data: null,
            render: function(data, type, row, meta) {
              if(!data.end_date){
                var enddate = "<p style='color:blue;'>Not End Date</p>";
              }
              else{
              const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
              const d = new Date(data.end_date); 
              var month = monthNames[d.getMonth()];
              var dateN = ("0" + (d.getDate())).slice(-2);
              var dateY = d.getFullYear();
              var enddate = dateN+" "+month+","+dateY;
              }
             return  enddate
            }
          },

      { data: null,
            render: function(data, type, row, meta) {
              var todayDate = new Date();
              var month = ("0" + (todayDate.getMonth() + 1)).slice(-2);
              var year = todayDate.getUTCFullYear(); 
              var tdate = todayDate.getDate(); 
              var maxDate = year + "-" + month + "-" + tdate;
              if(data.end_date <= maxDate)
              {
                status = "<span style='color:red'>Expire Pack</span>";
              }
              else if (data.status == "1") {
                status = "<span style='color:green'>Running</span>";
              }
           
              else{
                status ="<span style='color:blue;font-weight:500'>Applied</span>";
              }
              return  status
            }  
          },
  
        { data: null,
          render: function(data, type, row, meta) 
          {
            return '  <a href="manage-settings/'+data.id+'" title="settings"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> </a> <a href="manage-subadmins/'+data.id+'" title="view subadmins"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> </a> <a href="edit-admins/'+data.id+'" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><a href="delete-admins/'+data.id+'" onclick="return deleteAdmin()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg></a>';
          } 
        },

          { data: null,
          render: function(data, type, row, meta) {
            var status ='<label class="switch" onchange="myActive('+data.id+');"><input type="hidden" name="status" id="active" value="1"><input type="checkbox"><span class="slider round"></span></label>';
            
            if (data.status == "1") {
              status = '<label class="switch" onchange="myStatus('+data.id+');"><input type="hidden" name="status" id="status" value="2"><input type="checkbox" checked><span class="slider round"></span></label>';
            }
            return  status
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
    console.log('id',id)
    if (list.includes(id)) {
    list.pop(id)
    } else { 
      list.push(id);
    }
    console.log(list)
    });
  }
 
function filterData() {
	var planId = document.getElementById("user-plans").value;

	var t = $('#example').DataTable({
		processing: true,
		serverSide: true,
		destroy: true,
		ajax: {
			url: "{{url('admins-retrival')}}",
			data: function ( d ) {
				return $.extend( {}, d, {
					"userPlan": planId,
				});
			}
		},
		columns: [
		{ data: null },
				
		{ data: null,

          render: function(data, type, row, meta) {

	var referredBy = "";
			if (undefined !== data.referredBy) {
				referredBy = "<br/><span style='color:blue'>ReferredBy:</span>"+data.referredBy;
			}

          return data.status == "1" ?  '<img src="https://icon2.cleanpng.com/20171221/lle/success-png-image-5a3c42bdc288a6.4170951415138986857968.jpg" width="15px" height="15px;margin-left:5px;" />' +data.name+referredBy : data.status == "2" ? '<img src="https://icon-library.com/images/inactive-icon/inactive-icon-8.jpg" width="15" height="15">' +data.name+referredBy : data.name+referredBy;
         }

         },

        { data: null,
            render: function(data, type, row, meta) {
				const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
				if (undefined  !== data.created_at) {
					const d = new Date(data.created_at); 
					var month = monthNames[d.getMonth()];
					var dateN = ("0" + (d.getDate())).slice(-2);
					var dateY = d.getFullYear();
					console.log(d)
					console.log(data.created_at)
					return dateN+" "+month+","+dateY;
				} else {
					return "";
				}
            }
          },

				{ data: "email" },

    
				{ data: null,
					render: function(data, type, row, meta) {
						var creditPoint = "<br/><span style='color:blue'>Credit Point:</span> NA<br/>";
						if(data.creditPoint != 0) {
							creditPoint = "<br/><span style='color:blue'>Credit Point:</span>"+data.creditPoint+"</br>";
						}
						var activeUpto = "<br/><span style='color:red'>Active:</span> NA";
						if(data.activeUpto != 0) {
							activeUpto = "<br/><span style='color:red'>Active:</span>"+data.activeUpto;
						}
						return data.phone_no+ "<br/>"+ creditPoint+ activeUpto;
					}
				},
				{ data: "referral_code" },
				{ data: null,
					render: function(data, type, row, meta) {
						var plan = "NA"
						switch(data.user_plans_id) {
							case 1 : plan = "15 Days"; break;
							case 2 : plan = "1 Month"; break;
							case 3 : plan = "3 Months"; break;
							case 4 : plan = "6 Months"; break;
							case 5 : plan = "Trial (3 Days)"; break;
						}
						return  plan
					}
				},

				{ data: null,
					render: function(data, type, row, meta) {
					if(!data.start_date){
						var enddate = "<p style='color:blue;'>Not Start Date</p>";
					} else {
						const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
						const d = new Date(data.start_date); 
						var month = monthNames[d.getMonth()];
						var dateN = ("0" + (d.getDate())).slice(-2);
						var dateY = d.getFullYear();
						var enddate = dateN+" "+month+","+dateY;
					}
					return  enddate
				}
			},

			{ data: null,
            render: function(data, type, row, meta) {
              if(!data.end_date){
                var enddate = "<p style='color:blue;'>Not End Date</p>";
              }
              else{
              const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
              const d = new Date(data.end_date); 
              var month = monthNames[d.getMonth()];
              var dateN = ("0" + (d.getDate())).slice(-2);
              var dateY = d.getFullYear();
              var enddate = dateN+" "+month+","+dateY;
              }
             return  enddate
            }
          },
        // { data: "end_date"},

				{ data: null,
					render: function(data, type, row, meta) {
	          var todayDate = new Date();
            var month = ("0" + (todayDate.getMonth() + 1)).slice(-2);
            var year = todayDate.getUTCFullYear(); 
            var tdate = ("0" + (todayDate.getDate())).slice(-2); 
            var maxDate = year + "-" + month + "-" + tdate;
            if(data.end_date <= maxDate)
            {
              status = "<span style='color:red'>Expire Pack</span>";
            }
						else if (data.status == "1") {
							status = "<span style='color:green'>Running</span>";
						}

            else{
              status ="<span style='color:blue;font-weight:500'>Applied</span>";
            }
						return  status
					}	 
				},
				
				{ data: null,
					render: function(data, type, row, meta) {
						return ' <a href="edit-admins/'+data.id+'" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><a href="delete-admins/'+data.id+'" onclick="return deleteAdmin()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg></a>';
					} 
				},

                
        { data: null,
					render: function(data, type, row, meta) {
						var status ='<label class="switch" onchange="myActive('+data.id+');"><input type="hidden" name="status" id="active" value="1"><input type="checkbox"><span class="slider round"></span></label>';
						
						if (data.status == "1") {
							status = '<label class="switch" onchange="myStatus('+data.id+');"><input type="hidden" name="status" id="status" value="2"><input type="checkbox" checked><span class="slider round"></span></label>';
						}
						return  status
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
    });

	$('#example tbody').on( 'click', 'tr', function () {
		$(this).toggleClass('selected');

		var id = t.row( this ).data().id
		console.log('id',id)
		if (list.includes(id)) {
			list.pop(id)
		} else { 
		  list.push(id);
		}
		
		console.log(list)
    });
	
	}
function titleCase(str) {
   var splitStr = str.toLowerCase().split(' ');
   for (var i = 0; i < splitStr.length; i++) {
       // You do not need to check if i is larger than splitStr length, as your for does that for you
       // Assign it back to the array
       splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);     
   }
   // Directly return the joined string
   return splitStr.join(' '); 
}
    </script>

@endpush
@endsection
