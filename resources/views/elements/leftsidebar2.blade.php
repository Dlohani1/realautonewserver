
<div id="sidebar-menu" class="slimscroll-menu">
    <ul class="metismenu" id="menu-bar">
        <li class="menu-title"></li>

        <li>
            <a href="{{ route('dashboard') }}">
                <i data-feather="home"></i>
                <span> Dashboard </span>
            </a>
        </li>

        <?php  if(Auth::user()->usertype == 1){ ?>
        <li>
            <a href="javascript: void(0);">
                <i data-feather="users"></i>
                <span> Manage Admins </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="{{ url('/manage-admins') }}">Admins</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);">
                <i data-feather="settings"></i>
                <span> Settings </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="{{ url('/whats-app-api-capture') }}">Whats app API Capture</a></li>
                {{--<li><a href="{{ url('/email-api-capture') }}">Email API Capture</a></li>
                <li><a href="{{ url('/sms-api-capture') }}">SMS Api Capture</a></li>--}}
                <li><a href="{{ url('/change-password') }}">Change Password</a></li>
            </ul>
        </li>

        <?php }elseif(Auth::user()->usertype == 2){ ?>

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
				<li><a href="{{ url('/leads-master') }}">All Leads</a></li>
				<li><a href="{{ url('/add-leads') }}">Add Lead</a></li>
				<?php
				if (Auth::user()->id == "8") { ?>
				<li><a href="{{ url('/leads-assigned') }}">Assign Leads</a></li>
				<!--<li><a href="{{ url('/leads-assigned-staff') }}">Leads Report</a></li>-->
				<?php } ?>
            </ul>
        </li>

        <li>
            <a href="{{ url('/automation-master') }}">
                <i data-feather="loader"></i>
                <span> Campaigns </span>
            </a>
        </li>

        <li>
            <a href="{{ route('bulk_sms_master') }}">
                <i data-feather="smartphone"></i>
                <span> Bulk SMS </span>
            </a>
        </li>

        <li hidden>
            <a href="javascript: void(0);">
                <i data-feather="settings"></i>
                <span> Settings </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="{{ url('/whats-app-api-capture') }}">Whats app API Capture</a></li>
                <li><a href="{{ url('/email-api-capture') }}">Email API Capture</a></li>
                <li><a href="{{ url('/sms-api-capture') }}">SMS Api Capture</a></li>
                <li><a href="{{ url('/change-password') }}">Change Password</a></li>
            </ul>
        </li>
		 <li >
            <a href="javascript: void(0);">
                <i data-feather="settings"></i>
                <span> Settings </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="{{ url('/change-password') }}">Change Password</a></li>
				<li><a href="{{ url('/add-staff') }}">Add Staff</a></li>
            </ul>
        </li>

        <?php } elseif(Auth::user()->usertype == 3){ ?>
			<li>
            <a href="javascript: void(0);">
                 <i data-feather="slack"></i>
                <span> Leads </span>
                <span class="menu-arrow"></span>
            </a>
            <ul class="nav-second-level" aria-expanded="false">
                <li><a href="{{ url('/view-assigned-leads') }}">View</a></li>
				<li><a href="{{ url('/view-leads-followUp') }}">Follow Ups</a></li>
            </ul>
        </li>
		<?php
		} else { ?>



        <?php } ?>
    </ul>
</div>

