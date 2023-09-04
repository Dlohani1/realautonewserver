<style>
#mySidenav a {
  position: absolute;
  right: -100px;
  transition: 0.3s;
  padding: 25px;
  width: 120px;
  text-decoration: none;
  color: white;
  border-radius: 0 5px 5px 0; 
}

#mySidenav a:hover {
  right: 0;
}

#about {
  top: 230px;
}

button#togglePipButton {
    margin-left: 1099px;
}
embed {
    margin-top: -49px;
    margin-left: -81px;
}
</style>
<div class="left-side-menu">
<div class="media user-profile mt-2 mb-2">
    <img src="{{url('/')}}/assets/images/users/agent1.png" class="avatar-sm rounded-circle mr-2" alt="img" />
    <img src="{{url('/')}}/assets/images/users/agent1.png" class="avatar-xs rounded-circle mr-2" alt="img" />

    <div class="media-body">
        <h6 class="pro-user-name mt-0 mb-0">{{ ucwords(Auth::user()->name)}}</h6>
        @if(Auth::user()->usertype == 1)
        <span class="pro-user-desc">Admin</span>
        @elseif(Auth::user()->usertype == 2)
        <span class="pro-user-desc">Member</span>
        @elseif(Auth::user()->usertype == 3)
         <span class="pro-user-desc">Lead</span>
		@elseif(Auth::user()->usertype == 4)
         <span class="pro-user-desc">Team</span>
        @endif 
    </div>
    <!--<div class="dropdown align-self-center profile-dropdown-menu">
        <a class="dropdown-toggle mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
            aria-expanded="false">
            <span data-feather="chevron-down"></span>
        </a>
        <div class="dropdown-menu profile-dropdown">
           <a href="#" class="dropdown-item notify-item">
                <i data-feather="user" class="icon-dual icon-xs mr-2"></i>
                <span>My Account</span>
            </a>
            <a href="javascript:void(0);" class="dropdown-item notify-item">
                <i data-feather="help-circle" class="icon-dual icon-xs mr-2"></i>
                <span>Support</span>
            </a>
            <a href="{{ url('logout') }}" class="dropdown-item notify-item">
                <i data-feather="lock" class="icon-dual icon-xs mr-2"></i>
                <span>Lock Screen</span>
            </a> 

            <div class="dropdown-divider"></div>

            <a href="{{ url('logout') }}" class="dropdown-item notify-item">
                <i data-feather="log-out" class="icon-dual icon-xs mr-2"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>-->

</div>

