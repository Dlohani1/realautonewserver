<?php

namespace App\Http\Controllers;

use Excel;
use Helper;
use Session;
use DateTime;
use App\User;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Segment;
use App\Models\Campaign;
use App\Models\LeadcornCampaigns;
//use App\Models\LeadReports;
use App\Imports\LeadsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\LeadComments;
use App\Models\LeadFollowups;
use App\Models\LeadStatus;
//use Maatwebsite\Excel\Facades\Excel;
use App\Models\LeadDetails;
use App\Exports\LeadsExport;
use Illuminate\Support\Facades\Mail;
use App\Exports\UsersExport;

use App\Exports\SubAdminExport;


class LeadsController extends Controller
{

    public function __construct()
    {
       $this->middleware('auth');
    }

    /**
    * Show the Admin Category Page.
    *
    * @return \Illuminate\Http\Response
    */
	public function leads_bulk_upload(){
		return view("admin.leads-bulk-upload");
	}

	public function export(Request $request) 
    {
		$filter = array();
		//$filter['campaignId'] = $_POST['campaign'];
		$filter['fromDate'] = $_POST['from_date'];
		$filter['toDate'] = $_POST['to_date'];
		return Excel::download(new UsersExport($filter), 'leads-report.xlsx');		
        	//return Excel::download(new LeadsExport($filter), 'leads-report.xlsx');
    }


    public function downloadSubadminLeads(Request $request) {

	    $filter = array();
                //$filter['campaignId'] = $_POST['campaign'];
                $filter['fromDate'] = $_POST['from_date'];
                $filter['toDate'] = $_POST['to_date'];
                //return Excel::download(new UsersExport($filter), 'leads-report.xlsx');
                return Excel::download(new SubAdminExport($filter), 'leads-report.xlsx');
    }

    /**
    * Add Category master
    *
    * @return true or false
    */
    public function add_leads(){

        $data["projects"]  = Project::where('user_id', Auth::user()->id)->get();
        $data["segments"]  = Segment::where('user_id', Auth::user()->id)->get();
        $data["campaigns"] = Campaign::where([['user_id', Auth::user()->id],['is_active',1]])->get();

        return view("add-leads",$data);
    }

