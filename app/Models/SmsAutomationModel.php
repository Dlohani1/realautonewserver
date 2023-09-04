<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsAutomationModel extends Model
{
    protected $table   = "sms_automations";
    public $timestamps = true;


    public function getUser(){
        return $this->belongsTo('App\User','user_id');
    }

    public function getseriessms(){
        return $this->belongsTo('App\Models\BulkSmsAutomationMessageModel','series_id');
    }
}
