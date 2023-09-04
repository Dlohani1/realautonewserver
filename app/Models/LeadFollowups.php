<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadFollowups extends Model
{
    protected $table='leads_followups';
    public $timestamps=true;

    protected $fillable=['user_id','lead_id','followup_date','followup_time','comments'];


}
