<?php

namespace App\Http\Controllers;

use App\Role;
use App\RoleUser;
use App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Hash;
use Session;
use DB;

use App\Models\LeadcornCampaigns;
use App\Models\Lead;
use App\Models\Campaign;
use App\Models\LeadDetails;
use Helper;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{

public function subscribeplan(){

		return view('subscription');
	}


	public function manage_admins(){

		//$data['adminlist'] = User::where(array(array('usertype',2),array('status','=','3')))->get();
		//return view('manage-admins',$data);
		return view('manage-admins');
	}

	public function add_new_admin(){
		$userId = Auth::user()->id;
		$data['campaigns'] = Campaign::where('user_id',$userId)->get();
		return view('add-new-admin',$data);
		
	}
	
	public function addStaff(){
		$staffFilter = array(
			'admin_id' => Auth::user()->id,
		);
		
		$data['staffList'] = User::where($staffFilter)->get();

		$data['staffCount'] = User::where($staffFilter)->count();


		$userPack = DB::table('users_pack')->where('users_id', '=', Auth::user()->id)->first();

		if (isset($userPack) && $userPack->pack == "1"  && $data['staffCount'] > 0) {
			$data['limit'] = "1";
		} else {
			$data['limit'] = "0";
		}

		return view('add-staff',$data);
	}

	public function editStaff(Request $request, $adminsid){
		
		$data['admindata'] = User::where('id',$adminsid)->first();

		return view('edit-staff',$data);
    	}

	public function edit_staff_post(Request $request, $adminsid){
        
		$data = array(
           		'name'                        => $request['name'],
             		'phone_no'                    => $request['phone_no'],
             		'status'                      => $request['status'],
        	);
		
		if ($request['yesno'] == "1" && strlen(trim($request['pwd']))) {			
            		$data['password'] = Hash::make($request['pwd']);
		}

if ($request['status'] == "2") {
			$assignDetails =  DB::table('lead_assign_order')
				->select('executive_ids')
				->where('admin_id', Auth::user()->id)
				->get();
				
			$assignedIds = [];	
			foreach($assignDetails as $key => $value) {
				$ids = explode("|",$value->executive_ids);
				foreach($ids as $key1 => $value1) {
					$assignedIds[] = $value1;
				}
			}
			
			if(in_array($adminsid, $assignedIds)) {
				Session::flash('message', "Staff is assigned to differnet projects so cannot be inactive. 
Remove staff from Projects first.");
        	return back();
			}
		}

        	User::where('id', $adminsid)->update($data);
		
		Session::flash('message', "Records Updated successfully.");
        	return redirect('add-staff');
        //return redirect('edit-admins/'.$adminsid);
    }

	public function viewStaff() {

		$staffFilter = array(
			'admin_id' => Auth::user()->id,
		);
		$data['staffList'] = User::where($staffFilter)->get();
		return view('view-staff',$data);
	}
	
	public function saveStaff(Request $request){

        $msg = [
            'name.required' => 'Enter Your Name',
            'email.required' => 'Enter Your Last Name',
            'phone_no.required' => 'Enter Your Mobile No',
			'password.required' => 'Enter Your Password',
			'cpassword.required' => 'Enter Your Confirm Password',
        ];
        $this->validate($request, [
            'name' => 'required',
            'phone_no' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'min:6',
            'cpassword' => 'min:6|required_with:password|same:password'
        ], $msg);


	$userPack = DB::table('users_pack')->where('users_id', '=', Auth::user()->id )->first();

        $countSubadmin = DB::table('users')->where('admin_id', '=', Auth::user()->id)->count();

        if (isset($userPack) && $userPack->pack == "1" && $countSubadmin > 0) {
        	Session::flash('message', "You are limited to only 1 user.");
        	return back();	
        }


        $admin = new User();
        $admin->name       = $request['name'];
		$admin->email      = $request['email'];
        $admin->phone_no   = $request['phone_no'];
        $admin->password   = bcrypt($request->cpassword);
		$admin->admin_id   = Auth::user()->id;
        $admin->status     = 1;
		$admin->usertype   = 3;
        $admin->whatsapp_api_key_lock   = 1;
        $admin->email_api_key_lock      = 1;
        $admin->sms_api_key_lock        = 1;
        $admin->save();

	    return redirect()->route('add-staff')->with('message','Successfully registered.');
	}

	public function save_admin(Request $request){

        $msg = [
            'name.required' => 'Enter Your Name',
            'email.required' => 'Enter Your Last Name',
            'phone_no.required' => 'Enter Your Mobile No',
			'password.required' => 'Enter Your Password',
			'cpassword.required' => 'Enter Your Confirm Password',
        ];
        $this->validate($request, [
            'name' => 'required',
            'phone_no' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'min:6',
            'cpassword' => 'min:6|required_with:password|same:password'
        ], $msg);


        $admin = new User();
        $admin->name       = $request['name'];
		$admin->email      = $request['email'];
        $admin->phone_no   = $request['phone_no'];
        $admin->password   = bcrypt($request->cpassword);
        $admin->status     = 1;
		$admin->usertype   = 2;
        $admin->whatsapp_api_key_lock   = 1;
        $admin->email_api_key_lock      = 1;
        $admin->sms_api_key_lock        = 1;
		$admin->sms_api_key             = strlen(trim($request['sms_api_key'])) > 0 ? trim($request['sms_api_key']) : null;	
		$admin->sms_from_name           = strlen(trim($request['sms_from_name'])) > 0 ? trim($request['sms_from_name']) : null;
		$admin->email_api_key           = strlen(trim($request['email_api_key'])) > 0 ? trim($request['email_api_key']) : null;
		$admin->whatsapp_api_key        = strlen(trim($request['whatsapp_api_key'])) > 0 ? trim($request['whatsapp_api_key']) : null;
		$admin->whatsapp_username       = strlen(trim($request['whatsapp_username'])) > 0 ? trim($request['whatsapp_username']) : null;
		$admin->whatsapp_plans_id       = isset($request['whatsapp-plans']) ? $request['whatsapp-plans'] : 0;
        $admin->save();
		
		$adminId = $admin->id;
		
		$plan = isset($request['user-plans']) ? $request['user-plans'] : "1";
		
		$days = "15 days"; //trial plan
		
		if ($plan == "2") {
			$days = "90 days";
		} else if ($plan == "3") {
			$days = "180 days";
		} else if ($plan == "4") {
			$days = "365 days";
		}
		
		$startDate = $request['start_date'];
		
		$endDate = date('Y-m-d', strtotime($startDate. ' + '.$days));

		DB::insert('insert into user_plan_details (user_id,user_plans_id,start_date,end_date) values(?,?,?,?)',[$adminId,$plan,$startDate,$endDate]);

		$customerUsername = isset($request['whatsapp_username']) ? $request['whatsapp-username'] : "";
		
		if ( (isset($request['isChangePlan']) && $request['isChangePlan'] == "1") && $request['whatsapp-plans'] == "1" && $customerUsername != "" ) {
			$this->postWhatsAppPlan($customerUsername, $plan);
		}

	    return redirect()->route('add-new-admin')->with('message','Successfully registered.');
	}

    public function edit_admins(Request $request, $adminsid){

		$data['admindata'] = User::where('users.id',$adminsid)->leftjoin('user_plan_details','user_plan_details.user_id','=','users.id')->leftjoin('users_pack','users_pack.users_id','=','users.id')->first();
		
		if (isset($data['admindata']) && null == $data['admindata']->id) {
			$data['admindata']->id = $adminsid;
		}
		
		//echo "<pre>";
		//print_r($data['admindata']); die;
		return view('edit-admins',$data);
    }



    public function edit_admins_post(Request $request, $adminsid){
        
        
		$data = array(
            'name'                        => $request['name'],
            'phone_no'                    => $request['phone_no'],
            'status'                      => $request['status'],
            'whatsapp_api_key_lock'       => $request['whatsapp_api_key_lock'],
            'email_api_key_lock'          => $request['email_api_key_lock'],
            'sms_api_key_lock'            => $request['sms_api_key_lock'],
			'sms_api_key'                 => strlen(trim($request['sms-api-key'])) > 0 ? trim($request['sms-api-key']) : null,			
			'sms_from_name'               => strlen(trim($request['sms-username'])) > 0 ? trim($request['sms-username']) : null,
			'email_api_key'               => strlen(trim($request['email-api-key'])) > 0 ? trim($request['email-api-key']) : null,
			'whatsapp_api_key'            => strlen(trim($request['whatsapp_api_key'])) > 0 ? trim($request['whatsapp_api_key']) : null,
			'whatsapp_username'           => strlen(trim($request['whatsapp_username'])) > 0 ? trim($request['whatsapp_username']) : null,
        );

		if ($request['yesno'] == "1" && strlen(trim($request['pwd']))) {			
            $data['password'] = Hash::make($request['pwd']);
		}
/*

		if ($request['isChangePlan'] == "1") {
			$data['whatsapp_plans_id'] = isset($request['whatsapp-plans']) ? $request['whatsapp-plans'] : 0;
			$customerUsername = $request['whatsapp-username'];
			if ( $request['whatsapp-plans'] == "1" ) {
				$this->postWhatsAppPlan($customerUsername);
			}
		}

*/
//print_r($data); die;

        User::where('id', $adminsid)->update($data);


        $existPack=  DB::table('users_pack')->select('id')->where('users_id', $adminsid)->count();
		
		$pack = $request['pack'];

		if(!$existPack) {
			DB::insert('insert into users_pack (users_id,pack) values(?,?)',[$adminsid,$pack]);
		} else {
			$planData = array(
				"users_id" => $adminsid,
				"pack" => $pack
			);
			
			$updatePack = DB::table('users_pack')->where('users_id', $adminsid)->update($planData);			
			if ($pack == "2") {
				$leadData =  array(
					"leads_show" => 1
				);
				DB::table('leads')->where('user_id', $adminsid)->update($leadData);	
			}	
		}
			
		if (isset($request['user-plans'])) {
			$userId = $adminsid;
			$plan = $request['user-plans'];
			
			if ($plan == "1") {
				$days = "15 days";
			} else if ($plan == "2") {
				$days = "1 months";
			} else if ($plan == "3") {
				$days = "3 months";
			} else if ($plan == "4") {
				$days = "6 months";
			} else {
				$days = "3 days"; //trial plan
			}
		
			date_default_timezone_set('Asia/Kolkata');
			
			$customerUsername = isset($request['whatsapp_username']) ? $request['whatsapp_username'] : "";


			
			if ( (isset($request['isChangePlan']) && $request['isChangePlan'] == "1") && (($request['whatsapp-plans'] == "1" || $request['whatsapp-plans'] == "2" ) && 
$customerUsername != "" )) {
$wpplan =  $request['whatsapp-plans'];
				\Log::info('whatsapp: ' . $customerUsername . ' '.$plan. ' ' .$wpplan); 
				$this->postWhatsAppPlan($customerUsername,$wpplan);
			} 
			
			$startDate = $request['start_date'];
			
			$endDate = date('Y-m-d', strtotime($startDate. ' + '.$days));
			
			$existPlan =  DB::table('user_plan_details')->select('id')->where('user_id', $adminsid)->count();
		
			if(!$existPlan) {
				DB::insert('insert into user_plan_details (user_id,user_plans_id,start_date,end_date) values(?,?,?,?)',[$userId,$plan,$startDate,$endDate]);
			} else {
				$planData = array(
					"user_plans_id" => $plan,
					"start_date" => $startDate,
					"end_date" => $endDate
				);
				
				$affected = DB::table('user_plan_details')->where('user_id', $adminsid)->update($planData);			
			}
		}
		
		if (isset($request['sendLoginDetails']) && $request['sendLoginDetails'] == "1") {

			$userData = array(
				"name" => $request['name'],
				"phone" => $request['phone_no'],
				"email" => $request['email'],
			);
			
			$this->sendLoginDetails($userData, $startDate, $endDate);
		}
		
        Session::flash('message', "Records Updated successfully.");
        return redirect('manage-admins');
        //return redirect('edit-admins/'.$adminsid);
    }

    public function edit_admins_post1(Request $request, $adminsid){
        
		$data = array(
            'name'                        => $request['name'],
            'phone_no'                    => $request['phone_no'],
            'status'                      => $request['status'],
            'whatsapp_api_key_lock'       => $request['whatsapp_api_key_lock'],
            'email_api_key_lock'          => $request['email_api_key_lock'],
            'sms_api_key_lock'            => $request['sms_api_key_lock'],
			'sms_api_key'                 => strlen(trim($request['sms-api-key'])) > 0 ? trim($request['sms-api-key']) : null,			
			'sms_from_name'            => strlen(trim($request['sms-username'])) > 0 ? trim($request['sms-username']) : null,
			'email_api_key'               => strlen(trim($request['email-api-key'])) > 0 ? trim($request['email-api-key']) : null,
			'whatsapp_api_key'            => strlen(trim($request['whatsapp-api-key'])) > 0 ? trim($request['whatsapp-api-key']) : null,
			'whatsapp_username'            => strlen(trim($request['whatsapp-username'])) > 0 ? trim($request['whatsapp-username']) : null,
        );
		
		if ($request['yesno'] == "1" && strlen(trim($request['pwd']))) {			
            $data['password'] = Hash::make($request['pwd']);
		}

        User::where('id', $adminsid)->update($data);

        Session::flash('message', "Records Updated successfully.");
        return redirect('manage-admins');
        //return redirect('edit-admins/'.$adminsid);
    }

    /**
    * GET ACTIVE-IN-ACTIVE USERS DETAILS
    *
    * @return true or false
    */
    public function active_inactive_user(Request $request) {

        $id     = $request->get('id');
        $status = $request->get('status');

        if($status==1){
            User::where('id',$id)->update([
                'status' => 2,
            ]);
            $st   = 'InActive';
            $html = '<a href="javascript:void(0);" class="btn btn-warning" onclick="active_inactive_user('.$id.','.$st.')"><i class="fa fa-lock"></i></a>&emsp;';
            return json_encode(array('id' => $id, 'html' => $html));
        }
        else{
            User::where('id',$id)->update([
                'status' => 1,
            ]);
            $st   = 'Active';
            $html = '<a href="javascript:void(0);" class="btn btn-success" onclick="active_inactive_user('.$id.','.$st.')"><i class="fa fa-check-circle"></i></a>&emsp;';
            return json_encode(array('id' => $id, 'html' => $html));
        }
    }

	public function showReport(Request $request) {
		$id = $request->get('id');
		echo $id; die;		 
	}
	
	public function setSettings(Request $request){
		//$data["whatsappapikey"] = User::where('id',Auth::user()->id)->get()[0]->whatsapp_api_key;
        //$data["whatsapp_username"] = User::where('id',Auth::user()->id)->get()[0]->whatsapp_username;
		//return view("whats-app-api-capture",$data);		
		return view('admin-settings');
	}
	

public function postWhatsAppPlan1() {

 $usersmsapikey = User::where("id","19")->select("whatsapp_api_key")->get()[0];
$secretKey = $usersmsapikey;
$mobileno = "8878100065";
echo $secretKey;
die;	
$attachment = "";
$text = "Hello testing";

 $url = "https://app.messageautosender.com/api/v1/message/create";

                $base_link = "https://realauto.in/";

                if(strlen($attachment) > 0) {
                        if (strpos($attachment, $base_link) !== false){
                        } else {
                                $attachment = $base_link.$attachment;
                        }
                }

        $request = array('receiverMobileNo' => $mobileno, 'message' => $text,'filePathUrl' => $attachment);


        $ch = curl_init( $url );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $secretKey));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );

        # Send request.
        $result = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);
