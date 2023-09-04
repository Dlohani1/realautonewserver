<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadComments extends Model
{
    protected $table='lead_comments';
    public $timestamps=true;

    protected $fillable=['user_id','lead_id','comments'];


}
