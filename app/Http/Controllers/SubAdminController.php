<?php
namespace App\Http\Controllers;

use Excel;
use Session;
use App\User;
use App\Lead;
use App\Models\SubadminLead;
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
use App\Models\LeadComments;
use App\Models\LeadFollowups;
use App\Models\LeadStatus;

class SubAdminController extends Controller
{

    public function __construct()
    {
       $this->middleware('auth');
    }

    public function add_leads(){

        $data["projects"]  = Project::where('user_id', Auth::user()->id)->get();
        $data["segments"]  = Segment::where('user_id', Auth::user()->id)->get();
        $data["campaigns"] = Campaign::where([['user_id', Auth::user()->id],['is_active',1]])->get();

        return view("subadmin-add-leads",$data);
    }

     function save_leads(Request $request)
    {
        $msg = [
            'name.required'         => 'Lead Name Should Not Be Left Blank',
            'mail_id.required'      => 'Mail ID Should Not Be Left Blank',
            'mobile_no.required'    => 'Mobile Number Should Not Be Left Blank',
            'project_type.required' => 'Project Type Should Not Be Left Blank',
            'segment_type.required' => 'Please Select Any Segment',
            //'campaign_id.required'  => 'Please Select Campaign',
        ];
        
        $this->validate($request, [
            'name'         => 'required',
            'mail_id'      => 'required',
            'mobile_no'    => 'required',
            'project_type' => 'required',
            'segment_type' => 'required',
            //'campaign_id'  => 'required',
        ], $msg);

//print_r($request->all()); die;
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
            return redirect(route('add-leads-subadmin'))->with('error-message', 'This Segment Name Already Taken!! Please 
Provide Another Segment Name!!!');
        }

    date_default_timezone_set("Asia/kolkata");

    $request['mobile_no'] = substr($request['mobile_no'], -10);

    $subadmin = DB::table('users')->select('admin_id')->where('id', '=', Auth::user()->id)->first();
/*
    $lead = new SubadminLead();
    
    $lead->admin_id               = $subadmin->admin_id; //admin id
    $lead->project_id            = $projectid;
    $lead->project_type          = $request->project_type;
    $lead->project_name          = $projectname;
    $lead->segment_id            = $segmentid;
    $lead->segment_type          = $request->segment_type;
    $lead->segment_name          = $segmentname;
   // $lead->campaigns_id          = $request['campaign_id'];
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
    $lead->added_by = Auth::user()->id;
    $lead->status                = 1;
    $lead->campaign_activated_on =  date('Y-m-d H:i:s');
    $lead->save();
    $lastid = $lead->id;


DB::table('subadmin_leads')->insert([
[  'admin_id'               => $subadmin->admin_id, //admin id
    'project_id'            => $projectid,
    'project_type'          => $request->project_type,
   'project_name'        => $projectname,
   'segment_id'            => $segmentid,
   'segment_type'          => $request->segment_type,
   'segment_name'          => $segmentname,
   // $lead->campaigns_id          => $request['campaign_id'],
   'name'                  => $request['name'],
   'mail_id'               => $request['mail_id'],
   'mobile_no'             => $request['mobile_no'],
   'country'               => $request['country'],
   'state'                 => $request['state'],
   'city'                  => $request['city'],
   'zipcode'               => $request['zipcode'],
   'company'               => $request['company'],
   'position'              => $request['position'],
   'address1'              => $request['address1'],
   'address2'              => $request['address2'],
   'source'                => 'Self',
   'added_by' => Auth::user()->id,
   'status'                => 1,
   'campaign_activated_on' =>  date('Y-m-d H:i:s'),
   'created_at' => date('Y-m-d H:i:s')
]
                        ]);


 $lead = new Lead();
    
    $lead->user_id               = $subadmin->admin_id; //admin id
    $lead->project_id            = $projectid;
    $lead->project_type          = $request->project_type;
    $lead->project_name          = $projectname;
    $lead->segment_id            = $segmentid;
    $lead->segment_type          = $request->segment_type;
    $lead->segment_name          = $segmentname;
   // $lead->campaigns_id          = $request['campaign_id'];
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
    $lead->added_by = Auth::user()->id;
    $lead->status                = 1;
    $lead->campaign_activated_on =  date('Y-m-d H:i:s');
    $lead->save();
    $lastid = $lead->id;
*/

DB::table('leads')->insert([
[   'user_id'              => $subadmin->admin_id, //admin id
    'project_id'           => $projectid,
    'project_type'         => $request->project_type,
    'project_name'         => $projectname,
    'segment_id'           => $segmentid,
    'segment_type'         => $request->segment_type,
    'segment_name'         => $segmentname,
    // $lead->campaigns_id => $request['campaign_id'],
   'name'                  => $request['name'],
   'mail_id'               => $request['mail_id'],
   'mobile_no'             => $request['mobile_no'],
   'country'               => $request['country'],
   'state'                 => $request['state'],
   'city'                  => $request['city'],
   'zipcode'               => $request['zipcode'],
   'company'               => $request['company'],
   'position'              => $request['position'],
   'address1'              => $request['address1'],
   'address2'              => $request['address2'],
   'source'                => 'Self',
   'added_by' => Auth::user()->id,
   'status'                => 1,
   'campaign_activated_on' =>  date('Y-m-d H:i:s'),
   'assigned_to' => Auth::user()->id,
   'created_at' => date('Y-m-d H:i:s')
]
                        ]);

    Session::flash('message', "Leads Added Successfully.");
    return back();        
}

public function view_leads_subadmin(){
       // $lead  = DB::table('subadmin_leads')->where('assigned_to',Auth::user()->id)->orderby('lead_assigned_on','desc')->get();
       $lead = DB::table('subadmin_leads')->where('added_by',Auth::user()->id)->get();
       
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

        return view('view-subadmin-leads',array("leaddata" => $lead,"attendedLeads" => $attendedLeads));
    }

public function leadsRetrival() {

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
        $where1 = array('leads.added_by','=',Auth::user()->id);
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
            
        $lead = 
DB::table('leads')->where($whereArray)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter1)
        
