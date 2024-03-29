<?php

use App\User;
use App\Models\Campaign;
use App\Models\AutomationSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Pagination\Paginator;


if (! function_exists('GetLeadData')) {
    function GetLeadData($leadid) {
        return DB::table('leads')->select('id','name','mail_id','mobile_no')->where('id', $leadid)->first();
    }
}

if (! function_exists('GetCampaignsName')) {
    function GetCampaignsName($campaingid) {
	 	return Campaign::where('id', $campaingid)->first()->campaigns_name;
    }
}


/* Only For Automation Campaigns Starts Here */
if (! function_exists('Gettotalevents')) {
    function Gettotalevents($campaign_id) {
	 	return AutomationSeries::where('campaign_id', $campaign_id)->count();
    }
}

if (! function_exists('Gettotalemailevents')) {
    function Gettotalemailevents($campaign_id) {
	 	return AutomationSeries::where('campaign_id', $campaign_id)->where('automation_type',2)->count();
    }
}

if (! function_exists('Gettotalwhatsappevents')) {
    function Gettotalwhatsappevents($campaign_id) {
	 	return AutomationSeries::where('campaign_id', $campaign_id)->where('automation_type',3)->count();
    }
}

if (! function_exists('Gettotalsmsevents')) {
    function Gettotalsmsevents($campaign_id) {
	 	return AutomationSeries::where('campaign_id', $campaign_id)->where('automation_type',1)->count();
    }
}
/* Only For Automation Campaigns Ends Here */


/* Only For Bulk SMS Automation Campaigns Ends Here */
if (! function_exists('Gettotalbulksmsevents')) {
    function Gettotalbulksmsevents($sms_campaign_id) {
        return DB::table('sms_automations')->where('campaigns_id', $sms_campaign_id)->count();
    }
}


if (! function_exists('Gettotalbulksmswhatsappevents')) {
    function Gettotalbulksmswhatsappevents($sms_campaign_id) {
        return DB::table('sms_automations')->where('campaigns_id', $sms_campaign_id)->where('automation_type',3)->count();
    }
}
/* Only For Bulk SMS Automation Campaigns Ends Here */


if (! function_exists('Getuserapikey')) {
    function Getuserapikey($userid,$apitype) {

        if($apitype == 1){
            return User::where('id', $userid)->first()->sms_api_key;
        } elseif($apitype == 2){
            return User::where('id', $userid)->first()->email_api_key;
        }else{
            return User::where('id', $userid)->first()->whatsapp_api_key;
        }

    }
}


if (! function_exists('Getusersmsfromname')) {
    function Getusersmsfromname($userid,$apitype) {

        if($apitype == 1){
            return User::where('id', $userid)->first()->sms_from_name;
        }
    }
}


if (! function_exists('Checkuserexist')) {
    function Checkuserexist($useremail) {
		return  DB::table('users')->select('*')->where('email', $useremail)->count();
    }
}


if (! function_exists('testsendsmsnew')) {
    function testsendsmsnew($usersmsapikey,$sms_from_name,$mobileno,$smstext) {

        $api_key = $usersmsapikey;
        $contacts = $mobileno;

        $from = $sms_from_name;
        $sms_text = urlencode($smstext);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, "http://webmsg.smsbharti.com/app/smsapi/index.php");
         //curl_setopt($ch,CURLOPT_URL, "http://sms.realauto.in/app/smsapi/index.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".$api_key."&campaign=0&routeid=58&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text);
        $response = curl_exec($ch);
	
        curl_close($ch);
        //echo $response;

    }
}


