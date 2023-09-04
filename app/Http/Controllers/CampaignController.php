<?php
namespace App\Http\Controllers;

use Excel;
use Session;
use App\User;
use App\Models\Lead;
use App\Models\Campaign;
use App\Models\Project;
use App\Models\Segment;
use App\Imports\LeadsImport;
use App\Models\AutomationSeries;
use App\Models\SmsSeriesmessage;
use App\Models\LeadcornCampaigns;
//use App\Models\LeadReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
//use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\ImageManagerStatic as Image;
use DateTime;

use App\Models\LeadDetails;

class CampaignController extends Controller
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
    public function add_campaign(){

        return view('add-campaign');
    }


    public function add_segment(){

 	//$data = Segment::where('user_id',Auth::user()->id)->get();
	$data = DB::table('segment')->select( 'segment.id','segment.segment_name',DB::raw('count(leads.id) as leads'))->leftjoin('leads','leads.segment_id','=','segment.id')
->where('segment.user_id',Auth::user()->id)->groupby('segment.id')->get();
        return view('add-segment', array('segments'=>$data));

    }
	
    public function add_project(){

        $data = Project::where('user_id',Auth::user()->id)->get();
        return view('add-project', array('projects'=>$data));
    }


       public function save_new_segment(Request $request)
    {
       
        $msg = [
            'segment_name.required' => 'Segment Name Should Not Be Left Blank',
        ];

        $this->validate($request, [
            'segment_name' => 'required',
        ], $msg);

        $existName = Checkusersegmentname($request->segment_name);

        if ($existName == "") {

            if (isset($request['is_update']) && $request['is_update'] == "1") {
                
                $data['segment_name'] = $request['segment_name'];

                Segment::where('id', $request['segment_id'])->update($data);

                Lead::where('segment_id', $request['segment_id'])->update($data);                

                return redirect()->back()->with('message', 'Segment Updated Successfully !!');

            } else {

                $cp = new Segment();
                $cp->user_id        = Auth::user()->id;
                $cp->segment_name = $request['segment_name'];
                $cp->for_source   = "Facebook";
                $cp->status         = "1";
                $cp->save();
                $segmentId = $cp->id;
                
                return redirect()->back()->with('message', 'Segment Created Successfully !!');

            }

        } else {
            return redirect(route('add-segment'))->with('error-message', 'This Segment Name Already Taken!! Please Provide Another Segment Name!!!');
        } 
    }
	
	public function save_new_project(Request $request)
    {
        
        $msg = [
            'project_name.required' => 'Project Name Should Not Be Left Blank',
        ];
        $this->validate($request, [
            'project_name' => 'required',
        ], $msg);

        ////////// Checking Unique Campaign Name As Per User //////////
        $existName = Checkuserprojectname($request->project_name);
        ////////// End Of Checking Unique Campaign Name As Per User //////////

        if ($existName == "") {
            $cp = new Project();
            $cp->user_id        = Auth::user()->id;
            $cp->project_name = $request['project_name'];
            $cp->for_source   =  "Facebook";
            $cp->status         = "1";
            $cp->save();
			
            //return redirect(route('add-campaign'))->with('message', 'Campaign Added Successfully!!');
			// return redirect('view-campaigns-automations/'.$campaignId);
			return redirect()->back()->with('message', 'Project Created Successfully !!');
        }
        else {
            return redirect(route('add-project'))->with('error-message', 'This Project Name Already Taken!! Please Provide Another Project Name!!!');
        }
    }

    /**
    * Save Automation Country after post
    *
    * @return true or false
    */
    public function save_campaign_automation(Request $request)
    {
        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        $msg = [
            'campaigns_name.required' => 'Campaign Name Should Not Be Left Blank',
        ];
        $this->validate($request, [
            'campaigns_name' => 'required',
        ], $msg);

        ////////// Checking Unique Campaign Name As Per User //////////
        $existName = Checkusercampaignname($request->campaigns_name);
        ////////// End Of Checking Unique Campaign Name As Per User //////////

        if ($existName == "") {
            $cp = new Campaign();
            $cp->user_id        = Auth::user()->id;
            $cp->campaigns_name = $request['campaigns_name'];
            $cp->campaigns_id   = substr(str_shuffle($str_result), 0, 15);
            $cp->status         = 1;
            $cp->save();
			$campaignId = $cp->id;
            //return redirect(route('add-campaign'))->with('message', 'Campaign Added Successfully!!');
			 return redirect('view-campaigns-automations/'.$campaignId);
        }
        else {
            return redirect(route('add-campaign'))->with('error-message', 'This Campaign Name Already Taken!! Please Provide Another Campaign Name!!!');
        }
    }

