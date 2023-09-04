<?php

namespace App\Http\Controllers;

use Session;
use App\Models\SmsCampaignsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BulkSmsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewBulkSmsCampaign(Request $request, $id) {

        $data["campaignname"] = DB::table('sms_campaigns')->where('id',$id)->first();

        $data['smscount'] = DB::table('sms_automations')
            ->select('sms_automations.user_id','sms_automations.series_name','sms_automations.automation_type','bulk_sms_automation_message.id','bulk_sms_automation_message.message')
            ->join('bulk_sms_automation_message','bulk_sms_automation_message.series_id','=','sms_automations.id')
            ->where('sms_automations.user_id', Auth::user()->id)
            ->where('sms_automations.campaigns_id', $id)
            ->count();

        return view('view-bulk-sms-campaigns-automations', $data);
    }

    public function viewBulkSms() {

        $data['smsCampaigns'] = DB::table('sms_campaigns')->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();

        return view('sms-campaign-master', $data);
    }

    public function addBulkSms() {

        return view('add-sms-campaigns');
    }

    public function saveBulkSms(Request $request) {

        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        $this->validate(request(),[
            'campaigns_name'    => 'required',
        ]);

        $bulkSms = new SmsCampaignsModel();
        $bulkSms->user_id            = Auth::user()->id;
        $bulkSms->sms_campaigns_name = $request['campaigns_name'];
        $bulkSms->campaigns_id       = substr(str_shuffle($str_result),  0, 15);
        $bulkSms->save();

        Session::flash('message', "SMS Campaign Added successfully.");

        return redirect('add-sms-campaign');
    }

    public function editBulkSms(Request $request, $id) {

        $data["campaignname"] = DB::table('sms_campaigns')->where('id',$id)->first();

        return view('edit-bulk-sms-campaign', $data);
    }

    public function updateBulkSms(Request $request, $id) {

        $data = array(
            'sms_campaigns_name'  => $request['campaigns_name'],
        );

        DB::table('sms_campaigns')->where('id', $id)->update($data);

        Session::flash('message', "SMS Campaign Name Updated successfully.");

        return redirect('edit-sms-campaign/'.$id);
    }

}
