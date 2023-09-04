<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsCampaignsModel extends Model
{
    protected $table   = "sms_campaigns";
    public $timestamps = true;


    public function getUser(){
        return $this->belongsTo('App\User','user_id');
    }
}
