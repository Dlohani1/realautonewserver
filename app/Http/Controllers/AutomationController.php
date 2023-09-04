<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use App\Models\Lead;
use App\Models\AutomationSeries;
use App\Models\SmsSeriesmessage;
use App\Models\Campaign;
use App\Models\LeadcornCampaigns;
use App\Models\LeadReports;
use DB;
use Excel;
use App\Imports\LeadsImport;
use App\User;

//use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\ImageManagerStatic as Image;
use ElasticEmailClient\ElasticClient as Client;
use ElasticEmailClient\ApiConfiguration as Configuration;
use DateTime;
use App\Models\LeadDetails;


class AutomationController extends Controller
{

    public function __construct()
    {
       $this->middleware('auth');
    }

    /**
     * Leads Country after post
     *
     * @return true or false
     */
	 public function automation_master(){
      // $data['campaigns'] = Campaign::where('user_id',Auth::user()->id)->get();
		
		$data['campaigns'] =  DB::table('campaigns')
        //->select('Campaigns.*')
		->selectRaw('campaigns.*,count(leads.id) as totalLeads')
        ->leftjoin('leads','leads.campaigns_id','=','campaigns.id')
        ->where('campaigns.user_id',Auth::user()->id)
		->where('campaigns.is_active',1)
		->groupBy('campaigns.id')
		->orderby('id','desc')
        ->get();
		
		return view('automation-master',$data);
	 }

    /**
     * Leads Country after post
     *
     * @return true or false
     */
	 public function sms_automation(Request $request, $campaignsid){
		$smsseries = AutomationSeries::get();
		return view('add-sms-automation');
	 }

    /**
     * Save Automation SMS after post
     *
     * @return true or false
     */
	 public function save_sms_automation(Request $request){

		$this->validate(request(),[
			'series_name'       => 'required',
			'message'           => 'required'

		]);



    	$as = new AutomationSeries();
		$as->user_id         = Auth::user()->id;
		$as->campaign_id     = $request['campaignsid'];
		$as->series_name     = $request['series_name'];
		$as->automation_type = 1;
		$as->save();

        $insertedId = $as->id;

    	$sm = new SmsSeriesmessage();
            $sm->user_id            = Auth::user()->id;
            $sm->series_id          = $insertedId;
            $sm->message            = $request['message'];
            $sm->custom_full_name   = $request['custom_value'];
            $sm->delivery_type      = $request['delivery_type'];
            $sm->delivery_time      = $request['delivery_time'];
            $sm->delivery_day       = $request['delivery_day'];
	    $sm->save();
            $lastsmid = $sm->id;

	if ($request['delivery_type']!== 'initial') { 
                $array1 = array("delivery_date_time",">", date("Y-m-d h:i").":00"); // get the leads from cron table whose event delivery is greater than today
		$array2 = array("user_id","=", Auth::user()->id);
		$array3 = array("campaign_id","=", $request['campaignsid']);
		$array4 = array("is_stopped","=", "0");
		$array5 = array("is_active","=","1");
		$array6 = array("campaigns_id","=", $request['campaignsid']);		

		//$filter = array($array1, $array2, $array3, $array4);
		
		//$leadCron1 = LeadcornCampaigns::where($filter)->select('lead_id','delivery_date_time')->get();
		//$leadCreationDate = Lead::where([['is_active','1'],['campaigns_id',$request['campaignsid']]])->select('created_at')->get()
		
		$leadFilter = array($array2,$array5,$array6);
		
		$activeLeads = Lead::where($leadFilter)->select("id","campaign_activated_on")->get();
		
		if (null !== $activeLeads) {
		//if (null !== $leadCron1) {
			
			foreach($activeLeads as $key => $value) {
				//$leadCreationDate = Lead::where([['is_active','1'],['campaigns_id',$request['campaignsid']]])->select('created_at')->get()[0];
			
				//$msgDeliveryDate = strtotime($value->campaign_activated_on.'+ '.$request['delivery_day'].' days');
				//echo date("Y:m:d h:i:s",$msgDeliveryDate); die;
				
				if ($request['delivery_day'] > 0) {
					$campaignDateTime = explode(" ",$value->campaign_activated_on);
					$campaignDate = $campaignDateTime[0];
					$msgDeliveryDate = 	strtotime($campaignDate." ".$request['delivery_time'].'+ '.$request['delivery_day'].' days');
				//	$msgDeliveryDate = 	strtotime($value->campaign_activated_on.'+ '.$request['delivery_day'].' days');
				} else {
					$campaignDate = explode(" ",$value->campaign_activated_on);
					$msgDate = $campaignDate[1];
					$msgDeliveryDate = 	strtotime($campaignDate[0]." ".$request['delivery_time']);
				}

				if (strtotime("now") < $msgDeliveryDate) { 
					//if (strtotime($value->delivery_date_time) < $msgDeliveryDate) { 
					//echo $msgDeliveryDate; die;
					
					//$newdatetime = new DateTime(date('Y-m-d '.$request['delivery_time']).' + '.$request['delivery_day'].' day');
					$newdatetime = date("Y-m-d H:i:s",$msgDeliveryDate);

					$getleaddata = GetLeadData($value->id);
					$leadcorn = new LeadcornCampaigns();
					$leadcorn->user_id               = Auth::user()->id;
					$leadcorn->campaign_id           = $request['campaignsid'];
					
					$leadcorn->lead_id               = $value->id;
					$leadcorn->automation_messages_id= $lastsmid;

					$leadcorn->name                  = $getleaddata->name;
					$leadcorn->mail_id               = $getleaddata->mail_id;
					$leadcorn->mobile_no             = $getleaddata->mobile_no;

					$leadcorn->automation_type       = 1;

					$leadcorn->delivery_date_time    = $newdatetime;
					$leadcorn->message               = $request['message'];
					//$leadcorn->image                 = $row->image;
					$leadcorn->status                = 2;                
					$leadcorn->save();


					$leadDetail = new LeadDetails();					
					$leadDetail->lead_id               = $value->id;
					$leadDetail->automation_messages_id= $lastsmid;
					$leadDetail->delivery_date_time    = $newdatetime;       
					$leadDetail->save();
				}
				
			}
		}
}

        // CHECK IF CAMPAING ADDED
        //$queryx = LeadcornCampaigns::where('user_id',Auth::user()->id)->where('campaign_id',$request['campaignsid'])->where('automation_messages_id','!=',$lastsmid)->count();
        /*
	$filter = array(
			"user_id" => Auth::user()->id,
			"campaigns_id" => $request['campaignsid']
		);
		//print_r($filter); die;
		$queryx = Lead::where($filter)->count();
		
		if($queryx >0){
              //$queryrow = LeadcornCampaigns::where('user_id',Auth::user()->id)->where('campaign_id',$request['campaignsid'])->where('automation_messages_id','!=',$lastsmid)->groupBy('lead_id')->get();
              $queryrow = Lead::where($filter)->get();
			  
			  foreach($queryrow as $row){


                if($request['delivery_day'] == 0){ //Today
                    $newdatetime = date('Y-m-d '.$request['delivery_time']);
                } else { //After Day
                    $newdatetime = new DateTime(date('Y-m-d '.$request['delivery_time']).' + '.$request['delivery_day'].' day');
                }

                //$getleaddata = GetLeadData($row->lead_id);
				$getleaddata = GetLeadData($row->id);
				
                $leadcorn = new LeadcornCampaigns();
				$leadcorn->user_id               = Auth::user()->id;
				$leadcorn->campaign_id           = $request['campaignsid'];
				//$leadcorn->lead_id               = $row->lead_id;
				$leadcorn->lead_id               = $row->id;
				$leadcorn->automation_messages_id= $lastsmid;

				$leadcorn->name                  = $getleaddata->name;
				$leadcorn->mail_id               = $getleaddata->mail_id;
				$leadcorn->mobile_no             = $getleaddata->mobile_no;

				$leadcorn->automation_type       = 1;

				$leadcorn->delivery_date_time    = $newdatetime;
				$leadcorn->message               = $request['message'];
				//$leadcorn->image                 = $row->image;
				$leadcorn->status                = 2;                
				$leadcorn->save();
				
				$leadReport = new LeadReports();
				$leadReport->user_id               = Auth::user()->id;
				$leadReport->campaign_id           = $request['campaignsid'];
				//$leadcorn->lead_id               = $row->lead_id;
				$leadReport->lead_id               = $row->id;
				$leadReport->automation_messages_id= $lastsmid;

				$leadReport->name                  = $getleaddata->name;
				$leadReport->mail_id               = $getleaddata->mail_id;
				$leadReport->mobile_no             = $getleaddata->mobile_no;

				$leadReport->automation_type       = 1;

				$leadReport->delivery_date_time    = $newdatetime;
				$leadReport->message               = $request['message'];
				//$leadcorn->image                 = $row->image;
				$leadReport->status                = 2;                
				$leadReport->save();
				
              }
			  
        }
*/

		Session::flash('message', "SMS Added successfully.");
		//return redirect('sms-automation/'.$request['campaignsid']);
		return redirect('view-campaigns-automations/'.$request['campaignsid']);
		

	 }
    /**
     * EDIT SMS AUTOMATION
     *
     * @return true or false
     */
     public function edit_sms_automation(Request $request, $smsid){
        //DB::enableQueryLog();
		$data['smsseries'] = DB::table('automations')
        ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
        ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
        ->where('sms_automation_messages.id', $smsid)
        ->get()[0];
		//$query = DB::getQueryLog();
		//echo "<pre>"; print_r($query);die;
		return view('edit-sms-automation',$data);
     }