echo "<pre>";
print_r(var_dump($result));
        # Print response.
        if ($curl_errno > 0) {
            echo "cURL Error ($curl_errno): $curl_error <br/>";
            return "";
        } else {
            $data = json_decode($result);
            //echo "<pre>"; print_r($data); die;
        }





}

	public function postWhatsAppPlan($customerUsername, $plan = 1){


		if ($plan == "1") {
			$plan = "reseller_user_server_single_credit_500_15_days";
			$note = "Setting 500 Credits";
		} else if ($plan == "2") {
			$plan = "reseller_user_server_single_credit_1000_1_month";
			$note = "Setting 1000 Credits";

		}
		
		$inputArray = array(		
			"username" => "AYAN SAHA",
			"password" => "BANGALORE@2020",
			"customerUsername" => $customerUsername,
			"plan" => $plan,
			"note" => $note
		);
			
        $url       = "https://app.messageautosender.com/api/v1/reseller/customer/applyPlan";
        $ch        = curl_init( $url );
        $payload   = json_encode($inputArray );
        
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );

        # Send request.
        $Fetchresult = curl_exec($ch);

                                \Log::info('whatsapp api: ' . $Fetchresult);		
		//print_r($Fetchresult); die;
        $curl_errno  = curl_errno($ch);
        $curl_error  = curl_error($ch);
        curl_close($ch);

        # Print response.
        if ($curl_errno > 0) {
            echo "cURL Error ($curl_errno): $curl_error <br/>";
            return "IF Case..";
        } else {
        
		}
	}





public function addLeadFromFb($fbLead) {

        $lead = new Lead();
        $lead->user_id               = $fbLead['userId'];

        $lead->project_id            = $fbLead['projectId'];
        
        $lead->segment_id            = $fbLead['segmentId'];
        
        $lead->campaigns_id          = $fbLead['campaignId'];
        $lead->name                  = $fbLead['name'];
        $lead->mail_id               = $fbLead['email'];
        $lead->mobile_no             = $fbLead['contact'];        
        $lead->source                = "Facebook";
        $lead->status                = 1;
	$lead->project_name = $fbLead['projectName'];
	$lead->segment_name = $fbLead['segmentName'];


	date_default_timezone_set("Asia/Kolkata");
	$lead->campaign_activated_on = date("Y-m-d H:i:s");

        $lead->save();
$lastid = $lead->id;


       $useStaffWP = 0;

        $adminSettings = DB::table('admin_settings')
                ->select('auto_assign_leads','send_notification_via_whatsapp','send_notification_via_email','send_notification_to_staff','assignStaffWhatsappEvent')
                ->where('admin_id',$fbLead['userId'])
                ->get();
                if (isset($adminSettings[0]) ) {

                    if ($adminSettings[0]->auto_assign_leads  == "1") {
                        $this->leadAssign($fbLead['projectId'],$lastid);
                    }

                    if ($adminSettings[0]->assignStaffWhatsappEvent  == "1") {
                        $useStaffWP = 1;
                    }
                }	

\Log::info('Lead Added from Facebook');
//        $lastid = $lead->id;
$campaignActivatedOn = $lead->campaign_activated_on;

	$cautomationcount = DB::table('automations')
            
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.campaign_id', $fbLead['campaignId'])
            ->where('sms_automation_messages.delivery_type', 'scheduled')
            ->count();

        if($cautomationcount > 0){
            $cautomation = DB::table('automations')
                
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.is_active','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.campaign_id', $fbLead['campaignId'])
                ->where('sms_automation_messages.delivery_type', 'scheduled')
		//->where('sms_automation_messages.is_active', 1)
                ->get();

            foreach($cautomation as $row){
				$newDate = explode(" ",$campaignActivatedOn);

                if($row->delivery_day == 0){ //Today
					$newdatetime = strtotime($newDate[0]." ".$row->delivery_time);
                } else{ //After Day
                    //$newdatetime = new DateTime(date('Y-m-d '.$row->delivery_time).' + '.$row->delivery_day.' day');
                	$newdatetime = strtotime($newDate[0]." ".$row->delivery_time." ".$row->delivery_day." days");
				}

		if ($newdatetime > strtotime("now")) {

			$leadcorn = new LeadcornCampaigns();
			$leadcorn->user_id                = $fbLead['userId'];
			$leadcorn->campaign_id            = $fbLead['campaignId'];
			$leadcorn->lead_id                = $lastid;
			$leadcorn->automation_messages_id = $row->id;

			$leadcorn->name                   = $fbLead['name'];
			$leadcorn->mail_id                = $fbLead['email'];
			$leadcorn->mobile_no              = $fbLead['contact'];
	
			$leadcorn->automation_type        = $row->automation_type;
			$leadcorn->delivery_date_time     = date("Y-m-d H:i:s",$newdatetime);
			$leadcorn->message                = $row->message;
			$leadcorn->image                  = $row->image;
			$leadcorn->status                 = 2;
			//LeadReport
			//echo $newdatetime; die;
			$leadDetail = new LeadDetails();					
			$leadDetail->lead_id               = $lastid;
			$leadDetail->automation_messages_id= $row->id;
			$leadDetail->delivery_date_time    = date("Y-m-d H:i:s",$newdatetime); 

			if ($row->is_active == "0") {
				$leadcorn->is_stopped = "1";
				$leadDetail->is_cancelled    = "1";
				$leadcorn->stopped_reason = "Event is in off state on lead creation";
				$leadDetail->failure_reason =  "Event is in off state on lead creation";
			}
			$leadcorn->save();
			$leadDetail->save();
		} else {
			//echo date("Y-m-d H:i:s",$newdatetime); die;
			$leadDetail = new LeadDetails();					
			$leadDetail->lead_id               = $lastid;
			$leadDetail->automation_messages_id= $row->id;
			$leadDetail->delivery_date_time    = date("Y-m-d H:i:s",$newdatetime); 
			$leadDetail->is_cancelled    = "1";
			if ($row->is_active == "0") {
			$leadDetail->failure_reason =  "Event is in off state on lead creation";
			} else {
				$leadDetail->failure_reason =  "Delivery date set to passed date";
			}
			$leadDetail->save();
		}

            }
        }

		$smsEvents = DB::table('automations')
            
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.automation_type', 1)
            ->where('automations.campaign_id', $fbLead['campaignId'])
            ->where('sms_automation_messages.delivery_type', 'initial')
			->where('sms_automation_messages.is_active', 1)
            ->get();
		
		if (null !== $smsEvents) {
			foreach($smsEvents as $key => $value) {
				$message = isset($value->message) ? $value->message : "";
				
				$usersmsapikey = Getuserapikey($fbLead['userId'],1);
                    		$sms_from_name = Getusersmsfromname($fbLead['userId'],1);

                    		testsendsmsnew($usersmsapikey,$sms_from_name,$fbLead['contact'],str_replace("{Full Name}",$fbLead['name'],$message));


				//testsendsms($fbLead['contact'],str_replace("{Full Name}",$fbLead['name'],$message));
			}
		}


		
		$emailEvent = DB::table('automations')
                
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.automation_type', 2)
                ->where('automations.campaign_id', $fbLead['campaignId'])
                ->where('sms_automation_messages.delivery_type', 'initial')
				->where('sms_automation_messages.is_active', 1)
                ->get();
		
		if (null !== $emailEvent) {
			foreach($emailEvent as $key => $value) {
				$message = isset($value->message) ? $value->message : "";
				

				 $usersmsapikey = Getuserapikey($fbLead['userId'],2);
                                 testsendemailnew($usersmsapikey,$fbLead['email'],str_replace("{Full Name}",$fbLead['name'],$message));


				//testsendemail($fbLead['email'],str_replace("{Full Name}",$fbLead['name'],$message));
			}
		}
		

		
		$whatsappEvent = DB::table('automations')
                
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.automation_type', 3)
                ->where('automations.campaign_id', $fbLead['campaignId'])
                ->where('sms_automation_messages.delivery_type', 'initial')
				->where('sms_automation_messages.is_active', 1)
                ->get();
				
	    if (null !== $whatsappEvent) {
			foreach($whatsappEvent as $key => $value) {
				$message = isset($value->message) ? $value->message : "";

				//$usersmsapikey = Getuserapikey($fbLead['userId'],3);
                                
				if ($useStaffWP == 1) {
    $subAdmin = DB::table('leads')
                ->select('assigned_to')
                ->where('id',$lastid)
                ->get();

    if (isset($subAdmin[0]) ) {
        $subadminId = $subAdmin[0]->assigned_to;
        $usersmsapikey = Getuserapikey($subadminId,3);
        
    } else {
        $usersmsapikey = Getuserapikey($fbLead['userId'],3);
    }
} else {
   $usersmsapikey = Getuserapikey($fbLead['userId'],3);

}
	
				testsendwhatsappnew($usersmsapikey,$fbLead['contact'],str_replace("{Full Name}",$fbLead['name'],$message),$value->image);

				//testsendwhatsapp($fbLead['contact'],str_replace("{Full Name}",$fbLead['name'],$message),$value->image);
			}
		}

//if ($fbLead['userId'] == "8" || $fbLead['userId'] == "234" || $fbLead['userId'] == "20") {
			$adminId = $fbLead['userId'];
			$adminSettings = DB::table('admin_settings')
                                         ->select('auto_assign_leads','send_notification_via_whatsapp','send_notification_via_email','send_notification_to_staff')
                                         ->where('admin_id',$adminId)
                                         ->get();
			if (isset($adminSettings[0]) ) {

				if ($adminSettings[0]->auto_assign_leads  == "1") {
					//$this->leadAssign($fbLead['projectId'],$lastid);
				}

				$adminData = DB::table('users')->select('id','phone_no','email')->where('id',$fbLead['userId'])->get();
		
		
				$adminMobileNo = $adminData[0]->phone_no;
				$adminEmail = $adminData[0]->email;
	
				$leadData = array(
					"id" => $lastid,
					"userId" => $fbLead['userId'],
					"name" => $fbLead['name'],
					"mobno" => $fbLead['contact'],
					"email" => $fbLead['email'],
					"project" => $fbLead['projectName'],
					"sendStaff" => $adminSettings[0]->send_notification_to_staff
				);

				if ($adminSettings[0]->send_notification_via_whatsapp  == "1") {
					$this->sendLeadNotificationViaWhatsapp($adminMobileNo,$leadData);
				}

				if ($adminSettings[0]->send_notification_via_email  == "1") {
					$this->sendLeadNotificationViaEmail($adminEmail,$leadData);
				}	

			}

		}



function userAccessToken() {

// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/


//$ch = 
curl_init('https://graph.facebook.com/v11.0/oauth/access_token?grant_type=fb_exchange_token&client_id=3312603435483167&client_secret=ab4719b6fd51d77b5b639ffdc538a2bd&fb_exchange_token=SHORT-LIVED-USER-ACCESS-TOKEN');
$ch = curl_init('https://graph.facebook.com/oauth/access_token?client_id=3312603435483167&client_secret=ab4719b6fd51d77b5b639ffdc538a2bd&grant_type=client_credentials');


curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);
 
curl_close($ch);

print_r(var_dump($data));
//die;

/*


$ch = curl_init('https://graph.facebook.com/v11.0/1052462328826309?access_token=3312603435483167');


curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$data = curl_exec($ch);

curl_close($ch);

print_r(var_dump($data));
*/


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 
"https://graph.facebook.com/v11.0/1052462328826309?access_token=EAAvEy5cNwB8BAIos3A4vDpFmQZAZAEKhSYqaru3SXazE4r1sswRXZCYj1dZCsgA9pSYKUV7yvKa7J4LJCZCDGGLONFugQXmqC4r7eMZCtaetsyFpFAfeXpWJrq9yeBHkS0v05JFh4kBoQfyLizqvK96OZAiUSXVO4UM0E4OP5qXUKU7Pca5fkU9roTpq6K3wmgvEsSDni79EfAcD9PwFBMV2xTWRCfVe04ZD",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET"
));

$response = curl_exec($curl);





print_r(var_dump($response)); die;
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

