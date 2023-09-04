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


	public function manage_admins(){
		$data['adminlist'] = User::where('usertype',2)->get();
		return view('manage-admins',$data);
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
			$this->postWhatsAppPlan($customerUsername);
		}

	    return redirect()->route('add-new-admin')->with('message','Successfully registered.');
	}

    public function edit_admins(Request $request, $adminsid){

		$data['admindata'] = User::where('users.id',$adminsid)->leftjoin('user_plan_details','user_plan_details.user_id','=','users.id')->first();
		
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

		if ($request['isChangePlan'] == "1") {
			$data['whatsapp_plans_id'] = isset($request['whatsapp-plans']) ? $request['whatsapp-plans'] : 0;
			$customerUsername = $request['whatsapp-username'];
			if ( $request['whatsapp-plans'] == "1" ) {
				$this->postWhatsAppPlan($customerUsername);
			}
		}

//print_r($data); die;

        User::where('id', $adminsid)->update($data);
			
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
			
			if ( (isset($request['isChangePlan']) && $request['isChangePlan'] == "1") && ($request['whatsapp-plans'] == "1" && $customerUsername != "" )) {
				$this->postWhatsAppPlan($customerUsername);
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

	public function postWhatsAppPlan($customerUsername){
		
		$inputArray = array(		
			"username" => "AYAN SAHA",
			"password" => "BANGALORE@2020",
			"customerUsername" => $customerUsername,
			"plan" => "reseller_user_server_single_credit_500_15_days",
			"note" => "Setting 500 Credits"
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



	public function webhookVerify1(Request $request) {

             $input = json_decode(file_get_contents('php://input'), true);	
             $pageId = isset($input['entry'][0]['changes'][0]['value']['page_id']) ? $input['entry'][0]['changes'][0]['value']['page_id'] : 0;
	     $formId = isset($input['entry'][0]['changes'][0]['value']['form_id']) ? $input['entry'][0]['changes'][0]['value']['form_id'] : 0;
     	     $leadgenId = isset($input['entry'][0]['changes'][0]['value']['leadgen_id']) ? $input['entry'][0]['changes'][0]['value']['leadgen_id'] : 0;
\Log::info('Incoming ID: ' . $leadgenId);


	    if ($pageId != 0 && $formId != 0) {

		$leads = DB::table('subscribe_pages')      
		->select('subscribe_pages.name_field','subscribe_pages.email_field','subscribe_pages.phone_field','subscribe_pages.other_fields','subscribe_pages.id','page_token','project_id','segment_id','campaign_id','subscribe_pages.user_id','project_master.project_name','segment.segment_name')

		->join('project_master','project_master.id','=','subscribe_pages.project_id')
		->join('segment','segment.id','=','subscribe_pages.segment_id')

	    	->where([['page_id',$pageId],['form_id',$formId]])
            	->get();
		if (isset($leads[0]->id)) {
		/*	DB::table('subscribe_pages')
                ->where('id', $leads[0]->id)
                ->update(['leadgen_id' => "$leadgenId"]);
		*/
		$pageToken = $leads[0]->page_token;



$projectId =   $leads[0]->project_id;
$segmentId =   $leads[0]->segment_id;
$campaignId =  $leads[0]->campaign_id;
$userId = $leads[0]->user_id;
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
/*
if (stripos($value['name'], 'full_name') !== false) {
$name = $value['values'][0];
}
if (stripos($value['name'], 'email') !== false) {

$email = $value['values'][0];
}
if (stripos($value['name'], 'phone_number') !== false) {

$contact= $value['values'][0];

$contact = substr($contact, -10);
}


if ($value['name'] == "full_name") {

$name = $value['values'][0];
}

if ($value['name'] == "email") {
$email = $value['values'][0];
}

if ($value['name'] == "phone_number") {
$contact= $value['values'][0];

$contact = substr($contact, -10);
}

}

//$name = isset($data['field_data'][0]['full_name']) ?  $data['field_data'][0]['full_name'] : "test";

//$email = isset($data['field_data'][0]['email']) ?  $data['field_data'][0]['email'] : "";

//$contact = isset($data['field_data'][0]['phone_no']) ?  $data['field_data'][0]['phone_no'] : "";

$leadOn = isset($data['created_time']) ? $data['created_time'] : "";

DB::insert('insert into facebook_leads (subscribe_pages_id,leadgen_id,name,email,phone_no,lead_created_on) values(?,?,?,?,?,?)',[$leads[0]->id,$leadgenId, $name, $email, $contact, $leadOn]);

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

				$usersmsapikey = Getuserapikey($fbLead['userId'],3);
                testsendwhatsappnew($usersmsapikey,$fbLead['contact'],str_replace("{Full Name}",$fbLead['name'],$message),$value->image);

				//testsendwhatsapp($fbLead['contact'],str_replace("{Full Name}",$fbLead['name'],$message),$value->image);
			}
		}
	}



function userAccessToken() {

// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/


//$ch = curl_init('https://graph.facebook.com/v11.0/oauth/access_token?grant_type=fb_exchange_token&client_id=3312603435483167&client_secret=ab4719b6fd51d77b5b639ffdc538a2bd&fb_exchange_token=SHORT-LIVED-USER-ACCESS-TOKEN');
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
  CURLOPT_URL => "https://graph.facebook.com/v11.0/1052462328826309?access_token=EAAvEy5cNwB8BAIos3A4vDpFmQZAZAEKhSYqaru3SXazE4r1sswRXZCYj1dZCsgA9pSYKUV7yvKa7J4LJCZCDGGLONFugQXmqC4r7eMZCtaetsyFpFAfeXpWJrq9yeBHkS0v05JFh4kBoQfyLizqvK96OZAiUSXVO4UM0E4OP5qXUKU7Pca5fkU9roTpq6K3wmgvEsSDni79EfAcD9PwFBMV2xTWRCfVe04ZD",
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
	$planDetails = DB::table('user_plan_details')->select('id','end_date')->where('user_id', $userId)->get();
	
	$details = array();
	
	if (null !== $planDetails && isset($planDetails[0]))  {
		$details['end_date'] = $planDetails[0]->end_date;
		$endDate = strtotime($planDetails[0]->end_date);
		$details['end_month'] = date("M", $endDate);
		$details['end_year'] = date("Y", $endDate);
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
		
		if(isset($_REQUEST['userPlan']) && $_REQUEST['userPlan'] != "0") {
			$whereArray[] = array('user_plan_details.user_plans_id','=',$_REQUEST['userPlan']);
		}

		$adminData  = User::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)
		->select('users.id','users.name','users.phone_no','users.email','users.status','user_plan_details.start_date','user_plan_details.end_date','user_plan_details.user_plans_id')
		->leftjoin('user_plan_details','user_plan_details.user_id','users.id')
		->orderby('users.id','desc')
		->offset($offset)
		->limit($limit)
		->get();
		
		$count = User::where('usertype',2)->count();
		$filterCount = 0;
		
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
        
        	$webhookData = array();
		
		foreach($input as $key => $value) {
			if (stripos($key, 'name') !== false) {
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

				$usersmsapikey = Getuserapikey($fbLead['userId'],3);
                    		testsendwhatsappnew($usersmsapikey,$fbLead['contact'],str_replace("{Full Name}",$fbLead['name'],$message),$value->image);

				//testsendwhatsapp($fbLead['contact'],str_replace("{Full Name}",$fbLead['name'],$message),$value->image);
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
		DB::table('users')->where('id',$id)->delete();
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
                ->select('subscribe_pages.name_field','subscribe_pages.email_field','subscribe_pages.phone_field','subscribe_pages.other_fields','subscribe_pages.id','page_token','project_id'$
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



$projectId =   $leads[0]->project_id;
$segmentId =   $leads[0]->segment_id;
$campaignId =  $leads[0]->campaign_id;
$userId = $leads[0]->user_id;
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



}