    /**
     * EDIT SMS AUTOMATION POST
     *
     * @return true or false
     */
     public function edit_sms_automation_post(Request $request, $smsid){
	$data = array(
            'message'                 => $request['message'],
            'custom_full_name'        => $request['custom_full_name'],
            'delivery_type'           => $request['delivery_type'],
            'delivery_time'           => $request['delivery_type']!== 'initial' ? $request['delivery_time'] : null,
            'delivery_day'            => $request['delivery_type']!== 'initial' ? $request['delivery_day'] : null
        );
		
        SmsSeriesmessage::where('id', $smsid)->update($data);
	    
	$items = DB::table('sms_automation_messages')
				->select('series_id','is_active')
				->where('id','=', $smsid)
				->first();

		$seriesId = $items->series_id;
		$activeEvent = $items->is_active;

		$seriesData = array (
			'series_name' => $request['series_name']
		);

		AutomationSeries::where('id', $seriesId)->update($seriesData);
		

                $queryrow = AutomationSeries::where('id', $seriesId)->get();
                
		//dd($queryrow);
                $campaignId = $queryrow[0]->campaign_id;

		if ($request['delivery_type']!== 'initial' ) {

		$array2 = array("user_id","=", Auth::user()->id);
		$array5 = array("is_active","=","1");
		$array6 = array("campaigns_id","=", $campaignId);
        
	
		$leadFilter = array($array2,$array5,$array6);
		
		$activeLeads = Lead::where($leadFilter)->select("id","campaign_activated_on","name","mail_id","mobile_no")->get();


		$leadIds = array();

		$detailData1 = array();		
		// CHECK IF CAMPAING ADDED
       		// $queryx = LeadcornCampaigns::where('user_id',Auth::user()->id)->where('automation_messages_id',$smsid)->count();
        
		//if($queryx >0){

 		if (null !== $activeLeads) {

			$queryrow = DB::table('lead_corn_campaigns')->where('lead_corn_campaigns.user_id',Auth::user()->id)->where('automation_messages_id',$smsid)
			->select('leads.id as leadId','leads.campaign_activated_on','lead_corn_campaigns.id as cronId','sms_automation_messages.is_active as smsActive')
			->join('sms_automation_messages','sms_automation_messages.id','=','lead_corn_campaigns.automation_messages_id')
			->join('leads','leads.id','=','lead_corn_campaigns.lead_id')
			->get();

			foreach($queryrow as $key => $value){
//print_r($value);die;
              /*$queryrow = LeadcornCampaigns::where('user_id',Auth::user()->id)->where('automation_messages_id',$smsid)->get();
              foreach($queryrow as $key => $row){
//print_r($row); die;
					//echo "here"; die;
                if($request['delivery_day'] == 0){ //Today
                    $newdatetime = date('Y-m-d '.$request['delivery_time']);
                }else{ //After Day
                    $newdatetime = new DateTime(date('Y-m-d '.$request['delivery_time']).' + '.$request['delivery_day'].' day');
                }
*/
$a = "";
		if ($request['delivery_day'] > 0) {
/*
$timeData = explode(":",$request['delivery_time']);
					$hours = $timeData[0];
					$mins = $timeData[1];
					$exactTime = $request['delivery_day'].' days '.$hours." hours ".$mins." mins";
	$a = $value->campaign_activated_on;				
					$msgDeliveryDate = 	strtotime($value->campaign_activated_on." +".$exactTime);

*/
$campaignDateTime = explode(" ",$value->campaign_activated_on);
					$campaignDate = $campaignDateTime[0];
					$msgDeliveryDate = 	strtotime($campaignDate." ".$request['delivery_time'].'+ '.$request['delivery_day'].' days');

					//$msgDeliveryDate = 	strtotime($value->campaign_activated_on.'+ '.$request['delivery_day'].' days');
				} else {
					$campaignDate = explode(" ",$value->campaign_activated_on);
					$msgDate = $campaignDate[1];
					$msgDeliveryDate = 	strtotime($campaignDate[0]." ".$request['delivery_time']);
				}
				
				if (strtotime("now") < $msgDeliveryDate) { 
$leadIds[] = $value->leadId;


                $data1 = array(
                    'message'                 => $request['message'],
                    'delivery_date_time'      => date("Y-m-d H:i:s",$msgDeliveryDate)
                );
//dd($value);
if ($value->smsActive == "0") {
//dd("a");
$data1["is_stopped"] = "1";
$data1["stopped_reason"] = "Event is not Active";


$detailData1[$key]['is_cancelled'] = "1";
$detailData1[$key]['failure_reason'] = "Event not Active";

} else {

$data1["is_stopped"] = "0";
$data1["stopped_reason"] = NULL;

}

$data2 = array(
					'is_cancelled' => "1",
					'failure_reason' => "Delivery Date Updated"
				);
				
				$detailData1[$key]['lead_id'] = $value->leadId;
				$detailData1[$key]['automation_messages_id'] = $smsid;
				$detailData1[$key]['delivery_date_time'] = date("Y-m-d H:i:s",$msgDeliveryDate);
				
				if (LeadDetails::where([['lead_id', $value->leadId],['automation_messages_id',$smsid],['is_cancelled',"0"]])->count() > 0) {
					LeadDetails::where([['lead_id', $value->leadId],['automation_messages_id',$smsid],['is_cancelled',"0"]])->update($data2);
				}
//if ($row->

 LeadcornCampaigns::where('id', $value->cronId)->update($data1);

} else {
		if ( LeadcornCampaigns::where('id', $value->cronId)->count() > 0) {
$updateData = array(
"is_stopped" => "1",
"stopped_reason" => "Event Expired"
);

//LeadcornCampaigns::where('id', $value->cronId)->update($updateData);
                                        LeadcornCampaigns::where('id', $value->cronId)->delete();

				}

if (LeadDetails::where([['lead_id', $value->leadId],['automation_messages_id',$smsid],['is_cancelled',"0"]])->count() > 0) {
						
						$data2 = array(
							'is_cancelled' => "1",
							'failure_reason' => "Delivery Date set to passed date"
						);
						
						
						LeadDetails::where([['lead_id', $value->leadId],['automation_messages_id',$smsid],['is_cancelled',"0"]])->update($data2);
					}
}			    
//LeadReports::where('automation_messages_id', $smsid)->update($data1);
              }
        }



if (count($detailData1) > 0) {
			LeadDetails::insert($detailData1);
		}

//dd($leadFilter);
$leadIdsNotInCron = array();
		
		foreach($activeLeads as $key => $leadsValue) {
			if(!in_array($leadsValue->id,$leadIds)) {
				$leadIdsNotInCron[$key]['id'] = $leadsValue->id;
				$leadIdsNotInCron[$key]['name'] = $leadsValue->name;
$leadIdsNotInCron[$key]['mobile_no'] = $leadsValue->mobile_no;
$leadIdsNotInCron[$key]['mail_id'] = $leadsValue->mail_id;

                                $leadIdsNotInCron[$key]['campaign_activated_on'] = $leadsValue->campaign_activated_on;
			}
		}
		//dd($leadIdsNotInCron);
		$queryrow = AutomationSeries::where('id', $seriesId)->get(); 
		//dd($queryrow);
		$campaignId = $queryrow[0]->campaign_id;
		
		if (count($leadIdsNotInCron) > 0) {
			$data = array();
			$detailData = array();
			foreach($leadIdsNotInCron as $key => $value) {
				if ($request['delivery_day'] > 0) {

					$campaignDateTime = explode(" ",$value['campaign_activated_on']);
                                        $campaignDate = $campaignDateTime[0];
                                        $msgDeliveryDate =      strtotime($campaignDate." ".$request['delivery_time'].'+ '.$request['delivery_day'].' days');

                                        //$msgDeliveryDate =    strtotime($value->campaign_activated_on.'+ '.$request['delivery_day'].' days');
                                } else {
                                        $campaignDate = explode(" ",$value['campaign_activated_on']);
                                        $msgDate = $campaignDate[1];
                                        $msgDeliveryDate =      strtotime($campaignDate[0]." ".$request['delivery_time']);
                                }

                                if (strtotime("now") < $msgDeliveryDate) {

				$data[$key]['user_id'] =  Auth::user()->id;
				$data[$key]['lead_id'] = $value['id'];
				$data[$key]['campaign_id'] = $campaignId ;
				$data[$key]['automation_messages_id'] = $smsid;
				$data[$key]['name'] = $value['name'];
				$data[$key]['mobile_no'] = $value['mobile_no'];
				$data[$key]['mail_id'] = $value['mail_id'];

				$data[$key]['automation_type'] = 1;
				$data[$key]['delivery_date_time'] = date("Y-m-d H:i:s",$msgDeliveryDate);
				$data[$key]['message'] = $request['message'];
				$data[$key]['status'] = 2;

if ($activeEvent == "0") {

$data[$key]['is_stopped'] = "1";
$data[$key]['stopped_reason'] = "Event Off ";
$detailData[$key]['is_cancelled'] = "1";
$detailData[$key]['failure_reason'] = "Event Off";


}
                                $detailData[$key]['lead_id'] = $value['id'];
				$detailData[$key]['automation_messages_id'] = $smsid;
				$detailData[$key]['delivery_date_time'] = date("Y-m-d H:i:s",$msgDeliveryDate);
				

				} else {
				
				if (LeadDetails::where([['lead_id',  $value['id']],['automation_messages_id',$smsid],['is_cancelled',"0"]])->count() > 0) {
				
					$data2 = array(
						'is_cancelled' => "1",
						'failure_reason' => "Delivery Date updated"
					);
					
					LeadDetails::where([['lead_id', $value['id']],['automation_messages_id',$smsid],['is_cancelled',"0"]])->update($data2);
				}
				$detailData[$key]['lead_id'] = $value['id'];
				$detailData[$key]['automation_messages_id'] = $smsid;
				$detailData[$key]['delivery_date_time'] = date("Y-m-d H:i:s",$msgDeliveryDate);
				$detailData[$key]['is_cancelled'] = "1";
				$detailData[$key]['failure_reason'] = "Delivery Date set to passed date";

			}
			}
//dd($data); die;
		
			LeadcornCampaigns::insert($data);
			LeadDetails::insert($detailData);
		}

}


		Session::flash('message', "SMS Updated successfully.");
		//return redirect('edit-sms-automation/'.$request['smsid']);
		
		//$queryrow = AutomationSeries::where('id', $smsid)->get(); 
		//dd($queryrow);
		//$campaignId = $queryrow[0]->campaign_id;

		return redirect('view-campaigns-automations/'.$campaignId);
     }