/*

$ch = curl_init('https://graph.facebook.com/114679157061500?
  fields=access_token&
  access_token=3312603435483167|xcmaJU-DRhtR6SBd-0zmooJSubQ');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


$result = curl_exec($ch);
print_r(var_dump($result));


if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
*/
}

public function getUserPlan() {
	
	$userId = Auth::user()->id;
	$planDetails = DB::table('user_plan_details')
	->select('user_plan_details.id','end_date','is_unlimited','users_pack.pack')
	->leftjoin('users_pack','user_plan_details.user_id','=','users_pack.users_id')
	->where('user_id', $userId)->get();
	
	$details = array();
	
	if (null !== $planDetails && isset($planDetails[0]))  {
		$details['end_date'] = $planDetails[0]->end_date;
		$endDate = strtotime($planDetails[0]->end_date);
		$details['end_month'] = date("M", $endDate);
		$details['end_year'] = date("Y", $endDate);
		$details['is_unlimited'] = $planDetails[0]->is_unlimited; 
		$details['pack'] = isset($planDetails[0]->pack) ? $planDetails[0]->pack : 0; 
	}
	
	return json_encode($details);
}


   public function askfordemo(Request $request)
    {
         $msg = [
            'date.required' => 'Select Future Date',
            'time.required' => 'Select Future Time',
        ];
        $this->validate($request, [
            'date' => 'required',
            'time' => 'required',
        ], $msg);

        $data = array(
            'date' =>$request->get('date'),
            'time' =>$request->get('time'),
            'user_id' =>Auth::user()->id
        );
        DB::table('ask_for_demo')->insert($data);
        Session::flash('message', "Successfully.");
        return back();
    }

	public function delete_admins($id) {

	//DB::table('users')->where('id', $id)->update(array("status"=>"3")); // 3 means soft delete so it will not appear in super admin dashboard
	//Campaign::where('user_id','=',$id)->update(array('is_active' => 0));
	//LeadcornCampaigns::where('user_id','=',$id)->update(array('is_stopped' => 1,'stopped_reason' => 'Campaign Deactivated'));
	
        DB::table('users')->where('id', $id)->delete();
        DB::table('campaigns')->where('user_id', $id)->delete();
        DB::table('leads')->where('user_id', $id)->delete();
        DB::table('automations')->where('user_id', $id)->delete();
        DB::table('bulk_sms_automation_message')->where('user_id', $id)->delete();
        DB::table('leads_followups')->where('user_id', $id)->delete();
        DB::table('leads_status')->where('user_id', $id)->delete();
        DB::table('lead_comments')->where('user_id', $id)->delete();
        DB::table('lead_corn_campaigns')->where('user_id', $id)->delete();
        DB::table('lead_reports')->where('user_id', $id)->delete();
        DB::table('project_master')->where('user_id', $id)->delete();
        DB::table('segment')->where('user_id', $id)->delete();
        DB::table('sms_automations')->where('user_id', $id)->delete();
        DB::table('sms_automation_messages')->where('user_id', $id)->delete();
        DB::table('sms_campaigns')->where('user_id', $id)->delete();
        DB::table('user_plan_details')->where('user_id', $id)->delete();
        
	Session::flash('message', "Admin Records Permanently Delete Successfully.");
        return back();
    } 
	
	public function demoScheduler() {
		$data["demo"] = DB::table('ask_for_demo')
		->join('users','users.id','=','ask_for_demo.user_id')
		->select("name","email","phone_no","date","time")
		->get();
		
		return view("demo-scheduler",$data);
	}

	public function showScanner(){
		
		if ((null == Auth::user()->whatsapp_username || Auth::user()->whatsapp_username == "")) {
			
			$img = "https://realauto.in/assets/images/premium.jpg";
			return $img;
		}
		
		$adminwhatsapp_api_key = DB::select('select whatsapp_api_key from users where name = ?', ['admin']);
		$secretKey = $adminwhatsapp_api_key[0]->whatsapp_api_key;


        $apiRequest = array(
			"username" => "AYAN SAHA",
			"password" => "BANGALORE@2020",
			"customerUsername" => Auth::user()->whatsapp_username,
			"channeId" => 7549
        );


        $url       = "https://app.messageautosender.com/api/v1/reseller/customer/channel/status";
        $ch        = curl_init( $url );

        $payload   = json_encode( $apiRequest );

        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $secretKey, 'Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );


        # Send request.
        $Fetchresult = curl_exec($ch);
        $curl_errno  = curl_errno($ch);
        $curl_error  = curl_error($ch);
        curl_close($ch);

        # Print response.
        if ($curl_errno > 0) {
            echo "cURL Error ($curl_errno): $curl_error <br/>";
            return "IF Case..";
        } else {

            $data = json_decode($Fetchresult);

            if(isset($data->result->image) && !empty($data->result->image)) {
                @$imgScanner = $data->result->image;
                if(!empty($imgScanner)){
                    return $imgScanner;
                }else{ 
					$imageScanner="https://realauto.in/assets/images/dummyqr.png";
					return $imageScanner;
                }
            } else {
                $imageScanner="https://realauto.in/assets/images/dummyqr.png";
                return $imageScanner;
            }

        }
    }

	public function adminsRetrival() {
		
		
		$offset = $_REQUEST['start'] ;
		
		$limit = $_REQUEST['length'];
		
		$search = json_encode($_REQUEST['search']);
		
		foreach( $_REQUEST['search'] as $key => $value) {
		   $search = trim($value);
		   break;
		}	
		
		$searchValue = $search;
		$search = strlen($search);
	
		$whereArray = [];
		$orWhereFilter = [];
		
		$where1 = array('usertype','=',2);
		$checkStatus = array('status','!=','3');

		$whereArray = array($where1,$checkStatus);
	
		
		$orWhereFilter1 = $orWhereFilter2 =  array();
    			
		if ($search > 0) {
			$whereArray[] = array('name','like',"$searchValue%");
			$orWhereFilter1 = array($where1);
			$orWhereFilter2 = array($where1);
			$orWhereFilter1[] = array('phone_no','like',"$searchValue%");
			$orWhereFilter2[] = array('email','like',"$searchValue%");			
		}
		
		if(isset($_REQUEST['user-status']) && $_REQUEST['user-status'] != "0") {
			//$whereArray[] = array('status','=',$_REQUEST['user-status']);
			$currentDate = date("Y-m-d H:i:s");
			
			if ($_REQUEST['user-status'] == "1") {
				$whereArray[] = array('status','=',$_REQUEST['user-status']);
				$whereArray[] = array('user_plan_details.end_date','>=',$currentDate);
			}
				
			if ($_REQUEST['user-status'] == "3") {
                                $whereArray[] = array('user_plan_details.end_date','<',$currentDate);
                        }
			
		} else {

			if(isset($_REQUEST['startDate']) && $_REQUEST['startDate'] != "0") {
				$startDate = $_REQUEST['startDate']." "."00:00:00";
				$whereArray[] = array('user_plan_details.start_date','>=',$startDate);

			}

			if(isset($_REQUEST['endDate']) && $_REQUEST['endDate'] != "0") {
				$endDate = $_REQUEST['endDate']." "."00:00:00";                        
				$whereArray[] = array('user_plan_details.end_date','<=',$endDate);

			}

		}	

		
		
		if(isset($_REQUEST['userPlan']) && $_REQUEST['userPlan'] != "0") {
			$whereArray[] = array('user_plan_details.user_plans_id','=',$_REQUEST['userPlan']);
		}

if (isset($_REQUEST['user-status']) && $_REQUEST['user-status'] == "4") {

			$adminData  = User::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)
			->select('referrals.admin_id as referralId','users.id','users.created_at','users.name','users.phone_no','users.email','users.status','user_plan_details.start_date','users.referral_code',
			'user_plan_details.end_date',
			'user_plan_details.user_plans_id','users_pack.pack','users.whatsapp_api_key')
			->join('referrals','referrals.user_id','users.id')	
			->leftjoin('user_plan_details','user_plan_details.user_id','users.id')
			->leftjoin('users_pack','users_pack.users_id','users.id')

			->orderby('users.id','desc')
        	->offset($offset)
        	->limit($limit)
        	->get();
		
			
		} else if(isset($_REQUEST['startDate']) && $_REQUEST['startDate'] != "0") {


		$adminData  = User::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)
		->select('users.id','users.created_at','users.name','users.phone_no','users.email','users.status','user_plan_details.start_date',
                         'user_plan_details.end_date','user_plan_details.user_plans_id','users.referral_code','users.whatsapp_api_key')
		->leftjoin('user_plan_details','user_plan_details.user_id','users.id')
		->orderby('user_plan_details.start_date','asc')
		->offset($offset)
		->limit($limit)
		->get();
		} else  {

  		$adminData  = User::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)
                ->select('referrals.admin_id as referralId','users.id','users.created_at','users.name','users.phone_no','users.email','users.status','user_plan_details.start_date',
                         'user_plan_details.end_date','user_plan_details.user_plans_id','users_pack.pack','users.referral_code','users.whatsapp_api_key')
                ->leftjoin('referrals','referrals.user_id','users.id')
		->leftjoin('user_plan_details','user_plan_details.user_id','users.id')
		->leftjoin('users_pack','users_pack.users_id','users.id')
                ->orderby('users.id','desc')
        	->offset($offset)
        	->limit($limit)
        	->get();

		}

$admins = User::select('id','name')->get();
$users = array();
foreach($admins as $key => $value) {
	$users[$value->id] = $value->name;
}

		foreach($adminData as $key => $value) {
			$accountData = json_decode($this->getAccountDetails($value->whatsapp_api_key));
			$adminData[$key]['creditPoint'] = 0;
			$adminData[$key]['activeUpto'] = 0;
			if (isset($accountData->result) && $accountData->result != "") {
				$adminData[$key]['creditPoint'] = isset($accountData->result->creditPoint) ? 
				$accountData->result->creditPoint : 0;	
				
				if (isset($accountData->result->activeUpto)) {
					$dateD=date_create($accountData->result->activeUpto);
					$activeDate = date_format($dateD,"d-m-Y");
					$adminData[$key]['activeUpto'] = $activeDate;
				}
			}
if(isset($value->referralId)) {
				if (array_key_exists($value->referralId,$users)){
				$adminData[$key]['referredBy'] = $users[$value->referralId];	
				}
			}

		}
		
		$count = User::where('usertype',2)->count();
		$filterCount = 0;
		
		$filterCount = User::where($whereArray)
		->orWhere($orWhereFilter1)
		->orWhere($orWhereFilter2)
		->leftjoin('user_plan_details','user_plan_details.user_id','users.id')
		->count();
		

	$filterCount = User::where($whereArray)
		->orWhere($orWhereFilter1)
		->orWhere($orWhereFilter2)
		->leftjoin('user_plan_details','user_plan_details.user_id','users.id')
		->count();


		$result = array("search" => $whereArray,"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => $filterCount);
		
		$result['data'] = $adminData;
		
		print_r(json_encode($result));
	}
	
	public function statusactiveadmin(Request $request, $id)
	{
		$data = array(
            'status' =>$request->get('status'),
		);
		DB::table('users')->where('id', $id)->update($data);
		return response('Successfully !');
    }
	
	public function webhookWordpress(Request  $request, $userCode){

		$input = json_decode(file_get_contents('php://input'), true);	
\Log::info('wp data :'. file_get_contents('php://input'));   

        
        	$webhookData = array();
		
		foreach($input as $key => $value) {
			if (stripos($key, 'name') !== false) {
				$webhookData['name'] = $value;
			} else if (stripos($key, 'Name') !== false) {
                                $webhookData['name'] = $value;
                        }
			
			if (stripos($key, 'phone') !== false) {
				$webhookData['phone'] = $value;
			}
			
			if (stripos($key, 'email') !== false) {
				$webhookData['email'] = $value;
			}			
		}

		$leads = DB::table('wordpress_integrations')
            	->select('wordpress_integrations.user_id','wordpress_integrations.project_id','wordpress_integrations.segment_id','wordpress_integrations.campaign_id',
			'project_master.project_name','segment.segment_name')
		->join('project_master','project_master.id','=','wordpress_integrations.project_id')
		->join('segment','segment.id','=','wordpress_integrations.segment_id')

	    	->where('user_url_code', $userCode)
            	->get();

		if (isset($leads[0]->user_id)) {
		
			$projectId =   $leads[0]->project_id;
			$segmentId =   $leads[0]->segment_id;
			$campaignId =  $leads[0]->campaign_id;
			$userId = $leads[0]->user_id;
			$projectName = $leads[0]->project_name;

			$segmentName = $leads[0]->segment_name;

		}
$wordpressLead = array(

	"name" => $webhookData['name'],
	"email" => $webhookData['email'],
	"contact" => $webhookData['phone'],
	"projectId" => $projectId,
	"campaignId" => $campaignId,
	"segmentId" => $segmentId,
	"userId" => $userId,
	"projectName" => $projectName,
	"segmentName" => $segmentName
);


$this->addLeadFromWP($wordpressLead);
\Log::info('Incoming wp lead: ' . json_encode($wordpressLead)); 	

	}
