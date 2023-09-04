<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationSeries extends Model
{
    //
    protected $table='automations';
    public $timestamps=true;

    protected $fillable=['user_id','series_name','automation_type','campaign_id'];


    public function getUser(){
        return $this->belongsTo('App\User','user_id');
    }

    public function getseriessms(){
        return $this->belongsTo('App\Models\SmsSeriesmessage','series_id');
    }


}