     public function email_automation(Request $request, $campaignsid){
        return view('add-email-automation');
     }

    /**
     * Save Email Automation
     *
     * @return true or false
     */
    public function save_email_automation(Request $request){

		$this->validate(request(),[
			'series_name'       => 'required',
			//'delivery_day'      => 'required',
			//'delivery_time'     => 'required',
			'message'           => 'required',
            'image1'            => 'max:1000000000000000|image|mimes:jpeg,png,jpg,gif',

		]);
		
		$attachmentId = "";

		// FOR IMAGE
		if($request->hasFile('image1')){

			$files1 = $request->file('image1');
			$filename1 = md5($files1->getClientOriginalName() .rand().time()).'.'.$files1->extension();
			$destinationPath = public_path('/uploads/automation');
			$thumb_img = Image::make($files1->getRealPath())->resize(200, null, function ($constraint) {
				$constraint->aspectRatio();
			});

			$thumb_img->save($destinationPath.'/'.$filename1,80);
				
			$api_key = Auth::user()->email_api_key;	
			
			if (null !== $api_key) {
				$username = Auth::user()->name;
				$filepath = $destinationPath.'/'.$filename1;
				$data = http_build_query(array('username' => $username,'api_key' => $apikey,'file' => $filename1));
				$file = file_get_contents($filepath);
				$result = ''; 

				$fp = fsockopen('ssl://api.elasticemail.com', 443, $errno, $errstr, 30); 

				if ($fp){
					fputs($fp, "PUT /attachments/upload?".$data." HTTP/1.1\r\n");
					fputs($fp, "Host: api.elasticemail.com\r\n");
					fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
					fputs($fp, "Content-length: ". strlen($file) ."\r\n");
					fputs($fp, "Connection: close\r\n\r\n");
					fputs($fp, $file);
					while(!feof($fp)) {
						$result .= fgets($fp, 128);
					}
				} else { 
					$result =  array(
						'status'=>false,
						'error'=>$errstr.'('.$errno.')',
						'result'=>$result
					);
				}
				fclose($fp);
				$result = explode("\r\n\r\n", $result, 2); 

				$attachmentId = $result[1];
			}
		}else{
			$filename1 ='';
		}


    	$as = new AutomationSeries();
		$as->user_id         = Auth::user()->id;
		$as->campaign_id     = $request['campaignsid'];
		$as->series_name     = $request['series_name'];
		$as->automation_type = 2;
		$as->save();

        $insertedId = $as->id;

		$sm = new SmsSeriesmessage();
		$sm->user_id             = Auth::user()->id;
		$sm->series_id           = $insertedId;
		$sm->message             = $request['subject'].":>".$request['message'].":>".$attachmentId;
		$sm->delivery_type       = $request['delivery_type'];
		$sm->delivery_time       = $request['delivery_time'];
		$sm->delivery_day        = $request['delivery_day'];
		$sm->custom_full_name    = $request['custom_full_name'];
		$sm->image               = $filename1;
		$sm->save();
		$lastsmid = $sm->id;

        // CHECK IF CAMPAING ADDED
        $queryx = LeadcornCampaigns::where('user_id',Auth::user()->id)->where('campaign_id',$request['campaignsid'])->count();
        if($queryx >0){
              $queryrow = LeadcornCampaigns::where('user_id',Auth::user()->id)->where('campaign_id',$request['campaignsid'])->where('automation_messages_id','!=',$lastsmid)->groupBy('lead_id')->get();
              foreach($queryrow as $row){


                if($request['delivery_day'] == 0){ //Today
                    $newdatetime = date('Y-m-d '.$request['delivery_time']);
                }else{ //After Day
                    $newdatetime = new DateTime(date('Y-m-d '.$request['delivery_time']).' + '.$request['delivery_day'].' day');
                }

                $getleaddata = GetLeadData($row->lead_id);

                $leadcorn = new LeadcornCampaigns();
				$leadcorn->user_id               = Auth::user()->id;
				$leadcorn->campaign_id           = $request['campaignsid'];
				$leadcorn->lead_id               = $row->lead_id;
				$leadcorn->automation_messages_id= $lastsmid;

				$leadcorn->name                  = $getleaddata->name;
				$leadcorn->mail_id               = $getleaddata->mail_id;
				$leadcorn->mobile_no             = $getleaddata->mobile_no;

				$leadcorn->automation_type       = 2;

				$leadcorn->delivery_date_time    = $newdatetime;
				$leadcorn->message               = $request['message'];
				//$leadcorn->image                 = $row->image;
				$leadcorn->status                = 2;                
                $leadcorn->save();
				
				$leadReport = new LeadReports();
				$leadReport->user_id               = Auth::user()->id;
				$leadReport->campaign_id           = $request['campaignsid'];
				$leadcorn->lead_id               = $row->lead_id;
				//$leadReport->lead_id               = $row->id;
				$leadReport->automation_messages_id= $lastsmid;
				$leadReport->name                  = $getleaddata->name;
				$leadReport->mail_id               = $getleaddata->mail_id;
				$leadReport->mobile_no             = $getleaddata->mobile_no;
				$leadReport->automation_type       = 3;
				$leadReport->delivery_date_time    = $newdatetime;
				$leadReport->message               = $request['message'];
				$leadReport->image                 = $request['image_link'];
				$leadReport->status                = 2;                
				$leadReport->save();
              }
        }

		Session::flash('message', "Email Added successfully.");
		return redirect('view-campaigns-automations/'.$request['campaignsid']);
		//return redirect('email-automation/'.$request['campaignsid']);

	 }