        ->select('leads.lead_assigned_on','leads.assigned_to','leads.lead_status','leads.created_at','leads.is_cron_disabled',
            'leads.id', 'leads.name', 'leads.mail_id', 'leads.mobile_no', 'leads.project_name', 
'leads.segment_name','leads.source')       
        ->offset($offset)
        ->limit($limit)
        ->get();
        
        $search = json_encode($_REQUEST['search']);
        
        foreach( $_REQUEST['search'] as $key => $value) {
           $search = trim($value);
                   break;       
        }
        
        
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
        
        $count = DB::table('leads')->where($whereArray)->count();


        $s = DB::table('leads')->where($whereArray)->toSql();

        $filterCount =  
DB::table('leads')->where($whereArray)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter1)->count();
        
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

    $lead =  
DB::table('leads')->where($whereArray)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter1)
        ->whereIn('leads.id', $attendedLeads)
        ->join('campaigns', 'leads.campaigns_id', '=', 'campaigns.id')
        ->select('leads.lead_assigned_on','leads.assigned_to','leads.lead_status','leads.created_at','leads.is_cron_disabled',
        'campaigns.campaigns_name', 'leads.id', 'leads.name', 'leads.mail_id', 'leads.mobile_no', 'leads.project_name', 
'leads.segment_name','leads.source')
    
        ->offset($offset)
        ->limit($limit)
        ->get();
  
    $count = DB::table('leads')->where('assigned_to',Auth::user()->id)->count();

    $filterCount = 
