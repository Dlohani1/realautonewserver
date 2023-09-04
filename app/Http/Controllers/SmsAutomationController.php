<?php

namespace App\Http\Controllers;

use Session;
use App\Models\SmsCampaignsModel;
use App\Models\SmsAutomationModel;
use App\Models\BulkSmsAutomationMessageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

class SmsAutomationController extends Controller
{
    public function getBulkSmsAutomation() {

        $segment = DB::table('segment')->select('id', 'user_id', 'segment_name')->where('user_id', Auth::user()->id)->get();

        return view('bulk-sms-campaigns-another', compact('segment'));
    }

    public function saveWhatsappAutomationCamp(Request $request) {
        //dd($request->input());
        $this->validate(request(),[
            'campaigns_name' => 'required',
            'series_name'    => 'required',
            'message'        => 'required',
            //'image1'         => 'max:1000000000000000|image|mimes:jpeg,png,jpg,gif',
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

        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        $bulkSms = new SmsCampaignsModel();
        $bulkSms->user_id            = Auth::user()->id;
        $bulkSms->sms_campaigns_name = $request['campaigns_name'];
        $bulkSms->campaigns_id       = substr(str_shuffle($str_result),  0, 15);
        $bulkSms->save();

        $smsauto = new SmsAutomationModel();
        $smsauto->user_id         = Auth::user()->id;
        $smsauto->campaigns_id    = $bulkSms->id;
        $smsauto->series_name     = $request['series_name'];
        $smsauto->automation_type = 3;
        $smsauto->save();

        $bsamt = new BulkSmsAutomationMessageModel();
        $bsamt->user_id             = Auth::user()->id;
        $bsamt->series_id           = $smsauto->id;
        $bsamt->segment_id          = $request['segment_id'];
        $bsamt->message             = $request['message'];
        $bsamt->custom_full_name    = $request['custom_full_name'];
        $bsamt->image               = $request['image_link'];
        $bsamt->save();
		
		$leads = DB::table('leads')->select('name','mobile_no')->where('segment_id', $request['segment_id'])->get();
		
		if (null !== $leads) {
			foreach($leads as $key => $value) {
				$recieversMobileNo = $value->mobile_no;
				$recieversName = $value->name;
				$message = $request['message'];
				$attachment = $request['image_link'];
				testsendwhatsapp($recieversMobileNo,str_replace("{Full Name}",$recieversName,$message),$attachment);
			}
		}
		
        Session::flash('message', "Bulk Whatsapp Fired successfully.");

        return redirect('bulk-sms-campaign');
    }

    public function viewBulkSmsAutomation(Request $request, $id) {

        $segment = DB::table('segment')->select('id', 'user_id', 'segment_name')->where('user_id', Auth::user()->id)->get();

        return view('bulk-whatsapp-automation', compact('segment'));
    }

    public function saveWhatsappAutomation(Request $request) {

        $this->validate(request(),[
            'series_name'       => 'required',
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

        $smsauto = new SmsAutomationModel();
        $smsauto->user_id         = Auth::user()->id;
        $smsauto->campaigns_id    = $request['campaignsid'];
        $smsauto->series_name     = $request['series_name'];
        $smsauto->automation_type = 3;
        $smsauto->save();

        $bsamt = new BulkSmsAutomationMessageModel();
        $bsamt->user_id             = Auth::user()->id;
        $bsamt->series_id           = $smsauto->id;
        $bsamt->segment_id          = $request['segment_id'];
        $bsamt->message             = $request['message'];
        $bsamt->custom_full_name    = $request['custom_full_name'];
        $bsamt->image               = $request['image_link'];
        $bsamt->save();

		$leads = DB::table('leads')->select('name','mobile_no')->where('segment_id', $request['segment_id'])->get();
		
		if (null !== $leads) {
			foreach($leads as $key => $value) {
				$recieversMobileNo = $value->mobile_no;
				$recieversName = $value->name;
				$message = $request['message'];
				$attachment = $request['image_link'];
				testsendwhatsapp($recieversMobileNo,str_replace("{Full Name}",$recieversName,$message),$attachment);
			}
		}
		
        Session::flash('message', "Bulk Whatsapp Fired successfully.");

        return redirect('bulk-whatsapp-automation/'.$request['campaignsid']);
    }

    public function editWhatsappAutomation(Request $request, $id) {

        $data['segment'] = DB::table('segment')->select('id', 'user_id', 'segment_name')->where('user_id', Auth::user()->id)->get();

        $data['smsseries'] = DB::table('sms_automations')
            //->select('sms_automations.user_id','sms_automations.series_name','sms_automations.automation_type','bulk_sms_automation_message.id','bulk_sms_automation_message.message','bulk_sms_automation_message.delivery_day','bulk_sms_automation_message.delivery_time','bulk_sms_automation_message.segment_id','bulk_sms_automation_message.custom_full_name','bulk_sms_automation_message.message','bulk_sms_automation_message.image')
            ->select('sms_automations.user_id','sms_automations.series_name','sms_automations.automation_type','bulk_sms_automation_message.id','bulk_sms_automation_message.message','bulk_sms_automation_message.segment_id','bulk_sms_automation_message.custom_full_name','bulk_sms_automation_message.message','bulk_sms_automation_message.image')
            ->join('bulk_sms_automation_message','bulk_sms_automation_message.series_id','=','sms_automations.id')
            ->where('bulk_sms_automation_message.id', $id)
            ->first();

        return view('edit-bulk-whatsapp-automation', $data);
    }

    public function editWhatsappAutomationPost(Request $request, $id) {

        $this->validate(request(),[
            'series_name'       => 'required',
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
            'series_name'             => $request['series_name'],
        );

        $data1 = array(
            'message'                 => $request['message'],
            'custom_full_name'        => $request['txt_full_name'],
            'image'                   => $request['image_link'],
        );

        DB::table('sms_automations')->where('id', $id)->update($data);

        DB::table('bulk_sms_automation_message')->where('id', $id)->update($data1);

        Session::flash('message', "Bulk Whatsapp Automation Updated successfully.");

        return redirect('edit-bulk-whatsapp-automation/'.$id);
    }

}