    /**
    * Add Leads after post
    *
    * @return true or false
    */
    function add_leads_post_data(Request $request)
    {
        $msg = [
            'name.required'         => 'Lead Name Should Not Be Left Blank',
            'mail_id.required'      => 'Mail ID Should Not Be Left Blank',
            'mobile_no.required'    => 'Mobile Number Should Not Be Left Blank',
            'project_type.required' => 'Project Type Should Not Be Left Blank',
            'segment_type.required' => 'Please Select Any Segment',
            'campaign_id.required'  => 'Please Select Campaign',
        ];
		
        $this->validate($request, [
            'name'         => 'required',
            'mail_id'      => 'required',
            'mobile_no'    => 'required',
            'project_type' => 'required',
            'segment_type' => 'required',
            'campaign_id'  => 'required',
        ], $msg);

        ////////// Checking Unique Segment Name As Per User //////////
        $existName = Checkusersegmentname($request->segment_name);
        ////////// End Of Checking Unique Segment Name As Per User //////////

        if ($existName == "") { // Modified By Subrata Saha

            if($request->project_type == 2){ // FOR NEW

                $project = new Project();
                $project->user_id      = Auth::user()->id;
                $project->project_name = $request['project_name'];
                $project->for_source   = 'Facebook';
                $project->status       = 1;
                $project->save();
                $projectid   = $project->id;
                $projectname =  $request['project_name'];

            } else {
                $projectid   = $request['project_id'];
                $projectname = Project::where('id',$projectid)->first()->project_name;
            }

            if($request->segment_type == 2){ // FOR NEW

                $segment = new Segment();
                $segment->user_id      = Auth::user()->id;
                $segment->segment_name = $request['segment_name'];
                $segment->for_source   = 'Facebook';
                $segment->status       = 1;
                $segment->save();
                $segmentid   = $segment->id;
                $segmentname = $request['segment_name'];

            }else{
                $segmentid   = $request['segment_id'];
                $segmentname = Segment::where('id', $segmentid)->first()->segment_name;
            }

        } else {
            return redirect(route('add-leads'))->with('error-message', 'This Segment Name Already Taken!! Please Provide Another Segment Name!!!');
        }

date_default_timezone_set("Asia/kolkata");
$request['mobile_no'] = substr($request['mobile_no'], -10);


$leadExist = DB::table('leads')->select('mobile_no')->where(array(array('mobile_no',$request['mobile_no']),array('campaigns_id',$request['campaign_id'])))->count();

if ($leadExist) {
 DB::insert('insert into leads_duplicate (user_id,name,email,contact,campaign_id,project_id,segment_id,source) 
        values(?,?,?,?,?,?,?,?)',[Auth::user()->id,$request['name'],$request['mail_id'],$request['mobile_no'], $request['campaign_id'],$projectid,$segmentid,"RealAuto Add Lead Form"]);

  return redirect(route('add-leads'))->with('error-message', 'Lead Contact already exist!!');


}

        $lead = new Lead();
        $lead->user_id               = Auth::user()->id;

        $lead->project_id            = $projectid;
        $lead->project_type          = $request->project_type;
        $lead->project_name          = $projectname;

        $lead->segment_id            = $segmentid;
        $lead->segment_type          = $request->segment_type;
        $lead->segment_name          = $segmentname;

        $lead->campaigns_id          = $request['campaign_id'];
        $lead->name                  = $request['name'];
        $lead->mail_id               = $request['mail_id'];
        $lead->mobile_no             = $request['mobile_no'];
        $lead->country               = $request['country'];
        $lead->state                 = $request['state'];
        $lead->city                  = $request['city'];
        $lead->zipcode               = $request['zipcode'];
        $lead->company               = $request['company'];
        $lead->position              = $request['position'];
        $lead->address1              = $request['address1'];
        $lead->address2              = $request['address2'];
        $lead->source                = 'Self';
        $lead->status                = 1;
	$lead->campaign_activated_on =  date('Y-m-d H:i:s');
        $lead->save();

        $lastid = $lead->id;

    $useStaffWP = 0;

        $adminSettings = DB::table('admin_settings')
                ->select('auto_assign_leads','send_notification_via_whatsapp','send_notification_via_email','send_notification_to_staff','assignStaffWhatsappEvent')
                ->where('admin_id',Auth::user()->id)
                ->get();
                if (isset($adminSettings[0]) ) {

                    if ($adminSettings[0]->auto_assign_leads  == "1") {
                        $this->leadAssign($projectid,$lastid);
                    }

                    if ($adminSettings[0]->assignStaffWhatsappEvent  == "1") {
                        $useStaffWP = 1;
                    }
		}
	$campaignActivatedOn = $lead->campaign_activated_on;

        // ADD TEMP CORN DATA TABLE
        $cautomationcount = DB::table('automations')
            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.campaign_id', $request['campaign_id'])
            ->where('sms_automation_messages.delivery_type', 'scheduled')
            ->count();

        if($cautomationcount > 0){
            $cautomation = DB::table('automations')
                ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.is_active','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.campaign_id', $request['campaign_id'])
                ->where('sms_automation_messages.delivery_type', 'scheduled')
		//->where('sms_automation_messages.is_active', 1)
                ->get();

            foreach($cautomation as $row){
		$newDate = explode(" ",$campaignActivatedOn);

                if($row->delivery_day == 0){ //Today
                    //$newdatetime = date('Y-m-d '.$row->delivery_time);
		    $newdatetime = strtotime($newDate[0]." ".$row->delivery_time);
                }else{ //After Day
                    //$newdatetime = new DateTime(date('Y-m-d '.$row->delivery_time).' + '.$row->delivery_day.' day');
                	$newdatetime = strtotime($newDate[0]." ".$row->delivery_time." ".$row->delivery_day." days");
		}

		if ($newdatetime > strtotime("now")) {

                $leadcorn = new LeadcornCampaigns();
                $leadcorn->user_id                = Auth::user()->id;
                $leadcorn->campaign_id            = $request['campaign_id'];
                $leadcorn->lead_id                = $lastid;
                $leadcorn->automation_messages_id = $row->id;

                $leadcorn->name                   = $request['name'];
                $leadcorn->mail_id                = $request['mail_id'];
                $leadcorn->mobile_no              = $request['mobile_no'];
		
                $leadcorn->automation_type        = $row->automation_type;
                $leadcorn->delivery_date_time     = date("Y-m-d H:i:s",$newdatetime);
                $leadcorn->message                = $row->message;
                $leadcorn->image                  = $row->image;
                $leadcorn->status                 = 2;

	//LeadReport
				$leadDetail = new LeadDetails();					
				$leadDetail->lead_id               = $lastid;
				$leadDetail->automation_messages_id= $row->id;
				$leadDetail->delivery_date_time    = date("Y-m-d H:i:s",$newdatetime);                 

		if ($row->is_active == "0") {
			$leadcorn->is_stopped = "1";
			$leadcorn->stopped_reason = "Event is in off state on lead creation";
			$leadDetail->is_cancelled               = "1";
                        $leadDetail->failure_reason="Event in off state on lead creation";


		}

		$leadcorn->save();
$leadDetail->save();
		} else {

$leadDetail = new LeadDetails();
                                $leadDetail->lead_id               = $lastid;
                                $leadDetail->automation_messages_id= $row->id;
                                $leadDetail->delivery_date_time    = date("Y-m-d H:i:s",$newdatetime);
   $leadDetail->is_cancelled               = "1";
                        $leadDetail->failure_reason="Event expired on lead creation";

$leadDetail->save();


}
				/*
				$leadReport = new LeadReports();
                $leadReport->user_id                = Auth::user()->id;
                $leadReport->campaign_id            = $request['campaign_id'];
                $leadReport->lead_id                = $lastid;
                $leadReport->automation_messages_id = $row->id;
                $leadReport->name                   = $request['name'];
                $leadReport->mail_id                = $request['mail_id'];
                $leadReport->mobile_no              = $request['mobile_no'];
                $leadReport->automation_type        = $row->automation_type;
                $leadReport->delivery_date_time     = $newdatetime;
                $leadReport->message                = $row->message;
                $leadReport->image                  = $row->image;
                $leadReport->status                 = 2;
                $leadReport->save();
				*/
            }
        }

        // SEND MESSAGE
        /*
		$smscount = DB::table('automations')
            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.automation_type', 1)
            ->where('automations.campaign_id', $request['campaign_id'])
            ->where('sms_automation_messages.delivery_type', 'initial')
			->where('sms_automation_messages.is_active', 1)
            ->count();

        if($smscount > 0){
            $smsdata = DB::table('automations')
                ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.automation_type', 1)
                ->where('automations.campaign_id', $request['campaign_id'])
                ->where('sms_automation_messages.delivery_type', 'initial')
		->where('sms_automation_messages.is_active', 1)
                ->get();
			
			foreach($smsdata as $key => $value) {
				$message = isset($value->message) ? $value->message : "";
				testsendsms($request['mobile_no'],str_replace("{Full Name}",$request['name'],$message));
			}		
        }
		*/
		$smsEvents = DB::table('automations')
            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.automation_type', 1)
            ->where('automations.campaign_id', $request['campaign_id'])
            ->where('sms_automation_messages.delivery_type', 'initial')
			->where('sms_automation_messages.is_active', 1)
            ->get();
		
		if (null !== $smsEvents) {
			foreach($smsEvents as $key => $value) {
				$message = isset($value->message) ? $value->message : "";
				testsendsms($request['mobile_no'],str_replace("{Full Name}",$request['name'],$message));
			}
		}

        // SEND EMAIL
        /*
		$emailcount = DB::table('automations')
            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.automation_type', 2)
            ->where('automations.campaign_id', $request['campaign_id'])
            ->where('sms_automation_messages.delivery_type', 'initial')
			->where('sms_automation_messages.is_active', 1)
            ->count();

        if($emailcount > 0){
            $emaildata = DB::table('automations')
                ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.automation_type', 2)
                ->where('automations.campaign_id', $request['campaign_id'])
                ->where('sms_automation_messages.delivery_type', 'initial')
				->where('sms_automation_messages.is_active', 1)
                ->first();
			$message = isset($emaildata->message) ? $emaildata->message : "";

            testsendemail($request['mail_id'],str_replace("{Full Name}",$request['name'],$message));
        }
		*/
		
		$emailEvent = DB::table('automations')
                ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.automation_type', 2)
                ->where('automations.campaign_id', $request['campaign_id'])
                ->where('sms_automation_messages.delivery_type', 'initial')
				->where('sms_automation_messages.is_active', 1)
                ->get();
		
		if (null !== $emailEvent) {
			foreach($emailEvent as $key => $value) {
				$message = isset($value->message) ? $value->message : "";
			testsendemail($request['mail_id'],str_replace("{Full Name}",$request['name'],$message));
			}
		}
		
        // SEND WHATSAPP
		/*
        $whatsappcount = DB::table('automations')
            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.automation_type', 3)
            ->where('automations.campaign_id', $request['campaign_id'])
            ->where('sms_automation_messages.delivery_type', 'initial')
			->where('sms_automation_messages.is_active', 1)
            ->count();

        if($whatsappcount > 0){
            $whatsappdata = DB::table('automations')
                ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.automation_type', 3)
                ->where('automations.campaign_id', $request['campaign_id'])
                ->where('sms_automation_messages.delivery_type', 'initial')
				->where('sms_automation_messages.is_active', 1)
                ->first();

            testsendwhatsapp($request['mobile_no'],str_replace("{Full Name}",$request['name'],$whatsappdata->message),$whatsappdata->image);
        }
		*/
		
		$whatsappEvent = DB::table('automations')
                ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.automation_type', 3)
                ->where('automations.campaign_id', $request['campaign_id'])
                ->where('sms_automation_messages.delivery_type', 'initial')
				->where('sms_automation_messages.is_active', 1)
                ->get();
				
	    if (null !== $whatsappEvent) {
			foreach($whatsappEvent as $key => $value) {
				$message = isset($value->message) ? $value->message : "";
if ($useStaffWP == 1) {
    $subAdmin = DB::table('leads')
                ->select('assigned_to')
                ->where('id',$lastid)
                ->get();

    if (isset($subAdmin[0]) ) {
        $subadminId = $subAdmin[0]->assigned_to;
        $usersmsapikey = Getuserapikey($subadminId,3);
$leadContact = $request['mobile_no'];

$leadName = $request['name'];

  testsendwhatsappnew($usersmsapikey,$leadContact,str_replace("{Full Name}",$leadName,$message),$value->image);
      
    } else {
        testsendwhatsapp($request['mobile_no'],str_replace("{Full Name}",$request['name'],$message),$value->image);
 
    }
} else {
   testsendwhatsapp($request['mobile_no'],str_replace("{Full Name}",$request['name'],$message),$value->image);
  
//testsendwhatsappnew($usersmsapikey,$leadContact,str_replace("{Full Name}",$leadName,$message),$whatsappdata->image);
}
//				testsendwhatsapp($request['mobile_no'],str_replace("{Full Name}",$request['name'],$message),$value->image);
			}
		}
		
		
        // INSTANT SEND WHATSAPP (By Subrata Saha)
        /*
		$whatsappcounts = DB::table('sms_automations')
            ->select('sms_automations.user_id','sms_automations.series_name','sms_automations.automation_type','bulk_sms_automation_message.id','bulk_sms_automation_message.message','bulk_sms_automation_message.custom_full_name')
            ->join('bulk_sms_automation_message','bulk_sms_automation_message.series_id','=','sms_automations.id')
            ->where('sms_automations.automation_type', 3)
            ->where('sms_automations.campaigns_id', $request['campaign_id'])
            ->count();

        if($whatsappcounts > 0){
            $whatsappdatas = DB::table('sms_automations')
                ->select('sms_automations.user_id','sms_automations.series_name','sms_automations.automation_type','bulk_sms_automation_message.id','bulk_sms_automation_message.message','bulk_sms_automation_message.custom_full_name','bulk_sms_automation_message.image')
                ->join('bulk_sms_automation_message','bulk_sms_automation_message.series_id','=','sms_automations.id')
                ->where('sms_automations.automation_type', 3)
                ->where('sms_automations.campaigns_id', $request['campaign_id'])
                ->get();

            foreach($whatsappdatas as $row) {
                copytestsendwhatsappnew($request['mobile_no'],str_replace("{Full Name}",$request['name'],$row->message),$row->image);
            }
        }
		*/
$adminId = Auth::user()->id;
if($adminId == "8") {
$adminSettings = DB::table('admin_settings')
						 ->select('auto_assign_leads')
						 ->where('admin_id',Auth::user()->id)
						 ->get();
if (isset($adminSettings[0]) && $adminSettings[0]->auto_assign_leads == "1") { 
$this->leadAssign($projectid,$lastid);
}
}

		return redirect(route('leads-master'))->with('message', 'Leads Added Successfully!!');
    }

    /**
    * Leads Country after post
    *
    * @return true or false
    */
    public function leads_master_old(){

        $lead = Lead::where([['user_id',Auth::user()->id],['is_active',1]])->orderby('id', 'desc')->limit(500)->get();

        return view('leads-master',array("leaddata" => $lead));
    }


  public function leads_master_test(Request $request){
        if ($request->ajax()) {
            $data = Lead::where([['leads.user_id',Auth::user()->id],['leads.status',1]])->orderby('leads.id', 'desc')
             ->join('campaigns', 'leads.campaigns_id', '=', 'campaigns.id')
            ->select('campaigns.campaigns_name', 'leads.id', 'leads.name', 'leads.mail_id', 'leads.mobile_no', 'leads.project_name', 'leads.segment_name')
            ->get();
            //dd($data);

            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function($row){

                       // <a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a><a href="#">
                       $btn = '
                                <a href=edit-leads/'.$row->id.' >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
stroke-linecap="round" st$
                              </a><a href=delete-leads/'.$row->id.' onclick="return deleteConfirm()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
stroke-linecap="round" st$
                               </a>';

                        return $btn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

 
        $startDate = date("Y-m-d");
        $month = date('m');
        $year = date('Y');



  $data = Lead::select('id','created_at')->where('user_id',Auth::user()->id)->whereMonth('created_at',$month)->whereYear('created_at', $year)->limit(50)->orderBy('id','asc')->get();

        $ids = [];
        foreach($data as $key => $value) {
             $ids[] = $value->id;
        }
  
   return view('ajax-list-leads-testing',array('leads'=> $ids));
    }

	public function leads_master(Request $request){
/*
        if ($request->ajax()) {
            $data = Lead::where([['leads.user_id',Auth::user()->id],['leads.status',1]])->orderby('leads.id', 'desc')
             ->join('campaigns', 'leads.campaigns_id', '=', 'campaigns.id')
            ->select('campaigns.campaigns_name', 'leads.id', 'leads.name', 'leads.mail_id', 'leads.mobile_no', 'leads.project_name', 'leads.segment_name')
            ->get();
            //dd($data);

            return Datatables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function($row){

                       // <a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a><a href="#">
                       $btn = '
                                <a href=edit-leads/'.$row->id.' >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                              </a><a href=delete-leads/'.$row->id.' onclick="return deleteConfirm()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg>
                               </a>';

                        return $btn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }
*/

 $userPack = DB::table('users_pack')->where('users_id', '=', Auth::user()->id )->first();
                $starterPack = 0;
                $ids = [];
                if ((isset($userPack) && $userPack->pack == "1")) {
                      $starterPack = 1;
                        $startDate = date("Y-m-d");
        $month = date('m');
        $year = date('Y');

  $data = Lead::select('id','created_at')->where('user_id',Auth::user()->id)->whereMonth('created_at',$month)->whereYear('created_at', $year)->limit(50)->orderBy('id','asc')->get();

        
        foreach($data as $key => $value) {
             $ids[] = $value->id;
        }
 Lead::where('user_id',Auth::user()->id)->whereMonth('created_at',$month)->whereYear('created_at',$year)->whereNotIn('id',$ids)->update(array('leads_show' => 0));


                }
$campaigns = Campaign::where([['user_id', Auth::user()->id],['is_active',1]])->get();
$projects = Project::where([['user_id', Auth::user()->id]])->get();

        //return view('ajax-list-leads', array("campaigns"=>$campaigns));
        return view('ajax-list-leads',array('starterPack'=>$starterPack,'leads'=>$ids,'campaigns' => $campaigns,'projects' => $projects));
    }
	
    public function leads_assigned() {
		
	$staffFilter = array(
	    "status" => 1,
	    "usertype" => 3
	);
		
        $staff = User::where('admin_id',Auth::user()->id)->where($staffFilter)->get();
        
	$where1 = array('user_id',Auth::user()->id);
	//$where2 = array('status', 1);
	//$where3 = array('is_active',1);		
	$whereArray = array($where1);
	
	//$lead  = Lead::where($whereArray)->orderby('id',"desc")->get();
	$lead = array();
        return view('leads-assigned',array("leaddata" => $lead,"staffdata" => $staff));
    }
	
	
	
	public function leadsAssignedRetrival() {
		$offset = $_REQUEST['start'] ;
		
		$limit = $_REQUEST['length'];
		
		$search = json_encode($_REQUEST['search']);
		foreach( $_REQUEST['search'] as $key => $value) {
		   $search = trim($value);
		   break;
		}
		
		$staffFilter = array(
			"status" => 1,
			"usertype" => 3
		);

		$staff = User::where('admin_id',Auth::user()->id)->where($staffFilter)->get();
		
		$searchValue = $search;
		$search = strlen($search);
	
		$whereArray = [];
		$orWhereFilter = [];
		
		$where1 = array('leads.user_id','=',Auth::user()->id);
		$where2 = array('leads.status','=', "1");
		$where3 = array('leads.is_active','=',"1");
//$where3 = array();
		$whereArray = array($where1, $where2, $where3);

		$orWhereFilter1 = $orWhereFilter2 = $orWhereFilter3=  $orWhereFilter4= $orWhereFilter5= $orWhereFilter6= array();
		
		if(isset($_REQUEST['status']) && $_REQUEST['status'] >= 0)  {			
			$leadStatus = $_REQUEST['status'];
			$whereArray[] = array('lead_status','=',"$leadStatus");
		}	
		
		if(isset($_REQUEST['assignedTo']) &&  $_REQUEST['assignedTo'] > 0) {
			$assignedTo = $_REQUEST['assignedTo'];
			$whereArray[] = array('assigned_to','=',"$assignedTo");
		}				
		
		
		if ($search > 0) {
			$whereArray = array();				
			$orWhereFilter1 = array($where1, $where2, $where3);
			$orWhereFilter2 = array($where1, $where2, $where3);
			$orWhereFilter3 = array($where1, $where2, $where3);
			$orWhereFilter4 = array($where1, $where2, $where3);
			$orWhereFilter5 = array($where1, $where2, $where3);
			$orWhereFilter1[] = array('leads.mobile_no','like',"$searchValue%");
			$orWhereFilter2[] = array('leads.mail_id','like',"$searchValue%");			
			$orWhereFilter3[] = array('leads.name','like',"$searchValue%");
			$orWhereFilter4[] = array('leads.project_name','like',"$searchValue%");
			$orWhereFilter5[] = array('leads.segment_name','like',"$searchValue%");
		}

		$lead  = Lead::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)->orWhere($orWhereFilter3)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orderby('id','desc')->offset($offset)->limit($limit)->get();
		
		foreach($lead as $key1 => $value1) {
			foreach($staff as $key => $value) {
				if ($value1->assigned_to == $value->id) {
					$lead[$key1]->assignee = ucwords($value->name);
				} 
			}
		}
		
		//echo "<pre>";
	//lead assigend comment exist
$leadIds =[];
foreach($lead as $key => $value) {
$leadIds[] = $value->id;
}


   $leadAttended = DB::table('lead_comments')
                ->select('lead_id')
                ->distinct('lead_id')
                ->wherein('lead_id',$leadIds)
                ->get();
 $attendedLeads = [];

                if (null !== $leadAttended) {
                        foreach($leadAttended as $key => $value) {
                                        $attendedLeads[] = $value->lead_id;
                        }

                }


                $i =0 ;
                foreach($lead as $key => $value) {
                        $lead[$i]->isAttended = 0;
                        if (in_array($value->id,$attendedLeads)) {
                                $lead[$i]->isAttended = 1;
                        }
                        //if (null !== $lead[$i]->lead_assigned_on) {
                        //      $lead[$i]->lead_assigned_on = date("jS F, Y", strtotime($value->lead_assigned_on));
                        //}
                        $i++;
                }

// coment exist end
		
		$count = Lead::where($whereArray)->count();

		$filterCount =  Lead::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)->orWhere($orWhereFilter3)->count();
		
		$result = array("search" => $whereArray,"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => $filterCount);
		
		$result['data'] = $lead;
		
		print_r(json_encode($result));
	}
	
	
	public function view_leads_assigned(){
        $lead  = Lead::where('assigned_to',Auth::user()->id)->orderby('lead_assigned_on','desc')->get();
		$leadAttended = DB::table('lead_comments')
		->select('lead_id')
		->distinct('lead_id')
		->where('user_id',Auth::user()->id)
		->get();
		
		$attendedLeads = [];
		
		if (null !== $leadAttended) {
			foreach($leadAttended as $key => $value) {
					$attendedLeads[] = $value->lead_id;
			}
		}

        return view('view-assigned-leads',array("leaddata" => $lead,"attendedLeads" => $attendedLeads));
    }
	

    public function leads_assigned_staff(Request $request){
        // echo "<pre>";
	    
		//$leadIds = $_POST['lead_id'];
		$leadIds = explode(",",$_POST['lead-ids']);
		$staffId = $_POST['staff_id'];
		
		date_default_timezone_set("Asia/kolkata");
		$data = array(
			'assigned_to' => $staffId,
			'lead_assigned_on' => date("Y-m-d H:i:s") 
		);
		
		foreach($leadIds as $key => $value) {
			Lead::where('id', '=', $value)->update($data);
		}
		
		return redirect(route('leads-assigned'))->with('message', 'Leads Assigned Successfully!!');
    }


	public function setLeadStatus(Request $request){
        // echo "<pre>";
	    
		//$leadIds = $_POST['lead_id'];
		$leadIds = explode(",",$_POST['leadIds']);
		$status = $_POST['status'];
		
		foreach($leadIds as $key => $value) {
			Lead::where('id', '=', $value)->update(array('lead_status' => $status));
		}
		echo "success";
    }
    /**
    * Add Category master
    *
    * @return true or false
    */
    public function add_import_leads(){

        $data["projects"] = Project::where('user_id',Auth::user()->id)->get();
        $data["segments"] = Segment::where('user_id',Auth::user()->id)->get();
        //$data["campaigns"] = Campaign::where('user_id',Auth::user()->id)->get();
		$data["campaigns"] = Campaign::where([['user_id', Auth::user()->id],['is_active',1]])->get();

        return view("add-import-leads",$data);
    }

    function add_import_leads_post_data(Request $request)
    {
		
		
        $this->validate($request, [
            //'leads'  => 'required|mimes:xls,xlsx'
        ]);

        if($request->project_type == 2){ // FOR NEW
            $project = new Project();
            $project->user_id      = Auth::user()->id;
            $project->project_name = $request['project_name'];
            $project->for_source   = 'Facebook';
            $project->status       = 1;
            $project->save();
            $projectid   = $project->id;
            $projectname =  $request['project_name'];
        }else{
            $projectid   = $request['project_id'];
            $projectname = Project::where('id',$projectid)->first()->project_name;
        }

        if($request->segment_type == 2){ // FOR NEW
            $segment = new Segment();
            $segment->user_id      = Auth::user()->id;
            $segment->segment_name = $request['segment_name'];
            $segment->for_source   = 'Facebook';
            $segment->status       = 1;
            $segment->save();
            $segmentid   = $segment->id;
            $segmentname = $request['segment_name'];
        }else{
            $segmentid   = $request['segment_id'];
            $segmentname = Segment::where('id',$segmentid)->first()->segment_name;
        }

        $lead = request()->file('leads');
		
        $File = $lead;
        $arrResult = array();
	

        $handle = fopen($File, "r");

        if (empty($handle) === false) {
            $x=0; $y = 0;
            $count = 0;

	    $leadContactNos = [];
	    $brrResult = [];

	    /*
	     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
 		
		 $brrResult[] = $data;
		 $leadContactNos[] =  substr($brrResult[$y][2],-10);
		$y++;
	    }
*/
	    $leadExist = DB::table('leads')->select('mobile_no')->whereIn('mobile_no',$leadContactNos)->where('campaigns_id',$request['campaign_id'])->count();

	    if ($leadExist != 0) {

		$duplicateContact = "";
		$i=0;
		foreach($leadExist as $key  => $value) {
			$nos = [];
			if ($i > 0) {
				if (!in_array($value->mobile_no,$nos)) {
				  $duplicateContact .= ",".$value->mobile_no;
				}
				$nos[] = $value->mobile_no;
			} else {
				$duplicateContact = $value->mobile_no;
				$nos[] = $value->mobile_no;
				break;
 			} 
			$i++;
		}
  		return redirect(route('add-import-leads'))->with('message', 'Lead Contacts '.$duplicateContact.' already exist!!');

	    }
	

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $arrResult[] = $data;

                if ($count > 0) {
                    $lead = new Lead();

                    $lead->user_id               = Auth::user()->id;

                    $lead->project_id            = $projectid;
                    $lead->project_type          = $request->project_type;
                    $lead->project_name          = $projectname;

                    $lead->segment_id            = $segmentid;
                    $lead->segment_type          = $request->segment_type;
                    $lead->segment_name          = $segmentname;

                    $lead->campaigns_id          = $request['campaign_id'];

                    $lead->name                  = $arrResult[$x][0];
                    $lead->mail_id               = $arrResult[$x][1];
                    $lead->mobile_no             = $arrResult[$x][2];
                    
                    $lead->country               = isset($arrResult[$x][3]) ? $arrResult[$x][3] : null;
                     
		    $lead->state                 = isset($arrResult[$x][4]) ? $arrResult[$x][4] : null;
                    $lead->city                  = isset($arrResult[$x][5]) ? $arrResult[$x][5] : null;
                    $lead->zipcode               = isset($arrResult[$x][6]) ? $arrResult[$x][6] : null;
                    $lead->company               = isset($arrResult[$x][7]) ? $arrResult[$x][7] : null;
                    $lead->position              = isset($arrResult[$x][8]) ? $arrResult[$x][8] : null;
                    $lead->address1              = isset($arrResult[$x][9]) ? $arrResult[$x][9] : null;
                    $lead->address2              = isset($arrResult[$x][10]) ? $arrResult[$x][10] : null;
                    
		    $lead->source                = 'Upload';
                    $lead->status                = 1;

		    date_default_timezone_set("Asia/kolkata");

		    $lead->campaign_activated_on =  date('Y-m-d H:i:s');

                    $lead->save();
                    $lastid = $lead->id;

                    // ADD TEMP CORN DATA TABLE
                    $cautomationcount = DB::table('automations')
                        ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
                        ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                        ->where('automations.campaign_id', $request['campaign_id'])
                        ->where('sms_automation_messages.delivery_type', 'scheduled')
                        ->count();

                    if($cautomationcount > 0){
                        $cautomation = DB::table('automations')
                            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image','sms_automation_messages.is_active')
                            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                            ->where('automations.campaign_id', $request['campaign_id'])
                            ->where('sms_automation_messages.delivery_type', 'scheduled')
                            ->get();

                        foreach($cautomation as $row){

                            if($row->delivery_day == 0){ //Today
                                $newdatetime = date('Y-m-d '.$row->delivery_time);
                            }else{ //After Day
                                $newdatetime = new DateTime(date('Y-m-d '.$row->delivery_time).' + '.$row->delivery_day.' day');
                            }


//if (strttotime$newdatetime > strtotime("now")) {
                            $leadcorn = new LeadcornCampaigns();
                            $leadcorn->user_id               = Auth::user()->id;
                            $leadcorn->campaign_id           = $request['campaign_id'];
                            $leadcorn->lead_id               = $lastid;
                            $leadcorn->automation_messages_id= $row->id;

                            $leadcorn->name                  = $arrResult[$x][0];
                            $leadcorn->mail_id               = $arrResult[$x][1];
                            $leadcorn->mobile_no             = $arrResult[$x][2];

                            $leadcorn->automation_type       = $row->automation_type;

                            $leadcorn->delivery_date_time    = $newdatetime;
                            $leadcorn->message               = $row->message;
                            $leadcorn->image                 = $row->image;
                            $leadcorn->status                = 2;

							$leadDetail = new LeadDetails();					
							$leadDetail->lead_id               = $lastid;
							$leadDetail->automation_messages_id= $row->id;
							$leadDetail->delivery_date_time    = $newdatetime;      
							              
							if ($row->is_active == "0") {
								$leadcorn->is_stopped = "1";
								$leadcorn->stopped_reason = "Event is in off state on lead creation";
								$leadDetail->is_cancelled = "1";
								$leadDetail->failure_reason = "Event is in off state on lead creation";
							}

							$leadcorn->save();
							$leadDetail->save();
                          //  $leadcorn->save();
/*} else {

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

}*/
                        }
                    }

                    // SEND MESSAGE
                    //DB::enableQueryLog();
                    $smscount = DB::table('automations')
                        ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                        ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                        ->where('automations.automation_type', 1)
                        ->where('automations.campaign_id', $request['campaign_id'])
                        ->where('sms_automation_messages.delivery_type', 'initial')
						->where('sms_automation_messages.is_active', 1)
                        ->count();

                    if($smscount > 0){
                        //DB::enableQueryLog();
                        $smsdata = DB::table('automations')
                            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                            ->where('automations.automation_type', 1)
                            ->where('automations.campaign_id', $request['campaign_id'])
                            ->where('sms_automation_messages.delivery_type', 'initial')
							->where('sms_automation_messages.is_active', 1)
                            ->first();
						$message = isset($smsdata->message) ? $smsdata->message : "";
                        //echo str_replace("{Full Name}",$request['name'],$smsdata->message); die;
                        testsendsms($arrResult[$x][2],str_replace("{Full Name}",$arrResult[$x][0],$message));
                        //$query = DB::getQueryLog();
                        //echo "<pre>"; print_r($query);die;
                    }

                    // SEND EMAIL
                    $emailcount = DB::table('automations')
                        ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                        ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                        ->where('automations.automation_type', 2)
                        ->where('automations.campaign_id', $request['campaign_id'])
                        ->where('sms_automation_messages.delivery_type', 'initial')
						->where('sms_automation_messages.is_active', 1)
                        ->count();

                    if($emailcount > 0){
                        $emaildata = DB::table('automations')
                            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                            ->where('automations.automation_type', 2)
                            ->where('automations.campaign_id', $request['campaign_id'])
                            ->where('sms_automation_messages.delivery_type', 'initial')
							->where('sms_automation_messages.is_active', 1)
                            ->first();
						$message = isset($emaildata->message) ? $emaildata->message : "";
                        testsendemail($arrResult[$x][1],str_replace("{Full Name}",$arrResult[$x][0],$message));
                    }

                    // SEND WHATSAPP
                    $whatsappcount = DB::table('automations')
                        ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                        ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                        ->where('automations.automation_type', 3)
                        ->where('automations.campaign_id', $request['campaign_id'])
                        ->where('sms_automation_messages.delivery_type', 'initial')
						->where('sms_automation_messages.is_active', 1)
                        ->count();

                    if($whatsappcount > 0){
                        $whatsappdata = DB::table('automations')
                            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
                            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                            ->where('automations.automation_type', 3)
                            ->where('automations.campaign_id', $request['campaign_id'])
                            ->where('sms_automation_messages.delivery_type', 'initial')
							->where('sms_automation_messages.is_active', 1)
                            ->first();
						$message = isset($whatsappdata->message) ? $whatsappdata->message : "";

                        testsendwhatsapp($arrResult[$x][2],str_replace("{Full Name}",$arrResult[$x][0],$message),$whatsappdata->image);
                    }

                    // INSTANT SEND WHATSAPP (By Subrata Saha)
                    /*
					$whatsappcountss = DB::table('sms_automations')
                        ->select('sms_automations.user_id','sms_automations.series_name','sms_automations.automation_type','bulk_sms_automation_message.id','bulk_sms_automation_message.message','bulk_sms_automation_message.custom_full_name')
                        ->join('bulk_sms_automation_message','bulk_sms_automation_message.fu','=','sms_automations.id')
                        ->where('sms_automations.automation_type', 3)
                        ->where('sms_automations.campaigns_id', $request['campaign_id'])
                        ->count();

                    if($whatsappcountss > 0){
                        $whatsappdatass = DB::table('sms_automations')
                            ->select('sms_automations.user_id','sms_automations.series_name','sms_automations.automation_type','bulk_sms_automation_message.id','bulk_sms_automation_message.message','bulk_sms_automation_message.custom_full_name','bulk_sms_automation_message.image')
                            ->join('bulk_sms_automation_message','bulk_sms_automation_message.series_id','=','sms_automations.id')
                            ->where('sms_automations.automation_type', 3)
                            ->where('sms_automations.campaigns_id', $request['campaign_id'])
                            ->get();

                        foreach ($whatsappdatass as $rows)  {
                            copynewtestsendwhatsapp($arrResult[$x][2],str_replace("{Full Name}",$arrResult[$x][0],$rows->message),$rows->image);
                        }
                    }
					*/
                }
                $count++;
                $x++;
            }
            fclose($handle);
        }

        //Excel::import(new LeadsImport, $request->file('leads')->store('temp'));
        //return back();

		Session::flash('message', "Leads Uploaded successfully.");

		return redirect('add-import-leads');
    }
    /**
    * Edit LEADS view page
    *
    * @return true or false
    */
    public function edit_leads(Request $request, $leadsid){

        $data["projects"] = Project::where('user_id',Auth::user()->id)->get();
        $data["segments"] = Segment::where('user_id',Auth::user()->id)->get();
        //$data["campaigns"] = Campaign::where('user_id',Auth::user()->id)->get();
		$data["campaigns"] = Campaign::where([['user_id', Auth::user()->id],['is_active',1]])->get();
        $data["leadsdata"] = Lead::where('id',$leadsid)->get()[0];

        return view('edit-leads',$data);
    }

    /**
    * Edit country after post
    *
    * @return true or false
    */
    public function edit_leads_post(Request $request, $leadsid){

        $this->validate(request(),[
            'name'   => 'required',
        ]);

        if($request->project_type == 2){ // FOR NEW
            if(Segment::where('segment_name',$request['segment_name'])->count() > 0){
                $projectid = $request['project_id'];
                $projectname =  $request['project_name'];
            }else{
                $project = new Project();
                $project->user_id      = Auth::user()->id;
                $project->project_name = $request['project_name'];
                $project->for_source   = 'Facebook';
                $project->status       = 1;
                $project->save();
                $projectid   = $project->id;
                $projectname =  $request['project_name'];
           }

        }else{
            $projectid = $request['project_id'];
            $projectname = Project::where('id',$projectid)->first()->project_name;
        }

        if($request->segment_type == 2){ // FOR NEW

            if(Segment::where('segment_name',$request['segment_name'])->count() > 0){
                $segmentid   = $request['segment_id'];
                $segmentname = $request['segment_name'];
            }else{
                $segment = new Segment();
                $segment->user_id      = Auth::user()->id;
                $segment->segment_name = $request['segment_name'];
                $segment->for_source   = 'Facebook';
                $segment->status       = 1;
                $segment->save();
                $segmentid   = $segment->id;
                $segmentname = $request['segment_name'];
            }

        }else{
            $segmentid   = $request['segment_id'];
            $segmentname = Segment::where('id',$segmentid)->first()->segment_name;
        }
		
		$oldCampaignId = $request['old_campaign_id'];
		
        $data = array(
            'project_id'                  => $projectid,
            'project_type'                => $request->project_type,
            'project_name'                => $projectname,

            'segment_id'                  => $segmentid,
            'segment_type'                => $request->segment_type,
            'segment_name'                => $segmentname,

            'campaigns_id'                => $request['campaign_id'],

            'name'                        => $request['name'],
            'mail_id'                     => $request['mail_id'],
            'mobile_no'                   => $request['mobile_no'],
            'state'                       => $request['state'],
            'city'                        => $request['city'],
            'zipcode'                     => $request['zipcode'],
            'company'                     => $request['company'],
            'position'                    => $request['position'],
            'address1'                    => $request['address1'],
            'address2'                    => $request['address2'],
        );

		if ($oldCampaignId !== $data['campaigns_id']) {
date_default_timezone_set("Asia/kolkata");
$data['campaign_activated_on'] = date("Y-m-d H:i:s");
			//$data['campaign_activated_on'] = new DateTime(date('Y-m-d H:i:s'));
		}
		
		Lead::where('id', $leadsid)->update($data);
		
		if ($oldCampaignId !== $data['campaigns_id']) {
			$leadCronFilter = array('lead_id'=> $leadsid,'campaign_id' => $oldCampaignId);
			
		    $leadCronAffectedRows = LeadcornCampaigns::where($leadCronFilter)->update(array('is_stopped' => 1,'reason' => 'Campaign Changed'));
			$this->sendInstantCampaign_AddCron($leadsid, $data);
		}
		
		
        Session::flash('message', "Leads Updated successfully.");
		return redirect('leads-master');
        //return redirect('edit-leads/'.$leadsid);
    }
	
	public function addLeadComment() {
		if (strlen(trim($_POST['comment'])) > 0) {
			$leadComment = new LeadComments();
			$leadComment->user_id = Auth::user()->id;
			$leadComment->lead_id = $_POST['leadId'];
			$leadComment->comments = $_POST['comment'];
			$leadComment->status = isset($_POST['leadStatus']) ? $_POST['leadStatus'] : "0";
			//$leadComment->followup_date = strlen($_POST['followupDate']);
			//$leadComment->followup_time = null !== $_POST['followupTime'] ? $_POST['followupTime'] : null;		
			$leadComment->save();	
			$commentId = $leadComment->id;
		}
		
		if (isset($_POST['source']) && $_POST['source'] == "followup") {
			//$status = $_POST['leadStatus'];
			LeadFollowups::where('lead_id', '=', $_POST['leadId'])->update(array('status' => "1"));
		}
		if ( isset($_POST['followupDate']) && strlen($_POST['followupDate']) > 0) {
			$leadFolloup = new LeadFollowups();
			$leadFolloup->user_id = Auth::user()->id;
			$leadFolloup->lead_id = $_POST['leadId'];
			$leadFolloup->followup_date = $_POST['followupDate'];
			$leadFolloup->followup_time = $_POST['followupTime'];
			$leadFolloup->lead_comments_id = isset($commentId) ? $commentId : 0;
			$leadFolloup->save();
		}
		
		if (!isset($_POST['source']) && (isset($_POST['leadStatus']) && $_POST['leadStatus'] != "0")) {
			$leadStatus = new LeadStatus();
			$leadStatus->user_id = Auth::user()->id;
			$leadStatus->lead_id = $_POST['leadId'];
			$leadStatus->status = $_POST['leadStatus'];
			$leadStatus->save();
		}
		Lead::where('id', '=', $_POST['leadId'])->update(array('lead_status' => $_POST['leadStatus']));
		echo "success";					
	}
		
	public function addLeadFollowUp() {
		$leadFolloup = new LeadFollowups();
		$leadFolloup->user_id = Auth::user()->id;
		$leadFolloup->lead_id = $_POST['leadId'];
		$leadFolloup->followup_date = $_POST['followUpDate'];
		$leadFolloup->followup_time = $_POST['followUpTime'];
		$leadFolloup->comments = $_POST['comments'];
		$leadFolloup->save();
		
		if ($_POST['status'] != "0") {
			$leadStatus = new LeadStatus();
			$leadStatus->user_id = Auth::user()->id;
			$leadStatus->lead_id = $_POST['leadId'];
			$leadStatus->status = $_POST['status'];
			$leadStatus->save();
		}	
			
		echo $leadFolloup->id;					
	}
	
	
	public function viewLeadFolloups(Request $request) {
		
		//$lead  = Lead::where('assigned_to',Auth::user()->id)->get();
		//$pendingFollowup = array();
		//$pendingFollowup = array('leads_followups.status','=',null);
		
		/*if ((null !== $request->segment(2)) && $request->segment(2) == "pending") {
			$pendingFollowup = array('leads_followups.status','=',null);
		}*/
        //return view('view-assigned-leads',array("leaddata" => $lead));
		//$currentdate = date('Y-m-d');
		//echo $currentdate; die;
		/*$leadFollowups = DB::table('leads_followups')
		->select('leads_followups.*','leads.name','leads.mobile_no','leads.project_name')
		->join('leads','leads.id','=','leads_followups.lead_id')
		->where('leads_followups.user_id', Auth::user()->id)
		->where('leads_followups.followup_date','>=',$currentdate)
		->where(array($pendingFollowup))
		->where('leads.is_active', 1)
		->orderby('leads_followups.followup_date','asc')
		->get();
          
		return view('view-leads-followup',array("leadFollowups" => $leadFollowups));
*/

 return view('view-leads-followup');
	}
	
	public function getLeadComments() {
		$leadId = $_POST['leadId'];
		$comments = LeadComments::where('lead_id',$leadId)->get();
		$lead = Lead::where('id', '=', $leadId)->select("lead_status","mobile_no","mail_id","leads_show")->get()[0];
		return json_encode(array('show' => $lead->leads_show,'data'=>$comments,'status' => $lead->lead_status,'leadContact' => $lead->mobile_no,'leadEmail'=> $lead->mail_id ));
	}

	public function closeLead() {
		$leadId = $_POST['leadId'];
		Lead::where('id', '=', $leadId)->update(array('status' => 2));
			//DB::table('lead_corn_campaigns')->where('lead_id',$leadsid)->delete();
			            
	}
    /**
    * DELETE Category after get url
    *
    * @return true or false
    */
    public function delete_leads(Request $request, $leadsid){

        $data = Lead::where('id',$leadsid)->first();
        if(!empty($data)){
            //$delete = Lead::where('id',$leadsid)->delete();
			 Lead::where('id', '=', $leadsid)->update(array('is_active' => 0));
			//DB::table('lead_corn_campaigns')->where('lead_id',$leadsid)->delete();
			LeadcornCampaigns::where('lead_id', '=', $leadsid)->update(array('is_stopped' => 1,'stopped_reason' => "Lead Deleted"));
            Session::flash('message', "Leads Records Deleted Successfully.");
            return redirect('leads-master');
        }else{
            Session::flash('message', "Leads Records not found.");
            return redirect('leads-master');
        }
    }
	
	public function deleteLeadsFromCron() {
		$cronData = DB::table('lead_corn_campaigns')->select('lead_id')->distinct('lead_id')->get();
		
		foreach($cronData as $key => $value) {
			$leadExist = DB::table('leads')->where('id',$value->lead_id)->count();
			//echo $leadExist; die;
			$count =  0;
			if (!$leadExist) {
				DB::table('lead_corn_campaigns')->where('lead_id',$value->lead_id)->delete();
				$count++;
			} 
			
		}
		echo $count." Rows Deleted Successfully";
	}
	
	public function deleteStoppedCron() {
		$currentdatetime = date('Y-m-d H:i');
		$data = DB::table('lead_corn_campaigns')->select('id','delivery_date_time')->get();
		$count = 0;
		
		foreach($data as $row){

			$deliverydate = date('Y-m-d H:i',strtotime($row->delivery_date_time));

			if($deliverydate < $currentdatetime){
				DB::table('lead_corn_campaigns')->where('id',$row->id)->delete();
				$count++;
			}
		}
		echo $count." Rows Deleted Successfully";		
	}

	public function sendInstantCampaign_AddCron($leadId, $data) {
		 $cautomationcount = DB::table('automations')
            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.campaign_id', $data['campaigns_id'])
            ->where('sms_automation_messages.delivery_type', 'scheduled')
            ->count();

        if($cautomationcount > 0){
            $cautomation = DB::table('automations')
                ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.campaign_id', $data['campaigns_id'])
                ->where('sms_automation_messages.delivery_type', 'scheduled')
                ->get();

            foreach($cautomation as $row){

                if($row->delivery_day == 0){ //Today
                    $newdatetime = date('Y-m-d '.$row->delivery_time);
                }else{ //After Day
                    $newdatetime = new DateTime(date('Y-m-d '.$row->delivery_time).' + '.$row->delivery_day.' day');
                }

                $leadcorn = new LeadcornCampaigns();
                $leadcorn->user_id                = Auth::user()->id;
                $leadcorn->campaign_id            = $data['campaigns_id'];
                $leadcorn->lead_id                = $leadId;
                $leadcorn->automation_messages_id = $row->id;

                $leadcorn->name                   = $data['name'];
                $leadcorn->mail_id                = $data['mail_id'];
                $leadcorn->mobile_no              = $data['mobile_no'];

                $leadcorn->automation_type        = $row->automation_type;
                $leadcorn->delivery_date_time     = $newdatetime;
                $leadcorn->message                = $row->message;
                $leadcorn->image                  = $row->image;
                $leadcorn->status                 = 2;
                $leadcorn->save();
				/*
				$leadReport = new LeadReports();
                $leadReport->user_id                = Auth::user()->id;
                $leadReport->campaign_id            = $data['campaigns_id'];
                $leadReport->lead_id                = $leadId;
                $leadReport->automation_messages_id = $row->id;

                $leadReport->name                   = $data['name'];
                $leadReport->mail_id                = $data['mail_id'];
                $leadReport->mobile_no              = $data['mobile_no'];

                $leadReport->automation_type        = $row->automation_type;
                $leadReport->delivery_date_time     = $newdatetime;
                $leadReport->message                = $row->message;
                $leadReport->image                  = $row->image;
                $leadReport->status                 = 2;
                $leadReport->save();
				*/
            }
        }

        // SEND MESSAGE
        $smscount = DB::table('automations')
            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.automation_type', 1)
            ->where('automations.campaign_id', $data['campaigns_id'])
            ->where('sms_automation_messages.delivery_type', 'initial')
			->where('sms_automation_messages.is_active', 1)
            ->count();

        if($smscount > 0){
            $smsdata = DB::table('automations')
                ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.automation_type', 1)
                ->where('automations.campaign_id', $data['campaigns_id'])
                ->where('sms_automation_messages.delivery_type', 'initial')
				->where('sms_automation_messages.is_active', 1)
                ->first();
			 $message = isset($smsdata->message) ? $smsdata->message : "";
            testsendsms($data['mobile_no'],str_replace("{Full Name}",$data['name'],$message));
        }

        // SEND EMAIL
        $emailcount = DB::table('automations')
            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.automation_type', 2)
            ->where('automations.campaign_id', $data['campaigns_id'])
            ->where('sms_automation_messages.delivery_type', 'initial')
			->where('sms_automation_messages.is_active', 1)
            ->count();

        if($emailcount > 0){
            $emaildata = DB::table('automations')
                ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.automation_type', 2)
                ->where('automations.campaign_id', $data['campaigns_id'])
                ->where('sms_automation_messages.delivery_type', 'initial')
				->where('sms_automation_messages.is_active', 1)
                ->first();
			$message = isset($emaildata->message) ? $emaildata->message : "";

            testsendemail($data['mail_id'],str_replace("{Full Name}",$data['name'],$message));
        }

        // SEND WHATSAPP
        $whatsappcount = DB::table('automations')
            ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
            ->where('automations.automation_type', 3)
            ->where('automations.campaign_id', $data['campaigns_id'])
            ->where('sms_automation_messages.delivery_type', 'initial')
			->where('sms_automation_messages.is_active', 1)
            ->count();

        if($whatsappcount > 0){
            $whatsappdata = DB::table('automations')
                ->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
                ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                ->where('automations.automation_type', 3)
                ->where('automations.campaign_id', $data['campaigns_id'])
                ->where('sms_automation_messages.delivery_type', 'initial')
				->where('sms_automation_messages.is_active', 1)
                ->first();
			$message = isset($whatsappdata->message) ? $whatsappdata->message : "";
            testsendwhatsapp($data['mobile_no'],str_replace("{Full Name}",$data['name'],$message),$whatsappdata->image);
        }

        // INSTANT SEND WHATSAPP (By Subrata Saha)
		/*
        $whatsappcounts = DB::table('sms_automations')
            ->select('sms_automations.user_id','sms_automations.series_name','sms_automations.automation_type','bulk_sms_automation_message.id','bulk_sms_automation_message.message','bulk_sms_automation_message.custom_full_name')
            ->join('bulk_sms_automation_message','bulk_sms_automation_message.series_id','=','sms_automations.id')
            ->where('sms_automations.automation_type', 3)
            ->where('sms_automations.campaigns_id', $request['campaign_id'])
            ->count();

        if($whatsappcounts > 0){
            $whatsappdatas = DB::table('sms_automations')
                ->select('sms_automations.user_id','sms_automations.series_name','sms_automations.automation_type','bulk_sms_automation_message.id','bulk_sms_automation_message.message','bulk_sms_automation_message.custom_full_name','bulk_sms_automation_message.image')
                ->join('bulk_sms_automation_message','bulk_sms_automation_message.series_id','=','sms_automations.id')
                ->where('sms_automations.automation_type', 3)
                ->where('sms_automations.campaigns_id', $request['campaign_id'])
                ->get();

            foreach($whatsappdatas as $row) {
                copytestsendwhatsappnew($request['mobile_no'],str_replace("{Full Name}",$request['name'],$row->message),$row->image);
            }
        }
		*/
	}
	
	public function leadsView(Request $request) {
		//return view("leads-view");

		 $userPack = DB::table('users_pack')->where('users_id', '=', Auth::user()->id )->first();
		$starterPack = 0;
		$leads = [];
                
		if ((isset($userPack) && $userPack->pack == "1")) {
	   	        $starterPack = 1;		
  			$startDate = date("Y-m-d");
        		$month = date('m');
        		$year = date('Y');

  			$data = Lead::select('id','created_at')->where('user_id',Auth::user()->id)->whereMonth('created_at',$month)->whereYear('created_at', $year)->limit(50)->orderBy('id','asc')->get();

       			$ids = [];
        	
			foreach($data as $key => $value) {
             			$ids[] = $value->id;
        		}
print_r($ids);die;
			Lead::where('user_id',Auth::user()->id)->whereMonth('created_at',$month)->whereYear('created_at',$year)->whereNotIn('id',$ids)->update(array('leads_show' => 0));
		

		}
		
		return view("ajax-list-leads",array('starterPack'=>$starterPack,'leads' => $ids)); 	
	}

	public function leadsRetrival(Request $request) {		
		//$lead = Lead::where([['user_id',Auth::user()->id],['is_active',1]])->orderby('id', 'desc')->limit(10)->get();

		$offset = $_REQUEST['start'] ;
		
		$limit = $_REQUEST['length'];
		
		$userPack = DB::table('users_pack')->where('users_id', '=', Auth::user()->id )->first();

		if ($offset ==  "70" && (isset($userPack) && $userPack->pack == "1")) {
			$limit = 5;
		}

		$search = json_encode($_REQUEST['search']);
		foreach( $_REQUEST['search'] as $key => $value) {
		   $search = trim($value);
		   break;
		}
	
		$searchValue = $search;

        	$search = strlen($search);
	
		$whereArray = [];
		$orWhereFilter = [];
		$where1 = array('leads.user_id','=',Auth::user()->id);
		$where2 = array('leads.status','=', "1");
		$where3 = array('leads.is_active','=',"1");

		$whereArray = array($where1, $where2, $where3);

			$orWhereFilter1 = $orWhereFilter2 = $orWhereFilter3=  $orWhereFilter4= $orWhereFilter5= $orWhereFilter6= array();
    			
			if ($search > 0) {
				$whereArray = array();
				//$whereArray[] = array('leads.name','like',"$searchValue%");
				//$whereArray[] = $where4;
                		$orWhereFilter1 = array($where1, $where2, $where3);
				$orWhereFilter2 = array($where1, $where2, $where3);
				$orWhereFilter3 = array($where1, $where2, $where3);
				$orWhereFilter4 = array($where1, $where2, $where3);
				$orWhereFilter5 = array($where1, $where2, $where3);
				$orWhereFilter6 = array($where1, $where2, $where3);

				$orWhereFilter1[] = array('leads.mobile_no','like',"$searchValue%");
   				$orWhereFilter2[] = array('leads.mail_id','like',"$searchValue%");
				$orWhereFilter3[] = array('campaigns.campaigns_name','like',"$searchValue%");
				$orWhereFilter4[] = array('leads.name','like',"$searchValue%");
				$orWhereFilter5[] = array('leads.project_name','like',"$searchValue%");
				$orWhereFilter6[] = array('leads.segment_name','like',"$searchValue%");
            }

			if(isset($_REQUEST['source']) && $_REQUEST['source'] == "social") {
				if ($_REQUEST['sourceVal'] == "1") {
					//$orWhereFilter1[] = array('source','=',"Facebook");
					$whereArray[] = array('source','=',"Facebook");
				} 

				if ($_REQUEST['sourceVal'] == "3") {
                    //$orWhereFilter1[] = array('source','=',"FacebookTraffic");
                    $whereArray[] = array('source','=',"FacebookTraffic");
                }

				if ($_REQUEST['sourceVal'] == "4") {
                    //$orWhereFilter1[] = array('source','=',"Google");
                    $whereArray[] = array('source','=',"Google");
                }

				if ($_REQUEST['sourceVal'] == "5") {
                    //$orWhereFilter1[] = array('source','=',"GoogleAds_PPC");
                    $whereArray[] = array('source','=',"GoogleAds_PPC");
                }
				
				if ($_REQUEST['sourceVal'] == "6") {
                    //$orWhereFilter1[] = array('source','=',"Organic");
                    $whereArray[] = array('source','=',"Organic");
                }


		 if ($_REQUEST['sourceVal'] == "7") {
                    //$orWhereFilter1[] = array('source','=',"Organic");
                    $whereArray[] = array('source','=',"Wordpress");
                }

			}
	
			if(isset($_REQUEST['source']) && $_REQUEST['source'] == "leads-on") {
				$value = $_REQUEST['sourceVal'];
				$whereArray[] = array('leads.created_at','like',"$value%");
			}

		  if(isset($_REQUEST['source']) && $_REQUEST['source'] == "project") {
                $value = $_REQUEST['sourceVal'];
                $whereArray[] = array('leads.project_id','=',"$value");
            }			

            $lead = Lead::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)->orWhere($orWhereFilter3)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter6)
            ->join('campaigns', 'leads.campaigns_id', '=', 'campaigns.id')
            ->select('leads.created_at','leads.is_cron_disabled','campaigns.campaigns_name', 'leads.id', 'leads.name', 'leads.mail_id', 'leads.mobile_no', 'leads.project_name', 'leads.segment_name','leads.source','leads.leads_show')
            ->orderby('leads.id','desc')
            ->offset($offset)
            ->limit($limit)
            ->get();
		
	
		/*
                $lead = Lead::where([['leads.user_id',Auth::user()->id],['leads.status',1]])->orderby('leads.id', 'desc')
                ->join('campaigns', 'leads.campaigns_id', '=', 'campaigns.id')
                ->select('campaigns.campaigns_name', 'leads.id', 'leads.name', 'leads.mail_id', 'leads.mobile_no', 'leads.project_name', 'leads.segment_name')
                ->offset($offset)
                ->limit($limit)
                ->get();
		*/


/*
		$search = json_encode($_REQUEST['search']);
		
		foreach( $_REQUEST['search'] as $key => $value) {
		   $search = trim($value);
                   break;		
		}
*/		
		//$search = strlen($search);
		
