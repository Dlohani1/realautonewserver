<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Session;
use Auth;
use DB;
//use Hash;
use App\Models\Lead;
use Illuminate\Support\Facades\Hash;
class DashboardController extends Controller
{

    public function __construct()
    {
       $this->middleware('auth');
    }

    /**
     * Show the Admin Dashboard Page.
     *
     * @return \Illuminate\Http\Response
     */
	public function dashboard(Request $request){

		$source = array('Form HTML','Google','GoogleAds_PPC','FacebookTraffic','Organic','Wordpress');
		if (Auth::user()->usertype == 2) {
		$data["total_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1']])->count();
		$data["hot_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1'],['lead_status','=','1']])->count();
		$data["closed_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1'],['lead_status','=','2']])->count();
		$data["visited_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1'],['lead_status','=','3']])->count();
		
	    	$data["progress_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1'],['lead_status','=','0']])->count();
		$data["wrongno_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1'],['lead_status','=','7']])->count();
		$data["fake_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1'],['lead_status','=','4']])->count();
	
		$data["notinterested_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1'],['lead_status','=','6']])->count();
		$data["notreachable_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1'],['lead_status','=','8']])->count();
		$data["outoflocation_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1'],['lead_status','=','5']])->count();
	
		
		$data["uploads_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1']])->where('source','Upload')->count();
		$data["facebook_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1']])->where('source','Facebook')->count();
		
		$data["website_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1']])->wherein('source',$source)->count();
       
    	$data["self_leds"] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1']])->where('source','Self')->count();
    	//$data["assigned_leads"] = Lead::where('assigned_to',Auth::user()->id)->count();
    	


$data['today_upload_leads'] =Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1']])->whereIn('source',['Self','Upload'])->where('created_at','like',"%".date('Y-m-d')."%")->count();
$data['today_fb_leads'] =Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1']])->where('source','Facebook')->where('created_at','like',"%".date('Y-m-d')."%")->count();
$data['today_total_leads'] = Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1']])->where('created_at','like',"%".date('Y-m-d')."%")->count();
$data['today_website_leads'] =Lead::where([['user_id','=',Auth::user()->id],['is_active','=','1']])->wherein('source',$source)->where('created_at','like',"%".date('Y-m-d')."%")->count();
}
		$leadData = array();
		/*
		
		
		$subAdmin = DB::table('users')->where('admin_id',Auth::user()->id)->count();
		
		if ($subAdmin > 0) {

			$subAdmin = DB::table('users')->select('id','name')->where('admin_id',Auth::user()->id)->get();

			$assignedLeadCount = 0;
			$hotLeadCount = 0;
			$closedLeadCount = 0;
			$progressLeadCount = 0;
			
			$siteVisitLeadCount = 0;
			$fakeLeadCount = 0;
			$outOfLocationCount = 0;
			$notInterestedCount = 0;
			$wrongNoCount = 0;
			$notReachableCount = 0;
			$notAttended = 0;
			
			$notActiveLeadsCount = 0;

			date_default_timezone_set("Asia/kolkata");
            
            $todayDate = date("Y-m-d");

			foreach($subAdmin as $key => $value) {
				$leadData[$value->id]['assignedLeads'] =  Lead::where([['assigned_to','=',$value->id],['is_active','=','1']])->count();

				$leadData[$value->id]['name'] = $value->name; 
				$leadData[$value->id]['todayAssigned']  = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_assigned_on','like',"%$todayDate%"]])->count();

				
				$leadData[$value->id]['notActiveLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','0']])->count();

			
				$leadData[$value->id]['hotLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_status','=','1']])->count();
			
				$leadData[$value->id]['closedLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_status','=','2']])->count();
		
				$leadData[$value->id]['siteVisitLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_status','=','3']])->count();
			
				$leadData[$value->id]['fakeLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_status','=','4']])->count();
			
				$leadData[$value->id]['outOfLocationLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','5'],['lead_status','=','1']])->count();
			
				$leadData[$value->id]['notInterestedLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','6'],['lead_status','=','1']])->count();
			
				$leadData[$value->id]['wrongNoLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_status','=','7']])->count();
			
				$leadData[$value->id]['notReachableLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_status','=','8']])->count();
				$total = Lead::select('id')->where('lead_status','0')->where('assigned_to',$value->id)->count();

				$leadData[$value->id]['progressLeads'] = $total - $notAttended ;

			}
		}
*/

		//fix this below
		/*
		if ($subAdmin > 0) {
			$leads = DB::table('leads')
			->select('leads.id', 'lead_status', 'assigned_to', 'users.name','leads.status' )
			->join('users','users.id','=','leads.assigned_to')
			->where('user_id', Auth::user()->id)
			->where('assigned_to','!=','0')
			->orderby('assigned_to')
			->get();
		
			
			$assignedLeadCount = 0;
			$hotLeadCount = 0;
			$closedLeadCount = 0;
			$progressLeadCount = 0;
			
			$siteVisitLeadCount = 0;
			$fakeLeadCount = 0;
			$outOfLocationCount = 0;
			$notInterestedCount = 0;
			$wrongNoCount = 0;
			$notReachableCount = 0;
			$notAttended = 0;
			
			$notActiveLeadsCount = 0;

			date_default_timezone_set("Asia/kolkata");
            
            $todayDate = date("Y-m-d");
			
			foreach($leads as $key => $value) {

				$leadData[$value->assigned_to]['name'] = $value->name; 
				$leadData[$value->assigned_to]['todayAssigned']  = Lead::where([['assigned_to','=',$value->assigned_to],['is_active','=','1'],['lead_assigned_on','like',"%$todayDate%"]])->count();
 

				if(isset($oldValue) && $value->assigned_to != $oldValue){
					$assignedLeadCount = 0;
					$hotLeadCount = 0;
					$closedLeadCount = 0;
					$progressLeadCount = 0;
					$siteVisitLeadCount = 0;
					$fakeLeadCount = 0;
					$outOfLocationCount = 0;
					$notInterestedCount = 0;
					$wrongNoCount = 0;
					$notReachableCount = 0;
					$notAttended = 0;
	
				}
				 
				$leadAttended = DB::table('lead_comments')->select('lead_id')->distinct('lead_id')->where('user_id',$value->assigned_to) ->get();

				$commentedIds = [];
				foreach($leadAttended as $key1 => $value1) {
        				$commentedIds[] = $value1->lead_id;
				}

	
				$leadData[$value->assigned_to]['assignedLeads'] = ++$assignedLeadCount;
				
				if ($value->status == "0") {
					$leadData[$value->assigned_to]['notActiveLeads'] = ++$notActiveLeadsCount;
				}
				if ($value->lead_status == "0") {
					//$leadData[$value->assigned_to]['progressCount'] = ++$progressLeadCount;
					
					//$leadData[$value->assigned_to]['progressLeads'] = ++$progressLeadCount;

					if (!in_array($value->id, $commentedIds)) {
						$leadData[$value->assigned_to]['unattendedLeads'] = ++$notAttended;
					} 
				
				}
				if ($value->lead_status == "1") {
					$leadData[$value->assigned_to]['hotLeads'] = ++$hotLeadCount;
				}
				
				if ($value->lead_status == "2") {
					$leadData[$value->assigned_to]['closedLeads'] = ++$closedLeadCount;
				}
				
				if ($value->lead_status == "3") {
					$leadData[$value->assigned_to]['siteVisitLeads'] = ++$siteVisitLeadCount;
				}
				
				if ($value->lead_status == "4") {
					$leadData[$value->assigned_to]['fakeLeads'] = ++$fakeLeadCount;
				}
				
				if ($value->lead_status == "5") {
					$leadData[$value->assigned_to]['outOfLocationLeads'] = ++$outOfLocationCount;
				}
				
				if ($value->lead_status == "6") {
					$leadData[$value->assigned_to]['notInterestedLeads'] = ++$notInterestedCount;
				}
				
				if ($value->lead_status == "7") {
					$leadData[$value->assigned_to]['wrongNoLeads'] = ++$wrongNoCount;
				}
				
				if ($value->lead_status == "8") {
					$leadData[$value->assigned_to]['notReachableLeads'] = ++$notReachableCount;
				}
		
				
				$total = Lead::select('id')->where('lead_status','0')->where('assigned_to',$value->assigned_to)->count();

				$leadData[$value->assigned_to]['progressLeads'] = $total - $notAttended ;

				$oldValue = $value->assigned_to;
			}	
		}
		*/
		//fix this above
		
		if (Auth::user()->usertype == 3) {
			$data["assigned_leads"] = Lead::where('assigned_to',Auth::user()->id)->count();
			date_default_timezone_set("Asia/kolkata");
			$todayDate = date("Y-m-d");
			$data['todayAssignedLeads'] = Lead::where([['assigned_to','=',Auth::user()->id],['is_active','=','1'],['lead_assigned_on','like',"%$todayDate%"]])->count();
			$data['todayFollowup'] = DB::table('leads_followups')->where([['user_id','=',Auth::user()->id],['followup_date','=',$todayDate],['status','=',null]])->count();
		
		}
		
		$data['trialUsers'] = DB::table('user_plan_details')->where('user_plans_id', 5)->count();
		
		$data['activeUsers'] = DB::table('user_plan_details')->wherein('user_plans_id', [2,3,4])->count();
		
		date_default_timezone_set("Asia/kolkata");
		$todayDate = date("Y-m-d");
		$currentTime = date("H:m:s");
		
		$data['missedFollowups'] = DB::table('leads_followups')
		->where('user_id', Auth::user()->id)
		->where('followup_date', '=',$todayDate)
		->where('followup_time', '<',$currentTime)
		->where('status',null)
		->count();
		
	$assignedLeadIds = Lead::select('id')->where('assigned_to',Auth::user()->id)->get();
		
	$progressLeads = Lead::select('id')->where('lead_status','0')->where('assigned_to',Auth::user()->id)->count();


	$leadIds = array();
	
	foreach($assignedLeadIds as $key => $value) {
		$leadIds[] = $value->id;
	}
	
	$data['attendedLeads'] = DB::table('lead_comments')
	->where('user_id',Auth::user()->id)
	->whereIn('lead_id', $leadIds)
	->count();
		
	$leadAttended = DB::table('lead_comments')
    ->select('lead_id')
    ->distinct('lead_id')
    ->where('user_id',Auth::user()->id)
    ->count();


		//$data['unattendedLeads'] = $assignedLeadCount;
		
		//sub admin dashboard
if (Auth::user()->usertype == 3 ) {
$data['hotLeadsNo'] = DB::table('leads')->where([['assigned_to', Auth::user()->id],['lead_status','1']])->count();

$data['closedLeadsNo'] = DB::table('leads')->where([['assigned_to', Auth::user()->id],['lead_status','2']])->count();

$data['siteVisitNo'] = DB::table('leads')->where([['assigned_to', Auth::user()->id],['lead_status','3']])->count();

$data['fakeLeadsNo'] = DB::table('leads')->where([['assigned_to', Auth::user()->id],['lead_status','4']])->count();

$data['pendingLeadsNo'] = DB::table('leads')->where([['assigned_to', Auth::user()->id],['lead_status','0']])->count();


$data['notReachableLeadsNo'] = DB::table('leads')->where([['assigned_to', Auth::user()->id],['lead_status','8']])->count();

$data['wrongNoLeadsNo'] = DB::table('leads')->where([['assigned_to', Auth::user()->id],['lead_status','7']])->count();

$data['notInterestedLeadsNo'] = DB::table('leads')->where([['assigned_to', Auth::user()->id],['lead_status','6']])->count();		

$data['outOfLocationLeadsNo'] = DB::table('leads')->where([['assigned_to', Auth::user()->id],['lead_status','5']])->count();


}

$data['leadsReport'] = $leadData;
		
if (Auth::user()->usertype == 1 ) {
	$data["total_users"] = User::where('usertype',2)->count();
			return view("dashboard",$data);

 } else if (Auth::user()->usertype == 3 ) {

 $data['progressCount'] = DB::table('lead_comments')
 ->select('lead_id')
 ->distinct('lead_id')
 ->where('user_id',Auth::user()->id)
 ->count();


$data['totalProgressLeads'] = Lead::select('id')->where('lead_status','0')->where('assigned_to',Auth::user()->id)->count();
$assignedLeadIds =  Lead::select('id')->where('lead_status','0')->where('assigned_to',Auth::user()->id)->get();

$commentedLeads = DB::table('lead_comments')
 ->select('lead_id')
 ->distinct('lead_id')
 ->where('user_id',Auth::user()->id)->get();

$commentedIds = [];
foreach($commentedLeads as $key => $value) {
	$commentedIds[] = $value->lead_id;
}
$count = 0;
foreach($assignedLeadIds as $key => $value) {
	if (!in_array($value->id ,$commentedIds)) {
		$count++;
	}
}

$data['progressCount'] =  $data['totalProgressLeads'] -  $count ;
if (Auth::user()->id == 48 || Auth::user()->id == 99) {

return view("sub-admin-dashboard1",$data);
} else {

return view("sub-admin-dashboard",$data);

}


}  else {

	return view("testpage1", $data);
}
	return view("dashboard",$data);
}

    /**
     * Show the Whats app API Capture
     *
     * @return \Illuminate\Http\Response
     */
	 
	public function whats_app_api_capture(Request $request){
        $data["whatsappapikey"] = User::where('id',Auth::user()->id)->get()[0]->whatsapp_api_key;
        $data["whatsapp_username"] = User::where('id',Auth::user()->id)->get()[0]->whatsapp_username;
		return view("whats-app-api-capture",$data);
	}
	/**
     * Show the Whats app API Capture POST
     *
     * @return \Illuminate\Http\Response
     */
    public function post_whats_app_api_capture(Request $request){

            $data = array(
                'whatsapp_api_key'                    => $request['whatsapp_api_key'],
                'whatsapp_username'                   => $request['whatsapp_username'],
                'whatsapp_api_key_lock'               => 2,
            );

        User::where('id', Auth::user()->id)->update($data);

        Session::flash('message', "Whats app API Capture Updated successfully.");
        return redirect('whats-app-api-capture');
    }


    /**
     * Show the Email API Capture
     *
     * @return \Illuminate\Http\Response
     */
	public function email_api_capture(Request $request){
        $data["emailapikey"] = User::where('id',Auth::user()->id)->get()[0]->email_api_key;
		return view("email-api-capture",$data);
	}


    /**
     * Show the Email API Capture POST
     *
     * @return \Illuminate\Http\Response
     */
    public function post_email_api_capture(Request $request){

        $data = array(
            'email_api_key'                    => $request['email_api_key'],
            'email_api_key_lock'               => 2,
        );

    User::where('id', Auth::user()->id)->update($data);

    Session::flash('message', "Email API Capture Updated successfully.");
    return redirect('email-api-capture');
}

    /**
     * Show the SMS Api Capture
     *
     * @return \Illuminate\Http\Response
     */
	public function sms_api_capture(Request $request){
        $data["smsapikey"] = User::where('id',Auth::user()->id)->get()[0]->sms_api_key;
        $data["sms_from_name"] = User::where('id',Auth::user()->id)->get()[0]->sms_from_name;
		return view("sms-api-capture",$data);
	}

    /**
     * Show the Email API Capture POST
     *
     * @return \Illuminate\Http\Response
     */
    public function post_sms_api_capture(Request $request){

        $data = array(
            'sms_api_key'                    => $request['sms_api_key'],
            'sms_from_name'                  => $request['sms_from_name'],
            'sms_api_key_lock'               => 2,
        );

    User::where('id', Auth::user()->id)->update($data);

    Session::flash('message', "Sms API Capture Updated successfully.");
    return redirect('sms-api-capture');
}



    /**
     * Show the Change Password
     *
     * @return \Illuminate\Http\Response
     */
	public function change_password(Request $request){

		$user = User::where('id', Auth::user()->id)->select('email','phone_no')->first();

		$data = array("user" => $user);
		return view("change-password", $data);
	}

	public function post_user_update_password(Request $request){

        $this->validate(request(),[
            'oldpassword'                    => 'required',
            'new_password'                   => 'required',
            'c_f_password'                   => 'required|same:new_password',
        ]);


        if (!Hash::check($request['oldpassword'], Auth::user()->password)) {
            Session::flash('error', "old password doesn't match.");
            return redirect('/change-password');
         }else{
            DB::table('users')
			->where('id', Auth::user()->id)
			->update(array(
				'password' => Hash::make($request['c_f_password']),
			));

            Session::flash('message', "Password Updated Successfully.");
            return redirect('change-password');
         }




}



	public function adminlogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->intended('/admin');
    }

    public function leadsDetails() {

    	$leadData = array();
		
		$subAdmin = DB::table('users')->where('admin_id',Auth::user()->id)->count();
		
		if ($subAdmin > 0) {

			$subAdmin = DB::table('users')->select('id','name')->where('admin_id',Auth::user()->id)->get();

			$assignedLeadCount = 0;
			$hotLeadCount = 0;
			$closedLeadCount = 0;
			$progressLeadCount = 0;
			
			$siteVisitLeadCount = 0;
			$fakeLeadCount = 0;
			$outOfLocationCount = 0;
			$notInterestedCount = 0;
			$wrongNoCount = 0;
			$notReachableCount = 0;
			$notAttended = 0;
			
			$notActiveLeadsCount = 0;

			date_default_timezone_set("Asia/kolkata");
            
            $todayDate = date("Y-m-d");

			foreach($subAdmin as $key => $value) {
				$leadData[$value->id]['assignedLeads'] =  Lead::where([['assigned_to','=',$value->id],['is_active','=','1']])->count();

				$leadData[$value->id]['name'] = $value->name; 
				$leadData[$value->id]['todayAssigned']  = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_assigned_on','like',"%$todayDate%"]])->count();

				
				$leadData[$value->id]['notActiveLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','0']])->count();

			
				$leadData[$value->id]['hotLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_status','=','1']])->count();
			
				$leadData[$value->id]['closedLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_status','=','2']])->count();
		
				$leadData[$value->id]['siteVisitLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_status','=','3']])->count();
			
				$leadData[$value->id]['fakeLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_status','=','4']])->count();
			
				$leadData[$value->id]['outOfLocationLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','5'],['lead_status','=','1']])->count();
			
				$leadData[$value->id]['notInterestedLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','6'],['lead_status','=','1']])->count();
			
				$leadData[$value->id]['wrongNoLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_status','=','7']])->count();
			
				$leadData[$value->id]['notReachableLeads'] = Lead::where([['assigned_to','=',$value->id],['is_active','=','1'],['lead_status','=','8']])->count();
				$total = Lead::select('id')->where('lead_status','0')->where('assigned_to',$value->id)->count();


$assignedLeadIds =  Lead::select('id')->where('lead_status','0')->where('assigned_to',$value->id)->get();

$commentedLeads = DB::table('lead_comments')
 ->select('lead_id')
 ->distinct('lead_id')
 ->where('user_id',$value->id)->get();

$commentedIds = [];
foreach($commentedLeads as $key1 => $value1) {
	$commentedIds[] = $value1->lead_id;
}
$count = 0;
foreach($assignedLeadIds as $key2 => $value2) {
	if (!in_array($value2->id ,$commentedIds)) {
		$count++;
	}
}
$notAttended = $count;

$leadData[$value->id]['unattendedLeads'] = $notAttended;
$leadData[$value->id]['progressLeads'] = $total - $notAttended ;

			}
		}

		return response()->json($leadData); 

    }
}