    /**
     * EDIT email AUTOMATION
     *
     * @return true or false
     */
    public function edit_email_automation(Request $request, $smsid){
        //DB::enableQueryLog();
		$data['smsseries'] = DB::table('automations')
        ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
        ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
        ->where('sms_automation_messages.id', $smsid)
        ->get()[0];
		//$query = DB::getQueryLog();
		//echo "<pre>"; print_r($query);die;
		return view('edit-email-automation',$data);
     }


    /**
     * Save Email Automation
     *
     * @return true or false
     */
    public function edit_email_automation_post(Request $request,$smsid){

		$this->validate(request(),[
			'series_name'       => 'required',
			//'delivery_day'      => 'required',
			//'delivery_time'     => 'required',
			'message'           => 'required',
            'image1'            => 'max:1000000000000000|image|mimes:jpeg,png,jpg,gif',

		]);




		//ini_set('memory_limit','256M');
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');

		$attachmentId = $request->oldAttachmentId != 0 ? $request->oldAttachmentId : "";
	
		// image1
		if($request->hasFile('image1')){

			$path1 = public_path()."/uploads/automation/".$request['image1_text'];
			@unlink($path1);


			$files1 = $request->file('image1');
			$filename1 = md5($files1->getClientOriginalName() .rand().time()).'.'.$files1->extension();

			$destinationPath = public_path('/uploads/automation');
			$thumb_img = Image::make($files1->getRealPath())->resize(200, null, function ($constraint) {
				$constraint->aspectRatio();
			});
			$thumb_img->save($destinationPath.'/'.$filename1,80);
						$apikey = Auth::user()->email_api_key;	
			
			$username = Auth::user()->name;
			
			$api_key = Auth::user()->email_api_key;	
			
			if (null !== $api_key) {
				$filepath = $destinationPath.'/'.$filename1;
				$data = http_build_query(array('username' => $username,'api_key' => $apikey,'file' => $filename1));
				$file = file_get_contents($filepath);
				$result = ''; 

				$fp = fsockopen('ssl://api.elasticemail.com', 443, $errno, $errstr, 30); 

				if ($fp){
					fputs($fp, "PUT /attachments/upload?".$data." HTTP/1.1\r\n");
					fputs($fp, "Host: api.elasticemail.com\r\n");
					fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
					fputs($fp, "Content-length: ". strlen($file) ."\r\n");
					fputs($fp, "Connection: close\r\n\r\n");
					fputs($fp, $file);
					while(!feof($fp)) {
						$result .= fgets($fp, 128);
					}
				} else { 
					$result =  array(
						'status'=>false,
						'error'=>$errstr.'('.$errno.')',
						'result'=>$result
					);
				}
				fclose($fp);
				$result = explode("\r\n\r\n", $result, 2); 

				$attachmentId = $result[1];
			}
				
		}else{
			if($request['image1_text'] != ""){
                $filename1 = $request['image1_text'];
            }else{
                $filename1 = '';
            }
		}
		

		$data = array(
            'message'                 => $request['subject'].":>".$request['message'].":>".$attachmentId,
            'custom_full_name'        => $request['txt_full_name'],
            'image'                   => $filename1,
            'delivery_type'           => $request['delivery_type'],
            'delivery_time'           => $request['delivery_time'],
            'delivery_day'            => $request['delivery_day']
        );
        SmsSeriesmessage::where('id', $smsid)->update($data);

        // CHECK IF CAMPAING ADDED
        $queryx = LeadcornCampaigns::where('user_id',Auth::user()->id)->where('automation_messages_id',$smsid)->count();
        if($queryx >0){
              $queryrow = LeadcornCampaigns::where('user_id',Auth::user()->id)->where('automation_messages_id',$smsid)->get();
              foreach($queryrow as $row){

                if($request['delivery_day'] == 0){ //Today
                    $newdatetime = date('Y-m-d '.$request['delivery_time']);
                }else{ //After Day
                    $newdatetime = new DateTime(date('Y-m-d '.$request['delivery_time']).' + '.$request['delivery_day'].' day');
                }

                $data1 = array(
                    'message'                 => $request['message'],
                    'delivery_date_time'      => $newdatetime
                );
                LeadcornCampaigns::where('automation_messages_id', $smsid)->update($data1); 
				LeadReports::where('automation_messages_id', $smsid)->update($data1);
              }
        }


		Session::flash('message', "Email Automation Updated successfully.");
		$queryrow = AutomationSeries::where('id', $smsid)->get(); 
		//dd($queryrow);
		$campaignId = $queryrow[0]->campaign_id;

		return redirect('view-campaigns-automations/'.$campaignId);
	
		//return redirect('edit-email-automation/'.$smsid);

	 }



