@extends('layouts.app')
@section('title', 'All Leads')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- ajxa table get data  -->
 
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

.blur {
 text-shadow: 1px 1px 5px #aaa;
 color: transparent;
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
<?php
$ids = json_encode($leads);
?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" onclick="window.location.href='<?php echo url('/add-import-leads'); ?>'"> Import Lead <i class="uil-plus mr-1"></i></button>
                                <button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" onclick="window.location.href='<?php echo url('/add-leads'); ?>'"> Add Lead <i class="uil-plus mr-1"></i></button>
                                <button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" onclick="bulkDelete()"> Bulk Delete </button>
				<button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" onclick="bulkStop(1)">Stop Leads</button>
				<button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" onclick="bulkStop(0)">Switch ON Leads</button>

<button class="btn btn-primary mt-1 mr-1 pull-right" id="btn-new-event" onclick="setupCron()">Reset Leads</button>

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
															<table> 
     <tr>
 
       <td>
    <label for="search" style="display:block">Search Leads by : </label>
		 	<select onchange="showData(1)" id="leads-source">
				<option value="0">-- Source --</option>
				<option value="2">All</option>
				<option value="1">Only Facebook</option>				
			</select>
       </td>
	   <td>
	   <label for="search" style="display:block">Search By Date : </label><input  id="leads-date" style="display:block" type="date" onchange="showData(2)" />
	   </td>
	   <td style="padding-top:9%"><button type="button" style="display:block" onclick="showData(3)">Clear</button></td>
<td style="padding-top:9%"><button type="button" style="display:block" onclick="selectAll()">Select All</button></td>
     </tr>
   </table>
   <br/>
                                <div class="table-responsive">
                                     <table class="table table-bordered " id="example">
                                        <thead>
                                            <tr>
						
                                                <th>No</th>
												<th>Leads On </th>
                                                <th>Campaigns</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile No</th>
                                                <th>Project</th>
                                                <th>Segment</th>
												<th>Status </th>
                                                <th width="100px">Action</th>
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
<script type="text/javascript">
var d = "<?php echo $ids; ?>"
var result = [];
var myArray = <?= json_encode($leads); ?>;
console.log('m',myArray)

for(var i in d)
    result.push([i, d [i]]);

var t = Array.isArray(result);
console.log('da', result)

let list=new Array;


	$(document).ready(function() {

		var t = $('#example').DataTable( {


 "createdRow": function( row, data, dataIndex ) {
//console.log('d',data.id)
   if ( myArray.includes(data.id) ) {
console.log('test')
      $(row).addClass( 'blur' );
    }
  },
 
			//bLengthChange: false,
			//searching: false,
			processing: true,
			serverSide: true,
			ajax: "{{url('leads-retrival')}}",
 
			columns: [
				{ data: null },
				{ data: null,
					render: function(data, type, row, meta) {
						const monthNames = ["Jan", "Feb", "March", "April", "May", "June","July", "Aug", "Sept", "Oct", "Nov", "Dec"];
						const d = new Date(data.created_at); 
						var month = monthNames[d.getMonth()];
						var dateN = d.getDate();
						var dateY = d.getFullYear();
						return dateN+" "+month+","+dateY;
					}
				},
				{ data: "campaigns_name" },
				{ data: null,
					render: function(data, type, row, meta) {
						return data.source == "Facebook" ?  data.name+'<img src="https://www.seekpng.com/png/detail/851-8518238_facebook-icon-free-download-png-and-vector-facebook.png" width="20px" height="20px" />' :  data.name;
					}
				},
				{ data: "mail_id" },
				{ data: "mobile_no" },
				{ data: "project_name" },
				{ data: "segment_name" },
				{ data: null,
					render: function(data, type, row, meta) {
					
						
						return data.is_cron_disabled != "1" ?  '<span style ="color:green">ON</span>' :  '<span style ="color:red">OFF</span>';
						}
				},
				{ data: null,
					render: function(data, type, row, meta) {
return ' <a href="edit-leads/'+data.id+'" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><a href="delete-leads/'+data.id+'" onclick="return deleteConfirm()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg></a>&nbsp;<a title="View Details" href="view-reports/'+data.id+'" "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>';
						//return ' <a href="edit-leads/'+data.id+'" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><a href="delete-leads/'+data.id+'" onclick="return deleteConfirm()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg></a>';
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

function showData(source) {
	if(source == "3") {
		document.getElementById("leads-source").value = 0;
		document.getElementById("leads-date").value = "";
		var val = document.getElementById("leads-source").value;
		source = "social"
	} else if (source == "1") {
		var val = document.getElementById("leads-source").value;
		document.getElementById("leads-date").value = "";
		source = "social"
	} else {
		var val = document.getElementById("leads-date").value;
		document.getElementById("leads-source").value = 0;
		source = "leads-on"
	}	
		var t = $('#example').DataTable( {
			//bLengthChange: false,
			//searching: false,
			processing: true,
			serverSide: true,
			destroy: true,
			ajax: {
				url: "leads-retrival",
				data: function ( d ) {
					return $.extend( {}, d, {
						"source": source,
						"sourceVal":val
					});
				}
			 },
 
			columns: [
				{ data: null },
				{ data: null,
					render: function(data, type, row, meta) {
						const monthNames = ["Jan", "Feb", "March", "April", "May", "June","July", "Aug", "Sept", "Oct", "Nov", "Dec"];
						const d = new Date(data.created_at); 
						var month = monthNames[d.getMonth()];
						var dateN = d.getDate();
						var dateY = d.getFullYear();
						return dateN+" "+month+","+dateY;
					}
				},
				{ data: "campaigns_name" },
                                { data: null,

                                  render: function(data, type, row, meta) {


                                  return data.source == "Facebook" ?  data.name+'<img src="https://www.seekpng.com/png/detail/851-8518238_facebook-icon-free-download-png-and-vector-facebook.png" width="20px" height="20px" />' :  data.name;
	                         }

                                 },

				{ data: "mail_id" },
				{ data: "mobile_no" },
				{ data: "project_name" },
				{ data: "segment_name" },
				{ data: null,
					render: function(data, type, row, meta) {
					
						
						return data.is_cron_disabled != "1" ?  '<span style ="color:green">ON</span>' :  '<span style ="color:red">OFF</span>';
						}
				},
				{ data: null,
					render: function(data, type, row, meta) {
return ' <a href="edit-leads/'+data.id+'" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><a href="delete-leads/'+data.id+'" onclick="return deleteConfirm()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg></a>&nbsp;<a title="View Details" href="view-reports/'+data.id+'" "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a>';
						//return ' <a href="edit-leads/'+data.id+'" ><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a><a href="delete-leads/'+data.id+'" onclick="return deleteConfirm()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg></a>';
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

console.log('aa',t.row(this))
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

}
		
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

function bulkDelete() {

 var isSure = confirm("Do you really want to delete all the  selected leads ??");

    if (isSure) {

if (list.length > 0) {
//alert(list);
$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
})

var formData = new FormData();
		
		formData.append('leadIds', list);
		
		$.ajax({
			type:'POST',
			url: "{{ url('delete-bulkleads')}}",
			data: formData,
			cache:false,
			contentType: false,
			processData: false,
			success: (data) => {
				//alert("Leads deleted successfully")
swal("Success!","Leads Deleted Successfully!", "success");
setTimeout(function () {
        //alert('Reloading Page');
        location.reload(true);
      }, 2000);				
//window.location.reload()
			},
			error: function(data){
				console.log(data);
			}
	})
} else {

swal("Error!", "Please select Leads!", "info");
//alert("Please select Leads to Delete")

}
}
}

function setupCron() {

var isSure = confirm("Do you really want to setup the cron ??");


if (isSure) {

if (list.length > 0) {
//alert(list);
$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
})


var formData = new FormData();

formData.append('leadIds', list);
formData.append('status',status);

$.ajax({
        type:'POST',
        url: "{{ url('setup-cron')}}",
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {


//if (status == "1") {
//swal("Success!","Leads Switch OFF Successfully!", "success");
//} else {

swal("Success!","Leads Setup Successfully!", "success");

//}
setTimeout(function () {
        //alert('Reloading Page');
        location.reload(true);
      }, 2000);
//window.location.reload()
                        },
                        error: function(data){
                                console.log(data)
  
                        }
        })
} else {

swal("Error!", "Please select Leads!", "info");
//alert("Please select Leads to Delete")

}

}
}

function bulkStop(status) {

if (status == "1") {

 var isSure = confirm("Do you really want to OFF the  selected leads ??");
} else {

 var isSure = confirm("Do you really want to ON the  selected leads ??");

}

    if (isSure) {

if (list.length > 0) {
//alert(list);
$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
})
var formData = new FormData();

formData.append('leadIds', list);
formData.append('status',status);

$.ajax({
	type:'POST',
	url: "{{ url('deactivate-cron')}}",
	data: formData,
	cache:false,
	contentType: false,
	processData: false,
	success: (data) => {
                                //alert("Leads deleted successfully")
if (status == "1") {
swal("Success!","Leads Switch OFF Successfully!", "success");
} else {

swal("Success!","Leads Switch ON Successfully!", "success");

}
setTimeout(function () {
        //alert('Reloading Page');
        location.reload(true);
      }, 2000);
//window.location.reload()
                        },
                        error: function(data){
                                console.log(data);
                        }
        })
} else {

swal("Error!", "Please select Leads!", "info");
//alert("Please select Leads to Delete")

}
}
}


function selectAll() {
	
	var table = $('#example').DataTable();
	 
	table.rows().select();

	for (var i = 0; i < table.rows().data().length; i++) {
		
	  list.push(table.rows().data()[i].id)
	  
	}
	console.log('list',list)
}

</script>

@endsection