function addLeadFromWP($fbLead) {
$lead = new Lead();
        $lead->user_id               = $fbLead['userId'];

        $lead->project_id            = $fbLead['projectId'];
        
        $lead->segment_id            = $fbLead['segmentId'];
        
        $lead->campaigns_id          = $fbLead['campaignId'];
        $lead->name                  = $fbLead['name'];
        $lead->mail_id               = $fbLead['email'];
        $lead->mobile_no             = $fbLead['contact'];        
        $lead->source                = "Wordpress";
        $lead->status                = 1;
	$lead->project_name = $fbLead['projectName'];
	$lead->segment_name = $fbLead['segmentName'];


	date_default_timezone_set("Asia/Kolkata");
	$lead->campaign_activated_on = date("Y-m-d H:i:s");

        $lead->save();

        $lastid = $lead->id;

    $useStaffWP = 0;

        $adminSettings = DB::table('admin_settings')
                ->select('auto_assign_leads','send_notification_via_whatsapp','send_notification_via_email','send_notification_to_staff','assignStaffWhatsappEvent')
                ->where('admin_id',$fbLead['userId'])
                ->get();
                if (isset($adminSettings[0]) ) {

                    if ($adminSettings[0]->auto_assign_leads  == "1") {
                        $this->leadAssign($fbLead['projectId'],$lastid);
                    }

                    if ($adminSettings[0]->assignStaffWhatsappEvent  == "1") {
                        $useStaffWP = 1;
                    }
}
$campaignActivatedOn = $lead->campaign_activated_on;

	$cautomationcount = DB::table('automations')
            
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.campaign_id', $fbLead['campaignId'])
            ->where('sms_automation_messages.delivery_type', 'scheduled')
            ->count();

        if($cautomationcount > 0){
            $cautomation = DB::table('automations')
                
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.is_active','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.campaign_id', $fbLead['campaignId'])
                ->where('sms_automation_messages.delivery_type', 'scheduled')
		//->where('sms_automation_messages.is_active', 1)
                ->get();

            foreach($cautomation as $row){
				$newDate = explode(" ",$campaignActivatedOn);

                if($row->delivery_day == 0){ //Today
					$newdatetime = strtotime($newDate[0]." ".$row->delivery_time);
                } else{ //After Day
                    //$newdatetime = new DateTime(date('Y-m-d '.$row->delivery_time).' + '.$row->delivery_day.' day');
                	$newdatetime = strtotime($newDate[0]." ".$row->delivery_time." ".$row->delivery_day." days");
				}

		if ($newdatetime > strtotime("now")) {

			$leadcorn = new LeadcornCampaigns();
			$leadcorn->user_id                = $fbLead['userId'];
			$leadcorn->campaign_id            = $fbLead['campaignId'];
			$leadcorn->lead_id                = $lastid;
			$leadcorn->automation_messages_id = $row->id;

			$leadcorn->name                   = $fbLead['name'];
			$leadcorn->mail_id                = $fbLead['email'];
			$leadcorn->mobile_no              = $fbLead['contact'];
	
			$leadcorn->automation_type        = $row->automation_type;
			$leadcorn->delivery_date_time     = date("Y-m-d H:i:s",$newdatetime);
			$leadcorn->message                = $row->message;
			$leadcorn->image                  = $row->image;
			$leadcorn->status                 = 2;
			//LeadReport
			//echo $newdatetime; die;
			$leadDetail = new LeadDetails();					
			$leadDetail->lead_id               = $lastid;
			$leadDetail->automation_messages_id= $row->id;
			$leadDetail->delivery_date_time    = date("Y-m-d H:i:s",$newdatetime); 

			if ($row->is_active == "0") {
				$leadcorn->is_stopped = "1";
				$leadDetail->is_cancelled    = "1";
				$leadcorn->stopped_reason = "Event is in off state on lead creation";
				$leadDetail->failure_reason =  "Event is in off state on lead creation";
			}
			$leadcorn->save();
			$leadDetail->save();
		} else {
			//echo date("Y-m-d H:i:s",$newdatetime); die;
			$leadDetail = new LeadDetails();					
			$leadDetail->lead_id               = $lastid;
			$leadDetail->automation_messages_id= $row->id;
			$leadDetail->delivery_date_time    = date("Y-m-d H:i:s",$newdatetime); 
			$leadDetail->is_cancelled    = "1";
			if ($row->is_active == "0") {
			$leadDetail->failure_reason =  "Event is in off state on lead creation";
			} else {
				$leadDetail->failure_reason =  "Delivery date set to passed date";
			}
			$leadDetail->save();
		}

            }
        }

		$smsEvents = DB::table('automations')
            
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.automation_type', 1)
            ->where('automations.campaign_id', $fbLead['campaignId'])
            ->where('sms_automation_messages.delivery_type', 'initial')
			->where('sms_automation_messages.is_active', 1)
            ->get();
		
		if (null !== $smsEvents) {
			foreach($smsEvents as $key => $value) {
				$message = isset($value->message) ? $value->message : "";
				
				$usersmsapikey = Getuserapikey($fbLead['userId'],1);
                    		$sms_from_name = Getusersmsfromname($fbLead['userId'],1);

                    		testsendsmsnew($usersmsapikey,$sms_from_name,$fbLead['contact'],str_replace("{Full Name}",$fbLead['name'],$message));


				//testsendsms($fbLead['contact'],str_replace("{Full Name}",$fbLead['name'],$message));
			}
		}


		
		$emailEvent = DB::table('automations')
                
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.automation_type', 2)
                ->where('automations.campaign_id', $fbLead['campaignId'])
                ->where('sms_automation_messages.delivery_type', 'initial')
				->where('sms_automation_messages.is_active', 1)
                ->get();
		
		if (null !== $emailEvent) {
			foreach($emailEvent as $key => $value) {
				$message = isset($value->message) ? $value->message : "";
				

				 $usersmsapikey = Getuserapikey($fbLead['userId'],2);
                                 testsendemailnew($usersmsapikey,$fbLead['email'],str_replace("{Full Name}",$fbLead['name'],$message));


				//testsendemail($fbLead['email'],str_replace("{Full Name}",$fbLead['name'],$message));
			}
		}
		

		
		$whatsappEvent = DB::table('automations')
                
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.automation_type', 3)
                ->where('automations.campaign_id', $fbLead['campaignId'])
                ->where('sms_automation_messages.delivery_type', 'initial')
				->where('sms_automation_messages.is_active', 1)
                ->get();
				
	    if (null !== $whatsappEvent) {
			foreach($whatsappEvent as $key => $value) {
				$message = isset($value->message) ? $value->message : "";

				//$usersmsapikey = Getuserapikey($fbLead['userId'],3);
 if ($useStaffWP == 1) {
    $subAdmin = DB::table('leads')
                ->select('assigned_to')
                ->where('id',$lastid)
                ->get();

    if (isset($subAdmin[0]) ) {
        $subadminId = $subAdmin[0]->assigned_to;
        $usersmsapikey = Getuserapikey($subadminId,3);

    } else {
        $usersmsapikey = Getuserapikey($fbLead['userId'],3);
    }
} else {
   $usersmsapikey = Getuserapikey($fbLead['userId'],3);

}

                    		testsendwhatsappnew($usersmsapikey,$fbLead['contact'],str_replace("{Full Name}",$fbLead['name'],$message),$value->image);

				//testsendwhatsapp($fbLead['contact'],str_replace("{Full Name}",$fbLead['name'],$message),$value->image);
			}
		}


		
					$adminId = $fbLead['userId'];
			$adminSettings = DB::table('admin_settings')
			->select('auto_assign_leads','send_notification_via_whatsapp','send_notification_via_email','send_notification_to_staff')
			->where('admin_id',$adminId)
			->get();
			
			if (isset($adminSettings[0]) ) {

				if ($adminSettings[0]->auto_assign_leads  == "1") {
					//$this->leadAssign($fbLead['projectId'],$lastid);
				}

				$adminData = DB::table('users')->select('id','phone_no','email')->where('id',$fbLead['userId'])->get();		

				$adminMobileNo = $adminData[0]->phone_no;
				$adminEmail = $adminData[0]->email;
	
				$leadData = array(
					"id" => $lastid,
					"userId" => $fbLead['userId'],
					"name" => $fbLead['name'],
					"mobno" => $fbLead['contact'],
					"email" => $fbLead['email'],
					"project" => $fbLead['projectName'],
					"sendStaff" => $adminSettings[0]->send_notification_to_staff
				);

				if ($adminSettings[0]->send_notification_via_whatsapp  == "1") {
					$this->sendLeadNotificationViaWhatsapp($adminMobileNo,$leadData);
				}

				if ($adminSettings[0]->send_notification_via_email  == "1") {
					$this->sendLeadNotificationViaEmail($adminEmail,$leadData);
				}	
			}

}
	
	public function sendLoginDetails($data, $startDate, $endDate) {
		
		$userData = array(
			"name" => $data['name'],
			"startDate" => date("jS F, Y", strtotime($startDate)),
			"endDate" => date("jS F, Y", strtotime($endDate)),
			"email" => $data['email'],
			"password" => "123456",
			"welcome_mail" => "2"
		);
		
		$toemail = $data['email'];
		
		Mail::send('trial_plan_email_template', $userData, function($message) use ($toemail){
                $message->to($toemail)->subject('Welcome to RealAuto');
                });
		
		$usersmsapikey = User::where("id","8")->select("whatsapp_api_key")->get()[0];

		$api = $usersmsapikey->whatsapp_api_key;
		$mobileno = $data['phone'];
/*
		$msg = "Hi ".$data['name'].",
Your 3 days trial period will start from ".$userData['startDate']." and ends on ".$userData['endDate'].
				 
"

Please find below your login details :
Login Url : https://realauto.in/login
Email     : ".$userData['email'].
"
Password : 123456


Thanks & Regards
RealAuto Team";
*/
 $msg = "Hi ".$data['name'].",
Congratulations, Your account is updated with SMS and Whatsapp credits.

For Any Assistance or Support call - 8884482852


Thanks & Regards
RealAuto Team";
		
		//$msg = strip_tags($msg);		
		$attachment = "";
		testsendwhatsappnew($api,$mobileno,$msg,$attachment);
		
	}
	
	public function showWpHelp() {
		return view("wp-integration-help");
	}
	
	public function showFormFields(Request $request) {
		$formId = $_POST['formId'];
		$ch = curl_init();
		$accessToken = $_POST['accessToken'];
		curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v11.0/'.$formId.'?fields=questions');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('access_token' => $accessToken));

		$result = curl_exec($ch);
		//print_r(json_decode($result)); die;
	//return ($result);

		$formFields = json_decode($result);

		$questionFields = array();
		$i = 0;
		foreach($formFields->questions as $key => $value) {
				$data = array($value->key,$value->label);
				$questionFields[$i] = $data;
				$i++;
		}

		return json_encode($questionFields);


		if (curl_errno($ch)) {
				echo 'Error:' . curl_error($ch);
		}

		curl_close($ch);
    }

	
	public function showSuccessMsg() {
		
		return view("registeration-success-msg");
	}


