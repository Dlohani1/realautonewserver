<?php

namespace App\Imports;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Segment;
use App\Models\Campaign;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
use Auth;



class LeadsImport implements ToModel,WithHeadingRow
{

	/**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        if($request->project_type == 2){ // FOR NEW
            $project = new Project();
                $project->user_id      = Auth::user()->id;
                $project->project_name = $request['project_name'];
                $project->for_source   = 'Facebook';
                $project->status       = 1;
            $project->save();
            $projectid = $project->id;
            $projectname =  $request['project_name'];
        }else{
            $projectid = $request['project_id'];
            $projectname = Project::where('id',$projectid)->get()[0]->project_name;
        }

        if($request->segment_type == 2){ // FOR NEW
            $segment = new Segment();
                $segment->user_id      = Auth::user()->id;
                $segment->segment_name = $request['segment_name'];
                $segment->for_source   = 'Facebook';
                $segment->status       = 1;
            $segment->save();
            $segmentid = $segment->id;
            $segmentname = $request['segment_name'];
        }else{
            $segmentid = $request['segment_id'];
            $segmentname = Segment::where('id',$segmentid)->get()[0]->segment_name;
        }

	if($row['name'] !='' && $row['mobile_no'] !=''){
        $data = array(

                        'user_id'              => Auth::user()->id,
                        'project_id'           => $projectid,
                        'project_type'         => $row['project_type'],
                        'project_name'         => $projectname,

                        'segment_id'           => $segmentid,
                        'segment_type'         => $row['segment_type'],
                        'segment_name'         => $segmentname,

                        'campaigns_id'         =>$row['campaign_id'],


                        'name'                 =>$row['name'],
                        'areaid'               =>$row['areaid'],
                        'status'               =>$row['status'],
                        'possession'           =>$row['possession'],
                        'lead_type'            =>$row['lead_type']
                        );
        return new Lead($data);
	}


    }

}
