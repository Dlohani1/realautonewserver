<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsSeriesmessage extends Model
{
    protected $table='sms_automation_messages';
    public $timestamps=true;

    protected $fillable=['user_id','series_id','delivery_type','message','delivery_time','delivery_day'];

    public function getUser(){
        return $this->belongsTo('App\User','user_id');
    }


}