public function tutorialview()
	{
		$data['tutorial'] = DB::table('tutorial')->where('n_deleted',1)->orderBy('id','desc')->get();
		return view('tutorial.tutorial-view',$data);
	}
	public function tutorialsave(Request $request)
	{
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
		$msg = [
            'url.required' => 'Enter Your URl',
        ];
        $this->validate($request, [
            'url' => 'required',
        ], $msg);
        $imageName = $request->image->getClientOriginalName(); 
        $request->image->move(public_path('video/images'), $imageName);
        $data = array(
			'url' => $request->get('url'),
			'image'  => $imageName,
			'n_deleted' => '1',
			'topic' => isset($_POST['topic']) ? $_POST['topic'] : null,
			'description' => isset($_POST['description']) ? $_POST['description'] : null,
			'is_active' => '1',
        );
        DB::table('tutorial')->insert($data);
        return back()->with('message','Successfully Added Url.');
	}
	public function tutorialstatus(Request $request, $id)
	{
		// $offset = $_REQUEST['status'];
		// DD($offset);
        $data = array(
        	'is_active' =>$_REQUEST['status'],
        );
        DB::table('tutorial')->where('id',$id)->update($data);
        return response('Successfully!');
	}

	public function tutorialdelete($id)
	{
		$delete = array('n_deleted' => 0 );
		DB::table('tutorial')->where('id',$id)->update($delete);
		return back()->with('message','Successfully Deleted.');
	}
    public function tutorialedit($id)
    {
    	$data['tutorialupdate'] = DB::table('tutorial')->where('id', $id)->first();
    	return view('tutorial.tutorial-view-update', $data);
    }
	public function tutorialupdate(Request $request, $id)
	{
		if($request->image == null){
		$data = array(
        'url' => $request->get('url'),
        'topic' => $request->get('topic'),
	    'description' => $request->get('description'),
        'is_active' => '1',
        );
        DB::table('tutorial')->where('id',$id)->update($data);
        return redirect('tutorial')->with('message','Successfully Updated Url.');
		}
		else{
		$imageName = $request->image->getClientOriginalName();
        $request->image->move(public_path('video/images'), $imageName);
	    $data = array(
	        'url' => $request->get('url'),
			'topic' => $request->get('topic'),
			'description' => $request->get('description'),
			'image'  => $imageName,
	        'is_active' => '1',
        );
        DB::table('tutorial')->where('id',$id)->update($data);
        return redirect('tutorial')->with('message','Successfully Updated Url.');}
	}


	
	
	public function managesells()
	{  
		$staffFilter = array(
			'admin_id' => Auth::user()->id,
		);
		$data['staffList'] = User::where($staffFilter)->get();
		return view('manage-sells',$data);
	}

	public function savesells(Request $request){

        $msg = [
            'name.required' => 'Enter Your Name',
            'email.required' => 'Enter Your Last Name',
            'phone_no.required' => 'Enter Your Mobile No',
			'password.required' => 'Enter Your Password',
			'cpassword.required' => 'Enter Your Confirm Password',
        ];
        $this->validate($request, [
            'name' => 'required',
            'phone_no' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'min:6',
            'cpassword' => 'min:6|required_with:password|same:password'
        ], $msg);


        $admin = new User();
        $admin->name       = $request['name'];
		$admin->email      = $request['email'];
        $admin->phone_no   = $request['phone_no'];
        $admin->password   = bcrypt($request->cpassword);
		$admin->admin_id   = Auth::user()->id;
        $admin->status     = 1;
		$admin->usertype   = 4;
        $admin->save();

	    return redirect()->route('sells')->with('message','Successfully registered.');
	}

	public function deleteuser($id)
	{
		//DB::table('users')->where('id',$id)->delete();
		
		 DB::table('users')->where('id',$id)->update(array('status' => "2"));

		return redirect(route('add-staff'))->with('message', 'Users Deleted Successfully!!');
	}

    public function editsells(Request $request, $adminsid)
    {
		$data['admindata'] = User::where('id',$adminsid)->first();

		return view('edit-sells',$data);
    }

     public function users_assigned() {
		
		$sellsFilter = array(
			"status" => 1,
			"usertype" => 4
		);
		
        $sells = User::where('admin_id',Auth::user()->id)->where($sellsFilter)->get();
        //dd($sellsFilter);
		$where2 = array('users.status','=', "1");
	    $where3 = array('users.usertype','=',"2");		
		$whereArray = array($where2, $where3);
		
		$users  = User::where($whereArray)->orderby('id',"desc")->get();

        return view('user-assigned',array("usersdata" => $users,"sellsdata" => $sells));
    }

    public function users_assigned_sells(Request $request)
    {
    	$usersIds = explode(",",$_POST['users-ids']);
		$sellsId = $_POST['sells_id'];
		
		foreach($usersIds as $key => $value) {
			//DD($value);
			User::where('id', '=', $value)->update(array('assigned_to_users' => $sellsId));
		}
		
		return redirect(route('users-assigned'))->with('message', 'Users Assigned Successfully!!');
    }

    public function usersAssignedRetrival() {
		
       

		$offset = $_REQUEST['start'] ;
		
		$limit = $_REQUEST['length'];
		
		$search = json_encode($_REQUEST['search']);
		foreach( $_REQUEST['search'] as $key => $value) {
		   $search = trim($value);
		   break;
		}	
		
		$searchValue = $search;
		$search = strlen($search);
	
		$whereArray = [];
		$orWhereFilter = [];
		
		$where1 = array('usertype','=',2);

		$staff = User::where('admin_id',Auth::user()->id)->get();
		
		$whereArray = array($where1);

		$orWhereFilter1 = $orWhereFilter2 =  array();
    			
		if ($search > 0) {
			$whereArray[] = array('name','like',"$searchValue%");
			$orWhereFilter1 = array($where1);
			$orWhereFilter2 = array($where1);
			$orWhereFilter1[] = array('phone_no','like',"$searchValue%");
			$orWhereFilter2[] = array('email','like',"$searchValue%");			
		}
		
		if(isset($_REQUEST['user-status']) && $_REQUEST['user-status'] != "0") {
			$whereArray[] = array('status','=',$_REQUEST['user-status']);
		}

		$adminData  = User::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)
		->select('users.id','users.name','users.phone_no','users.email','users.status','assigned_to_users')
		->leftjoin('user_plan_details','user_plan_details.user_id','users.id')
		->orderby('users.id','desc')
		->offset($offset)
		->limit($limit)
		->get();

        foreach($adminData as $key1 => $value1) {
			foreach($staff as $key => $value) {
				if ($value1->assigned_to_users == $value->id) {
					$adminData[$key1]->assignee = ucwords($value->name);
				} 
			}
		}

		$count = User::where('usertype',2)->count();
		$filterCount = 0;
		
		$filterCount = User::where($whereArray)
		->orWhere($orWhereFilter1)
		->orWhere($orWhereFilter2)->count();
		$result = array("search" => $whereArray,"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => $filterCount);
		
		$result['data'] = $adminData;
		
		print_r(json_encode($result));
	}

	public function edit_sells_post(Request $request, $adminsid){
        
		$data = array(
            'name'                        => $request['name'],
            'phone_no'                    => $request['phone_no'],
            'status'                      => $request['status'],
        );
		
		if ($request['yesno'] == "1" && strlen(trim($request['pwd']))) {			
            $data['password'] = Hash::make($request['pwd']);
		}

        User::where('id', $adminsid)->update($data);
		
		Session::flash('message', "Records Updated successfully.");
        return redirect('manage-sells');
        //return redirect('edit-admins/'.$adminsid);
    }
	
	public function setDefaultCampaign() {
		//print_r($_POST['leadIds']);
		
		$defaultCampaigns = explode(',',$_POST['leadIds']);
		
		$data = array(
            'is_default' =>  1
        );
		
		Campaign::whereIn('id', $defaultCampaigns)->update($data);
	}
	
	
        public function webhookVerify(Request $request) {

             $input = json_decode(file_get_contents('php://input'), true);
             $pageId = isset($input['entry'][0]['changes'][0]['value']['page_id']) ? $input['entry'][0]['changes'][0]['value']['page_id'] : 0;
             $formId = isset($input['entry'][0]['changes'][0]['value']['form_id']) ? $input['entry'][0]['changes'][0]['value']['form_id'] : 0;
             $leadgenId = isset($input['entry'][0]['changes'][0]['value']['leadgen_id']) ? $input['entry'][0]['changes'][0]['value']['leadgen_id'] : 0;

\Log::info('Incoming ID: ' . $leadgenId);


            if ($pageId != 0 && $formId != 0) {

                $leads = DB::table('subscribe_pages')
				
->select('subscribe_pages.name_field','subscribe_pages.email_field','subscribe_pages.phone_field','subscribe_pages.other_fields','subscribe_pages.id','page_token','project_id',
'campaign_id','segment_id','project_name','segment_name','subscribe_pages.user_id','subscribe_pages.is_active')
                ->join('project_master','project_master.id','=','subscribe_pages.project_id')
                ->join('segment','segment.id','=','subscribe_pages.segment_id')

                ->where([['page_id',$pageId],['form_id',$formId]])
                ->get();
                if (isset($leads[0]->id)) {
                /*      DB::table('subscribe_pages')
                ->where('id', $leads[0]->id)
                ->update(['leadgen_id' => "$leadgenId"]);
                */
                $pageToken = $leads[0]->page_token;


$setting = $leads[0]->is_active;


\Log::info('Incoming setting: ' . $setting);

$projectId =   $leads[0]->project_id;
$segmentId =   $leads[0]->segment_id;
$campaignId =  $leads[0]->campaign_id;
$userId = $leads[0]->user_id;
\Log::info('user id: ' . $userId);
\Log::info('PageId, formId: ' . $pageId.','.$formId);
$projectName = $leads[0]->project_name;

$segmentName = $leads[0]->segment_name;

$nameField = $leads[0]->name_field;
$emailField = $leads[0]->email_field;

$phoneField = $leads[0]->phone_field;

$otherFields = $leads[0]->other_fields;


if (null !== $otherFields || $otherFields != '0') {

$otherFieldsData = explode(",",$otherFields);

}

$otherFieldsValue = array();

\Log::info('saved field: ' . $phoneField);

                //DB::insert('insert into facebook_leads (subscribe_pages_id,leadgen_id) values(?,?)',[$leads[0]->id,$leadgenId]);



$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://graph.facebook.com/v11.0/".$leadgenId."?access_token=".$pageToken,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "postman-token: c219df04-9cd9-5bbc-51ae-0d5e0a302b52"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

\Log::info('Incoming lead: ' . $response);

$data = json_decode($response,true);

$name = $email = $contact = "";
if (isset($data['field_data'])) {

foreach($data['field_data'] as $key => $value) {
\Log::info('Incoming check: ' . $value['values'][0]);

if (isset($otherFieldsData) && count($otherFieldsData) > 0 ) {
 foreach($otherFieldsData as $key1 => $value1) {
  if ($value1 == $value['name'] ) {

$otherFieldsValue[$value1] = $value['values'][0];
break;
        }
}
}

/*

if ($value['name'] == "full_name") {

$name = $value['values'][0];
}

if ($value['name'] == "email") {
$email = $value['values'][0];
}

if ($phoneField == $value['name']) {

$contact = $value['values'][0];

$contact = substr($contact, -10);
\Log::info('Incoming phone: ' . $contact);


} else if ($value['name'] == "phone_number" && $phoneField == null) {
   $contact= $value['values'][0];
   $contact = substr($contact, -10);

\Log::info('Incoming phone1: ' . $contact);

}

*/

if ($nameField == $value['name']) {

$name = $value['values'][0];


} else if ((null == $nameField || $nameField == '0') && stripos($value['name'], 'full_name') !== false) {
        $name = $value['values'][0];
}

if ($emailField == $value['name']) {

$email = $value['values'][0];

} else if ((null == $emailField || $emailField == '0') && stripos($value['name'], 'email') !== false) {

 $email = $value['values'][0];
}


if ($phoneField == $value['name']) {

$contact = $value['values'][0];

$contact = substr($contact, -10);
\Log::info('Incoming phone: ' . $contact);
} else if ((null == $phoneField || $phoneField == '0') && stripos($value['name'], 'phone_number') !== false ) {


$contact= $value['values'][0];


$contact = substr($contact, -10);
\Log::info('Incoming phone2: ' . $contact);


}



}
//$name = isset($data['field_data'][0]['full_name']) ?  $data['field_data'][0]['full_name'] : "test";

//$email = isset($data['field_data'][0]['email']) ?  $data['field_data'][0]['email'] : "";

//$contact = isset($data['field_data'][0]['phone_no']) ?  $data['field_data'][0]['phone_no'] : "";

$leadOn = isset($data['created_time']) ? $data['created_time'] : "";
$otherDetails = json_encode($otherFieldsValue);

if ($setting == "1") {

$users = DB::table('leads')->select('mobile_no')->where(array(array('mobile_no',$contact),array('campaigns_id',$campaignId)))->count();

		
if (!$users ) {

DB::insert('insert into facebook_leads (subscribe_pages_id,leadgen_id,name,email,phone_no,lead_created_on,other_fields) values(?,?,?,?,?,?,?)',[$leads[0]->id,$leadgenId, $name, $email,
$contact, $leadOn,$otherDetails]);

$fbLead = array(

        "name" => $name,
        "email" => $email,
        "contact" => $contact,
        "projectId" => $projectId,
        "campaignId" => $campaignId,
        "segmentId" => $segmentId,
        "userId" => $userId,
        "projectName" => $projectName,
        "segmentName" => $segmentName
);

$this->addLeadFromFb($fbLead);

} else {
DB::insert('insert into leads_duplicate (user_id,name,email,contact,campaign_id,project_id,segment_id,source) 
		values(?,?,?,?,?,?,?,?)',[$leads[0]->id, $name, $email,$contact, $campaignId,$projectId,$segmentId,"fb"]);
}

} else {
DB::insert('insert into leads_duplicate (user_id,name,email,contact,campaign_id,project_id,segment_id,source)
                values(?,?,?,?,?,?,?,?)',[$leads[0]->id, $name, $email,$contact, $campaignId,$projectId,$segmentId,"fbpage setting-inactive"]);

}

}
   }
            }

\Log::info('Incoming verification token: ' . $leadgenId); // I added this line
        if ($request->get('hub_mode') === 'subscribe' && $request->get('hub_verify_token') === "abc123") {
                return Response::create($request->get('hub_challenge'))->send();
        }
/*
                error_log(print_r($request->input()),true);
$request_body = file_get_contents('php://input');

error_log(print_r($request_body),true);

                $challenge = $_REQUEST['hub_challenge'];
                $verify_token = $_REQUEST['hub_verify_token'];


                if ($verify_token === "abc123") {


                        $request_body = file_get_contents('php://input')

                        error_log('testwebhook');
                        echo $challenge;
                }
*/

        }

 public function updateHuzefaLeads() {

$leads = array(
"Paramjeetdahiya8090@gmail.com"  =>  "918684956600",
"saudziad@gmail.com"  =>  "919711116664",
"shishpalsingh68@gmail.com"  =>  "919791435211",
"ritesh233t@hotmail.com"  =>  "918810447612",
"ajitsingh79230@gmail.com"  =>  "917678640554",
"puneet6542@gmail.com"  =>  "919650916776",
"arunyadavup2016@gmail.com"  =>  "918742942865",
"mandlamanju74@gmail.com"  =>  "919990850578",
"nafet49@gmail.com"  =>  "919999155432",
"praveen.sharma19941008@gmail.com"  =>  "918010381725",
"girisushilkumar38@gmail.com" 	 =>  "919354427948",
"praveen251172@yahoo.co.in" =>  "919958766005",
"deepak_sethi86@yahoo.com"  =>  "919968999909",
"vasu.aggarwal543@gmail.com"  =>  "917982395238",
"saagar14485@gmail.com"  =>  "919560826382",
"jitender7403@gmail.com" => "919555982718",
"uksuryavanshi@gmail.com" => "919868482453",
"ganeshsinghaswal@gmail.com" => "918130735719",
"sheetaltyagi1976@gmail.com" => "919469677631",
"devrajdevdev798@gmail.com" => "8920119620",
"rihassan@gmail.com	Hassan" => "918178454757"
);

	foreach ($leads as $key => $value) {
		
		$phoneno =  substr($value, -10);
		
		$email = $key;
LeadcornCampaigns::where('mail_id', '=', $email)->update(array('mobile_no' => $phoneno,"is_stopped" => "1","stopped_reason" => "Mobile no updated lately"));		
//Lead::where('mail_id', '=', $email)->update(array('mobile_no' => $phoneno));

	}
echo "successs"; 
}



public function runCron() {
//die('test');		
		date_default_timezone_set('Asia/Kolkata');

		// $data = DB::table('lead_corn_campaigns')->where([['status','2'],['is_stopped','0'],['delivery_date_time','like',date('Y-m-d')."%"]])->get();
		$data = DB::table('lead_corn_campaigns')->select('id','automation_type','is_stopped','user_id','mobile_no','name','message','mail_id','image','delivery_date_time','automation_messages_id','lead_id')->where([['status','2'],['is_stopped','0'],['cron_set','0'],['delivery_date_time','like',date('Y-m-d')."%"]])->distinct()->get();
		$c = DB::table('lead_corn_campaigns')->where([['status','2'],['is_stopped','0'],['delivery_date_time','like',date('Y-m-d')."%"]])->count();

        $currentdatetime = date('Y-m-d H:i');
        \Log::info('cront running at: '.$c.' '.$currentdatetime);

		$leadIds = [];
		
		foreach($data as $row){
			$deliverydate = date('Y-m-d H:i',strtotime($row->delivery_date_time));
			
            if($deliverydate <= $currentdatetime){
				$leadIds[] = $row->id;
			}
		}
		
		DB::table('lead_corn_campaigns')->whereIn('id', $leadIds)->update(array('cron_set' => "1"));
		
		 
		foreach($data as $row){

            $deliverydate = date('Y-m-d H:i',strtotime($row->delivery_date_time));
			
            if($deliverydate <= $currentdatetime){
                if($row->automation_type == 1 && !$row->is_stopped){
                    $usersmsapikey = Getuserapikey($row->user_id,1);
                    $sms_from_name = Getusersmsfromname($row->user_id,1);
                    testsendsmsnew($usersmsapikey,$sms_from_name,$row->mobile_no,str_replace("{Full Name}",$row->name,$row->message));

                   /* $updatecmp = array(
                        'status'                    => 1,
                    );
    
                     LeadcornCampaigns::where('id', $row->id)->update($updatecmp); */
                     

                        /*$leadcorn = new LeadcornCampaigns();
                            $leadcorn->user_id               = Auth::user()->id;
                            $leadcorn->campaign_id           = $request['campaign_id'];
                            $leadcorn->lead_id               = $lastid;
                            $leadcorn->automation_messages_id= $row->id;
        
                            $leadcorn->name                  = $request['name'];
                            $leadcorn->mail_id               = $request['mail_id'];
                            $leadcorn->mobile_no             = $request['mobile_no'];
        
                            $leadcorn->automation_type       = $row->automation_type;
        
                            $leadcorn->delivery_date_time    = $newdatetime;
                            $leadcorn->message               = $row->message;
                            $leadcorn->image                 = $row->image;
                            $leadcorn->status                = 2;
        
                        $leadcorn->save();*/
                     
                    $updateData = array(
						'is_delivered' => "1",
						'actual_delivered_on' => date("Y-m-d H:i:s")
					);

					DB::table('lead_details')->where([['automation_messages_id',$row->automation_messages_id],['lead_id',$row->lead_id]])->update($updateData);

					DB::table('lead_corn_campaigns')->where('id',$row->id)->delete();
				}
				
                if($row->automation_type == 2 && !$row->is_stopped){
                    $usersmsapikey = Getuserapikey($row->user_id,2);
                    testsendemailnew($usersmsapikey,$row->mail_id,str_replace("{Full Name}",$row->name,$row->message));

                    /*$updatecmp = array(
                        'status'                    => 1,
                    );
    
                     LeadcornCampaigns::where('id', $row->id)->update($updatecmp);*/
                    $updateData = array(
						'is_delivered' => "1",
						'actual_delivered_on' => date("Y-m-d H:i:s")
					);

					DB::table('lead_details')->where([['automation_messages_id',$row->automation_messages_id],['lead_id',$row->lead_id]])->update($updateData);

					DB::table('lead_corn_campaigns')->where('id',$row->id)->delete();
                }
                
				if($row->automation_type == 3 && !$row->is_stopped){
                    $usersmsapikey = Getuserapikey($row->user_id,3);
                    
					testsendwhatsappnew($usersmsapikey,$row->mobile_no,str_replace("{Full Name}",$row->name,$row->message),$row->image);
					
					\Log::info('wp cron: '); 	
                    
					/*$updatecmp = array(
                        'status'                    => 1,
                    );
    
                    LeadcornCampaigns::where('id', $row->id)->update($updatecmp);*/
                    
					$updateData = array(
						'is_delivered' => "1",
						'actual_delivered_on' => date("Y-m-d H:i:s")
					);

					DB::table('lead_details')->where([['automation_messages_id',$row->automation_messages_id],['lead_id',$row->lead_id]])->update($updateData);
                    DB::table('lead_corn_campaigns')->where('id',$row->id)->delete();
				}
			}
		}
	}

	public function ajaxactive(Request $request, $id) {
        	$data = array(
        	   'status' =>$request->get('status'),
        	);
        	
        	DB::table('users')->where('id',$id)->update($data);
        	return response('Successfully!');
	}
/*
public function editsells(Request $request, $adminsid)
    {
		$data['admindata'] = User::where('id',$adminsid)->first();

		return view('edit-sells',$data);
    }

public function edit_sells_post(Request $request, $adminsid){
        
		$data = array(
            'name'                        => $request['name'],
            'phone_no'                    => $request['phone_no'],
            'status'                      => $request['status'],
        );
		
		if ($request['yesno'] == "1" && strlen(trim($request['pwd']))) {			
            $data['password'] = Hash::make($request['pwd']);
		}

        User::where('id', $adminsid)->update($data);
		
		Session::flash('message', "Records Updated successfully.");
        return redirect('manage-sells');
        //return redirect('edit-admins/'.$adminsid);
    }
*/

public function getLeadInfo($leadId) {
		
		$leadData =  DB::table('leads')->where('id',$leadId)->first();
		return $leadData;
	}

	public function setLeadsCron() {
		
		$leadIds = explode(",",$_POST['leadIds']);
		
		foreach($leadIds as $leadKey => $leadValue) {
			DB::table('lead_corn_campaigns')->where('lead_id',$leadValue)->delete();
			DB::table('lead_details')->where('lead_id',$leadValue)->delete();
			
			$leadData = $this->getLeadInfo($leadValue);
			//print_r(leadData); die;
			$campaignActivatedOn = $leadData->campaign_activated_on;
			
			$cautomation = DB::table('automations')
			
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.is_active','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
			->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
			->where('automations.campaign_id', $leadData->campaigns_id)
			->where('sms_automation_messages.delivery_type', 'scheduled')
			//->where('sms_automation_messages.is_active', 1)
			->get();
			
			foreach($cautomation as $row){
				$newDate = explode(" ",$campaignActivatedOn);

                if($row->delivery_day == 0){ //Today
					$newdatetime = strtotime($newDate[0]." ".$row->delivery_time);
                } else{ //After Day
                    //$newdatetime = new DateTime(date('Y-m-d '.$row->delivery_time).' + '.$row->delivery_day.' day');
                	$newdatetime = strtotime($newDate[0]." ".$row->delivery_time." ".$row->delivery_day." days");
				}
				
				if ($newdatetime > strtotime("now")) {

					$leadcorn = new LeadcornCampaigns();
					$leadcorn->user_id                = $leadData->user_id;
					$leadcorn->campaign_id            = $leadData->campaigns_id;
					$leadcorn->lead_id                = $leadValue;
					$leadcorn->automation_messages_id = $row->id;

					$leadcorn->name                   = $leadData->name;
					$leadcorn->mail_id                = $leadData->mail_id;
					$leadcorn->mobile_no              = $leadData->mobile_no;

					$leadcorn->automation_type        = $row->automation_type;
					$leadcorn->delivery_date_time     = date("Y-m-d H:i:s",$newdatetime);
					$leadcorn->message                = $row->message;
					$leadcorn->image                  = $row->image;
					$leadcorn->status                 = 2;
			
					//LeadReport
					//echo $newdatetime; die;
					$leadDetail = new LeadDetails();					
					$leadDetail->lead_id               = $leadValue;
					$leadDetail->automation_messages_id= $row->id;
					$leadDetail->delivery_date_time    = date("Y-m-d H:i:s",$newdatetime); 

					if ($row->is_active == "0") {
						$leadcorn->is_stopped = "1";
						$leadDetail->is_cancelled    = "1";
						$leadcorn->stopped_reason = "Event is in off state on lead creation";
						$leadDetail->failure_reason =  "Event is in off state on lead creation";
					}
					$leadcorn->save();
					$leadDetail->save();
				} else {
					//echo date("Y-m-d H:i:s",$newdatetime); die;
					$leadDetail = new LeadDetails();					
					$leadDetail->lead_id               = $leadValue;
					$leadDetail->automation_messages_id= $row->id;
					$leadDetail->delivery_date_time    = date("Y-m-d H:i:s",$newdatetime); 
					$leadDetail->is_cancelled    = "1";
					if ($row->is_active == "0") {
						$leadDetail->failure_reason =  "Event is in off state on lead creation";
					} else {
						$leadDetail->failure_reason =  "Delivery date set to passed date";
					}
					$leadDetail->save();
				}				
			}
		}
	}

public function deletesell($id)
	{
		DB::table('users')->where('id',$id)->delete();
		return back()->with('message', 'Deleted Successfully!!');
	}
public function viewAccountSettings(Request $request) {
			$data['settings'] = DB::table('admin_settings')
						 
->select('auto_assign_leads','send_notification_via_email','send_notification_via_whatsapp','send_notification_to_staff','assignStaffWhatsappEvent')
						 ->where('admin_id',Auth::user()->id)
						 ->get();
        return view('account-settings',$data);
   
	}
	
	public function accountSettings(Request $request) {

		$adminId = isset($_POST['admin_id']) ? $_POST['admin_id'] :  Auth::user()->id;
		$existSettings = DB::table('admin_settings')
						 ->select('id')
						 ->where('admin_id',$adminId)
						 ->count();
		if(isset($_POST['fieldName']) && $_POST['fieldName'] == "auto_assign_leads") {
			$fieldName = "auto_assign_leads";
			$status = $_POST['status'] == "true" ? 1 : 0;
		} else if(isset($_POST['fieldName']) && $_POST['fieldName'] == "send_via_email") {
			$fieldName = "send_notification_via_email";
			$status = $_POST['status'] == "true" ? 1 : 0;
		} else if(isset($_POST['fieldName']) && $_POST['fieldName'] == "send_via_whatsapp") {
			$fieldName = "send_notification_via_whatsapp";
			$status = $_POST['status'] == "true" ? 1 : 0;
		} else if(isset($_POST['fieldName']) && $_POST['fieldName'] == "send_staff") {
                        $fieldName = "send_notification_to_staff";
                        $status = $_POST['status'] == "true" ? 1 : 0;
                } else if(isset($_POST['fieldName']) && $_POST['fieldName'] == "use_staff") {
            		$fieldName = "assignStaffWhatsappEvent";
            		$status = $_POST['status'] == "true" ? 1 : 0;
    		} else if(isset($_POST['fieldName']) && $_POST['fieldName'] == "send_report") {
            		$fieldName = "send_daily_report";
            		$status = $_POST['status'] == "true" ? 1 : 0;
    		}
			
		
		if($existSettings > 0) {
			
			$data = array($fieldName => $status);
			//print_r($data); die;
			DB::table('admin_settings')
				->where('admin_id', $adminId)
				->update($data);
		
		} else {
			DB::insert('insert into admin_settings (admin_id,'.$fieldName.') values(?,?)',[$adminId,$status]);

		}
		echo "1";
	}

public function leadAssign($projectId, $leadId) {
		
		$assignDetails =  DB::table('lead_assign_order')
		->select('executive_ids')
		->where('project_id', $projectId)
		->get();
		$assignedTo = null;
		
		if (isset($assignDetails[0]) && null !== $assignDetails[0]) {
			$executiveIds = $assignDetails[0]->executive_ids;			
			$ids = explode("|",$executiveIds);
		  	
			date_default_timezone_set("Asia/kolkata");
			$todayDate =  date('Y-m-d');

			$assignedData = DB::table('leads')
			->select('assigned_to',DB::raw('COUNT(id) as totalLeads'))
			->where('lead_assigned_on','like',$todayDate."%")
			->wherein('assigned_to', $ids)
			->groupby('assigned_to')
			->get();
			
			$assignedDetails = [];
			$alreadyAssigned = [];
			
			foreach($assignedData as $key => $value) {
				$assignedDetails[$value->assigned_to] = $value->totalLeads;
				$min = $value->totalLeads;
				$alreadyAssigned[] = $value->assigned_to;
			}
			
			foreach($ids as $key => $value) {
				if(!in_array($value,$alreadyAssigned)) {
					$assignedTo = $value;
					break;
				}
			}
			
			
			if (null == $assignedTo) {
				foreach($assignedDetails as $key => $value) {
					$min = $value;
					$to = $key;
					break;
				}
				
				foreach($assignedDetails as $key => $value) {
					if ($min > $value) {
						$to = $key;
						break;
					}
				}
				$assignedTo = $to;
			}
			
			if(null !== $assignedTo) {
				//update leads table
				
				$data = array(
					'assigned_to' => $assignedTo,
					'lead_assigned_on' => date("Y-m-d H:i:s") 
				);
				
				Lead::where('id', '=', $leadId)->update($data);
		
			}
		}
	}

public function sendLeadNotificationViaWhatsapp($adminMobileNo, $leadData) {
		
                $usersmsapikey = User::where("id","8")->select("whatsapp_api_key")->get()[0];

                $api = $usersmsapikey->whatsapp_api_key;
                $mobileno = $adminMobileNo;


                $userPack = DB::table('users_pack')->where('users_id', '=', $leadData['userId'] )->first();


		$leadName = $leadData['name'];
		$leadMobno = $leadData['mobno'];
		$leadEmail = $leadData['email'];
		$leadProject = $leadData['project'];

 $count = Lead::where(array(array('user_id',$leadData['userId'])))->count();
if ((isset($userPack) && $userPack->pack == "1") && $count > 75) {


$leadName = $leadName;
$leadMobno = $leadData['mobno'][0].str_repeat("*",strlen($leadMobno)-1);
$emailDetail = explode('@',$leadEmail);
$emailUser = $emailDetail[0];
$leadEmail = $emailUser[0].str_repeat("*",strlen($emailUser)-1)."@".$emailDetail[1];
$leadProject = $leadData['project'];

}

$msg = "Congratulations👏,
New Leads From *Facebook*
Name- *$leadName*
Mobile No- *$leadMobno*
Email- *$leadEmail*
Project Name-".$leadProject."
👉 Login to see more

https://realauto.in/leads-master

Thanks & Regards
RealAuto Team";

                //$msg = strip_tags($msg);
                $attachment = "";
                testsendwhatsappnew($api,$mobileno,$msg,$attachment);

		if (isset($leadData['sendStaff']) && $leadData['sendStaff'] == "1") {

			
			  \Log::info('phoneno missing:'. json_encode($leadData));


                         $assignedTo = Lead::select('assigned_to')->where('id', '=', $leadData['id'])->first();
			if ($assignedTo != null && $assignedTo->assigned_to != 0 ) {
				$staffList = User::select('phone_no')->where('id',$assignedTo->assigned_to)->first();

				$mobileno = $staffList->phone_no;

                                testsendwhatsappnew($api,$mobileno,$msg,$attachment);
			}
			/*
			foreach($staffList as $key => $value) {
				$mobileno = $value->phone_no;

 				testsendwhatsappnew($api,$mobileno,$msg,$attachment);
			}*/


		}

	}


public function sendLeadNotificationViaEmail($adminEmail, $leadData) {
		
              
		$toemail = $adminEmail;

		$leadName = $leadData['name'];
		$leadMobno = $leadData['mobno'];
		$leadEmail = $leadData['email'];
		$leadProject = $leadData['project'];

$userPack = DB::table('users_pack')->where('users_id', '=', $leadData['userId'] )->first();
 $count = Lead::where(array(array('user_id',$leadData['userId'])))->count();
if ((isset($userPack) && $userPack->pack == "1") && $count > 75) {

$leadName = $leadName[0].str_repeat("*",strlen($leadName)-1);
$leadMobno = $leadData['mobno'][0].str_repeat("*",strlen($leadMobno)-1);
$emailDetail = explode('@',$leadEmail);
$emailUser = $emailDetail[0];
$leadEmail = $emailUser[0].str_repeat("*",strlen($emailUser)-1)."@".$emailDetail[1];
$leadProject = $leadData['project'];

}


		$data = array(
			'name'             =>  $leadName,
			'phone'            =>  $leadMobno,
			'email'            =>  $leadEmail,
			'project'          =>  $leadProject,
			'source'           =>  "Facebook"
		);



        Mail::send('lead-notification-email', $data, function($message) use ($toemail){
            $message->to($toemail)->subject("New Lead: ");
        });


$alternativeEmail =  DB::table('users')->select('alternative_email')->where('id', '=', $leadData['userId'] )->first();


        if ($alternativeEmail->alternative_email !== null) {

                $toemail = $alternativeEmail->alternative_email;
		 Mail::send('lead-notification-email', $data, function($message) use ($toemail){
            $message->to($toemail)->subject("New Lead: ");
        });

}




 		if (isset($leadData['sendStaff']) && $leadData['sendStaff'] == "1") {


 $assignedTo = Lead::select('assigned_to')->where('id', '=', $leadData['id'])->first();
                        if ($assignedTo != null && $assignedTo->assigned_to != 0 ) {
                                $staffList = User::select('email')->where('id',$assignedTo->assigned_to)->first();

                                   $toemail = $staffList->email;

                                
                                Mail::send('lead-notification-email', $data, function($message) use ($toemail){
                                        $message->to($toemail)->subject("New Lead: ");
                                });

                        }

/*
                        $staffList = User::select('email')->where('admin_id',$leadData['userId'])->get();

                        foreach($staffList as $key => $value) {
                                $toemail = $value->email;

                                Mail::send('lead-notification-email', $data, function($message) use ($toemail){
            				$message->to($toemail)->subject("New Lead: ");
        			});

                        }
*/

                }


	}

public function subscribeTo(Request $request) {

$userDetail = User::where("id", Auth::user()->id)->select("name","email","phone_no")->get()[0];

$ch = curl_init();

$digits = 3;
$unique = $userDetail->phone_no."_".rand(pow(10, $digits-1), pow(10, $digits)-1);

curl_setopt($ch, CURLOPT_URL, 'https://api.cashfree.com/api/v2/subscriptions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"subscriptionId\":\"$unique\", \"planId\":\"test123\", \"customerName\" : \"$userDetail->name\", 
\"customerEmail\":\"$userDetail->email\", 
\"customerPhone\":\"$userDetail->phone_no\",  \"returnUrl\":\"test.php\",   \"notificationChannels\": [\"EMAIL\", \"SMS\"]}");

$headers = array();
$headers[] = 'Cache-Control: no-cache';
$headers[] = 'Content-Type: application/json';
$headers[] = 'X-Client-Id: 16488668f7042021e8585e8609688461';
$headers[] = 'X-Client-Secret: b77d289340d3921d3b1cd348f3193c441ccb643b';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

$data = json_decode($result,true);
 
$adminId = Auth::user()->id;
        $subscriptionId = isset($data['subReferenceId']) ? $data['subReferenceId'] : 0;
        $planId = 0;
$amt = 10;
        DB::insert('insert into razorpay_subscription (admin_id,plan_id,subscription_id,total_amt,duration) values(?,?,?,?,?)',[$adminId,$planId,$subscriptionId,$amt,"monthly"]);
 
//print_r($data); die;
echo isset($data['authLink']) ? $data['authLink'] : "";
die;
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);


//echo "dd";
}

public function subscribeTo1($planId){

$ch = curl_init();
$fields = array();
/*
if ($planId == "3") {
	$fields["plan_id"] = "plan_IXeXkZ6yEQtVOo";
	$amt = "3302.82";
} else if ($_GET['plan'] == "2") {
	$fields["plan_id"] = "plan_IXeNn77sdynVEc";
	$amt = 1;
} else {
	$fields["plan_id"] = "plan_IXeMen4JuDxIdZ";
	$amt = 1;
}
*/
$amt = 50;
 $fields["plan_id"] = "plan_IY1arKSSWUZxV5";


$fields["total_count"] = 2;
//$fields["start_at"] = "1323993600";

$fields["start_at"] = "1639669134";

curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/subscriptions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_USERPWD,  'rzp_test_nAE8qLyiH5DuMh' . ':' . '1qlkPSsKxQL4MwVbX1YPkNIN' );


curl_setopt($ch, CURLOPT_USERPWD,  'rzp_live_OTsOwae2RLoAZk' . ':' . 'fp3A6ePnKeXbFKSlbz11RqGs' );

$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$data = curl_exec($ch);


if (empty($data) OR (curl_getinfo($ch, CURLINFO_HTTP_CODE != 200))) {
   $data = FALSE;
} else {

	$response = json_decode($data,true);

	$adminId = Auth::user()->id;
	$subscriptionId = $response['id'];
	$planId = $response['plan_id'];
	DB::insert('insert into razorpay_subscription (admin_id,plan_id,subscription_id,total_amt,duration) values(?,?,?,?,?)',[$adminId,$planId,$subscriptionId,$amt,"monthly"]);
	//return json_decode($data, TRUE);
	
	
	$url = $response['short_url'];
	
	header("Location: ".$url);
	die;
}
curl_close($ch);
	}



