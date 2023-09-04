<?php

namespace App\Exports;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use DB;

class SubAdminExport implements FromView
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public $created_at_from;
    public $created_at_to;

    public function __construct($data)
    {
        $this->created_at_from = $data['fromDate'];
        $this->created_at_to = $data['toDate'];
    }


    public function view(): View
    {
    	
    	
    	$data = Db::table('leads')
    	->join('users','users.id','=','leads.assigned_to')
        ->where('leads.assigned_to',Auth::user()->id)
		->whereDate('leads.created_at','>=',$this->created_at_from)
		->whereDate('leads.created_at','<=',$this->created_at_to)
    	->select('leads.id','leads.created_at','source','leads.name','users.name as subadmin','mobile_no','mail_id','lead_assigned_on','project_name','lead_status')
    	->orderby('lead_assigned_on','asc')
    	->get();
         
    	$ids = [];

    	foreach ($data as $key => $value) {
    		$ids[] = $value->id;
    	}

    	$leadComments = DB::table('lead_comments')
    	->join('users','lead_comments.user_id','=','users.id')
		->select('lead_comments.lead_id','lead_comments.comments','lead_comments.created_at','users.name')
		->whereIn('lead_comments.lead_id', $ids)
		->orderby('lead_comments.lead_id')
		->get();
        
		
        
    	    $j = 0;
        foreach ($data as $key1 => $value1) {
                $i  =  -1;
                        $k = 0;
                foreach ($leadComments as $key => $value) {

                        if ($value->lead_id == $value1->id ) {
                                        $k++;
                                $i++;
                                        $d = date("F jS, Y, g:i a",strtotime($value->created_at));
                                        $a = explode(',',$d);

                                        $data[$key1]->comments[$i]['comment_date'] = $a[0].",".$a[1]. " at ". $a[2];
                                        $data[$key1]->comments[$i]['comment_by'] = $value->name;
                                        $data[$key1]->comments[$i]['comment'] = $value->comments;
                        }
                }
                        if ($k > $j) { $j = $k; }

        }

        return view('excel.subadmin_leads',array('leads'=>$data,'commentTotal'=>$j));
    }
	  
}