     public function whatsapp_automation(Request $request, $campaignsid){
        return view('add-whatsapp-automation');
    }

    /**
     * Save Email Automation
     *
     * @return true or false
     */
    public function save_whatsapp_automation(Request $request){

		$this->validate(request(),[
			'series_name'       => 'required',
			//'delivery_day'      => 'required',
			//'delivery_time'     => 'required',
			'message'           => 'required',
            //'image1'            => 'max:1000000000000000|image|mimes:jpeg,png,jpg,gif',

		]);

		// FOR IMAGE
		// if($request->hasFile('image1')){

		// 	$files1 = $request->file('image1');
		// 	$filename1 = md5($files1->getClientOriginalName() .rand().time()).'.'.$files1->extension();
		// 	$destinationPath = public_path('/uploads/automation');
		// 	$thumb_img = Image::make($files1->getRealPath())->resize(200, null, function ($constraint) {
		// 		$constraint->aspectRatio();
		// 	});

		// 	$thumb_img->save($destinationPath.'/'.$filename1,80);

		// }else{
		// 	$filename1 ='';
		// }


    	$as = new AutomationSeries();
            $as->user_id         = Auth::user()->id;
            $as->campaign_id     = $request['campaignsid'];
            $as->series_name     = $request['series_name'];
            $as->automation_type = 3;
		$as->save();

        $insertedId = $as->id;
/*
$finalUrl = "";
if (Auth::user()->id == "8" && strlen($request['image_link']) > 0) { 
//print_r($_POST); die;
$parameters = array('path' => "/".$request['dropbox_link']);
$headers = array('Authorization: Bearer aYjbweMcf_sAAAAAAAAAAYzNYgkvbetODenA80qKcRqKePzntXWhWmfmnPk1ecrJ',	

//$headers = array('Authorization: Bearer I2IBsf7CWR4AAAAAAAAAAfZdQlXSy3_WauExF4glQ6mbo985uFMfGm3FW1jiat_F',
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
//die;
$url = explode("?", $test->url);
$finalUrl = $url[0]."?dl=1";
}
*/
    	$sm = new SmsSeriesmessage();
            $sm->user_id             = Auth::user()->id;
            $sm->series_id           = $insertedId;
            $sm->message             = $request['message'];
            $sm->delivery_type       = $request['delivery_type'];
            $sm->delivery_time       = $request['delivery_time'];
            $sm->delivery_day        = $request['delivery_day'];
            $sm->custom_full_name    = $request['custom_full_name'];
	    $sm->image               = $request['image_link'];

	 /*
   if (Auth::user()->id == "8") {
	     $sm->image = $finalUrl;
	}
*/
			$sm->save();

        $lastsmid = $sm->id;
if ($request['delivery_type']!== 'initial' ) {
		$array1 = array("delivery_date_time",">", date("Y-m-d h:i").":00"); // get the leads from cron table whose event delivery is greater than today
		$array2 = array("user_id","=", Auth::user()->id);
		$array3 = array("campaign_id","=", $request['campaignsid']);
		$array4 = array("is_stopped","=", "0");
		
		$array5 = array("is_active","=","1");
		$array6 = array("campaigns_id","=", $request['campaignsid']);

		//$filter = array($array1, $array2, $array3, $array4);
		
		//$leadCron1 = LeadcornCampaigns::where($filter)->select('lead_id','delivery_date_time')->get();
		
		$leadFilter = array($array2,$array5,$array6);

		$activeLeads = Lead::where($leadFilter)->select("id","campaign_activated_on")->get();
		
		//if (null !== $leadCron1) {
		if (null !== $activeLeads) {	
			
			foreach($activeLeads as $key => $value) {
				//$leadCreationDate = Lead::where([['is_active','1'],['campaigns_id',$request['campaignsid']]])->select('created_at')->get()[0];
			
				//$msgDeliveryDate = 	strtotime($leadCreationDate->created_at.'+ '.$request['delivery_day'].' days');
				//echo date("Y:m:d h:i:s",$msgDeliveryDate); die;
				

				if ($request['delivery_day'] > 0) {
$campaignDateTime = explode(" ",$value->campaign_activated_on);
					$campaignDate = $campaignDateTime[0];
					$msgDeliveryDate = 	strtotime($campaignDate." ".$request['delivery_time'].'+ '.$request['delivery_day'].' days');
					//$msgDeliveryDate = 	strtotime($value->campaign_activated_on.'+ '.$request['delivery_day'].' days');
				} else {
					$campaignDate = explode(" ",$value->campaign_activated_on);
					$msgDate = $campaignDate[1];
					$msgDeliveryDate = 	strtotime($campaignDate[0]." ".$request['delivery_time']);
				}

				if (strtotime("now") < $msgDeliveryDate) { 
				//if (strtotime($value->delivery_date_time) < $msgDeliveryDate) { 
				//echo $msgDeliveryDate; die;
					//$newdatetime = new DateTime(date('Y-m-d '.$request['delivery_time']).' + '.$request['delivery_day'].' day');
					$newdatetime = date("Y-m-d H:i:s",$msgDeliveryDate);
					$getleaddata = GetLeadData($value->id);
					$leadcorn = new LeadcornCampaigns();
					$leadcorn->user_id               = Auth::user()->id;
					$leadcorn->campaign_id           = $request['campaignsid'];
					
					$leadcorn->lead_id               = $value->id;
					$leadcorn->automation_messages_id= $lastsmid;

					$leadcorn->name                  = $getleaddata->name;
					$leadcorn->mail_id               = $getleaddata->mail_id;
					$leadcorn->mobile_no             = $getleaddata->mobile_no;

					$leadcorn->automation_type       = 3;

					$leadcorn->delivery_date_time    = $newdatetime;
					$leadcorn->message               = $request['message'];
					$leadcorn->image                 = $request['image_link'];
					$leadcorn->status                = 2;                
					$leadcorn->save();

                                        $leadDetail = new LeadDetails();
                                        $leadDetail->lead_id               = $value->id;
                                        $leadDetail->automation_messages_id= $lastsmid;
                                        $leadDetail->delivery_date_time    = $newdatetime;
                                        $leadDetail->save();
					
				}
				
			}
		}
}

        // CHECK IF CAMPAING ADDED
        //$queryx = LeadcornCampaigns::where('user_id',Auth::user()->id)->where('campaign_id',$request['campaignsid'])->count();
       
	   //if($queryx >0){
         //     $queryrow = LeadcornCampaigns::where('user_id',Auth::user()->id)->where('campaign_id',$request['campaignsid'])->where('automation_messages_id','!=',$lastsmid)->groupBy('lead_id')->get();
       
	/*
	        $filter = array(
			"user_id" => Auth::user()->id,
			"campaigns_id" => $request['campaignsid']
		);
		//print_r($filter); die;
		$queryx = Lead::where($filter)->count();
		
		if($queryx >0){
              //$queryrow = LeadcornCampaigns::where('user_id',Auth::user()->id)->where('campaign_id',$request['campaignsid'])->where('automation_messages_id','!=',$lastsmid)->groupBy('lead_id')->get();
              $queryrow = Lead::where($filter)->get();
			  foreach($queryrow as $row){


                if($request['delivery_day'] == 0){ //Today
                    $newdatetime = date('Y-m-d '.$request['delivery_time']);
                }else{ //After Day
                    $newdatetime = new DateTime(date('Y-m-d '.$request['delivery_time']).' + '.$request['delivery_day'].' day');
                }

                //$getleaddata = GetLeadData($row->lead_id);
				$getleaddata = GetLeadData($row->id);

				$leadcorn = new LeadcornCampaigns();
				$leadcorn->user_id               = Auth::user()->id;
				$leadcorn->campaign_id           = $request['campaignsid'];
				//$leadcorn->lead_id               = $row->lead_id;
				$leadcorn->lead_id               = $row->id;
				$leadcorn->automation_messages_id= $lastsmid;

				$leadcorn->name                  = $getleaddata->name;
				$leadcorn->mail_id               = $getleaddata->mail_id;
				$leadcorn->mobile_no             = $getleaddata->mobile_no;

				$leadcorn->automation_type       = 3;

				$leadcorn->delivery_date_time    = $newdatetime;
				$leadcorn->message               = $request['message'];
				$leadcorn->image                 = $request['image_link'];
				$leadcorn->status                = 2;                
				$leadcorn->save();
				
				$leadReport = new LeadReports();
				$leadReport->user_id               = Auth::user()->id;
				$leadReport->campaign_id           = $request['campaignsid'];
				//$leadcorn->lead_id               = $row->lead_id;
				$leadReport->lead_id               = $row->id;
				$leadReport->automation_messages_id= $lastsmid;
				$leadReport->name                  = $getleaddata->name;
				$leadReport->mail_id               = $getleaddata->mail_id;
				$leadReport->mobile_no             = $getleaddata->mobile_no;
				$leadReport->automation_type       = 3;
				$leadReport->delivery_date_time    = $newdatetime;
				$leadReport->message               = $request['message'];
				$leadReport->image                 = $request['image_link'];
				$leadReport->status                = 2;                
				$leadReport->save();
				
              }
        }
*/




		Session::flash('message', "Whatsapp Added successfully.");
		//$queryrow = AutomationSeries::where('id', $smsid)->get(); 
		//dd($queryrow);
		//$campaignId = $queryrow[0]->campaign_id;

		return redirect('view-campaigns-automations/'.$request['campaignsid']);
		//return redirect('whatsapp-automation/'.$request['campaignsid']);

	 }