public function getDropboxLink(Request $request) {

$parameters = array('path' => "/".$request['dropbox_link']);
//$headers = array('Authorization: Bearer aYjbweMcf_sAAAAAAAAAAYzNYgkvbetODenA80qKcRqKePzntXWhWmfmnPk1ecrJ',

$headers = array('Authorization: Bearer Gm2wpFjlAI8AAAAAAAAAAV54kmUSTAZs6JlX7nJxgxh4TInOrBCwb78cbpjpd3tb',
'Content-Type: application/json');
$curlOptions = array(
CURLOPT_HTTPHEADER => $headers,
CURLOPT_POST => true,
CURLOPT_POSTFIELDS => json_encode($parameters),
CURLOPT_RETURNTRANSFER => true,
CURLOPT_VERBOSE => true
);
$ch = curl_init('https://api.dropboxapi.com/2/sharing/create_shared_link_with_settings');
curl_setopt_array($ch, $curlOptions);
$response = curl_exec($ch);
//print_r(var_dump($response));
//echo "<pre>";
$test = json_decode($response);
//print_r(json_decode($response));
//print_r($test->shared_link_already_exists);
//die;

if (isset($test->url)) {
$url = explode("?", $test->url);
} else {

$url = explode("?",$test->error->shared_link_already_exists->metadata->url);

}

$finalUrl = $url[0]."?dl=1";

echo $finalUrl; die;
}
	
	public function saveAdminCode() {
		$code = $_POST['code'];
		$data = array('admin_code' => $code);
		
		User::where('id', Auth::user()->id)->update($data);
		return redirect()->route('generate-code')->with('message','Successfully Created Code.');
	}
	public function generateAdminCode() {
		$code = User::select('admin_code')->where('id', Auth::user()->id)->first();
		$code = isset($code->admin_code) ? $code->admin_code : "";
		return view('customer-code',array('admin_code'=> $code));

	}


	public function viewTutorialGallery()
	{
		$data['tutorial'] = DB::table('tutorial')->get();
		
		return view('tutorials-gallery',$data);
	}

	public function makeStarter(Request $request, $userId)
	{
		 $ids = [];
		$startDate = $request->date;
        	$month = date('m', strtotime($request->date));
        	$year = date('Y', strtotime($request->date));

               $data = Lead::select('id','created_at')->where('user_id', $userId)->whereMonth('created_at',$month)->whereYear('created_at', $year)->limit(50)->orderBy('id','asc')->get();

               
                foreach($data as $key => $value) {
                     $ids[] = $value->id;
                }

print_r($ids); die;
                
                Lead::where('user_id',$userId)->whereMonth('created_at',$month)->whereYear('created_at',$year)->whereNotIn('id',$ids)->update(array('leads_show' => 1));

	}

	public function testToken($userId) {
		
		//echo $userId; die;
		$pageSubscribed = DB::table('subscribe_pages')->where('user_id',$userId)->get();

		foreach($pageSubscribed as $key => $value) {
			$id = $value->id;
			$pageToken = $value->page_token;

    $ch =
curl_init('https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=3312603435483167&client_secret=ab4719b6fd51d77b5b639ffdc538a2bd&fb_exchange_token='.$pageToken);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $data = curl_exec($ch);
$data = json_decode($data,true);
print_r($data); 
//$pageToken = $data['access_token'];
//DB::table('users')->where('id', $id)->update(array("page_token"=>$pageToken));
echo "success"; 
		}
/*
		$ch = 
curl_init('https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=3312603435483167&client_secret=ab4719b6fd51d77b5b639ffdc538a2bd&fb_exchange_token='.$pageToken);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$data = curl_exec($ch);
$data = json_decode($data,true);
//print_r($data); die;
$pageToken = $data['access_token'];
DB::table('users')->where('id', $id)->update(array("page_token"=>$pageToken));
*/
echo "test";

		curl_close($ch);

	}

    public function edit_subadmins(Request $request, $adminsid){

		$data['admindata'] = 
User::where('users.id',$adminsid)->leftjoin('user_plan_details','user_plan_details.user_id','=','users.id')->leftjoin('users_pack','users_pack.users_id','=','users.id')->first();
		
		if (isset($data['admindata']) && null == $data['admindata']->id) {
			$data['admindata']->id = $adminsid;
		}
		
		//echo "<pre>";
		//print_r($data['admindata']); die;
		return view('edit-subadmins',$data);
    }