		$whereArray1 = array($where1, $where2, $where3);
		
        $count = Lead::where($whereArray1)->count();

		$filterCount =  Lead::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)->orWhere($orWhereFilter3)->join('campaigns', 'leads.campaigns_id', '=', 'campaigns.id')->count();
		
		if ((isset($userPack) && $userPack->pack == "1") && $filterCount > 50) {

		$lead =Lead::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)->orWhere($orWhereFilter3)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter6)
                ->join('campaigns', 'leads.campaigns_id', '=', 'campaigns.id')
                ->select('leads.created_at','leads.is_cron_disabled','campaigns.campaigns_name', 'leads.id', 'leads.name', 'leads.mail_id', 'leads.mobile_no', 'leads.project_name',
                    'leads.segment_name','leads.source','leads.leads_show')
                ->orderby('leads.id','asc')
                ->offset($offset)
                ->limit($limit)
                ->get();

		//$filterCount = 50;

		}

//attended leads count


$leadIds =[];
foreach($lead as $key => $value) {
$leadIds[] = $value->id;
}


   $leadAttended = DB::table('lead_comments')
                ->select('lead_id')
                ->distinct('lead_id')
                ->wherein('lead_id',$leadIds)
                ->get();
 $attendedLeads = [];

                if (null !== $leadAttended) {
                        foreach($leadAttended as $key => $value) {
                                        $attendedLeads[] = $value->lead_id;
                        }

                }


 $i =0 ;
                foreach($lead as $key => $value) {
                        $lead[$i]->isAttended = 0;
                        if (in_array($value->id,$attendedLeads)) {
                                $lead[$i]->isAttended = 1;
                        }
                        
                        $i++;
  

              }	
                