    /**
     * EDIT SMS AUTOMATION
     *
     * @return true or false
     */
    public function edit_whatsapp_automation(Request $request, $smsid){
	
      // DB::enableQueryLog();
		$data['smsseries'] = DB::table('automations')
        ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
        ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
        ->where('sms_automation_messages.id', $smsid)
        ->get()[0];
		//$query = DB::getQueryLog();
		//echo "<pre>"; print_r($query);die;
		return view('edit-whatsapp-automation',$data);
     }


    /**
     * Save Email Automation
     *
     * @return true or false
     */
    public function edit_whatsapp_automation_post(Request $request,$smsid){

		$this->validate(request(),[
			'series_name'       => 'required',
			//'delivery_day'      => 'required',
			//'delivery_time'     => 'required',
			'message'           => 'required',
            //'image1'            => 'max:1000000000000000|image|mimes:jpeg,png,jpg,gif',

		]);





		// ini_set('max_execution_time', 0);
		// ini_set('memory_limit', '-1');


		// // image1
		// if($request->hasFile('image1')){

		// 	$path1 = public_path()."/uploads/automation/".$request['image1_text'];
		// 	@unlink($path1);


		// 	$files1 = $request->file('image1');
		// 	$filename1 = md5($files1->getClientOriginalName() .rand().time()).'.'.$files1->extension();

		// 	$destinationPath = public_path('/uploads/automation');
		// 	$thumb_img = Image::make($files1->getRealPath())->resize(200, null, function ($constraint) {
		// 		$constraint->aspectRatio();
		// 	});
		// 	$thumb_img->save($destinationPath.'/'.$filename1,80);

		// }else{
		// 	if($request['image1_text'] != ""){
        //         $filename1 = $request['image1_text'];
        //     }else{
        //         $filename1 = '';
        //     }

		// }
		
		
		$data = array(
            'message'                 => $request['message'],
            'custom_full_name'        => $request['txt_full_name'],
            'image'                   => $request['image_link'],
            'delivery_type'           => $request['delivery_type'],
	    'delivery_time'           => $request['delivery_type']!== 'initial' ? $request['delivery_time'] : null,
            'delivery_day'            => $request['delivery_type']!== 'initial' ? $request['delivery_day'] : null
        );

        SmsSeriesmessage::where('id', $smsid)->update($data);

	
		//$seriesId = SmsSeriesmessage::find($smsid)->value('series_id');
	//dd($seriesId);
	
		$items = DB::table('sms_automation_messages')
				->select('series_id','is_active')
				->where('id','=', $smsid)
				->first();

		$activeEvent = $items->is_active;
		$seriesId = $items->series_id;

		$seriesData = array (
			'series_name' => $request['series_name']
		);

		AutomationSeries::where('id', $seriesId)->update($seriesData);

	$queryrow = AutomationSeries::where('id', $seriesId)->get();
                //dd($queryrow);
                $campaignId = $queryrow[0]->campaign_id;
	

	if ($request['delivery_type']!== 'initial') {	
		$array2 = array("user_id","=", Auth::user()->id);
		$array5 = array("is_active","=","1");
		$array6 = array("campaigns_id","=", $campaignId);
		
		$leadFilter = array($array2,$array5,$array6);
		
		$activeLeads = Lead::where($leadFilter)->select("id","campaign_activated_on","name","mail_id","mobile_no")->get();
		
                $leadIds = array();
		$detailData1 = array();

		
		
		if (null !== $activeLeads) {

        // CHECK IF CAMPAING ADDED
       // $queryx = LeadcornCampaigns::where('user_id',Auth::user()->id)->where('automation_messages_id',$smsid)->count();
        //if($queryx >0){
          //    $queryrow = LeadcornCampaigns::where('user_id',Auth::user()->id)->where('automation_messages_id',$smsid)->get();
            //  foreach($queryrow as $row){
		/*

                if($request['delivery_day'] == 0){ //Today
                    $newdatetime = date('Y-m-d '.$request['delivery_time']);
                }else{ //After Day
                    $newdatetime = new DateTime(date('Y-m-d '.$request['delivery_time']).' + '.$request['delivery_day'].' day');
                }
*/
$queryrow = DB::table('lead_corn_campaigns')->where('lead_corn_campaigns.user_id',Auth::user()->id)->where('automation_messages_id',$smsid)
			->select('leads.id as leadId','leads.campaign_activated_on','lead_corn_campaigns.id as cronId','sms_automation_messages.is_active as smsActive')
			->join('sms_automation_messages','sms_automation_messages.id','=','lead_corn_campaigns.automation_messages_id')	
			->join('leads','leads.id','=','lead_corn_campaigns.lead_id')
			->get();

			foreach($queryrow as $key => $value){
    				
			    if ($request['delivery_day'] > 0) {

					$campaignDateTime = explode(" ",$value->campaign_activated_on);
                                        $campaignDate = $campaignDateTime[0];
                                        $msgDeliveryDate =      strtotime($campaignDate." ".$request['delivery_time'].'+ '.$request['delivery_day'].' days');

                                        //$msgDeliveryDate =    strtotime($value->campaign_activated_on.'+ '.$request['delivery_day'].' days');
                                } else {
                                        $campaignDate = explode(" ",$value->campaign_activated_on);
                                        $msgDate = $campaignDate[1];
                                        $msgDeliveryDate =      strtotime($campaignDate[0]." ".$request['delivery_time']);
                                }
  

				
				if (strtotime("now") < $msgDeliveryDate) { 


                $leadIds[]=$value->leadId;

                $data1 = array(
                    'message'                 => $request['message'],
                    'image'                   => $request['image_link'],
                    'delivery_date_time'      => date("Y-m-d H:i:s",$msgDeliveryDate)
                );

if ($value->smsActive == "0") {
//dd("a");
$data1["is_stopped"] = "1";
$data1["stopped_reason"] = "Event is not Active";

$detailData1[$key]["is_cancelled"] = "1";
$detailData1[$key]["failure_reason"] = "Event is not Active";

} else {

$data1["is_stopped"] = "0";
$data1["stopped_reason"] = NULL;
$detailData1[$key]["is_cancelled"] = "0";
$detailData1[$key]["failure_reason"] = NULL;


}

$data2 = array(
					'is_cancelled' => "1",
					'failure_reason' => "Delivery Date Updated"
				);
				
				$detailData1[$key]['lead_id'] = $value->leadId;
				$detailData1[$key]['automation_messages_id'] = $smsid;
				$detailData1[$key]['delivery_date_time'] = date("Y-m-d H:i:s",$msgDeliveryDate);
				
				if (LeadDetails::where([['lead_id', $value->leadId],['automation_messages_id',$smsid],['is_cancelled',"0"]])->count() > 0) {
					LeadDetails::where([['lead_id', $value->leadId],['automation_messages_id',$smsid],['is_cancelled',"0"]])->update($data2);
				}

                LeadcornCampaigns::where('id', $value->cronId)->update($data1);  
}  else {
if ( LeadcornCampaigns::where('id', $value->cronId)->count() > 0) {

$updateData = array(
"is_stopped" => "1",
"stopped_reason" => "Event Expired"
);
LeadcornCampaigns::where('id', $value->cronId)->delete();

if (LeadDetails::where([['lead_id', $value->leadId],['automation_messages_id',$smsid],['is_cancelled',"0"]])->count() > 0) {
						
						$data2 = array(
							'is_cancelled' => "1",
							'failure_reason' => "Delivery Date set to passed date"
						);
						
						
						LeadDetails::where([['lead_id', $value->leadId],['automation_messages_id',$smsid],['is_cancelled' ,"0"]])->update($data2);
					}
//LeadcornCampaigns::where('id', $value->cronId)->update($updateData);
					//LeadcornCampaigns::where('id', $value->cronId)->delete();
}				}
				//LeadReports::where('automation_messages_id', $smsid)->update($data1);


              }
        }


if (count($detailData1) > 0) {
			LeadDetails::insert($detailData1);
		}



$leadIdsNotInCron = array();

                foreach($activeLeads as $key => $leadsValue) {
                        if(!in_array($leadsValue->id,$leadIds)) {
                                $leadIdsNotInCron[$key]['id'] = $leadsValue->id;
                                $leadIdsNotInCron[$key]['name'] = $leadsValue->name;

$leadIdsNotInCron[$key]['mail_id'] = $leadsValue->mail_id;

$leadIdsNotInCron[$key]['mobile_no'] = $leadsValue->mobile_no;

                                $leadIdsNotInCron[$key]['campaign_activated_on'] = $leadsValue->campaign_activated_on;
                        }
                }
                //dd($leadIdsNotInCron);
                //$queryrow = AutomationSeries::where('id', $seriesId)->get();
                
                //$campaignId = $queryrow[0]->campaign_id;

                if (count($leadIdsNotInCron) > 0) {
                        $data = array();
			$detailData = array();

                        foreach($leadIdsNotInCron as $key => $value) {
                                if ($request['delivery_day'] > 0) {
  

                                        $campaignDateTime = explode(" ",$value['campaign_activated_on']);
                                        $campaignDate = $campaignDateTime[0];
                                        $msgDeliveryDate =      strtotime($campaignDate." ".$request['delivery_time'].'+ '.$request['delivery_day'].' days');

                                        //$msgDeliveryDate =    strtotime($value->campaign_activated_on.'+ '.$request['delivery_day'].' days');
                                } else {
                                        $campaignDate = explode(" ",$value['campaign_activated_on']);
                                        $msgDate = $campaignDate[1];
                                        $msgDeliveryDate =      strtotime($campaignDate[0]." ".$request['delivery_time']);
                                }



                                if (strtotime("now") < $msgDeliveryDate) {

                                $data[$key]['user_id'] =  Auth::user()->id;
                                $data[$key]['lead_id'] = $value['id'];
                                $data[$key]['campaign_id'] = $campaignId ;
                                $data[$key]['automation_messages_id'] = $smsid;
                                $data[$key]['name'] = $value['name'];

				$data[$key]['mobile_no'] = $value['mobile_no'];

				$data[$key]['mail_id'] = $value['mail_id'];

                                $data[$key]['automation_type'] = 3;
                                $data[$key]['delivery_date_time'] = date("Y-m-d H:i:s",$msgDeliveryDate);
                                $data[$key]['message'] = $request['message'];
				$data[$key]['image']   = $request['image_link'];

                                $data[$key]['status'] = 2;
if ($activeEvent == "0") {

$data[$key]['is_stopped'] = "1";
$data[$key]['stopped_reason'] = "Event Off ";
$detailData[$key]['is_cancelled'] = "1";
$detailData[$key]['failure_reason'] = "Event Off";


}
//Lead Report Data
				$detailData[$key]['lead_id'] = $value['id'];
				$detailData[$key]['automation_messages_id'] = $smsid;
				$detailData[$key]['delivery_date_time'] = date("Y-m-d H:i:s",$msgDeliveryDate);
                                } else {
				
				if (LeadDetails::where([['lead_id',  $value['id']],['automation_messages_id',$smsid],['is_cancelled',"0"]])->count() > 0) {
				
					$data2 = array(
						'is_cancelled' => "1",
						'failure_reason' => "Delivery Date updated"
					);
					
					LeadDetails::where([['lead_id', $value['id']],['automation_messages_id',$smsid],['is_cancelled',"0"]])->update($data2);
				}
				$detailData[$key]['lead_id'] = $value['id'];
				$detailData[$key]['automation_messages_id'] = $smsid;
				$detailData[$key]['delivery_date_time'] = date("Y-m-d H:i:s",$msgDeliveryDate);
				$detailData[$key]['is_cancelled'] = "1";
				$detailData[$key]['failure_reason'] = "Delivery Date set to passed date";

			}
                        }
//dd($data); die;

                        LeadcornCampaigns::insert($data);
LeadDetails::insert($detailData);
                }

}

		Session::flash('message', "Whatsapp Automation Updated successfully.");
		//$queryrow = AutomationSeries::where('id', $smsid)->get(); 
		//dd($queryrow);
		//$campaignId = $queryrow[0]->campaign_id;

		return redirect('view-campaigns-automations/'.$campaignId);
		//return redirect('edit-whatsapp-automation/'.$smsid);

	 }

