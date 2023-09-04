<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use DB;

class UsersExport implements FromView
{

	protected $campaignId;
	protected $toDate;
	protected $fromDate;

	function __construct($filter) {
		//$this->campaignId = $filter['campaignId'];
		$this->toDate = $filter['toDate'];
		$this->fromDate = $filter['fromDate'];
	}
 
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    { 
    	$data = Db::table('leads')
    	->leftjoin('users','users.id','=','leads.assigned_to')
        ->where('leads.user_id',Auth::user()->id)
	->where('leads.is_active',1)

	->whereDate('leads.created_at','>=',$this->fromDate)
	->whereDate('leads.created_at','<=',$this->toDate)
    	->select('leads.id','leads.created_at','source','leads.name','users.name as subadmin','mobile_no','mail_id','lead_assigned_on','project_name','lead_status')
    	->orderby('lead_assigned_on','asc')
    	->get();

/*
   	echo  "<pre>";

   print_r($data); die;
  
*/
    	$ids = [];

    	foreach ($data as $key => $value) {
    		$ids[] = $value->id;
    	}

    	$leadComments = DB::table('lead_comments')
		->join('users','users.id','=','lead_comments.user_id')
		->select('name','lead_id','comments','lead_comments.created_at')
		->whereIn('lead_id', $ids)
		->orderby('lead_id')
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
    				$data[$key1]->comments[$i]['comment'] = htmlentities($value->comments);
    			}
    		}
			
			if ($k > $j) { 
				$j = $k;
			}		
    	}
	//echo "<pre>"; print_r($data); die;	
        return view('excel.export',array('leads'=>$data,'commentTotal' => $j));
    }
}