DB::table('leads')->where($whereArray)->orWhere($orWhereFilter4)->orWhere($orWhereFilter5)->orWhere($orWhereFilter1)->whereIn('leads.id', 
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

        $result = array("search" => $whereArray,"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => 
$filterCount);
    
        $result['data'] = $lead;
        print_r(json_encode($result));
    }

    public function leadsRetrival1() {

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
        $where1 = array('subadmin_leads.added_by','=',Auth::user()->id);
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
'subadmin_leads.segment_name','subadmin_leads.source')
       
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
        ->where('user_id',Auth::user()->id)
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

    public function getLeadComments() {
        $leadId = $_POST['leadId'];
        $comments = LeadComments::where('lead_id',$leadId)->get();
        $lead =DB::table('leads')->where('id', '=', $leadId)->select("lead_status","mobile_no","mail_id")->get()[0];
        return json_encode(array('data'=>$comments,'status' => $lead->lead_status,'leadContact' => $lead->mobile_no,'leadEmail'=> $lead->mail_id ));
    }

    public function addLeadComment() {
        if (strlen(trim($_POST['comment'])) > 0) {
            $leadComment = new LeadComments();
            $leadComment->user_id = Auth::user()->id;
            $leadComment->lead_id = $_POST['leadId'];
            $leadComment->comments = $_POST['comment'];
            $leadComment->status = isset($_POST['leadStatus']) ? $_POST['leadStatus'] : "0";
          
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

            DB::table('leads')
            ->where('id', $_POST['leadId'])
            ->update(['lead_status' => $_POST['leadStatus']]);
            
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

            //$where5 = array();
       // $where5 = array('leads.is_active', 1);
            
        //$whereArray = array($where1, $where2, $where3, $where4, $where5);
        $whereArray = array($where1, $where2, $where3, $where4);
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
            $orWhereFilter1[] = array('subadmin_leads.mobile_no','like',"$searchValue%");
            $orWhereFilter2[] = array('subadmin_leads.mail_id','like',"$searchValue%");          
            $orWhereFilter3[] = array('subadmin_leads.name','like',"$searchValue%");
            $orWhereFilter4[] = array('subadmin_leads.project_name','like',"$searchValue%");
        }
                
       // $lead  = 
Lead::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)->orWhere($orWhereFilter3)->orWhere($orWhereFilter4)->offset($offset)->limit($limit)->get();
        $leadFollowups = LeadFollowups::select('leads_followups.*','subadmin_leads.name','subadmin_leads.mobile_no','subadmin_leads.project_name','subadmin_leads.source')
        ->join('subadmin_leads','subadmin_leads.id','=','leads_followups.subadmin_lead_id')
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
        
        $count = LeadFollowups::select('leads_followups.*','subadmin_leads.name','subadmin_leads.mobile_no','subadmin_leads.project_name')
        ->join('subadmin_leads','subadmin_leads.id','=','leads_followups.subadmin_lead_id')
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
        $where2 = array('leads_followups.followup_date','=',$currentdate);
        $where3 = array('leads_followups.followup_time','<',$currenttime[1]);
        $where4 = array('leads_followups.status','=',null);
       // $where5 = array('leads.is_active', 1);
        
       // $whereArray = array($where1, $where2, $where3, $where4, $where5);
        $whereArray = array($where1, $where2, $where3, $where4);

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
            $orWhereFilter1[] = array('subadmin_leads.mobile_no','like',"$searchValue%");
            $orWhereFilter2[] = array('subadmin_leads.mail_id','like',"$searchValue%");          
            $orWhereFilter3[] = array('subadmin_leads.name','like',"$searchValue%");
            $orWhereFilter4[] = array('subadmin_leads.project_name','like',"$searchValue%");
        }
                
       // $lead  = 
Lead::where($whereArray)->orWhere($orWhereFilter1)->orWhere($orWhereFilter2)->orWhere($orWhereFilter3)->orWhere($orWhereFilter4)->offset($offset)->limit($limit)->get();
        $leadFollowups = LeadFollowups::select('leads_followups.*','subadmin_leads.name','subadmin_leads.mobile_no','subadmin_leads.project_name','subadmin_leads.source')
        ->join('subadmin_leads','subadmin_leads.id','=','leads_followups.subadmin_lead_id')
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
        
        $count = LeadFollowups::select('leads_followups.*','subadmin_leads.name','subadmin_leads.mobile_no','subadmin_leads.project_name')
        ->join('subadmin_leads','subadmin_leads.id','=','leads_followups.subadmin_lead_id')
        ->where($whereArray)
        ->orWhere($orWhereFilter1)
        ->orWhere($orWhereFilter2)
        ->orWhere($orWhereFilter3)
        ->orWhere($orWhereFilter4)
        ->count();
        
        $filterCount =  $count;        
        $result = array("search" => $whereArray,"draw" => $_REQUEST['draw'],"recordsTotal" => $count,"recordsFiltered" => $filterCount);//

    
        $result['data'] = $leadFollowups;
        
        print_r(json_encode($result));
    }

	 public function edit_leads(Request $request, $leadsid){

        $data["projects"] = Project::where('user_id',Auth::user()->id)->get();
        $data["segments"] = Segment::where('user_id',Auth::user()->id)->get();
        //$data["campaigns"] = Campaign::where('user_id',Auth::user()->id)->get();
        //$data["campaigns"] = Campaign::where([['user_id', Auth::user()->id],['is_active',1]])->get();
        $data["leadsdata"] = DB::table('subadmin_leads')->where('id', '=', $leadsid)->get()[0];

        return view('edit-subadmin-lead',$data);
    }

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
                $project->for_source   = 'Self';
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
        
    
        $data = array(
            'project_id'                  => $projectid,
            'project_type'                => $request->project_type,
            'project_name'                => $projectname,

            'segment_id'                  => $segmentid,
            'segment_type'                => $request->segment_type,
            'segment_name'                => $segmentname,

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

$affected = DB::table('subadmin_leads')
              ->where('id', $leadsid)
              ->update($data);
        
        

        Session::flash('message', "Leads Updated successfully.");
        return redirect('view-leads-subadmin');
        //return redirect('edit-leads/'.$leadsid);
    }


}