	public function testsendwhatsappTest(){
		
		$mobileno = $_POST['mobile'];
		$text = str_replace("{Full Name}","USER",$_POST['msg']);
		$attachment = $_POST['attachment'];
        //$secretKey = '149c8b29185e6cacec185084f924964b5d6715ca8f1422d9fb';
/*		
		$base_link = "https://realauto.in/";
		if(strlen(trim($attachment)) > 0) {

			if (strpos($attachment, $base_link) !== false){

			} else {
				$attachment = $base_link.$attachment;
			}
		} else {
			$attachment = "";
		}
*/		
        $secretKey = Auth::user()->whatsapp_api_key;;
        $url = "https://app.messageautosender.com/api/v1/message/create";
        $request = array('receiverMobileNo' => $mobileno, 'message'=> $text,'filePathUrl' => $attachment);
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
            //$data = json_decode($result);
		
			echo "Whatsapp Sent!!";
        }

     }

	function testsendsms() {

        // $api_key = '260A0C06CECD8B';
        // $contacts = '9434534818';
		$mobileno = $_POST['mob_no'];
		$smstext = str_replace("{Full Name}","USER",$_POST['sms_text']);
	
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, "key=".$api_key."&campaign=0&routeid=58&type=text&contacts=".$contacts."&senderid=".$from."&msg=".$sms_text);
        $response = curl_exec($ch);