if (! function_exists('testsendemailnew')) {
    function testsendemailnew($usersmsapikey,$emaildid,$emailtext) {

        //$api_key = 'E6EC2000FFDD31E4191FCFF581D6AE060E022A7C98790B3731DC87FFC342877C2AEA80D83FDCFD5107EAD6FC18A65115';
        
		/*$api_key =  $usersmsapikey;
        $post = array('from'  => 'mr.ayansaha@gmail.com',
                    'msg_to'  => $emaildid,
                    'subject' => 'Email Testing',
                    'body_text' => $emailtext,
                    'apikey' => $api_key
                );
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, "https://api.elasticemail.com/v2/email/send");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        curl_close($ch);
		*/
        //echo $response;
		//print_r($_POST);
		
		//$toEmail = $_POST['receiversEmail'];
		//$emailtext = $_POST['msg'];
		
		/*
		$post = array('from'  => 'mr.ayansaha@gmail.com',
			'msg_to'  => $emaildid,
			'subject' => 'Email Testing',
			'body_text' => $emailtext,
			'apikey' => $api_key
		);
		*/
		$api_key = Auth::user()->email_api_key;		
		$fromEmail = Auth::user()->email; 
		$fromEmail = "lohanideepak93@gmail.com";
		
		$msgData = explode(":>",$emailtext);
		$subject = $msgData[0];
		$actualMsg = $msgData[1];
		
		$html = view('email-body', ["data"=>"Deepak"])->render();
		
		$post = array(
		    'from'  => $fromEmail,
			'fromName' => "Deepak Lohani",
			'msgTo'  => $emaildid,
			'subject' => $subject,
			'bodyHtml' => $html,
			'apikey' => $api_key
		);
		
		//print_r($post); die;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, "https://api.elasticemail.com/v2/email/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$response = curl_exec($ch);
		$curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
		curl_close($ch);

        

    }
}


if (! function_exists('testsendwhatsappnew')) {
    function testsendwhatsappnew($usersmsapikey,$mobileno,$text,$attachment) {

        //$secretKey = '149c8b29185e6cacec185084f924964b5d6715ca8f1422d9fb';
        //$secretKey = Auth::user()->whatsapp_api_key;
        $secretKey = $usersmsapikey;
        $url = "https://app.messageautosender.com/api/v1/message/create";

	$base_link = "https://realauto.in/";
	$dropbox = "dropbox";
if(strlen(trim($attachment)) > 0) {

	if (!(strpos($attachment,$base_link) !== false) && !(strpos($attachment,$dropbox) !== false)) { //does notcontain
		$attachment = $base_link.$attachment;

	} 	
}

/*
	if(strlen(trim($attachment)) > 0) {

	    if (strpos($attachment, $base_link) != 0 ){
		$attachment = $base_link.$attachment;			
	    } else {

 $attachment = $base_link.$attachment;

}
	} else {
	  $attachment = "";
	}
*/

\Log::info('attachemnt on trial:'.$attachment);

        $request = array('receiverMobileNo' => $mobileno, 'message' => $text, 'filePathUrl' => $attachment);

        $ch = curl_init( $url );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $secretKey));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );

        # Send request.
        $result     = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);
\Log::info('whatsapp cron: ' . $mobileno. ' '.json_encode($result));

        # Print response.
        if ($curl_errno > 0) {
            echo "cURL Error ($curl_errno): $curl_error <br/>";
            return "";
        } else {
            $data = json_decode($result);
            //echo "<pre>"; print_r($data); die;
        }
    }
}


if (! function_exists('testsendsms')) {
    function testsendsms($mobileno,$smstext) {

        // $api_key = '260A0C06CECD8B';
        // $contacts = '9434534818';
	
        $api_key = Auth::user()->sms_api_key;
        $contacts = $mobileno;

        $from = Auth::user()->sms_from_name;
        $sms_text = urlencode($smstext);
        $sms_text = "\n".$sms_text;
		$sms_text = nl2br($sms_text);
		$sms_text = strip_tags($sms_text);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, "http://webmsg.smsbharti.com/app/smsapi/index.php");
        //curl_setopt($ch,CURLOPT_URL, "http://sms.realauto.in/app/smsapi/index.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 
"key=".$api_key."&campaign=0&routeid=58&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text);
        $response = curl_exec($ch);
        curl_close($ch);
        //echo $response;

    }
}