<div id="sidebar-menu" class="slimscroll-menu">
    <ul class="metismenu" id="menu-bar">
        <li class="menu-title"></li>

        <li>
            <a href="{{ route('dashboard') }}">
                <i data-feather="home"></i>
                <span> Dashboard </span>
            </a>
        </li>
            <?php if (Auth::user()->usertype == 1 || Auth::user()->usertype == 2) { ?>
 

			<li>
				<a href="{{ url('/automation-master') }}">
					<i data-feather="loader"></i>
					<span> Automation </span>
				</a>
			</li>
			
			 <li>
            <a href="{{ route('bulk_sms_master') }}">
                <i data-feather="smartphone"></i>
                <span> Send Broadcast</span>
            </a>
        </li> 


 

		<?php } ?>
	
            <?php if (Auth::user()->usertype == 1 || Auth::user()->usertype == 2) { ?>
                                <li>
            <a href="javascript: void(0);">
                 <i data-feather="slack"></i>
                <span>Integrations </span>
                <span class="menu-arrow"></span>
            </a>
                        <ul class="nav-second-level" aria-expanded="false">

                                <li><a href="{{ url('/facebook/integration') }}">Facebook Integration</a></li>
								 <li><a href="{{ url('/wordpress/integration') }}">Wordpress Integration</a></li>
                    
 <li>
                                <a href="{{ url('/generate-code') }}">
                                       
                                        <span> HTML Integration</span>
                                </a>
                        </li>
                                <!--<li><a href="{{ url('/leads-assigned-staff') }}">Leads Report</a></li>-->

                        </ul>
                        </li>
      


        <?php  if(Auth::user()->usertype == 1){ ?>
        <li>
            <a href="javascript: void(0);">
                <i data-feather="users"></i>
                <span> Manage Users </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="{{ url('/manage-admins') }}">Users</a></li>
		<li><a href="{{ url('/demo-scheduler') }}">Demos Scheduled</a></li>
		</ul>
        </li>

        <li>
            <a href="{{ route('gallery') }}">
                <i data-feather="settings"></i>
                <span> Settings </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="{{ url('/whats-app-api-capture') }}">WhatsApp API</a></li>
                <li><a href="{{ url('/email-api-capture') }}">Email API</a></li>
                <li><a href="{{ url('/sms-api-capture') }}">SMS API</a></li>
                <li><a href="{{ url('/change-password') }}">Change Password</a></li>
            </ul>
        </li>

          <li>
            <a href="{{url('/')}}/manage-sells">
                <i data-feather="users"></i>
                <span> Manage Team </span>
            </a>
        </li>

        <li>
            <a href="{{url('/')}}/users-assigned">
                <i data-feather="users"></i>
                <span> Assigned Users</span>
            </a>
        </li>
	<li>
           <!-- <a href="{{ route('add-video') }}">
                <i data-feather="video"></i>
                <span> Add Video </span>
            </a>
        </li>
	
  	<li>-->
            <a href="{{ route('tutorial') }}">
                <i data-feather="video"></i>
                <span> Add Tutorial Video </span>
            </a>
        </li>

        <?php } elseif(Auth::user()->usertype == 2 && Auth::user()->id != "59"){ ?>

        {{--<li>
            <a href="javascript: void(0);">
                <i data-feather="users"></i>
                <span> Admin User </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="{{ url('/manage-admin-user') }}">User's</a></li>
            </ul>
        </li>--}}

        <li>
            <a href="javascript: void(0);">
                <i data-feather="users"></i>
                <span> Leads </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
				<li><a href="{{ url('/leads-master') }}">My Leads</a></li>
				 <li><a href="{{ url('/view-subadmin-leads') }}"> Team Leads</a></li>
				<li><a href="{{ url('/leads-assigned') }}">Assign Leads</a></li>
   <li><a href="{{ url('/view-leads-followUp') }}">Follow Ups</a></li>
                <li><a href="{{ url('/overdue-followups') }}">Overdue Follow Ups</a></li>
				<!--<li><a href="{{ url('/leads-assigned-staff') }}">Leads Report</a></li>-->
				<?php //}
 ?>
            </ul>
        </li>

   <?php if (Auth::user()->usertype == 1 || Auth::user()->usertype == 2) { ?>     

     
        <!--<li>
            <a href="{{ route('send-whatsapp-to-groups') }}">
                <i data-feather="smartphone"></i>
                <span> WhatsApp Broadcast</span>
            </a>
        </li> -->
<?php } ?>

 <?php if (Auth::user()->usertype == 2 ) { ?>
   <li>
            <a href="/subscribe-plan">
                <i data-feather="home"></i>
                <span> Buy Credits </span>
            </a>
<?php }
}

 ?>

   <li>
            <a href="/tutorial-gallery">
                <i data-feather="video"></i>
                <span> Tutorials </span>
            </a>
        </li>
         

        <li hidden>
            <a href="javascript: void(0);">
                <i data-feather="settings"></i>
                <span> Settings </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="{{ url('/whats-app-api-capture') }}">WhatsApp API</a></li>
                <li><a href="{{ url('/email-api-capture') }}">Email API</a></li>
                <li><a href="{{ url('/sms-api-capture') }}">SMS API</a></li>
                <li><a href="{{ url('/change-password') }}">Change Password</a></li>
            </ul>
        </li>
		 <li style="margin-bottom: 30px;">
            <a href="javascript: void(0);">
                <i data-feather="settings"></i>
                <span> Settings </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">

                		<li><a href="{{ url('/change-password') }}">Change Password</a></li>
				<li><a href="{{ url('/add-segment') }}"><span> Segment </span></a></li>
				<li><a href="{{ url('/add-project') }}"><span> Project </span></a></li>
				<li><a href="{{ url('/add-staff') }}">Manage Team</a></li>
				<!--<li><a href="{{ url('/view-staff') }}">Staff List</a></li>-->
				<li><a href="{{ url('/lead-settings') }}"> Auto Assign Leads</a></li>
				<li><a href="{{ url('/account-settings') }}">  Account Settings</a></li>
            </ul>
        </li>
        
     
       <!-- video -->
<!--        <div id="mySidenav" class="sidenav">
          <a id="about"><button id="togglePipButton" style="width: 89px;height: 50px;margin-bottom: 20px;position: fixed;margin-top: -26px;background-color: #04AA6D">
                   <video id="video" src="http://www.w3schools.com/html/mov_bbb.mp4"  style="float: right;width: 129px;height: 72px;margin-top: -8px;margin-right: 
-37px;"></video> 
                     <iframe id="video"  src="https://player.vimeo.com/video/598228595?h=765274455d" width="640" height="400" frameborder="0" ></iframe> 
					 <embed src="{{url('/')}}/img/buttons.png" height="165" width="50"> 
                </button></a>
        </div> -->
        <!-- end -->

        <?php } elseif(Auth::user()->usertype == 3){ ?>
			<li>
            <a href="javascript: void(0);">
                 <i data-feather="slack"></i>
                <span> Leads </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
<!--<li><a href="{{ url('/add-leads-subadmin') }}">Add Lead</a></li>-->
                <li><a href="{{ url('/view-leads-subadmin') }}">View Team Leads</a></li>

                <li><a href="{{ url('/view-assigned-leads') }}">View Assigned Leads</a></li>
	        <li><a href="{{ url('/view-leads-followUp') }}">View Follow Ups</a></li>
                <li><a href="{{ url('/overdue-followups') }}">Overdue Follow Ups</a></li>
            </ul>
        </li>
		<?php
		} else { ?>



        <?php } ?>
    </ul>
</div>
</div>
<script type="text/javascript">

</script>