echo $response;	
	$curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);
        
		if ($curl_errno > 0) {
            echo "cURL Error ($curl_errno): $curl_error <br/>";
            return "";
        } else {
			
			echo "SMS Sent !!";
            //echo "<pre>"; print_r($response); die;
        }
    }
	
	
	public function testSendEmail(){
		//print_r($_POST);
		$api_key = Auth::user()->email_api_key;		
		$fromEmail = Auth::user()->email; 
		$toEmail = $_POST['receiversEmail'];
		$emailtext = $_POST['msg'];
		
		$filename = "8_1627045788.jpg";
		
		//$file_name_with_full_path = realpath('./'.$filename);
		$file_name_with_full_path = "https://realauto.in/uploads/8_1627045788.jpg";
		$filetype = "image/jpeg";
		
		/*
		$post = array('from'  => 'mr.ayansaha@gmail.com',
			'msg_to'  => $emaildid,
			'subject' => 'Email Testing',
			'body_text' => $emailtext,
			'apikey' => $api_key
		);
		*/
		
		$fromEmail = "lohanideepak93@gmail.com";
		
		
		$html = view('email-body', ["data"=>$emailtext])->render();
		
		$post = array(
		    'from'  => $fromEmail,
			'fromName' => "Deepak Lohani",
			'msgTo'  => $toEmail,
			'subject' => $_POST['subject'],
			'bodyHtml' => $html,
			//'file_1' => new CurlFile($file_name_with_full_path, $filetype, $filename),
			'attachments' => "1669",
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

        # Print response.
        if ($curl_errno > 0) {
            echo "cURL Error ($curl_errno): $curl_error <br/>";
            return "";
        } else {
           // $data = json_decode($result);
			
			echo "Test Email Sent Successfully!!";
            //echo "<pre>"; print_r($data); die;
        }

     }
	 
	public function deactivateCron() {
		/*
		$leadId = $_POST['leadId'];
		$campaignId = $_POST['campaignId'];
		$isStopped = $_POST['isStopped'];
		
		$leadCronFilter = array('lead_id'=> $leadId,'campaign_id' => $campaignId);
		
		$leadCronAffectedRows = LeadcornCampaigns::where($leadCronFilter)->update(array('is_stopped' => $isStopped,'stopped_reason' => 'Campaign Stopped'));
		
		$leadFilter = array('id'=> $leadId,'campaigns_id' => $campaignId);
		
		
		$afectedLeads = Lead::where($leadFilter)->update(array('is_cron_disabled' => $isStopped));
		
		print_r($afectedLeads);
		*/

/*

	        $leadIds = explode(",",$_POST['leadIds']);

                $leads = DB::table('leads')->whereIn('id', $leadIds)->update(['is_cron_disabled' => "1"]);


                DB::table('lead_corn_campaigns')->whereIn('lead_id',$leadIds)->update(array('is_stopped' => "1",'stopped_reason' => "Lead is Off"));

*/

if (isset($_POST['status'])) {
			$status = $_POST['status'];

			$leadIds = explode(",",$_POST['leadIds']);

			$leads = DB::table('leads')->whereIn('id', $leadIds)->update(['is_cron_disabled' => "$status"]);

			if ($status == "1") {
				DB::table('lead_corn_campaigns')->whereIn('lead_id',$leadIds)->update(array('is_stopped' => 1,'stopped_reason' => "Lead is Off"));
			} else {
				DB::table('lead_corn_campaigns')->whereIn('lead_id',$leadIds)->update(array('is_stopped' => 0,'stopped_reason' => ""));	
			}
		}

	}
public function send_whatsapp_group_view(Request $request){
	$whatsappgrp = array();

          $adminwhatsapp_api_key = DB::select('select whatsapp_api_key from users where name = ?', ['admin']);
        $apiRequest = array(
           
            "username" => Auth::user()->whatsapp_username,
            "password" => "Real@123",
            "customerUsername" => Auth::user()->whatsapp_username,
        );
        
        $secretKey = $adminwhatsapp_api_key[0]->whatsapp_api_key;
        
       
        $url  = "https://app.messageautosender.com/api/v1/wa/groups";
        $ch        = curl_init( $url );
        
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
            // echo "cURL Error ($curl_errno): $curl_error <br/>";
            // return "IF Case..";
        } else {
            $data = json_decode($Fetchresult);

            $whatsappgrp= isset($data->result) ? $data->result : "";
        }

        return view('send-whatsapp-group-view', compact('whatsappgrp'));
    }


    public function send_whatsapp_group(){
		
		//print_r($_POST); die;
		if ($_POST['isGroup'] == 1) {
			$group = implode(",",$_POST['group']);
		} else {
			$group = $_POST['group'];
		}		
		
		$text = str_replace("{Full Name}","USER",$_POST['msg']);

		$attachment = $_POST['attachment'];
        //$secretKey = '149c8b29185e6cacec185084f924964b5d6715ca8f1422d9fb';
			
		if ($_POST['deliveryType'] == 1) {

			$adminId = Auth::user()->id;
			$isGroup = $_POST['isGroup'];
			$deliveryDateTime =  $_POST['deliverDate']." ". $_POST['deliverTime'];
			DB::insert('insert into grp_cron_msgs (admin_id,is_group,receivers,message,attachment, 	
delivery_date_time) values(?,?,?,?,?,?)',[$adminId,$isGroup,$group,$text,$attachment,$deliveryDateTime]);


		} else {

		$base_link = "https://realauto.in/";
		if(strlen(trim($attachment)) > 0) {
			$attachment = $attachment;
			
		} else {
			$attachment = "";
		}
		$apiRequest = array(
            "username" => "AYAN SAHA",
            "password" => "BANGALORE@2020",
            "customerUsername" => Auth::user()->whatsapp_username,
        );

        $secretKey = Auth::user()->whatsapp_api_key;;
        
        $url = "https://app.messageautosender.com/api/v1/message/create";
        $request = array( 'message'=> $text,'filePathUrl' => $attachment);
        if ($_POST['isGroup'] == 0) {
        	$request['receiverMobileNo'] = $group;
        } else {
        	$request['recipientIds'] = $group;
        }

       // print_r($request); die;
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
		//print_r($result);
        # Print response.
        if ($curl_errno > 0) {
            echo "cURL Error ($curl_errno): $curl_error <br/>";
            return "";
        } else {
            //$data = json_decode($result);
		
			echo "Whatsapp Sent!!";
        }
    }

     }

}
