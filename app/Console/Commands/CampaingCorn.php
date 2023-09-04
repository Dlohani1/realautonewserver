<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\LeadcornCampaigns;
use Session;
use Auth;
use Helper;
use DateTime;
class CampaingCorn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'corn:scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //return 0;
       // DB::table('leads')->delete();

	date_default_timezone_set('Asia/Kolkata');

       // $data = DB::table('lead_corn_campaigns')->where([['status','2'],['is_stopped','0'],['delivery_date_time','like',date('Y-m-d')."%"]])->get();

$currentdatetime = date('Y-m-d H:i');

$data = 
DB::table('lead_corn_campaigns')->select('id','automation_type','is_stopped','user_id','mobile_no','name','message','mail_id','image','delivery_date_time','automation_messages_id','lead_id')->where([['status','2'],['is_stopped','0'],['cron_set','0'],['delivery_date_time','like',date('Y-m-d')."%"]])->distinct()->get();

$c = DB::table('lead_corn_campaigns')->where([['status','2'],['is_stopped','0'],['cron_set','0'],['delivery_date_time','like',date('Y-m-d')."%" ]])->count();

        //$currentdatetime = date('Y-m-d H:i');
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

DB::table('lead_details')->where([['automation_messages_id',$row->automation_messages_id],['lead_id',$row->lead_id],['is_cancelled','0']])->update($updateData);

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

DB::table('lead_details')->where([['automation_messages_id',$row->automation_messages_id],['lead_id',$row->lead_id],['is_cancelled','0']])->update($updateData);

                     
                     DB::table('lead_corn_campaigns')->where('id',$row->id)->delete();
                }
                if($row->automation_type == 3 && !$row->is_stopped){

			$adminId = $row->user_id;

			$adminSettings = DB::table('admin_settings')
			->select('assignStaffWhatsappEvent')
			->where('admin_id',$adminId)
			->get();
			
			 if (isset($adminSettings[0]) && $adminSettings[0]->assignStaffWhatsappEvent  == "1" ) {

                   	 	
                        		  $subAdmin = DB::table('leads')
                        		->select('assigned_to')
                        		->where('id',$row->lead_id)
                        		->get();

					if (isset($subAdmin[0]->assigned_to) && $subAdmin[0]->assigned_to != 0) {
						$subAdminId = $subAdmin[0]->assigned_to;
						$usersmsapikey = Getuserapikey($subAdminId,3);
					} else { \Log::info('test subadmin');
						$usersmsapikey = Getuserapikey($row->user_id,3);
					}
                    		
                	} else {		

                    		$usersmsapikey = Getuserapikey($row->user_id,3);
			}

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

DB::table('lead_details')->where([['automation_messages_id',$row->automation_messages_id],['lead_id',$row->lead_id],['is_cancelled','0']])->update($updateData);
                    
                    
                    DB::table('lead_corn_campaigns')->where('id',$row->id)->delete();

                }

            }

        }
date_default_timezone_set('Asia/Kolkata');
	$currentdatetime = date('Y-m-d H:i');

	$data = DB::table('grp_cron_msgs')->select('grp_cron_msgs.*','users.id as 
userId','users.whatsapp_api_key','users.whatsapp_username','users.whatsapp_pwd')->join('users','users.id','=','grp_cron_msgs.admin_id')->where([['delivery_date_time','like',date('Y-m-d')."%"]])->distinct()->get();
        
        foreach($data as $row){
        	//print_r($row); die;
            $deliverydate = date('Y-m-d H:i',strtotime($row->delivery_date_time));
            
            if($deliverydate <= $currentdatetime){
           // echo "dd"; die;
                $apiRequest = array(
                    "username" => "AYAN SAHA",
                    "password" => "BANGALORE@2020",
                    "customerUsername" => $row->whatsapp_username,
                );

                $secretKey = $row->whatsapp_api_key;;
                
                $url = "https://app.messageautosender.com/api/v1/message/create";
                $request = array( 'message'=> $row->message,'filePathUrl' => $row->attachment);
                if ($row->is_group == 0) {
                    $request['receiverMobileNo'] = $row->receivers;
                } else {
                    $request['recipientIds'] = $row->receivers;
                }

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
                    // echo "cURL Error ($curl_errno): $curl_error <br/>";
                    // return "";
                    continue;
                } else {
                    //$data = json_decode($result);
                 DB::table('grp_cron_msgs')->where('id',$row->id)->delete();
                    //echo "Whatsapp Sent!!";
                }
            }
        }

    }
}
