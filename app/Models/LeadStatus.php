<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    protected $table='leads_status';
    public $timestamps=true;

    protected $fillable=['user_id','lead_id','status'];

}