/**
    * COPY CAMPAING NAME New 17 Aug 2021
    *
    * @return true or false
    */
    public function copyCampaign($id){


        $str_result  = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $campDtls    = DB::table('campaigns')->where('id', $id)->first();
        $newCampaign = new Campaign();
        $newCampaign->user_id         = $campDtls->user_id;
        $newCampaign->campaigns_name  = "Copy-" . $campDtls->campaigns_name;
        $newCampaign->save();
        $newCampaignId = $newCampaign->id;


        $autoDtl = DB::table('automations')
            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.is_active','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.campaign_id', $id)
            ->get();
 
        if (null !== $autoDtl) {
          
            foreach ($autoDtl as $key => $autoDtl) {
                $as = new AutomationSeries();
                $as->user_id         = $autoDtl->user_id;
                $as->campaign_id     = $newCampaignId;
                $as->series_name     = "Copy-".$autoDtl->series_name;
                //$as->automation_type = 1;
                $as->automation_type = $autoDtl->automation_type;
                $as->save();
                $sm = new SmsSeriesmessage();
                $sm->user_id            = $autoDtl->user_id;
                $sm->series_id          = $as->id;
                $sm->is_active          = $autoDtl->is_active;
				$sm->image              = $autoDtl->image;
                $sm->message            = $autoDtl->message;
                $sm->custom_full_name   = $autoDtl->custom_full_name;
                $sm->delivery_type      = $autoDtl->delivery_type;
                $sm->delivery_time      = $autoDtl->delivery_time;
                $sm->delivery_day       = $autoDtl->delivery_day;
                $sm->save();
            }
        }  

        return redirect()->back()->with('message', 'Record Duplicated Successfully For Automation Campaign!');
    }



    /**
    * COPY CAMPAING NAME
    *
    * @return true or false
    */
    public function copyCampaign_old($id){

        //$tasks   = Campaign::findOrFail($id);
        //$newTask = $tasks->replicate();
        //$newTask->save();

        // String of all alphanumeric character
        $str_result  = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $campDtls    = DB::table('campaigns')->where('id', $id)->first();
        $newCampaign = new Campaign();
        $newCampaign->user_id         = $campDtls->user_id;
        //$newCampaign->campaigns_name  = "Copy-" . $campDtls->campaigns_name . "-" . $id;
        $newCampaign->campaigns_name  = "Copy-" . $campDtls->campaigns_name;
        $newCampaign->campaigns_id    = substr(str_shuffle($str_result),  0, 15);
        $newCampaign->save();

        return redirect()->back()->with('message', 'Record Duplicated Successfully For Automation Campaign!');
    }

    /**
    * COPY SMS AUTOMATION CAMPAING NAME
    *
    * @return true or false
    */
    public function copySmsAutoCampaign($id){

        //$tasks   = Campaign::findOrFail($id);
        //$newTask = $tasks->replicate();
        //$newTask->save();

        $autoDtls = DB::table('automations')
            ->select('automations.id as autoID','automations.user_id','automations.campaign_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('sms_automation_messages.id', $id)
            ->first();

        $as = new AutomationSeries();
        $as->user_id         = $autoDtls->user_id;
        $as->campaign_id     = $autoDtls->campaign_id;
        $as->series_name     = "Copy-".$autoDtls->series_name;
        $as->automation_type = 1;
        $as->save();

        $sm = new SmsSeriesmessage();
        $sm->user_id            = $autoDtls->user_id;
        $sm->series_id          = $as->id;
        $sm->message            = $autoDtls->message;
        $sm->custom_full_name   = $autoDtls->custom_full_name;
        $sm->delivery_type      = $autoDtls->delivery_type;
        $sm->delivery_time      = $autoDtls->delivery_time;
        $sm->delivery_day       = $autoDtls->delivery_day;
        
	$sm->is_active = "0";
	$sm->save();

        $lastsmid = $sm->id;

	$array2 = array("user_id","=", Auth::user()->id);	
	$array5 = array("is_active","=","1");
	$array6 = array("campaigns_id","=", $autoDtls->campaign_id);


        // CHECK IF CAMPAING ADDED
        //$queryx = LeadcornCampaigns::where('user_id', $autoDtls->user_id)->where('campaign_id', $autoDtls->campaign_id)->where('automation_messages_id','!=',$lastsmid)->count();

        $leadFilter = array($array2,$array5,$array6);
		
        $activeLeads = Lead::where($leadFilter)->select("id","campaign_activated_on")->get();




	//if($queryx >0){

          //  $queryrow = LeadcornCampaigns::where('user_id', $autoDtls->user_id)->where('campaign_id', $autoDtls->campaign_id)->where('automation_messages_id','!=',$lastsmid)->groupBy('lead_id')->get();

            //foreach($queryrow as $row){

	if (null !== $activeLeads) {
		
		foreach($activeLeads as $value){
		/*
                if($autoDtls->delivery_day == 0){ //Today
                    $newdatetime = date('Y-m-d '. $autoDtls->delivery_time);
                }else{ //After Day
                    $newdatetime = new DateTime(date('Y-m-d '.$autoDtls->delivery_time).' + '. $autoDtls->delivery_day.' day');
                }
		*/
		if ($autoDtls->delivery_day > 0) {
			$msgDeliveryDate = 	strtotime($value->campaign_activated_on.'+ '.$autoDtls->delivery_day.' days');
		} else {
			$campaignDate = explode(" ",$value->campaign_activated_on);
			$msgDate = $campaignDate[1];
			$msgDeliveryDate = 	strtotime($campaignDate[0]." ".$autoDtls->delivery_time);
			$msgDeliveryDate = 	strtotime($campaignDate[0]." ".$autoDtls->delivery_time);
		}

		if (strtotime("now") < $msgDeliveryDate) {
                $getleaddata = GetLeadData($value->id);

                $leadcorn = new LeadcornCampaigns();
                $leadcorn->user_id                = $autoDtls->user_id;
                $leadcorn->campaign_id            = $autoDtls->campaign_id;
                $leadcorn->lead_id                = $value->id;
                $leadcorn->automation_messages_id = $lastsmid;
                $leadcorn->name                   = $getleaddata->name;
                $leadcorn->mail_id                = $getleaddata->mail_id;
                $leadcorn->mobile_no              = $getleaddata->mobile_no;
                $leadcorn->automation_type        = 1;
                $leadcorn->delivery_date_time     = date("Y-m-d H:i:s",$msgDeliveryDate);
                $leadcorn->message                = $autoDtls->message;
                $leadcorn->status                 = 2;
		$leadcorn->is_stopped = "1";
		$leadcorn->stopped_reason = "Event only copied";
                $leadcorn->save();
		}		
				
            }
        }

        return redirect()->back()->with('message', 'Record Duplicated Successfully For SMS!');
    }

    /**
    * COPY Email AUTOMATION CAMPAING NAME
    *
    * @return true or false
    */
    public function copyEmailAutoCampaign(Request $request, $id){

        //$tasks   = Campaign::findOrFail($id);
        //$newTask = $tasks->replicate();
        //$newTask->save();

        $autoDtls = DB::table('automations')
            ->select('automations.id as autoID','automations.user_id','automations.campaign_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('sms_automation_messages.id', $id)
            ->first();

        // FOR IMAGE
        if($request->hasFile('image1')){

            $files1 = $request->file('image1');
            $filename1 = md5($files1->getClientOriginalName() .rand().time()).'.'.$files1->extension();
            $destinationPath = public_path('/uploads/automation');
            $thumb_img = Image::make($files1->getRealPath())->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $thumb_img->save($destinationPath.'/'.$filename1,80);

        }else{
            $filename1 ='';
        }

        $as = new AutomationSeries();
        $as->user_id         = $autoDtls->user_id;
        $as->campaign_id     = $autoDtls->campaign_id;
        $as->series_name     = "Copy-".$autoDtls->series_name;
        $as->automation_type = 2;
        $as->save();

        $sm = new SmsSeriesmessage();
        $sm->user_id            = $autoDtls->user_id;
        $sm->series_id          = $as->id;
        $sm->image              = $filename1;
        $sm->message            = $autoDtls->message;
        $sm->custom_full_name   = $autoDtls->custom_full_name;
        $sm->delivery_type      = $autoDtls->delivery_type;
        $sm->delivery_time      = $autoDtls->delivery_time;
        $sm->delivery_day       = $autoDtls->delivery_day;
        $sm->save();

        $lastsmid = $sm->id;

        // CHECK IF CAMPAING ADDED
        $queryx = LeadcornCampaigns::where('user_id', $autoDtls->user_id)->where('campaign_id', $autoDtls->campaign_id)->count();

        if($queryx >0){

            $queryrow = LeadcornCampaigns::where('user_id', $autoDtls->user_id)->where('campaign_id', $autoDtls->campaign_id)->where('automation_messages_id','!=',$lastsmid)->groupBy('lead_id')->get();

            foreach($queryrow as $row){

                if($autoDtls->delivery_day == 0){ //Today
                    $newdatetime = date('Y-m-d '. $autoDtls->delivery_time);
                }else{ //After Day
                    $newdatetime = new DateTime(date('Y-m-d '. $autoDtls->delivery_time).' + '. $autoDtls->delivery_day .' day');
                }

                $getleaddata = GetLeadData($row->lead_id);

                $leadcorn = new LeadcornCampaigns();
                $leadcorn->user_id                = $autoDtls->user_id;
                $leadcorn->campaign_id            = $autoDtls->campaign_id;
                $leadcorn->lead_id                = $row->lead_id;
                $leadcorn->automation_messages_id = $lastsmid;
                $leadcorn->name                   = $getleaddata->name;
                $leadcorn->mail_id                = $getleaddata->mail_id;
                $leadcorn->mobile_no              = $getleaddata->mobile_no;
                $leadcorn->automation_type        = 2;
                $leadcorn->delivery_date_time     = $newdatetime;
                $leadcorn->message                = $autoDtls->message;
                $leadcorn->status                 = 2;
                $leadcorn->save();
            }
        }

        return redirect()->back()->with('message', 'Record Duplicated Successfully For Email!');
    }

    /**
    * COPY Whatsaap AUTOMATION CAMPAING NAME
    *
    * @return true or false
    */
    public function copywhatsappAutoCampaign($id){

        //$tasks   = Campaign::findOrFail($id);
        //$newTask = $tasks->replicate();
        //$newTask->save();

        $autoDtls = DB::table('automations')
            ->select('automations.id as autoID','automations.user_id','automations.campaign_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('sms_automation_messages.id', $id)
            ->first();

        $as = new AutomationSeries();
        $as->user_id         = $autoDtls->user_id;
        $as->campaign_id     = $autoDtls->campaign_id;
        $as->series_name     = "Copy-".$autoDtls->series_name;
        $as->automation_type = 3;
        $as->save();

        $sm = new SmsSeriesmessage();
        $sm->user_id            = $autoDtls->user_id;
        $sm->series_id          = $as->id;
        $sm->message            = $autoDtls->message;
        $sm->custom_full_name   = $autoDtls->custom_full_name;
        $sm->delivery_type      = $autoDtls->delivery_type;
        $sm->delivery_time      = $autoDtls->delivery_time;
        $sm->delivery_day       = $autoDtls->delivery_day;
	$sm->is_active = "0";
        $sm->save();

        $lastsmid = $sm->id;

	$array2 = array("user_id","=", Auth::user()->id);
		
	$array5 = array("is_active","=","1");
	$array6 = array("campaigns_id","=", $autoDtls->campaign_id);


	$leadFilter = array($array2,$array5,$array6);
		
	$activeLeads = Lead::where($leadFilter)->select("id","campaign_activated_on")->get();

        // CHECK IF CAMPAING ADDED
        //$queryx = LeadcornCampaigns::where('user_id', $autoDtls->user_id)->where('campaign_id', $autoDtls->campaign_id)->count();

        //if($queryx >0){
	  if(null !== $activeLeads) {
          //  $queryrow = LeadcornCampaigns::where('user_id', $autoDtls->user_id)->where('campaign_id', $autoDtls->campaign_id)->where('automation_messages_id','!=',$lastsmid)->groupBy('lead_id')->get();

            //foreach($queryrow as $row){
foreach($activeLeads as $value) {
				if ($autoDtls->delivery_day > 0) {
					$msgDeliveryDate = 	strtotime($value->campaign_activated_on.'+ '.$autoDtls->delivery_day.' days');
				} else {
					$campaignDate = explode(" ",$value->campaign_activated_on);
					$msgDate = $campaignDate[1];
					$msgDeliveryDate = 	strtotime($campaignDate[0]." ".$autoDtls->delivery_time);
					$msgDeliveryDate = 	strtotime($campaignDate[0]." ".$autoDtls->delivery_time);
				}

                /*
if($autoDtls->delivery_day == 0){ //Today
                    $newdatetime = date('Y-m-d '. $autoDtls->delivery_time);
                }else{ //After Day
                    $newdatetime = new DateTime(date('Y-m-d '. $autoDtls->delivery_time).' + '. $autoDtls->delivery_day .' day');
                }
*/
	if (strtotime("now") < $msgDeliveryDate) {
                $getleaddata = GetLeadData($value->id);

                $leadcorn = new LeadcornCampaigns();
                $leadcorn->user_id                = $autoDtls->user_id;
                $leadcorn->campaign_id            = $autoDtls->campaign_id;
                $leadcorn->lead_id                = $value->id;
                $leadcorn->automation_messages_id = $lastsmid;
                $leadcorn->name                   = $getleaddata->name;
                $leadcorn->mail_id                = $getleaddata->mail_id;
                $leadcorn->mobile_no              = $getleaddata->mobile_no;
                $leadcorn->automation_type        = 3;
                $leadcorn->delivery_date_time     = date("Y-m-d H:i:s",$msgDeliveryDate);
                $leadcorn->message                = $autoDtls->message;
                $leadcorn->status                 = 2;
		$leadcorn->is_stopped = "1";
		$leadcorn->stopped_reason = "Event only copied";
                $leadcorn->save();
            }
        }
	}
        return redirect()->back()->with('message', 'Record Duplicated Successfully For Whatsaap!');
    }

    /**
    * EDIT CAMPAING NAME
    *
    * @return true or false
    */
    public function edit_campaigns(Request $request, $campaignsid){

        $data["campaignname"] = Campaign::where('id', $campaignsid)->first();

        return view('edit-campaign',$data);
    }

    /**
    * EDIT CAMPAING NAME POST
    *
    * @return true or false
    */
    public function edit_campaign_name(Request $request, $campaignsid){

        $data = array(
            'campaigns_name'  => $request['campaigns_name'],
        );

        Campaign::where('id', $campaignsid)->update($data);

        Session::flash('message', "Campaign Name Updated successfully.");

        //return redirect('edit-campaigns/'.$campaignsid);
	return redirect('automation-master');

    }

    /**
    * Leads Country after post
    *
    * @return true or false
    */
    public function view_campaigns_automations(Request $request, $campaignsid){

        $data["campaignname"] = Campaign::where('id',$campaignsid)->first();

		$data['automationseries'] = DB::table('automations')
            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.is_active')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.user_id', Auth::user()->id)
            ->where('automations.campaign_id', $campaignsid)
            ->orderBy('delivery_day','asc')
			->orderBy('delivery_time','asc')
            ->get();

		return view('view-campaigns-automations',$data);
	}
	
	public function deleteCampaign(Request $request, $campaignsid) {
		/*
		DB::table('lead_corn_campaigns')->where('campaign_id',$campaignsid)->delete();
		
		DB::table('leads')->where('campaigns_id',$campaignsid)->delete();
		$smsAutomations = DB::table('automations')->select('id')->where('campaign_id',$campaignsid)->get();
		$seriesIds = [];
		foreach($smsAutomations as $key => $value) {
			$seriesIds[] = $value->id; 
		}
		DB::table('sms_automation_messages')->wherein('series_id',$seriesIds)->delete();
		DB::table('sms_automations')->where('campaigns_id',$campaignsid)->delete();
		
		DB::table('automations')->where('campaign_id',$campaignsid)->delete();
		DB::table('campaigns')->where('id',$campaignsid)->delete();
		*/
		$eventFilter = array('campaign_id'=> $campaignsid);
		$afectedLeads = LeadcornCampaigns::where($eventFilter)->update(array('is_stopped' => 1,'stopped_reason' => 'Campaign Deactivated'));
		
		$afectedCampaign = Campaign::where('id','=',$campaignsid)->update(array('is_active' => 0));
	
		return redirect()->back()->with('message', 'Campaign Deleted Successfully!');
	}
	
	public function store() {		
		request()->validate([
                   'file'  => 'required|mimes:jpeg,png,doc,docx,pdf,txt,mp4|max:8192',
                ]);
		
		$path_parts = pathinfo($_FILES["file"]["name"]);
		$extension = $path_parts['extension'];
		
		$dest = "public";
		$filename =  Auth::user()->id."_".time().".".$extension ;
		$filepath = public_path('uploads/');

    move_uploaded_file($_FILES['file']['tmp_name'], $filepath.$filename);

    // Note that here, we are copying the destination of your moved file. 
    // If you try with the origin file, it won't work for the same reason.
    copy($filepath.$filename, public_path('uploads').$filename);
	
	return Response()->json([
		"success" => true,
		"filepath" => 'uploads/'.$filename
	]);
		
		/*
        if ($files = $request->file('file')) {
             
            //store file into document folder
            $file = $request->file->store('public/documents');
				
            //store your file into database
            //$document = new Document();
            //$document->title = $file;
            //$document->save();
              
            return Response()->json([
                "success" => true,
                "file" => $file
            ]);
  
        }
*/
  
        return Response()->json([
                "success" => false,
                "file" => ''
          ]);
	}
	
	public function deactivateEvent() {
		//echo "da"; die;
		$seriesId = $_POST['seriesId'];
		$status = $_POST['status'];
		$isCronEvent = $_POST['isCronEvent'];
		
		$eventFilter = array('id'=> $seriesId);
		
		
		$afectedLeads = SmsSeriesmessage::where($eventFilter)->update(array('is_active' => $status));
		
		if ($isCronEvent == "1") {
			$leadCronFilter = array('automation_messages_id'=> $seriesId);
			$reason = null;
			$data2 = array();
			$data2['failure_reason'] = "";

			$data2['is_cancelled'] = "0";

			if (!$status) {
				$reason = "Event Deactivated";
				$data2['is_cancelled'] = "1";
				$data2['failure_reason'] = "Event Off";
			}
			
			LeadcornCampaigns::where($leadCronFilter)->update(array('is_stopped' => !$status,'stopped_reason' => $reason));
            LeadDetails::where([['automation_messages_id',$seriesId],['is_cancelled',"0"]])->update($data2);
		}
		print_r($afectedLeads);
	}
	
	public function campaignsRetrival(Request $request) {
		$offset = $_REQUEST['start'] ;
		
		$limit = $_REQUEST['length'];
		
		$search = json_encode($_REQUEST['search']);
		foreach( $_REQUEST['search'] as $key => $value) {
		   $search = trim($value);
		   break;
		}

		$searchValue = $search;

		$search = strlen($search);
		
		$where1 = array('campaigns.user_id','=',Auth::user()->id);
		$where2 = array('campaigns.is_active','=', "1");		
                	
		$whereArray = array($where1, $where2);

		$orWhereFilter1 = array();
    				
		if ($search > 0) {
			$whereArray = array();
		   	$orWhereFilter1 = array($where1, $where2);
			$orWhereFilter1[] = array('campaigns.campaigns_name','like',"$searchValue%");
		}
		
		$data['campaigns'] =  DB::table('campaigns')
		->selectRaw('campaigns.*,count(leads.id) as totalLeads')
		->leftjoin('leads','leads.campaigns_id','=','campaigns.id')
		->where($whereArray)
		->orWhere($orWhereFilter1)
		->groupBy('campaigns.id')
		->orderby('campaigns.id','desc')
		->offset($offset)
		->limit($limit)
		->get();
		
		foreach($data['campaigns'] as $key => $value) {
			$totalEvents = Gettotalevents($value->id);
			$data['campaigns'][$key]->totalEvents = $totalEvents;
			$data['campaigns'][$key]->emailEvents = Gettotalemailevents($value->id);
			$data['campaigns'][$key]->whatsappEvents = Gettotalwhatsappevents($value->id);
			$data['campaigns'][$key]->smsEvents = Gettotalsmsevents($value->id);                                         
		}
		
		$count = DB::table('campaigns')->where($whereArray)->orWhere($orWhereFilter1)->count();
		$filterCount = DB::table('campaigns')->where($whereArray)->orWhere($orWhereFilter1)->count();
		$whereArray = array();
		$result = array("search" => $whereArray,"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => $filterCount);
		
		$result['data'] = $data['campaigns'];
		
		print_r(json_encode($result));
		
	}
}