public function manageSubAdmins($adminId)
	{  
		$staffFilter = array(
			'admin_id' => $adminId
		);
		$data['staffList'] = User::where($staffFilter)->get();
		return view('manage-subadmins',$data);
	}

public function viewSubadminLeads(Request $request) {


		return view('subadmin-leads');
	}



    public function subadminLeadsRetrival() {

        $offset = $_REQUEST['start'] ;
        
        $limit = $_REQUEST['length'];
        

        $search = json_encode($_REQUEST['search']);
        foreach( $_REQUEST['search'] as $key => $value) {
           $search = trim($value);
           break;
        }
    
        $searchValue = $search;

        $search = strlen($search);
    
        $whereArray = [];
        $orWhereFilter = [];

        $where1 = array('subadmin_leads.admin_id','=',Auth::user()->id);
        $where2 = array('subadmin_leads.status','=', "1");
        //$where3 = array('subadmin_leads.is_active','=',"1");
        $where3 = $where2;
        $whereArray = array($where1, $where2, $where3);

        $orWhereFilter1 = $orWhereFilter2 = $orWhereFilter3=  $orWhereFilter4= $orWhereFilter5= $orWhereFilter6= array();
                
            if ($search > 0) {
                $whereArray = array();
                //$whereArray[] = array('subadmin_leads.name','like',"$searchValue%");
                //$whereArray[] = $where4;
                $orWhereFilter1 = array($where1, $where2, $where3);
                $orWhereFilter2 = array($where1, $where2, $where3);
                $orWhereFilter3 = array($where1, $where2, $where3);
                $orWhereFilter4 = array($where1, $where2, $where3);
                $orWhereFilter5 = array($where1, $where2, $where3);
                $orWhereFilter6 = array($where1, $where2, $where3);

                $orWhereFilter1[] = array('subadmin_leads.mobile_no','like',"$searchValue%");
                //$orWhereFilter2[] = array('subadmin_leads.mail_id','like',"$searchValue%");
                //$orWhereFilter3[] = array('campaigns.campaigns_name','like',"$searchValue%");
                $orWhereFilter4[] = array('subadmin_leads.name','like',"$searchValue%");
                $orWhereFilter5[] = array('subadmin_leads.project_name','like',"$searchValue%");
                //$orWhereFilter6[] = array('subadmin_leads.segment_name','like',"$searchValue%");
            }

            if (isset($_REQUEST['status']) && $_REQUEST['status'] >= 0) {
                
                $whereArray[] = array('subadmin_leads.lead_status','=',$_REQUEST['status']);
                
            }
            
        $lead = DB::table('subadmin_leads')->where($whereArray)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter1)
        
        ->select('subadmin_leads.lead_assigned_on','subadmin_leads.assigned_to','subadmin_leads.lead_status','subadmin_leads.created_at','subadmin_leads.is_cron_disabled',
            'subadmin_leads.id', 'subadmin_leads.name', 'subadmin_leads.mail_id', 'subadmin_leads.mobile_no', 'subadmin_leads.project_name', 
'subadmin_leads.segment_name','subadmin_leads.source','users.name as subadmin')
		->join('users','users.id','=','subadmin_leads.added_by')     
        ->offset($offset)
            ->limit($limit)
        ->get();
        
        $search = json_encode($_REQUEST['search']);
        
        foreach( $_REQUEST['search'] as $key => $value) {
           $search = trim($value);
                   break;       
        }
        
        
        $leadAttended = DB::table('lead_comments')
        ->select('subadmin_lead_id')
        ->distinct('subadmin_lead_id')
        //->where('user_id',Auth::user()->id)
        ->get();
        
        $attendedLeads = [];
        
        if (null !== $leadAttended) {
            foreach($leadAttended as $key => $value) {
                    $attendedLeads[] = $value->subadmin_lead_id;
            }
            
        }
        
        $count = DB::table('subadmin_leads')->where($whereArray)->count();


        $s = DB::table('subadmin_leads')->where($whereArray)->toSql();

        $filterCount =  DB::table('subadmin_leads')->where($whereArray)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter1)->count();
        
