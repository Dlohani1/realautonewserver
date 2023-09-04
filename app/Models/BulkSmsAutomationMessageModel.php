<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkSmsAutomationMessageModel extends Model
{
    protected $table   = "bulk_sms_automation_message";
    public $timestamps = true;

    public function getUser(){
        return $this->belongsTo('App\User','user_id');
    }
}