//attended leads count end
		$result = array("search" => $whereArray,"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => $filterCount);
		$result['data'] = $lead;
		
		print_r(json_encode($result));
	}

	public function subscribePage(Request $request) {

		$pageName = $_POST['pageName'];
		
		$pageId = $_POST['pageId'];
			
		$formId = $_POST['formId'];
		
		$userId = Auth::user()->id;
		
		$pageToken = $_POST['pageToken'];

		$campaignId=$_POST['campaignId'];

		$segmentId = $_POST['segmentId'];

		$projectId = $_POST['projectId'];
	
		if($_POST['projectName'] != "0") {
			$project = new Project();
			$project->user_id      = Auth::user()->id;
			$project->project_name = $_POST['projectName'];
			$project->for_source   = 'Facebook';
			$project->status       = 1;
			$project->save();
			$projectId   = $project->id;
		}
	
		if($_POST['segmentName'] != "0") {
			$segment = new Segment();
			$segment->user_id      = Auth::user()->id;
			$segment->segment_name = $_POST['segmentName'];
			$segment->for_source   = 'Facebook';
			$segment->status       = 1;
			$segment->save();
			$segmentId   = $segment->id;
		}

		$ch = curl_init('https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=3312603435483167&client_secret=ab4719b6fd51d77b5b639ffdc538a2bd&fb_exchange_token='.$pageToken);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$data = curl_exec($ch);

		curl_close($ch);

		$data = json_decode($data,true);

		$pageToken = isset($data['access_token']) ? $data['access_token'] : $pageToken;

		$formName = $_POST['formName'];

		$pageSubscribed = DB::table('subscribe_pages')
		->where('page_id', '=', $pageId)
		->where('form_id', '=', $formId)
		->first();

		if (is_null($pageSubscribed)) {
			$name = isset($_POST['nameField']) ? $_POST['nameField'] : 0;
			$phone = isset($_POST['phoneField']) ? $_POST['phoneField'] : 0;
			$email = isset($_POST['emailField']) ? $_POST['emailField'] : 0;
			$otherFields = isset($_POST['otherField']) ? $_POST['otherField'] : 0;
			
			DB::insert('insert into subscribe_pages (user_id,page_id,page_name,page_token,form_id,form_name,campaign_id,segment_id,project_id,name_field,email_field,
phone_field,other_fields) 
		values(?,?,?,?,?,?,?,?,?,?,?,?,?)',[$userId,$pageId,$pageName,$pageToken,$formId,$formName,$campaignId,$segmentId,$projectId,$name,$email,$phone,$otherFields]);

		} else {

/*
			DB::table('subscribe_pages')
			->where('page_id', '=', $pageId)
			->where('form_id', '=', $formId)
			->update(['page_token' => $pageToken]);
*/

 DB::table('subscribe_pages')
                        ->where('page_id', '=', $pageId)
                        ->where('form_id', '=', $formId)
                        ->delete();



		

 			$name = isset($_POST['nameField']) ? $_POST['nameField'] : 0;
                        $phone = isset($_POST['phoneField']) ? $_POST['phoneField'] : 0;
                        $email = isset($_POST['emailField']) ? $_POST['emailField'] : 0;
                        $otherFields = isset($_POST['otherField']) ? $_POST['otherField'] : 0;

                        DB::insert('insert into subscribe_pages 
(user_id,page_id,page_name,page_token,form_id,form_name,campaign_id,segment_id,project_id,name_field,email_field,phone_field,other_fields)
                values(?,?,?,?,?,?,?,?,?,?,?,?,?)',[$userId,$pageId,$pageName,$pageToken,$formId,$formName,$campaignId,$segmentId,$projectId,$name,$email,$phone,$otherFields]);
}


		echo "Page subscribed successfully";
	}

	public function deleteBulkLeads() {
		//echo "dd";
		$leadIds = explode(",",$_POST['leadIds']);

		$leads = DB::table('leads')->whereIn('id', $leadIds)->update(['is_active' => "0"]);

 
		DB::table('lead_corn_campaigns')->whereIn('lead_id',$leadIds)->update(array('is_stopped' => 1,'stopped_reason' => "Lead Deleted"));
	}


	public function viewReport(Request $request, $leadId) {

//if (Auth::user()->id == "8") {
		
		$leadData = DB::table('leads')->select('id','name','mail_id','mobile_no','campaigns_id','campaign_activated_on')->where('id', $leadId)->first();
		if(LeadDetails::where('lead_id',$leadId)->count() < 1) {
		$cautomation = DB::table('automations')
		->select('sms_automation_messages.id as eventId','automations.user_id','automations.series_name',
'automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day',
'sms_automation_messages.delivery_time','sms_automation_messages.delivery_type',
'sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
		->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
		->where('automations.campaign_id', $leadData->campaigns_id)
		->where('sms_automation_messages.delivery_type', 'scheduled')
		->get();

$data = array();

		foreach($cautomation as $key => $value) {
				$newData['lead_id'] = $leadData->id;				
				$newData['automation_messages_id'] = $value->eventId; 
				
				if ($value->delivery_day > 0) {
					$campaignDateTime = explode(" ",$leadData->campaign_activated_on);
                                        $campaignDate = $campaignDateTime[0];
                                        $msgDeliveryDate =      strtotime($campaignDate." ".$request['delivery_time'].'+ '.$request['delivery_day'].' days');

					//$msgDeliveryDate = 	strtotime($leadData->campaign_activated_on.'+ '.$value->delivery_day.' days');
				} else {
					$campaignDate = explode(" ",$leadData->campaign_activated_on);
					$msgDate = $campaignDate[1];
					$msgDeliveryDate = 	strtotime($campaignDate[0]." ".$value->delivery_time);
					
				}
				
			
					$newData['delivery_date_time'] = date("Y-m-d H:i:s",$msgDeliveryDate);
	
				$cronData = LeadcornCampaigns::where([['lead_id',$leadId],['automation_messages_id',$value->eventId]])->select('delivery_date_time')->first();
				if (null !== $cronData) {
					$newData['is_delivered'] = "0";
					
 					$newData['delivery_date_time'] = $cronData->delivery_date_time;
	
				} else {
					$newData['is_delivered'] = "1";
					$newData['delivery_date_time'] = NULL;
				}
				
				$data[] = $newData;
		}

		if (count($data) > 0) {

			LeadDetails::insert($data);
		}

		}

		$eventDetails = LeadDetails::where('lead_id',$leadId)
		->select('lead_details.*','leads.campaign_activated_on','sms_automation_messages.*','automations.series_name','automations.automation_type','sms_automation_messages.updated_at')
		->join('sms_automation_messages','sms_automation_messages.id','=','lead_details.automation_messages_id')
		->join('automations','automations.id','=','sms_automation_messages.series_id')
		->join('leads','leads.id','=','lead_details.lead_id')
		->orderby('sms_automation_messages.id')
		->get();
		

		/*echo "<pre>";		
		foreach($eventDetails as $key => $value){
			print_r($value);
		}
		*/
		//print_r($eventDetails); die;
		
		return view("view-lead-details",array("cautomation"=>$eventDetails));
//} else {
//echo "Testing";
//return redirect('leads-master');
//} 	
	}
	
	public function wordpressIntegration() {
		$data["projects"]  = Project::where('user_id', Auth::user()->id)->get();
		$data["segments"]  = Segment::where('user_id', Auth::user()->id)->get();
		$data["campaigns"] = Campaign::where([['user_id', Auth::user()->id],['is_active',1]])->get();
		
		$data["wpWebhooks"] = DB::table('wordpress_integrations')
		->join('campaigns','campaigns.id','=','wordpress_integrations.campaign_id')
		->select("wordpress_integrations.*","campaigns.campaigns_name")
		->where('wordpress_integrations.user_id', Auth::user()->id)
		->get();
		
		return view("wordpress-integration",$data);	
	}
	
	public function random_strings($length_of_string){
  
		// String of all alphanumeric character
		$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	  
		// Shuffle the $str_result and returns substring
		// of specified length
		return substr(str_shuffle($str_result), 0, $length_of_string);
	}
	
	public function integrateWordpressSite(Request $request) {
		
		//print_r($request->input()); die;
		
		$userId = Auth::user()->id;

		$campaignId=$request['campaign_id'];

		$segmentId = $request['segment_id'];

		$projectId = $request['project_id'];
		
		$website = $request['website'];


		if($request['project_id'] == "0") {
			$project = new Project();
			$project->user_id      = Auth::user()->id;
			$project->project_name = $request['project_name'];
			$project->for_source   = 'Wordpress';
			$project->status       = 1;
			$project->save();
			$projectId   = $project->id;
		}
	
		if($request['segment_id'] == "0") {
			$segment = new Segment();
			$segment->user_id      = Auth::user()->id;
			$segment->segment_name = $request['segment_name'];
			$segment->for_source   = 'Wordpress';
			$segment->status       = 1;
			$segment->save();
			$segmentId   = $segment->id;
		}

		$userCode = $this->random_strings(8);

		DB::insert('insert into wordpress_integrations (user_id,user_url_code,campaign_id,segment_id,project_id,website) 
		values(?,?,?,?,?,?)',[$userId,$userCode,$campaignId,$segmentId,$projectId, $website]);
		
		Session::flash('message', "Webhook Url Created.");
		return redirect('wordpress/integration');
	}	
	
	public function assignedLeadsRetrival() {

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
		$where1 = array('leads.assigned_to','=',Auth::user()->id);
		$where2 = array('leads.status','=', "1");
		//$where3 = array('leads.is_active','=',"1");
		$where3 = $where2;
		$whereArray = array($where1, $where2, $where3);

			$orWhereFilter1 = $orWhereFilter2 = $orWhereFilter3=  $orWhereFilter4= $orWhereFilter5= $orWhereFilter6= array();
    			
			if ($search > 0) {
				$whereArray = array();
				//$whereArray[] = array('leads.name','like',"$searchValue%");
				//$whereArray[] = $where4;
				$orWhereFilter1 = array($where1, $where2, $where3);
				$orWhereFilter2 = array($where1, $where2, $where3);
				$orWhereFilter3 = array($where1, $where2, $where3);
				$orWhereFilter4 = array($where1, $where2, $where3);
				$orWhereFilter5 = array($where1, $where2, $where3);
				$orWhereFilter6 = array($where1, $where2, $where3);

				$orWhereFilter1[] = array('leads.mobile_no','like',"$searchValue%");
   				//$orWhereFilter2[] = array('leads.mail_id','like',"$searchValue%");
				//$orWhereFilter3[] = array('campaigns.campaigns_name','like',"$searchValue%");
				$orWhereFilter4[] = array('leads.name','like',"$searchValue%");
				$orWhereFilter5[] = array('leads.project_name','like',"$searchValue%");
				//$orWhereFilter6[] = array('leads.segment_name','like',"$searchValue%");
            }

			if (isset($_REQUEST['status']) && $_REQUEST['status'] >= 0) {
				
				$whereArray[] = array('leads.lead_status','=',$_REQUEST['status']);
				
			}
			
			/*
			if(isset($_REQUEST['source']) && $_REQUEST['source'] == "leads-on") {
					$value = $_REQUEST['sourceVal'];
					$whereArray[] = array('leads.created_at','like',"$value%");
				
			}
			*/

		$lead = Lead::where($whereArray)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter1)
		->join('campaigns', 'leads.campaigns_id', '=', 'campaigns.id')
		->select('leads.lead_assigned_on','leads.assigned_to','leads.lead_status','leads.created_at','leads.is_cron_disabled','campaigns.campaigns_name', 'leads.id', 'leads.name', 'leads.mail_id', 'leads.mobile_no', 'leads.project_name', 'leads.segment_name','leads.source')
		->orderby('lead_assigned_on','desc')
		->offset($offset)
        	->limit($limit)
		->get();
		

		/*
                $lead = Lead::where([['leads.user_id',Auth::user()->id],['leads.status',1]])->orderby('leads.id', 'desc')
                ->join('campaigns', 'leads.campaigns_id', '=', 'campaigns.id')
                ->select('campaigns.campaigns_name', 'leads.id', 'leads.name', 'leads.mail_id', 'leads.mobile_no', 'leads.project_name', 'leads.segment_name')
                ->offset($offset)
                ->limit($limit)
                ->get();
		*/



		$search = json_encode($_REQUEST['search']);
		
		foreach( $_REQUEST['search'] as $key => $value) {
		   $search = trim($value);
                   break;		
		}
		
		//$search = strlen($search);
			
		//$lead  = Lead::where('assigned_to',Auth::user()->id)->orderby('id','desc')->get();
		
		$leadAttended = DB::table('lead_comments')
		->select('lead_id')
		->distinct('lead_id')
		->where('user_id',Auth::user()->id)
		->get();
		
		$attendedLeads = [];
		
		if (null !== $leadAttended) {
			foreach($leadAttended as $key => $value) {
					$attendedLeads[] = $value->lead_id;
			}
			
		}
		
		$count = Lead::where($whereArray)->count();


		$s = Lead::where($whereArray)->toSql();

		$filterCount =  Lead::where($whereArray)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter1)->count();
		/*
		if (isset($_REQUEST['status']) && $_REQUEST['status'] >= 0) {
			$lead = Lead::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)->orWhere($orWhereFilter3)
			->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter6)
			->join('campaigns', 'leads.campaigns_id', '=', 'campaigns.id')
			->select('leads.lead_assigned_on','leads.assigned_to','leads.lead_status','leads.created_at','leads.is_cron_disabled',
			'campaigns.campaigns_name', 'leads.id', 'leads.name', 'leads.mail_id', 'leads.mobile_no', 'leads.project_name', 'leads.segment_name','leads.source')
			->orderby('lead_assigned_on','desc')
			->offset($offset)
			->limit($limit)
			->whereIn('leads.id', $attendedLeads)
			->get();
			
			$count = Lead::where($whereArray)->whereIn('leads.id', $attendedLeads)->count();
			$s = "1";

			$filterCount =  Lead::where($whereArray)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter1)->whereIn('leads.id', $attendedLeads)->count();
		
		} 
*/
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

	 $lead = Lead::where($whereArray)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter1)
		->whereIn('leads.id', $attendedLeads)

                        ->join('campaigns', 'leads.campaigns_id', '=', 'campaigns.id')
                        ->select('leads.lead_assigned_on','leads.assigned_to','leads.lead_status','leads.created_at','leads.is_cron_disabled',
                        'campaigns.campaigns_name', 'leads.id', 'leads.name', 'leads.mail_id', 'leads.mobile_no', 'leads.project_name', 'leads.segment_name','leads.source')
                        ->orderby('lead_assigned_on','desc')
                        ->offset($offset)
                        ->limit($limit)
                        ->get();
  
	 $count = Lead::where('assigned_to',Auth::user()->id)->count();

	$filterCount = Lead::where($whereArray)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter1)
                ->whereIn('leads.id', $attendedLeads)->count();
           

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

	//$lead = array_values($lead);