//comment below section if not attended is not required    

$i =0 ;
$notAttended  = 0;

foreach($lead as $key => $value) {
    $lead[$i]->isAttended = 0;
    if (in_array($value->id,$attendedLeads)) {
        $lead[$i]->isAttended = 1;
         //unset($lead[$i]);
    } else {
        $notAttended++;
        //unset($lead[$i]);
    }

    $i++;
}

if ( isset($_REQUEST['status']) && $_REQUEST['status'] == "0") {
                        
    $i =0 ;
    $notAttended  = 0;

    $lead =  DB::table('subadmin_leads')->where($whereArray)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter1)
        ->whereIn('subadmin_leads.id', $attendedLeads)
        ->join('campaigns', 'subadmin_leads.campaigns_id', '=', 'campaigns.id')
        ->select('subadmin_leads.lead_assigned_on','subadmin_leads.assigned_to','subadmin_leads.lead_status','subadmin_leads.created_at','subadmin_leads.is_cron_disabled',
        'campaigns.campaigns_name', 'subadmin_leads.id', 'subadmin_leads.name', 'subadmin_leads.mail_id', 'subadmin_leads.mobile_no', 'subadmin_leads.project_name', 
'subadmin_leads.segment_name','subadmin_leads.source')
    
        ->offset($offset)
        ->limit($limit)
        ->get();
  
    $count = DB::table('subadmin_leads')->where('assigned_to',Auth::user()->id)->count();

    $filterCount = DB::table('subadmin_leads')->where($whereArray)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter1)->whereIn('subadmin_leads.id', 
$attendedLeads)->count();
           

    foreach($lead as $key => $value) {
        $lead[$i]->isAttended = 0;
        if (in_array($value->id,$attendedLeads)) {
            $lead[$i]->isAttended = 1;
             //unset($lead[$i]);

        } else {
        
            $notAttended++;
                //unset($lead[$i]);
             
        }
        
        $i++;
    }
}

        $result = array("search" => $attendedLeads,"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => $filterCount);
    
        $result['data'] = $lead;
        print_r(json_encode($result));
	}


public function whatsappChannelAction(Request $request)
	{
		$action = $_POST['action'];

		$adminwhatsapp_api_key = DB::select('select whatsapp_api_key from users where name = ?', ['admin']);
        $secretKey = $adminwhatsapp_api_key[0]->whatsapp_api_key;
        $apiRequest = array(
            "username" => "AYAN SAHA",
            "password" => "BANGALORE@2020",
            "customerUsername" => Auth::user()->whatsapp_username,
            "action" => $action
        );

        $url       = "https://app.messageautosender.com/api/v1/reseller/customer/channel/operation";
        $ch        = curl_init( $url );
        /*
        $payload   = json_encode( array('customerUsername' => Auth::user()->whatsapp_username, 'channeId' => 7549) );
        */
        $payload = json_encode($apiRequest);

        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $secretKey, 'Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );

        # Send request.
        $Fetchresult = curl_exec($ch);
        $curl_errno  = curl_errno($ch);
        $curl_error  = curl_error($ch);
        curl_close($ch);

        # Print response.
        if ($curl_errno > 0) {
            echo "cURL Error ($curl_errno): $curl_error <br/>";
            return "IF Case..";
        } else {

            $data = json_decode($Fetchresult);

            //print_r($data);
            $message = ($action == "reset") ? "Whatsapp Logout Successfully" : "Whatsapp Restarted Successfully" ;
            return response()->json(['message' => $message]);
            
        }    

	}

	public function dailyWhatsappReport(Request $request) {

		$userId = $request->id;

$admin = DB::table('users')->select('name','email','phone_no')->where('id',$userId)->first();
$adminName = $admin->name;		
$adminMobileNo = $admin->phone_no;

$today_total_leads = Lead::where([['user_id','=',$userId],['is_active','=','1']])->where('created_at','like',"%".date('Y-m-d')."%")->count();

$today_upload_leads =Lead::where([['user_id','=',$userId],['is_active','=','1']])->whereIn('source',['Self','Upload'])->where('created_at','like',"%".date('Y-m-d')."%")->count();

$today_fb_leads =Lead::where([['user_id','=',$userId],['is_active','=','1']])->where('source','Facebook')->where('created_at','like',"%".date('Y-m-d')."%")->count();

$source = array('Form HTML','Google','GoogleAds_PPC','FacebookTraffic','Organic');

$today_website_leads =Lead::where([['user_id','=',$userId],['is_active','=','1']])->wherein('source',$source)->where('created_at','like',"%".date('Y-m-d')."%")->count();


$subadmin = DB::table('users')->select('id','name')->where('admin_id',$userId)->get();

$subadminIds = array();
$i = 0;

$msg = "
Hope you had a great evening!
Here's a summary of what happened in ".$adminName." Account :

Today Leads : ".$today_total_leads."
Leads Uploaded :".$today_upload_leads."
Leads From Facebook :".$today_fb_leads."
Leads From Website :".$today_website_leads."

Agents Performance : ";


$subAdminFound = 0;
date_default_timezone_set("Asia/kolkata");
foreach($subadmin as $key => $value) {
$subAdminFound = 1;
	$subadminIds[$i]['id'] = $value->id;
	$subadminIds[$i]['name'] = $value->name;

	$assignedToday = Lead::where('created_at','like',"%".date('Y-m-d')."%")->where('assigned_to',$value->id)->count();

	$todayLead = Lead::select('id')->where('created_at','like',"%".date('Y-m-d')."%")->where('assigned_to',$value->id)->get();

	$todayLeadIds = array();

	foreach($todayLead as $key1 => $value1) {
		$todayLeadIds[] = $value1->id; 	
	}

	$attendedLeads = DB::table('lead_comments')->wherein('lead_id',$todayLeadIds) ->count();

	$notAttendedLeads = $assignedToday - $attendedLeads;

	$todayDate = date("Y-m-d");
	$currentTime = date("H:m:s");

	$missedFollowups = DB::table('leads_followups')
	->where('user_id', $value->id)
	->where('followup_date', '=',$todayDate)
	->where('followup_time', '<',$currentTime)
	->where('status',null)
	->count();
		
	$msg .= "

	".$value->name." :
	Assigned Leads : ".$assignedToday."
	Attended :".$attendedLeads."
	Not Attended :".$notAttendedLeads."
	Missed Followups :".$missedFollowups ;


}

if ($subAdminFound < 1) {

$msg .= "No Agent Found";
}


$msg .= "

Keep pushing it !!!

Looking for more such stats? Access them using RealAuto Dashboard

Thanks
RealAuto Team
";		
//echo "<pre>";		
//echo $msg; 
$usersmsapikey = User::where("id","8")->select("whatsapp_api_key")->get()[0];

		$api = $usersmsapikey->whatsapp_api_key;
		$mobileno = $adminMobileNo;
		$mobileno = "8878100065";
		$attachment = "";
		testsendwhatsappnew($api,$mobileno,$msg,$attachment);

}

public function manageSettings(Request $request) {
		$data['settings'] = DB::table('admin_settings')
		->select('auto_assign_leads','send_notification_via_email','send_notification_via_whatsapp','send_notification_to_staff',
		'assignStaffWhatsappEvent','send_daily_report')
		->where('admin_id',$request->id)
		->get();
		return view('manage-settings',$data);

	}

public function generateSingleReferralCode(Request $request) {
		$users= User::where("usertype","2")->select("id","phone_no")->get();

		
			$id = $value->id;
			$mobno = $value->phone_no;
			$code = substr($mobno, -4);
			$data = array('referral_code' => $code);
		
			User::where('id', $id)->update($data);

		echo "success";
	}	

public function generateReferralCode(Request $request) {
		$users= User::where("usertype","2")->select("id","phone_no")->get();

		foreach($users as $key => $value) {
			$id = $value->id;
			$mobno = $value->phone_no;
			$code = substr($mobno, -4);
			$data = array('referral_code' => $code);
		
			User::where('id', $id)->update($data);
		}
		echo "success";
	}
public function showAffliates(Request $request) {
		return view('know_your_affilates');
	}

	public function getAffliates(Request $request) {
		$code = $_REQUEST['code'];
		//$code = "1234";

		$affliates = DB::table('referrals')
		->select("users.name","referrals.user_id")
		->join('users', 'users.id', '=', 'referrals.user_id')
		->where('referrals.referral_code', $code)
		->get();

		$data = array();

		$i = 0;

		foreach($affliates as $key => $value) {
			$payment =  DB::table('payment_details')
			->where('admin_id', $value->user_id)
			->get();
			$data[$i]['userid'] = $value->user_id;
			$data[$i]['name'] = $value->name;

			foreach($payment as $key1 => $value1) {

			  $data[$i]['payment_month'][$value1->payment_month] = "yes";
			}
			$i++;
		}

		return view('know_your_affilates',array("data" => $data));
		echo "<pre>";
		print_r($data);
	}

	private function getAccountDetails($api) {
		$secretKey = $api;	
		$url = "https://app.messageautosender.com/api/v1/account/detail";
		$ch = curl_init( $url );
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $secretKey));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		# Send request.
		$result= curl_exec($ch);
		curl_close($ch);
		return $result;	
	}


// Referrels
 public function usersreferrels() {
		
		// $sellsFilter = array(
		// 	"status" => 1,
		// 	"usertype" => 4
		// );
		
        // $sells = User::where('id',Auth::user()->id)->where($sellsFilter)->get();
        // //dd($sellsFilter);
		// $where2 = array('users.status','=', "1");
	    // $where3 = array('users.usertype','=',"2");		
		// $whereArray = array($where2, $where3);
		
		// $users  = User::where($whereArray)->orderby('id',"desc")->get();

        // return view('user-referrel',array("usersdata" => $users,"sellsdata" => $sells));
 	return view('user-referrel');
    }


     public function usersAssignedReferrel() {
		
       

		$offset = $_REQUEST['start'] ;
		
		$limit = $_REQUEST['length'];
		
		$search = json_encode($_REQUEST['search']);
		foreach( $_REQUEST['search'] as $key => $value) {
		   $search = trim($value);
		   break;
		}	
		
		$searchValue = $search;
		$search = strlen($search);
	
		$whereArray = [];
		$orWhereFilter = [];
		//$ref_code = Auth::user()->referral_code;

		$userreferralcode = User::where("id", Auth::user()->id)->select("referral_code")->get();
		$where1 = array('users.referral_code','=','');

		$staff = User::where('admin_id',Auth::user()->id)->get();
		
		$whereArray = array($where1);

		$orWhereFilter1 = $orWhereFilter2 =  array();
    			
		if ($search > 0) {
			$whereArray[] = array('name','like',"$searchValue%");
			$orWhereFilter1 = array($where1);
			// $orWhereFilter2 = array($where1);
			$orWhereFilter1[] = array('phone_no','like',"$searchValue%");
			$orWhereFilter2[] = array('email','like',"$searchValue%");			
		}
		
		if(isset($_REQUEST['user-status']) && $_REQUEST['user-status'] != "0") {
			$whereArray[] = array('status','=',$_REQUEST['user-status']);
		}
		$adminData  = User::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)
		->select('users.id','users.name','users.phone_no','users.email','users.status','users.assigned_to_users','referrals.referral_code')
		->leftjoin('referrals','referrals.user_id','=','users.id')
		->orderby('referrals.id','desc')
		->offset($offset)
		->limit($limit)
		->get();

        foreach($adminData as $key1 => $value1) {
			foreach($staff as $key => $value) {
				if ($value1->assigned_to_users == $value->id) {
					$adminData[$key1]->assignee = ucwords($value->name);
				} 
			}
		}

		$count = User::where('usertype',2)->count();
		$filterCount = 0;
		
		$filterCount = User::where($whereArray)
		->orWhere($orWhereFilter1)
		->orWhere($orWhereFilter2)->count();
		$result = array("search" => $whereArray,"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => $filterCount);
		
		$result['data'] = $adminData;
		
		print_r(json_encode($result));
	}


 // End Referrels
/// Referrals Code


	public function saveReferralCode() {
		$code = $_POST['hiddencode'];
		$data = array('referral_code' => $code);
		User::where('id', Auth::user()->id)->update($data);
		return redirect()->route('createReferralCode')->with('message','Successfully Created Code.');
	}
	public function createReferralCode() {
		$code = User::select('referral_code')->where('id', Auth::user()->id)->first();
		$code = isset($code->referral_code) ? $code->referral_code : "";
		return view('referral-code',array('referral_code'=> $code));
	}
	
////////




}
