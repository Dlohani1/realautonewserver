<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Helper;
use Session;
use DB;
use App\Models\Campaign;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
		return redirect('/dashboard');
        return view('home');
    }

    public function contact_more_information(Request $request){

		$request->validate([
			'email' => 'required|unique:users',
			'phone' => 'required|regex:/[0-9]{10}/'
		]);

        $toemail = 'mr.ayansaha@gmail.com';
        
		$data = array(
            'name'             =>  $request['name'],
            'phone'            =>  $request['phone'],
            'email'            =>  $request['email'],
            'company_name'     =>  $request['company_name'],
	    'industry'         =>  isset($request['industry']) ? $request['industry'] : null
        );


        Mail::send('contact_more_information_template', $data, function($message) use ($toemail){
            $message->to($toemail)->subject('Need More Information');
        });

		$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		$length = 5;

		mt_srand(time()); // Set the seed for the random number generator

		for ($i = 0; $i < $length; $i++) {
			$randomIndex = mt_rand(0, strlen($alphabet) - 1);
			$randomChar = $alphabet[$randomIndex];
			$randomString .= $randomChar;
		}


		
		$user = new User();
		$user->name  = $data['name'];
		$user->email = $data['email'];
		$user->phone_no = $data['phone'];
		$user->usertype = "2";
		//$user->status = "2";

		$user->status = "1";

		$user->password = Hash::make("123456");
		$user->company_name = $data['company_name'];
		$user->industry = $data['industry'];
		$user->referral_code =  $randomString;
		$user->save();
		$userId   = $user->id;


		if (isset($_REQUEST['code']) && trim($_REQUEST['code']) != "") {
			$code = trim($_REQUEST['code']);
			date_default_timezone_set("Asia/Kolkata");
			$created_at = date("Y-m-d H:i:s");

			//DB::insert('insert into referrals (user_id,referral_code,created_at) values(?,?,?)',[$userId,$code,$created_at]);
		$admin = User::select('id')->where('referral_code',$code)->first();
			
			$adminId = isset($admin) ? $admin->id : null;
			
			DB::insert('insert into referrals (user_id,referral_code,admin_id,created_at) values(?,?,?,?)',[$userId,$code,$adminId,$created_at]);

		}
		

		DB::insert('insert into users_pack (users_id,pack) values(?,?)',[$userId,"1"]);

DB::insert('insert into admin_settings (admin_id,send_notification_via_email,send_notification_via_whatsapp) values(?,?,?)',[$userId,1,1]);

	    $cp = new Campaign();
            $cp->user_id        = $userId;
            $cp->campaigns_name = "Demo Campaign";
            $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

            $cp->campaigns_id   = substr(str_shuffle($str_result), 0, 15);
            $cp->status         = 1;
            $cp->save();

		$plan = "5";
		$days = "30 days";
		
		date_default_timezone_set("Asia/Kolkata");
		$startDate = date("Y-m-d");
		
		$endDate = date('Y-m-d', strtotime($startDate. ' + '.$days));
		
		DB::insert('insert into user_plan_details (user_id,user_plans_id,start_date,end_date) values(?,?,?,?)',[$userId,$plan,$startDate,$endDate]);
	

	
		$toemail = $data['email'];
		
		$userData = array(
			"name" => $data['name'],
			"startDate" => date("jS F, Y", strtotime($startDate)),
			"endDate" => date("jS F, Y", strtotime($endDate)),
			"email" => $data['email'],
			"password" => "123456",
			"welcome_mail" => "1"
		);
		
		Mail::send('trial_plan_email_template', $userData, function($message) use ($toemail){
                	$message->to($toemail)->subject('Welcome to RealAuto');
                });
		
		$usersmsapikey = User::where("id","8")->select("whatsapp_api_key")->get()[0];
          // print_r($usersmsapikey);die;
		$api = $usersmsapikey->whatsapp_api_key;
		$mobileno = $data['phone'];
		
/*
$msg = "Hi ".$data['name'].",
Thank you for Applying 3 Days FREE Realauto Trial for your Business. Our Executive will soon connect you with USER ID & PASSWORD.


Thanks & Regards
RealAuto Team";
*/

 $msg = "Hi ".$data['name'].",
Thank you for connection with Realauto for your Business.

Please find below your login details  : 

Login Url : https://realauto.in/login
Email : ".$data['email']."
Password : 123456


Thanks & Regards
RealAuto Team";
		
		//$msg = strip_tags($msg);		
		$attachment = "https://realauto.in/assets/realauto-video/trailfinal.mp4";

		//$attachment ="https://www.dropbox.com/s/r6mezdtt0slklor/sample.mp4?dl=1";
		testsendwhatsappnew($api,$mobileno,$msg,$attachment);

		$this->addLead($data);		
        Session::flash('message', "Registeration Successfull. Your Login details will be send to your whatsapp & email shortly.");
        return redirect('/login');
    }

    public function addLead($data) {
	$leadContact  = $data['phone'];	
	$leadName = $data['name'];
	$leadEmail = $data['email'];
	$count = DB::table('leads')->select('mobile_no')->where('mobile_no',$leadContact)->count();
if (!$count) {
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://realauto.in/client/f7130194-c6d2-40d3-9914-d882ca7735ae/save-lead",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n\"campaign_name\":\"Realauo.in Google LP\",\n\"project_name\":\"Realauto\",\n\"segment_name\":\"GoogleLP\",\n\"name\":\"$leadName\",\n\"Phone\":\"$leadContact\",\n\"Email\":\"$leadEmail\",\n\"source\":\"google\"\n}",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: 64ce95df-e5e0-ec44-5449-00b28a86594d"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
}

    }
}
