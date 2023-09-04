<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use Session;

use App\Models\Project;
use App\Models\Segment;
use App\Models\Campaign;
use DB;

class FacebookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show()
    {   

		$data["projects"]  = Project::where('user_id', Auth::user()->id)->get();
		$data["segments"]  = Segment::where('user_id', Auth::user()->id)->get();
		$data["campaigns"] = Campaign::where([['user_id', Auth::user()->id],['is_active',1]])->get();
		
		$data["fbPages"] = DB::table('subscribe_pages')
		->join('campaigns','campaigns.id','=','subscribe_pages.campaign_id')
		->select("page_id","page_name","form_id","form_name","subscribe_pages.created_at","campaigns.campaigns_name","subscribe_pages.is_active","subscribe_pages.id")
		->where('subscribe_pages.user_id', Auth::user()->id)
		->get();

//echo "<pre>";
//print_r($data); die;		
		return view("facebook-integration",$data);
    }

public function show1()
    {

                $data["projects"]  = Project::where('user_id', Auth::user()->id)->get();
                $data["segments"]  = Segment::where('user_id', Auth::user()->id)->get();
                $data["campaigns"] = Campaign::where([['user_id', Auth::user()->id],['is_active',1]])->get();

                $data["fbPages"] = DB::table('subscribe_pages')
                ->join('campaigns','campaigns.id','=','subscribe_pages.campaign_id')
                ->select("page_id","page_name","form_id","form_name","subscribe_pages.created_at","campaigns.campaigns_name")
                ->where('subscribe_pages.user_id', Auth::user()->id)
                ->get();

//echo "<pre>";
//print_r($data); die;
                return view("fb-integration-test",$data);
    }


public function show2() {

//echo "test";

  date_default_timezone_set('Asia/Kolkata');

        $data = DB::table('lead_corn_campaigns')->select('mobile_no')->where([['status','2'],['is_stopped','0'],['delivery_date_time','like',date('Y-m-d')."%"]])->distinct()->count();


echo $data; die;



}

public function savePageSetting(Request $request) {
    $id = $_POST['id'];
    $status = isset ($_POST['status'])   ? $_POST['status']:"1";
    echo $status;
    DB::table('subscribe_pages')
    ->where('id', $id)
    ->update(['is_active' => $status]);
}

}