if (! function_exists('testsendemail')) {
    function testsendemail($emaildid,$emailtext) {

        //$api_key = 'E6EC2000FFDD31E4191FCFF581D6AE060E022A7C98790B3731DC87FFC342877C2AEA80D83FDCFD5107EAD6FC18A65115';
        /*
		$api_key =  Auth::user()->email_api_key;
        $post = array('from'  => Auth::user()->email,
                    'msg_to'  => $emaildid,
                    'subject' => 'Email Testing',
                    'body_text' => $emailtext,
                    'apikey' => $api_key
                );
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, "https://api.elasticemail.com/v2/email/send");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        curl_close($ch);
        //echo $response;
		*/
		$api_key = Auth::user()->email_api_key;		
		$fromEmail = Auth::user()->email; 
		//$fromEmail = "lohanideepak93@gmail.com";
		$fromName = Auth::user()->name;
		
		$msgData = explode(":>",$emailtext);
		$subject = isset($msgData[0]) ? $msgData[0] : "";
		$actualMsg = isset($msgData[1]) ? $msgData[1] : "";
		$attachment = isset($msgData[2]) ? $msgData[2] : "";
		$html = view('email-body', ["data"=> $actualMsg])->render();
		
		$post = array(
		    'from'  => $fromEmail,
			'fromName' => $fromName,
			'msgTo'  => $emaildid,
			'subject' => $subject,
			'bodyHtml' => $html,
			'attachments' => $attachment,
			'apikey' => $api_key
		);
		
		//print_r($post); die;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, "https://api.elasticemail.com/v2/email/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$response = curl_exec($ch);
		$curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
		curl_close($ch);

    }
}


if (! function_exists('testsendwhatsapp')) {
    function testsendwhatsapp($mobileno,$text,$attachment) {


        //$secretKey = '149c8b29185e6cacec185084f924964b5d6715ca8f1422d9fb';
        $secretKey = Auth::user()->whatsapp_api_key;
        $url = "https://app.messageautosender.com/api/v1/message/create";
		
	$base_link = "https://realauto.in/";
        $dropbox = "dropbox";
if(strlen(trim($attachment)) > 0) {

        if (!(strpos($attachment,$base_link) !== false) && !(strpos($attachment,$dropbox) !== false)) { //does notcontain
                $attachment = $base_link.$attachment;

        }
}
		
/*
$base_link = "https://realauto.in/";

		if(strlen(trim($attachment)) > 0) {
			if (strpos($attachment, $base_link) !== false){
				
			} else {
				$attachment = $base_link.$attachment;
				//$attachment = $attachment;
			}
		} else {
$attachment = "";
}
*/

\Log::info('whatsapp upload ' . $mobileno. ' '.json_encode($attachment));

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

        # Print response.
        if ($curl_errno > 0) {
            echo "cURL Error ($curl_errno): $curl_error <br/>";
            return "";
        } else {
            $data = json_decode($result);
            //echo "<pre>"; print_r($data); die;
        }

    }
}


////////// Check Campaign Name Exixts (By Subrata Saha) //////////
if (! function_exists('Checkusercampaignname')) {
    function Checkusercampaignname($name) {
        return DB::table('campaigns')->where('campaigns_name', $name)->where('user_id', Auth::user()->id)->first();
    }
}


////////// Check Campaign Name Exixts (By Subrata Saha) //////////
if (! function_exists('Checkusersegmentname')) {
    function Checkusersegmentname($name) {
        return DB::table('segment')->where('segment_name', $name)->where('user_id', Auth::user()->id)->first();
   }
}


////////// Whatsapp SMS Sender API Details For Lead Insert (By Subrata Saha) //////////
if (! function_exists('copytestsendwhatsappnew')) {
    function copytestsendwhatsappnew($mobileno,$text,$attachment) {

        $secretKey = Auth::user()->whatsapp_api_key;
        $url = "https://app.messageautosender.com/api/v1/message/create";
        $request = array('receiverMobileNo' => $mobileno, 'message' => $text, 'filePathUrl' => $attachment);

        $ch = curl_init( $url );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $secretKey));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );

        # Send request.
        $result     = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        # Print response.
        if ($curl_errno > 0) {
            echo "cURL Error ($curl_errno): $curl_error <br/>";
            return "";
        } else {
            $data = json_decode($result);
            //echo "<pre>"; print_r($data); die;
        }
    }
}


