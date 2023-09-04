<?php

use App\User;
use App\Models\Campaign;
use App\Models\AutomationSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Pagination\Paginator;
use App\Models\Lead;

if (! function_exists('GetLeadData')) {
    function GetLeadData($leadid) {

        return Lead::where('id',$leadid)->get()[0];	 	

    }
}

if (! function_exists('GetCampaignsName')) {
    function GetCampaignsName($campaingid) {
	 	return Campaign::where('id', $campaingid)->get()[0]->campaigns_name;
    }
}

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

if (! function_exists('Getuserapikey')) {
    function Getuserapikey($userid,$apitype) {

        if($apitype == 1){
            return User::where('id', $userid)->get()[0]->sms_api_key;
        } elseif($apitype == 2){
            return User::where('id', $userid)->get()[0]->email_api_key;
        }else{
            return User::where('id', $userid)->get()[0]->whatsapp_api_key;
        }
    }
}

if (! function_exists('Getusersmsfromname')) {
    function Getusersmsfromname($userid,$apitype) {

        if($apitype == 1){
            return User::where('id', $userid)->get()[0]->sms_from_name;
        } 
    }
}

if (! function_exists('Checkuserexist')) {
    function Checkuserexist($useremail) {
		return  DB::table('users')->select('*')->where('email',$useremail)->count();
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".$api_key."&campaign=0&routeid=58&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text."&template_id=6278");
        $response = curl_exec($ch);
        curl_close($ch);
        //echo $response;

    }
}


if (! function_exists('testsendemailnew')) {
    function testsendemailnew($usersmsapikey,$emaildid,$emailtext) {

        //$api_key = 'E6EC2000FFDD31E4191FCFF581D6AE060E022A7C98790B3731DC87FFC342877C2AEA80D83FDCFD5107EAD6FC18A65115';
        $api_key =  $usersmsapikey;
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
        //echo $response;

    }
}

if (! function_exists('testsendwhatsappnew')) {
    function testsendwhatsappnew($usersmsapikey,$mobileno,$text,$attachment) {

        //$secretKey = '149c8b29185e6cacec185084f924964b5d6715ca8f1422d9fb';
        //$secretKey = Auth::user()->whatsapp_api_key;
        $secretKey = $usersmsapikey;
        $url = "https://app.messageautosender.com/api/v1/message/create";
        $request = array('receiverMobileNo' => $mobileno, 'message'=> $text, 'filePathUrl'=>$attachment);
        //$key = "X-API-Key:149c8b29185e6cacec185084f924964b5d6715ca8f1422d9fb";

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



if (! function_exists('testsendsms')) {
    function testsendsms($mobileno,$smstext) {

        // $api_key = '260A0C06CECD8B';
        // $contacts = '9434534818';

        $api_key = Auth::user()->sms_api_key;
        $contacts = $mobileno;

        $from = Auth::user()->sms_from_name;
        $sms_text = urlencode($smstext);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, "http://webmsg.smsbharti.com/app/smsapi/index.php");
        //curl_setopt($ch,CURLOPT_URL, "http://sms.realauto.in/app/smsapi/index.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".$api_key."&campaign=0&routeid=58&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text."&template_id=6278");
        $response = curl_exec($ch);
        curl_close($ch);
        //echo $response;

    }
}


if (! function_exists('testsendemail')) {
    function testsendemail($emaildid,$emailtext) {

        //$api_key = 'E6EC2000FFDD31E4191FCFF581D6AE060E022A7C98790B3731DC87FFC342877C2AEA80D83FDCFD5107EAD6FC18A65115';
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

    }
}

if (! function_exists('testsendwhatsapp')) {
    function testsendwhatsapp($mobileno,$text,$attachment) {

        //$secretKey = '149c8b29185e6cacec185084f924964b5d6715ca8f1422d9fb';
        $secretKey = Auth::user()->whatsapp_api_key;
        //$secretKey = '38a3658572de5c9ccb5c7f9e6d004aa4bae08d96f6d54ebe52';
        $url = "https://app.messageautosender.com/api/v1/message/create";
        $request = array('receiverMobileNo' => $mobileno, 'message'=> $text,'filePathUrl'=>$attachment);
        //$key = "X-API-Key:149c8b29185e6cacec185084f924964b5d6715ca8f1422d9fb";

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


////////// Whatsapp QR Code Scanner API Details //////////
if (! function_exists('whatsappscanner')) {
    function whatsappscanner() {

        $adminwhatsapp_api_key = DB::select('select whatsapp_api_key from users where name = ?', ['admin']);
        $secretKey = $adminwhatsapp_api_key[0]->whatsapp_api_key;

        $url       = "https://app.messageautosender.com/api/v1/reseller/customer/channel/status";
        $ch        = curl_init( $url );
        $payload   = json_encode( array('customerUsername' => Auth::user()->whatsapp_username, 'channeId' => 7549) );
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
            
            if(!empty($data->result->image)) {
                $imgScanner = $data->result->image;
                return $imgScanner;
            }
        }

    }
}


////////// Whatsapp Credit Counter API Details //////////
if (! function_exists('whatsappcounter')) {
    function whatsappcounter() {

        $adminwhatsapp_api_key = DB::select('select whatsapp_api_key from users where name = ?', ['admin']);
        $secretKey = $adminwhatsapp_api_key[0]->whatsapp_api_key;
        $url       = "https://app.messageautosender.com/api/v1/reseller/customer/detail";
        $ch        = curl_init( $url );
        $payload   = json_encode( array('customerUsername' => Auth::user()->whatsapp_username) );
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
            if($data->status == 400){
                return "Bad Request";
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
}



?>