//end
/*
		if($notAttended > 0) {
		$filterCount = $filterCount > $notAttended ? $filterCount - $notAttended : $notAttended - $filterCount;

		}
*/
		$result = array("search" => count($attendedLeads),"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => $filterCount);
	
		$result['data'] = $lead;
		print_r(json_encode($result));
	}


public function followupsAssignedRetrival() {

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
        $currentdate = date('Y-m-d');
	$currentTime = explode(" ",date('Y-m-d H:i:s'));
	$where1 = array('leads_followups.user_id','=',Auth::user()->id);

	$where2 = array('leads_followups.followup_date','=',$currentdate);

        //$where2 = array('leads_followups.followup_date','=',$currentdate);


	$where3 = array('leads_followups.status','=',null);
        $where4=array('leads_followups.followup_time','>=',$currentTime[1]);
 
	$where5 = array('leads.is_active', 1);
		
	$whereArray = array($where1, $where2, $where3, $where4, $where5);

	//$whereArray = array($where1, $where2, $where3);

        $orWhereFilter1 = $orWhereFilter2 = $orWhereFilter3=  $orWhereFilter4= $orWhereFilter5= $orWhereFilter6= array();
        
        if(isset($_REQUEST['status']) && $_REQUEST['status'] >= 0) {
            $leadStatus = $_REQUEST['status'];
                $whereArray[] = array('lead_status','=',"$leadStatus");
            
        }
        
        if ($search > 0) {
            $whereArray = array();              
            $orWhereFilter1 = array($where1, $where2, $where3, $where4);
            $orWhereFilter2 = array($where1, $where2, $where3, $where4);
            $orWhereFilter3 = array($where1, $where2, $where3, $where4);
            $orWhereFilter4 = array($where1, $where2, $where3, $where4);
            $orWhereFilter1[] = array('leads.mobile_no','like',"$searchValue%");
            $orWhereFilter2[] = array('leads.mail_id','like',"$searchValue%");          
            $orWhereFilter3[] = array('leads.name','like',"$searchValue%");
            $orWhereFilter4[] = array('leads.project_name','like',"$searchValue%");
        }
				
       // $lead  = Lead::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)->orWhere($orWhereFilter3)->orWhere($orWhereFilter4)->offset($offset)->limit($limit)->get();
        $leadFollowups = LeadFollowups::select('leads_followups.*','leads.name','leads.mobile_no','leads.project_name','leads.source')
		->join('leads','leads.id','=','leads_followups.lead_id')
		->where($whereArray)
		->orWhere($orWhereFilter1)
		->orWhere($orWhereFilter2)
		//->orWhere($orWhereFilter3)
		//->orWhere($orWhereFilter4)
		->offset($offset)
		->limit($limit)
		->orderby('leads_followups.followup_date','asc')
		->orderby('leads_followups.followup_time','asc')
		->get();
        //$count = Lead::where($whereArray)->count();
		
		/*
		$count = DB::table('leads_followups')
		->join('leads','leads.id','=','leads_followups.lead_id')
        ->where('leads_followups.user_id', Auth::user()->id)
        ->where('leads_followups.followup_date','>=',$currentdate)
        ->where('leads_followups.status','=',null)
        ->where('leads.is_active', 1)->count();
		*/
		
		$count = LeadFollowups::select('leads_followups.*','leads.name','leads.mobile_no','leads.project_name')
		->join('leads','leads.id','=','leads_followups.lead_id')
		->where($whereArray)
		->orWhere($orWhereFilter1)
		->orWhere($orWhereFilter2)
		//->orWhere($orWhereFilter3)
		//->orWhere($orWhereFilter4)
		->count();
		
        $filterCount =  $count;        
        $result = array("search" => $whereArray,"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => $filterCount);//

        
        //echo $currentdate; die;
        /*
		$leadFollowups = DB::table('leads_followups')
        ->select('leads_followups.*','leads.name','leads.mobile_no','leads.project_name')
        ->join('leads','leads.id','=','leads_followups.lead_id')
        ->where('leads_followups.user_id', Auth::user()->id)
        ->where('leads_followups.followup_date','>=',$currentdate)
        ->where('leads_followups.status','=',null)
        ->where('leads.is_active', 1)
        ->orderby('leads_followups.followup_date','asc')
        ->get();
        */
        $result['data'] = $leadFollowups;
        
        print_r(json_encode($result));
    }

	public function followupsAssignedRetrival1() {

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
        
        $where1 = array('assigned_to','=',Auth::user()->id);

        
        $whereArray = array($where1);

        $orWhereFilter1 = $orWhereFilter2 = $orWhereFilter3=  $orWhereFilter4= $orWhereFilter5= $orWhereFilter6= array();
        
        if(isset($_REQUEST['status']) && $_REQUEST['status'] >= 0) {
            $leadStatus = $_REQUEST['status'];
                $whereArray[] = array('lead_status','=',"$leadStatus");
            
        }
        
        if ($search > 0) {
            $whereArray = array();              
            $orWhereFilter1 = array($where1);
            $orWhereFilter2 = array($where1);
            $orWhereFilter3 = array($where1);
            $orWhereFilter4 = array($where1);
            $orWhereFilter1[] = array('leads.mobile_no','like',"$searchValue%");
            $orWhereFilter2[] = array('leads.mail_id','like',"$searchValue%");          
            $orWhereFilter3[] = array('leads.name','like',"$searchValue%");
            $orWhereFilter4[] = array('leads.project_name','like',"$searchValue%");
        }

       // $lead  = Lead::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)->orWhere($orWhereFilter3)->orWhere($orWhereFilter4)->offset($offset)->limit($limit)->get();
        
        //$count = Lead::where($whereArray)->count();
		$currentdate = date('Y-m-d');
		$count = DB::table('leads_followups')
		->join('leads','leads.id','=','leads_followups.lead_id')
        ->where('leads_followups.user_id', Auth::user()->id)
        ->where('leads_followups.followup_date','>=',$currentdate)
        //->where('leads_followups.status','=',null)
       // ->where('leads.is_active', 1)
        ->count();
		
        $filterCount =  $count;        
        $result = array("search" => $whereArray,"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => $filterCount);//

        
        //echo $currentdate; die;
        $leadFollowups = DB::table('leads_followups')
        ->select('leads_followups.*','leads.name','leads.mobile_no','leads.project_name')
        ->join('leads','leads.id','=','leads_followups.lead_id')
        ->where('leads_followups.user_id', Auth::user()->id)
        ->where('leads_followups.followup_date','>=',$currentdate)
        //->where('leads_followups.status','=',null)
        //->where('leads.is_active', 1)
        ->orderby('leads_followups.followup_date','asc')
        ->get();
        
        $result['data'] = $leadFollowups;
        
        print_r(json_encode($result));
    }

    //end date search 
     public function enddateRetrival(Request $request) {
	date_default_timezone_set("Asia/Kolkata");
	$t=time();
	$currentTime = date("h:i:s",$t);

        $date = $_REQUEST['followup_date'];
	
	$dateFilter = array('leads_followups.followup_date','=',$date);
	
	
	if (strlen($date) < 1) {
		$date= date("Y-m-d");
		$dateFilter = array('leads_followups.followup_date','<=',$date);
	}

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
        
        $where1 = array('assigned_to','=',Auth::user()->id);

        
        $whereArray = array($where1);

        $orWhereFilter1 = $orWhereFilter2 = $orWhereFilter3=  $orWhereFilter4= $orWhereFilter5= $orWhereFilter6= array();
        
        if(isset($_REQUEST['status']) && $_REQUEST['status'] >= 0) {
            $leadStatus = $_REQUEST['status'];
                $whereArray[] = array('lead_status','=',"$leadStatus");
            
        }
        
        if ($search > 0) {
            $whereArray = array();              
            $orWhereFilter1 = array($where1);
            $orWhereFilter2 = array($where1);
            $orWhereFilter3 = array($where1);
            $orWhereFilter4 = array($where1);
            $orWhereFilter1[] = array('leads.mobile_no','like',"$searchValue%");
            $orWhereFilter2[] = array('leads.mail_id','like',"$searchValue%");          
            $orWhereFilter3[] = array('leads.name','like',"$searchValue%");
            $orWhereFilter4[] = array('leads.project_name','like',"$searchValue%");
        }

       // $lead  = Lead::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)->orWhere($orWhereFilter3)->orWhere($orWhereFilter4)->offset($offset)->limit($limit)->get();
        
//        $count = Lead::where($whereArray)->count();

	$count  =  DB::table('leads_followups')
       // ->select('leads_followups.*','leads.name','leads.mobile_no','leads.project_name')
        ->join('leads','leads.id','=','leads_followups.lead_id')
        ->where('leads_followups.user_id', Auth::user()->id)
        ->where(array($dateFilter))
        //->where('leads_followups.followup_date','>=',$currentdate)
        ->where('leads_followups.status','=',null)
        ->where('leads.is_active', 1)
        //->orderby('leads_followups.followup_date','asc')
        ->count();


        //$filterCount =  Lead::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)->orWhere($orWhereFilter3)->count();
        
	$filterCount =  DB::table('leads_followups')
       // ->select('leads_followups.*','leads.name','leads.mobile_no','leads.project_name')
        ->join('leads','leads.id','=','leads_followups.lead_id')
        ->where('leads_followups.user_id', Auth::user()->id)
        ->where(array($dateFilter))
 	//->where('leads_followups.followup_time','<=',$currentTime)
        //->where('leads_followups.followup_date','>=',$currentdate)
        ->where('leads_followups.status','=',null)
        ->where('leads.is_active', 1)
        //->orderby('leads_followups.followup_date','asc')
        ->count();


        $result = array("search" => $dateFilter,"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => $filterCount);//

        $currentdate = date('Y-m-d');
        //echo $currentdate; die;
        $leadFollowups = DB::table('leads_followups')
        ->select('leads_followups.*','leads.name','leads.mobile_no','leads.project_name')
        ->join('leads','leads.id','=','leads_followups.lead_id')
        ->where('leads_followups.user_id', Auth::user()->id)
        ->where(array($dateFilter))
      	//->where('leads_followups.followup_date','>=',$currentdate)
	//->where('leads_followups.followup_time','<=',$currentTime)
        ->where('leads_followups.status','=',null)
        ->where('leads.is_active', 1)
	->offset($offset)->limit($limit)
        ->orderby('leads_followups.followup_date','asc')
        ->get();
        
        $result['data'] = $leadFollowups;
        
        print_r(json_encode($result));
    }

public function leadSettings() {
		$staffFilter = array(
			'admin_id' => Auth::user()->id,
		);
		$projectFilter = array(
			'user_id' => Auth::user()->id,
		);
		
		
		$data['staffList'] = User::where($staffFilter)->get();
		
		$data['projectList'] = Project::where($projectFilter)->get();
		
		$data['assignedProjects'] = DB::table('lead_assign_order')->where($staffFilter)->get();
		
		$data['auto_assign'] =  DB::table('admin_settings')->select('auto_assign_leads')->where($staffFilter)->first();
		
		return view('leads-settings',$data);
	}
	
public function autoLeadAssign(Request $request) {

		//die('ddd');
		//echo "<pre>";
		//print_r($_POST);
		//die;
		$adminId = Auth::user()->id;
		
		//$projectId = $_POST['project_id'];
		
		$project = $_POST['project_lists'];
		
		foreach($project as $key => $value) {
				$projects = explode("|",$value);
				$projectId = $projects[0];
				break; //only one project selected
		}
		
		$executiveList = $_POST['staffPriority'];
		$executiveIds = str_replace(",","|",$executiveList);
		 
		/*
		$executiveList = $_POST['user-list'];
		
		$executiveIds = "";
		$i = 0;
		foreach( $executiveList as $key => $value ) {
			$val = explode("-",$value);
			//$username = $val[0];
			$id  = $val[1];
			if ($i>0) {
				$executiveIds .="|";
			}
			$executiveIds .= $id;
			$i++;
		}
		*/
		//print_r($executiveIds);
		//die;
		$exist = DB::table('lead_assign_order')->where('project_id',$projectId)->count();
		
		if ($exist > 0) {
			DB::table('lead_assign_order')->where('project_id', $projectId)->update(['executive_ids' =>$executiveIds]);	
		} else {
			DB::insert('insert into lead_assign_order (admin_id,project_id,executive_ids) 
			values(?,?,?)',[$adminId,$projectId,$executiveIds]);
		}
		echo "success";
		return back();

	}

	public function autoLeadAssign1(Request $request) {

		//die('ddd');
		//echo "<pre>";
		//print_r($_POST);
		$adminId = Auth::user()->id;
		
		$projectId = $_POST['project_id'];
		
		$executiveList = $_POST['user-list'];
		
		$executiveIds = "";
		$i = 0;
		foreach( $executiveList as $key => $value ) {
			$val = explode("-",$value);
			//$username = $val[0];
			$id  = $val[1];
			if ($i>0) {
				$executiveIds .="|";
			}
			$executiveIds .= $id;
			$i++;
		
		}
		
		//print_r($executiveIds);
		
		DB::insert('insert into lead_assign_order (admin_id,project_id,executive_ids) 
		values(?,?,?)',[$adminId,$projectId,$executiveIds]);
		echo "success";
		return back();

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

	public function overdueFollowup(Request $request) {
		/*
		$pendingFollowup = array('leads_followups.status','=',null);
		
		$currentdate = date('Y-m-d');
		
		$leadFollowups = DB::table('leads_followups')
		->select('leads_followups.*','leads.name','leads.mobile_no','leads.project_name')
		->join('leads','leads.id','=','leads_followups.lead_id')
		->where('leads_followups.user_id', Auth::user()->id)
		->where('leads.is_active', 1)
		->orderby('leads_followups.followup_date','asc')
		->get();
          
		return view('overdue-followups',array("leadFollowups" => $leadFollowups));
		*/
		return view('overdue-followups');
	}
	
	public function overdueFollowupsRetrival() {

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
        date_default_timezone_set("Asia/kolkata");
		$currentdate = date('Y-m-d');
		$currenttime = explode(" ",date('Y-m-d H:i:s'));
		//$t = explode(" " ,$currenttime);
		//$s = $t[1];
		//$v = explode(':', $s);
		//$t1 = $v[0].':'.$v[1];
		
		
		$where1 = array('leads_followups.user_id','=',Auth::user()->id);
		
		
		$where2 = array('leads_followups.followup_date','<=',$currentdate);
		$where3 = array('leads_followups.followup_time','<=',$currenttime[1]);
		
		//$where2 = array();
		//$where3 = array();

		$where4 = array('leads_followups.status','=',null);
		$where5 = array('leads.is_active', 1);
		
		//$whereArray = array($where1, $where4, $where5);

		$whereArray = array($where1, $where2, $where3, $where4, $where5);

        $orWhereFilter1 = $orWhereFilter2 = $orWhereFilter3=  $orWhereFilter4= $orWhereFilter5= $orWhereFilter6= array();
        
        if(isset($_REQUEST['status']) && $_REQUEST['status'] >= 0) {
            $leadStatus = $_REQUEST['status'];
                $whereArray[] = array('lead_status','=',"$leadStatus");
            
        }
        
        if ($search > 0) {
            $whereArray = array();              
            $orWhereFilter1 = array($where1, $where2, $where3, $where4);
            $orWhereFilter2 = array($where1, $where2, $where3, $where4);
            $orWhereFilter3 = array($where1, $where2, $where3, $where4);
            $orWhereFilter4 = array($where1, $where2, $where3, $where4);
            $orWhereFilter1[] = array('leads.mobile_no','like',"$searchValue%");
            $orWhereFilter2[] = array('leads.mail_id','like',"$searchValue%");          
            $orWhereFilter3[] = array('leads.name','like',"$searchValue%");
            $orWhereFilter4[] = array('leads.project_name','like',"$searchValue%");
        }
				
       // $lead  = Lead::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)->orWhere($orWhereFilter3)->orWhere($orWhereFilter4)->offset($offset)->limit($limit)->get();
        $leadFollowups = LeadFollowups::select('leads_followups.*','leads.name','leads.mobile_no','leads.project_name','leads.source')
		->join('leads','leads.id','=','leads_followups.lead_id')
		->where($whereArray)
		->orWhere($orWhereFilter1)
		->orWhere($orWhereFilter2)
		->orWhere($orWhereFilter3)
		->orWhere($orWhereFilter4)
		->offset($offset)
		->limit($limit)
		->orderby('leads_followups.followup_date','desc')
		->get();
		
        //$count = Lead::where($whereArray)->count();
		/*
		$count = DB::table('leads_followups')
		->join('leads','leads.id','=','leads_followups.lead_id')
        ->where('leads_followups.user_id', Auth::user()->id)
        ->where('leads_followups.followup_date','>=',$currentdate)
        ->where('leads_followups.status','=',null)
        ->where('leads.is_active', 1)->count();
		*/
		
		$count = LeadFollowups::select('leads_followups.*','leads.name','leads.mobile_no','leads.project_name')
		->join('leads','leads.id','=','leads_followups.lead_id')
		->where($whereArray)
		->orWhere($orWhereFilter1)
		->orWhere($orWhereFilter2)
		->orWhere($orWhereFilter3)
		->orWhere($orWhereFilter4)
		->count();
		
        $filterCount =  $count;        
        $result = array("search" => $whereArray,"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => $filterCount);//

        
        //echo $currentdate; die;
        /*
		$leadFollowups = DB::table('leads_followups')
        ->select('leads_followups.*','leads.name','leads.mobile_no','leads.project_name')
        ->join('leads','leads.id','=','leads_followups.lead_id')
        ->where('leads_followups.user_id', Auth::user()->id)
        ->where('leads_followups.followup_date','>=',$currentdate)
        ->where('leads_followups.status','=',null)
        ->where('leads.is_active', 1)
        ->orderby('leads_followups.followup_date','asc')
        ->get();
        */
        $result['data'] = $leadFollowups;
        
        print_r(json_encode($result));
    }


    public function deleteAutoAssign(Request $request) {
	//echo $request->id;

	DB::table('lead_assign_order')->where('id',$request->id)->delete();
	return back();
    }

     public function starterLeads(){
	//$userPack = DB::table('users_pack')->where('users_id', '=', Auth::user()->id )->first();
//print_r($userPack); die;

    //$startingDate = explode(' ',$userPack->created_at);
	//$startDate1 = $startingDate[0];
	$startDate = date("Y-m-d");
	$month = date('m');
	$year = date('Y');


/*	$data = Lead::select('id','created_at')->where([
				['user_id','=',Auth::user()->id],
				['created_at', '<', $startDate],
				
			])->limit(50)->orderby('id','desc')->get();
*/
  $data = Lead::select('id','created_at')->where('user_id',Auth::user()->id)->whereMonth('created_at',$month)->whereYear('created_at', $year)->limit(50)->orderBy('id','asc')->get();

	$ids = [];
	foreach($data as $key => $value) {
	//$m = explode("-",$value->created_at);
//	print_r($m); die;
		$ids[] = $value->id;
	}
	//print_r($ids); die;
	$lead = Lead::where([['user_id',Auth::user()->id],['is_active',1]])->orderby('id', 'desc')->limit(1100)->get();

	return view('leads-master',array("leaddata" => $lead,"leads"=>$ids));
    
	}

public function saveLeadFromApi(Request $request) {
$data  = $request->json()->all();
   
if (isset($data['data'])) {

$data = $data['data'];

 \Log::info('dd '. json_encode($data));

}
		$code = $request->client_id;

		$admin = User::where('admin_code',$code)->first();
/*		
		$campaignName = isset($_POST['campaign_name']) ? $_POST['campaign_name'] : null ;
		$projectName = isset($_POST['project_name']) ? $_POST['project_name'] : null;
		$segmentName = isset($_POST['segment_name']) ? $_POST['segment_name'] : null;
		
		$leadName = isset($_POST['lead_name']) ? $_POST['lead_name'] : null;
		$leadContact = isset($_POST['lead_contact']) ? $_POST['lead_contact'] : null;
		$leadMail = isset($_POST['lead_mail']) ? $_POST['lead_mail'] : null; 
*/

		$campaignName = isset($data['campaign_name']) ? $data['campaign_name'] : null ;
                $projectName = isset($data['project_name']) ? $data['project_name'] : null;
                $segmentName = isset($data['segment_name']) ? $data['segment_name'] : null;

                $leadName = isset($data['name']) ? $data['name'] : null;
                $leadContact = isset($data['Phone']) ? $data['Phone'] : null;
		
								
                $leadMail = isset($data['Email']) ? $data['Email'] : null;
 \Log::info('1'); 
		
		foreach($data as $key => $value ) {
		
		    $checkEmail = "Email";
		    if (stripos($checkEmail,$key) !== false) {
  		  	$leadMail = $value;
			break;
 		    } 
	
		}


 if ($segmentName == null) {

	$segmentName = isset($data['Segment_name']) ? $data['Segment_name'] : null;

}

/*
		foreach($data as $key => $value ) {

                    $checkSegment = "Segment_name";
                    if (stripos($checkSegment,$key) !== false) {
                        $segmentName = $value;
                        break;
                    }

                }
*/

		$fields = array('campaign_name','project_name','segment_name','name','Phone','Email','source');	

		$otherFields = null;

		foreach($data as $key => $value ) {

                    
                    if (!in_array($key,$fields) ){
                        $otherFields[$key] = $value;
                    }

                }
 \Log::info('2'); 
		/*	
		if (null !== $otherFields) {
			$otherFields = json_encode($otherFields);

		}
*/
	
		if ($campaignName == null) {\Log::info('31');

			return response()->json([
				'error' => 'Campaign Name missing'
			], 400);
		} else if ($projectName == null) {\Log::info('32');

			return response()->json([
				'error' => 'project name missing'
			], 400);
		} else if ($segmentName == null) {\Log::info('33');

			return response()->json([
				'error' => 'segment name missing'
			], 400);
		} else{\Log::info('34');

			if($leadName == null || $leadContact == null ) {
				return response()->json([
				'error' => 'Lead Name, Lead Contact or Lead Mail is missing'
			], 400);
			}
		}
 \Log::info('3'.$segmentName);
		
		
		if ($admin == null) {
			return response()->json([
				'error' => 'Admin not Found. Pass correct client ID'
			], 400);
		}
 \Log::info('4');

	
		$campaign = Campaign::where(array(array('user_id',$admin->id),array('campaigns_name',$campaignName)))->first();

		if ($campaign == null) {
			  return response()->json([
				'error' => 'Campaign does not exist'
			], 400);
		}

 \Log::info('5');

		$segment = Segment::where(array(array('user_id',$admin->id),array('segment_name', $segmentName)))->first();
		
		if ($segment == null) { \Log::info('6');
/*
			  return response()->json([
				'error' => 'Segment does not exist'
			], 400);
*/
$segment = new Segment();
                $segment->user_id      = $admin->id;
                $segment->segment_name = $segmentName;
                $segment->for_source   = 'Facebook';
                $segment->status       = 1;
                $segment->save();

                

		}


		$project = Project::where(array(array('user_id',$admin->id),array('project_name',$projectName)))->first();
		
		if ($project == null) { \Log::info('7');
/*
			  return response()->json([
				'error' => 'project does not exist'
			], 400);
*/

$project = new Project();
                $project->user_id      = $admin->id;
                $project->project_name = $projectName;
                $project->for_source   = 'Facebook';
                $project->status       = 1;
                $project->save();


		}
		
		if ($leadName == null || $leadName == "") { \Log::info('8');

			  return response()->json([
				'error' => 'Lead name is missing or empty'
			], 400);
		}
		
/*
		if ($leadMail == null || $leadMail == "") { \Log::info('9');

			  return response()->json([
				'error' => 'Lead Mail is missing  or empty'
			], 400);
		}
*/
		
		if ($leadContact == null || $leadContact == "") { \Log::info('10');

			  return response()->json([
				'error' => 'Lead contact is missing or empty'
			], 400);
		}

		$leadContact = substr($leadContact, -10);

		$leadSource = "Organic";

		if (isset($data['source'])) {
		if ($data['source'] == "google") {
			$leadSource = "GoogleAds_PPC";
		}
		if ($data['source'] == "facebook") {
                        $leadSource = "FacebookTraffic";
                }


		}

 \Log::info('Api Data :'.json_encode($data)); 

$leadExist = DB::table('leads')->select('mobile_no')->where(array(array('is_active','1'),array('mobile_no',$leadContact),array('campaigns_id',$campaign->id)))->count();

if ($leadExist > 0) {
date_default_timezone_set('Asia/Kolkata');

	$currentDate = date ("Y-m-d h:i:s");

DB::insert('insert into leads_duplicate (user_id,name,email,contact,campaign_id,project_id,segment_id,source,created_at) 
        values(?,?,?,?,?,?,?,?,?)',[$admin->id, $leadName, $leadMail,$leadContact, $campaign->id,$project->id,$segment->id,$leadSource,$currentDate]);

return response()->json([
                                'error' => 'Lead contact already exist !!'
                        ], 400);

} 


		$lead = new Lead();

		$lead->user_id               = $admin->id;

		$lead->project_id            = $project->id;
		$lead->project_type          = 1;
		$lead->project_name          = $project->project_name;

		$lead->segment_id            = $segment->id;
		$lead->segment_type          = 1;
		$lead->segment_name          = $segment->segment_name;

		$lead->campaigns_id          = $campaign->id;

		$lead->name                  = $leadName;
		$lead->mail_id               = $leadMail;
		$lead->mobile_no             = $leadContact;
		
		$lead->country               = null;
		
		$lead->state                 = null;
		$lead->city                  = null;
		$lead->zipcode               = null;
		$lead->company               = null;
		$lead->position              = null;
		$lead->address1              = null;
		$lead->address2              = null;
		
		$lead->source                = $leadSource;
		$lead->status                = 1;
		$lead->other_fields =   null !== $otherFields ? json_encode($otherFields) : null ;
		date_default_timezone_set("Asia/Kolkata");
		$lead->campaign_activated_on = new DateTime(date('Y-m-d H:i:s'));
		$lead->save();
  		$lastid = $lead->id;		

        	$adminSettings = DB::table('admin_settings')
                ->select('auto_assign_leads','send_notification_via_whatsapp','send_notification_via_email','send_notification_to_staff','assignStaffWhatsappEvent')
                ->where('admin_id',$admin->id)
                ->get();
		$useStaffWP = 0;

                if (isset($adminSettings[0]) ) {

                    if ($adminSettings[0]->auto_assign_leads  == "1") {
                        $this->leadAssign($project->id,$lastid);
                    }

		    if ($adminSettings[0]->assignStaffWhatsappEvent  == "1") {
                        $useStaffWP = 1;
                    }
                }		

$cautomationcount = DB::table('automations')
                        
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
                        ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                        ->where('automations.campaign_id', $campaign->id)
                        ->where('sms_automation_messages.delivery_type', 'scheduled')
                        ->count();

                    if($cautomationcount > 0){
                        $cautomation = DB::table('automations')
                            
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image','sms_automation_messages.is_active')
                            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                            ->where('automations.campaign_id', $campaign->id)
                            ->where('sms_automation_messages.delivery_type', 'scheduled')
                            ->get();

                        foreach($cautomation as $row){

                            if($row->delivery_day == 0){ //Today
                                $newdatetime = date('Y-m-d '.$row->delivery_time);
                            }else{ //After Day
                                $newdatetime = new DateTime(date('Y-m-d '.$row->delivery_time).' + '.$row->delivery_day.' day');
                            }

                            $leadcorn = new LeadcornCampaigns();
                            $leadcorn->user_id               = $admin->id;
                            $leadcorn->campaign_id           = $campaign->id;
                            $leadcorn->lead_id               = $lastid;
                            $leadcorn->automation_messages_id= $row->id;

                            $leadcorn->name                  = $leadName;
                            $leadcorn->mail_id               = $leadMail;
                            $leadcorn->mobile_no             = $leadContact;

                            $leadcorn->automation_type       = $row->automation_type;

                            $leadcorn->delivery_date_time    = $newdatetime;
                            $leadcorn->message               = $row->message;
                            $leadcorn->image                 = $row->image;
                            $leadcorn->status                = 2;

							$leadDetail = new LeadDetails();					
							$leadDetail->lead_id               = $lastid;
							$leadDetail->automation_messages_id= $row->id;
							$leadDetail->delivery_date_time    = $newdatetime;      
							              
							if ($row->is_active == "0") {
								$leadcorn->is_stopped = "1";
								$leadcorn->stopped_reason = "Event is in off state on lead creation";
								$leadDetail->is_cancelled = "1";
								$leadDetail->failure_reason = "Event is in off state on lead creation";
							}

							$leadcorn->save();
							$leadDetail->save();

                        }
                    }
					$smscount = DB::table('automations')
                        
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                        ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                        ->where('automations.automation_type', 1)
                        ->where('automations.campaign_id', $campaign->id)
                        ->where('sms_automation_messages.delivery_type', 'initial')
						->where('sms_automation_messages.is_active', 1)
                        ->count();

                    if($smscount > 0){
                        //DB::enableQueryLog();
                        $smsdata = DB::table('automations')
                            
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                            ->where('automations.automation_type', 1)
                            ->where('automations.campaign_id', $campaign->id)
                            ->where('sms_automation_messages.delivery_type', 'initial')
							->where('sms_automation_messages.is_active', 1)
                            ->first();
						$message = isset($smsdata->message) ? $smsdata->message : "";
$usersmsapikey = Getuserapikey($admin->id,1);
                                $sms_from_name = Getusersmsfromname($admin->id,1);

                                testsendsmsnew($usersmsapikey,$sms_from_name,$leadContact,str_replace("{Full Name}",$leadName,$message));
                        

//echo str_replace("{Full Name}",$request['name'],$smsdata->message); die;
                        //testsendsms($leadContact,str_replace("{Full Name}",$leadName,$message));
                        //$query = DB::getQueryLog();
                        //echo "<pre>"; print_r($query);die;
                    }

                    // SEND EMAIL
                    $emailcount = DB::table('automations')
                        
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                        ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                        ->where('automations.automation_type', 2)
                        ->where('automations.campaign_id', $campaign->id)
                        ->where('sms_automation_messages.delivery_type', 'initial')
						->where('sms_automation_messages.is_active', 1)
                        ->count();

                    if($emailcount > 0){
                        $emaildata = DB::table('automations')
                            
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                            ->where('automations.automation_type', 2)
                            ->where('automations.campaign_id', $campaign->id)
                            ->where('sms_automation_messages.delivery_type', 'initial')
							->where('sms_automation_messages.is_active', 1)
                            ->first();
						$message = isset($emaildata->message) ? $emaildata->message : "";
				$usersmsapikey = Getuserapikey($admin->id,2);
testsendemailnew($usersmsapikey,$leadMail,str_replace("{Full Name}",$leadName,$message));                        

//testsendemail($leadMail,str_replace("{Full Name}",$leadName,$message));
                    }

                    // SEND WHATSAPP
                    $whatsappcount = DB::table('automations')
                        
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message')
                        ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                        ->where('automations.automation_type', 3)
                        ->where('automations.campaign_id', $campaign->id)
                        ->where('sms_automation_messages.delivery_type', 'initial')
						->where('sms_automation_messages.is_active', 1)
                        ->count();

                    if($whatsappcount > 0){
                        $whatsappdata = DB::table('automations')
                            
->select('automations.user_id','automations.series_name','automations.automation_type','sms_automation_messages.id','sms_automation_messages.message','sms_automation_messages.delivery_day','sms_automation_messages.delivery_time','sms_automation_messages.delivery_type','sms_automation_messages.custom_full_name','sms_automation_messages.message','sms_automation_messages.image')
                            ->join('sms_automation_messages','sms_automation_messages.series_id','=','automations.id')
                            ->where('automations.automation_type', 3)
                            ->where('automations.campaign_id', $campaign->id)
                            ->where('sms_automation_messages.delivery_type', 'initial')
							->where('sms_automation_messages.is_active', 1)
                            ->first();
						$message = isset($whatsappdata->message) ? $whatsappdata->message : "";
//$usersmsapikey = Getuserapikey($admin->id,3);

if ($useStaffWP == 1) {
    $subAdmin = DB::table('leads')
                ->select('assigned_to')
                ->where('id',$lastid)
                ->get();

    if (isset($subAdmin[0]) ) {
        $subadminId = $subAdmin[0]->assigned_to;
        $usersmsapikey = Getuserapikey($subadminId,3);        
    } else {
	$usersmsapikey = Getuserapikey($admin->id,3);
    }
} else {
    
$usersmsapikey = Getuserapikey($admin->id,3);

}

testsendwhatsappnew($usersmsapikey,$leadContact,str_replace("{Full Name}",$leadName,$message),$whatsappdata->image);

                        //testsendwhatsapp($leadContact,str_replace("{Full Name}",$leadName,$message),$whatsappdata->image);
                    }


$adminSettings = DB::table('admin_settings')
                                         ->select('auto_assign_leads','send_notification_via_whatsapp','send_notification_via_email','send_notification_to_staff')
                                         ->where('admin_id',$admin->id)
                                         ->get();
                        if (isset($adminSettings[0]) ) {

                               /* if ($adminSettings[0]->auto_assign_leads  == "1") {
                                        $this->leadAssign($project->id,$lastid);
                                }
                                */
$adminData = DB::table('users')->select('id','phone_no','email')->where('id',$admin->id)->get();


                                $adminMobileNo = $adminData[0]->phone_no;
                                $adminEmail = $adminData[0]->email;

                                $leadData = array(
                                        "id" => $lastid,
                                        "userId" => $admin->id,
                                        "name" => $leadName,
                                        "mobno" => $leadContact,
                                        "email" => $leadMail,
                                        "project" => $project->project_name,
                                        "sendStaff" => $adminSettings[0]->send_notification_to_staff
                                );
  if ($adminSettings[0]->send_notification_via_whatsapp  == "1") {
                                        $this->sendLeadNotificationViaWhatsapp($adminMobileNo,$leadData);
                                }

                                if ($adminSettings[0]->send_notification_via_email  == "1") {
                                        $this->sendLeadNotificationViaEmail($adminEmail,$leadData);
                                }

}


		return response()->json([
			'success' => 'Lead Saved Successfully','message' => json_encode($otherFields)
		], 200);
	}
/*
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
*/
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
if ((isset($userPack) && $userPack->pack == "1") && $count > 50) {


$leadName = $leadName;
$leadMobno = $leadData['mobno'][0].str_repeat("*",strlen($leadMobno)-1);
$emailDetail = explode('@',$leadEmail);
$emailUser = $emailDetail[0];
$leadEmail = $emailUser[0].str_repeat("*",strlen($emailUser)-1)."@".$emailDetail[1];
$leadProject = $leadData['project'];

}

$msg = "Congratulations👏,
New Lead Arrived
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




                         $assignedTo = Lead::select('assigned_to')->where('id', '=', $leadData['id'])->first();
                        
			if ($assignedTo != null) {
                                $staffList = User::select('phone_no')->where('id',$assignedTo->assigned_to)->first();

				if ($staffList != null) {
                                	$mobileno = $staffList->phone_no;

                                	testsendwhatsappnew($api,$mobileno,$msg,$attachment);
				}
                        }
                   


                }



	}


    public function sendLeadNotificationViaEmail($adminEmail, $leadData) {
		
              
    	$toemail = $adminEmail;

    	$leadName = $leadData['name'];
    	$leadMobno = $leadData['mobno'];
    	$leadEmail = $leadData['email'];
    	$leadProject = $leadData['project'];

        $userPack = DB::table('users_pack')->where('users_id', '=', $leadData['userId'] )->first();

        //$alternativeEmail =  DB::table('users')->select('alternative_email')->where('id', '=', $leadData['userId'])->first();


        $count = Lead::where(array(array('user_id',$leadData['userId'])))->count();

        if ((isset($userPack) && $userPack->pack == "1") && $count > 50) {
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
			'project'          =>  $leadProject
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
            if ($assignedTo != null) {
                $staffList = User::select('email')->where('id',$assignedTo->assigned_to)->first();

		if ($staffList != null ) {
                	$toemail = $staffList->email;

                	\Log::info('Mail sent to :'.$toemail);   

                	Mail::send('lead-notification-email', $data, function($message) use ($toemail){
                    	$message->to($toemail)->subject("New Lead: ");
			
		
	                });
		}
            }
        }
    }


  public function otherFields(Request $request){
        $id = $request['id'];
        $data = DB::table('leads')->where('id',$id)->select('mobile_no','source','other_fields','name')->get();
        $a = array();
        $source = null;
        foreach ($data as $key => $value) {
            $source = $value->source;
            $contact = $value->mobile_no;
            $a = json_decode($value->other_fields,true);
            break;
        }

        if (isset($source) && null !== $source && $source == "Facebook") {
            $data = DB::table('facebook_leads')->where('phone_no',$contact)->select('other_fields')->get();
            foreach ($data as $key => $value) {
            
                $a = json_decode($value->other_fields,true);
                break;
            }

        }


        return $a;
    }
public function otherFields1(Request $request){
        $id = $request['id'];
        $data = DB::table('leads')->where('id',$id)->select('other_fields','name')->get();
        $a = array();
        foreach ($data as $key => $value) {
            $a = json_decode($value->other_fields,true);
            break;
        }
        return $a;
    }


public function switchoff(Request $request){
    $switchoff = $request['switchoff'];
    $userid = Auth::user()->id;
    $data = array(
        'auto_assign_leads' =>!$switchoff,
        );
    DB::table('admin_settings')->where('admin_id',$userid)->update($data);
    return Response($data);
} 

}