////////// Whatsapp SMS Sender API Details For Lead Import (By Subrata Saha) //////////
if (! function_exists('copynewtestsendwhatsapp')) {
    function copynewtestsendwhatsapp($mobileno,$text,$attachment) {

        //$secretKey = '149c8b29185e6cacec185084f924964b5d6715ca8f1422d9fb';
        $secretKey = Auth::user()->whatsapp_api_key;
        $url = "https://app.messageautosender.com/api/v1/message/create";
        $request = array('receiverMobileNo' => $mobileno, 'message' => $text,'filePathUrl' => $attachment);

        $ch = curl_init( $url );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Key: ' . $secretKey));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );

        # Send request.
        $result     = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        # Print response.
        if ($curl_errno > 0) {
           echo "cURL Error ($curl_errno): $curl_error <br/>";
            return "";
        } else {
            $data = json_decode($result);
            //echo "<pre>"; print_r($data); die;
        }

    }
}


////////// Whatsapp QR Code Scanner API Details (By Subrata Saha) //////////
if (! function_exists('whatsappscanner')) {
    function whatsappscanner() {

 if ((null == Auth::user()->whatsapp_username || Auth::user()->whatsapp_username == "")) {
			$img = "https://www.zauca.com/wp-content/uploads/2017/10/planPremium.png";
			return $img;
        }

        $adminwhatsapp_api_key = DB::select('select whatsapp_api_key from users where name = ?', ['admin']);
        $secretKey = $adminwhatsapp_api_key[0]->whatsapp_api_key;
$apiRequest = array(
                        "username" => "AYAN SAHA",
                        "password" => "BANGALORE@2020",
                        "customerUsername" => Auth::user()->whatsapp_username,
                );

        $url       = "https://app.messageautosender.com/api/v1/reseller/customer/channel/status";
        $ch        = curl_init( $url );
        $payload   = json_encode( array('customerUsername' => Auth::user()->whatsapp_username, 'channeId' => 7549) );
        
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


            /*if(!empty($data->result->image)) {
                $imgScanner = $data->result->image;
                return $imgScanner;
            }*/

            if(isset($data->result->image) && !empty($data->result->image)) {
                @$imgScanner = $data->result->image;
                if(!empty($imgScanner)){
                    return $imgScanner;
                }else{
                    return 0;
                }
            }   else {
 		if (	isset($data->result->channelStatus) && $data->result->channelStatus != "IMAGE_VISIBLE") {
                    return "https://i.pinimg.com/originals/d7/34/49/d73449313ecedb997822efecd1ee3eac.gif";
                }
				return "https://realauto.in/assets/images/dummyqr.png";
	    }

        }
    }
}


////////// Whatsapp Credit Counter API Details (By Subrata Saha) //////////
if (! function_exists('whatsappcounter')) {
    function whatsappcounter() {

        $adminwhatsapp_api_key = DB::select('select whatsapp_api_key from users where name = ?', ['admin']);
$apiRequest = array(
			"username" => "AYAN SAHA",
			"password" => "BANGALORE@2020",
			"customerUsername" => Auth::user()->whatsapp_username,
		);
		
		$secretKey = $adminwhatsapp_api_key[0]->whatsapp_api_key;
		$url       = "https://app.messageautosender.com/api/v1/reseller/customer/detail";
        $ch        = curl_init( $url );
        //$payload   = json_encode( array('customerUsername' => Auth::user()->whatsapp_username) );
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
//print_r($data); die;
            if(@$data->status == 400){
                //return "Bad Request";
				return 0;
            }else{
                if(isset($data) && !empty($data)) {
                    @$creditStatus = $data->result->creditPoint;
                    if(!empty($creditStatus)){
                        return $creditStatus;
                    }else{
                        return 0;
                    }
                }
            }
        }

    }
	if (! function_exists('Checkusersegmentname')) {
    function Checkusersegmentname($name) {
        return DB::table('segment')->where('segment_name', $name)->where('user_id', Auth::user()->id)->first();
    }
}

if (! function_exists('Checkuserprojectname')) {
    function Checkuserprojectname($name) {
        return DB::table('project_master')->where('project_name', $name)->where('user_id', Auth::user()->id)->first();
    }
}

}


?>
