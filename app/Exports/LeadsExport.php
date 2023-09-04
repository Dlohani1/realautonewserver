<?php
namespace App\Exports;
  
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\Auth;
use DB;

class LeadsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents

{
	protected $campaignId;
	protected $toDate;
	protected $fromDate;

 function __construct($filter) {
       // $this->campaignId = $filter['campaignId'];
		$this->toDate = $filter['toDate'];
		$this->fromDate = $filter['fromDate'];
 
 }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }
	
    public function collection()
    { 
		$from = date($this->fromDate);
		$to = date($this->toDate);
		
		$test = DB::table('leads')->select('leads.id','name','mail_id','mobile_no','project_name','campaigns.campaigns_name','segment_name','source','leads.created_at')
		->join('campaigns','campaigns.id','=','leads.campaigns_id')
		->where('leads.assigned_to',Auth::user()->id)
		->whereDate('leads.created_at','>=',$this->fromDate)
		->whereDate('leads.created_at','<=',$this->toDate)
		
		->get();
		$i = 0;
		foreach($test as $key => $value) {
			 $j = $i+1;
			 $test[$i]->id = $j;
			 $test[$i]->name = $value->name;
			 $test[$i]->mail_id = $value->mail_id;
			 $test[$i]->mobile_no = $value->mobile_no;
	                 $test[$i]->created_at = date("F jS, Y, g:i a",strtotime($value->created_at));

			 $i++;
		}
		
		//print_r($test); die;
		return $test;
	}
	
	public function headings(): array

    {

        return [
			'Sl.no',
            		'Name',
            		'Email',
            		'Phone',
			'Project',
			'Campaign',
			'Segment',
			'Source',
			'Date'
        ];

    }
}
