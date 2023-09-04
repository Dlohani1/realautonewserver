<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use DB;

class VideoController extends Controller 
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function videos()
    {
        $data['videos'] = DB::table('real_tutorial')->get();
        

	return view('video',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function savevideos(Request $request)
    {
         $msg = [
            'url.required' => 'Enter Video Url',
            'type.required' => 'Please Select Type',
            'topic.required' => 'Enter Your Topic',
        ];
        $this->validate($request, [
            'url' => 'required',
            'type' => 'required',
            'topic' => 'required',
        ], $msg);
        $type =  $request->get('type');
        $video_type =  $request->get('video_type');
        if($type != 0 && $video_type != 0){
        $data = array(
            'url' =>$request->get('url'),
            'type' =>$request->get('type'),
            'video_type' =>$request->get('video_type'),
            'topic' =>$request->get('topic'),
            'is_active' => '1',
        );
        DB::table('real_tutorial')->insert($data);
        Session::flash('message', "Records Insert successfully.");
        }
        else{
            Session::flash('error', "Please Select Type");
        }
        return back();
    }

    public function gallery()
    {
        $data['tutorial'] = DB::table('tutorial')->where('is_active','1')->paginate(30);
        return view('tutorial.gallery', $data);
    }
}
