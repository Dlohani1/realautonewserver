<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $table='campaigns';
    public $timestamps=true;

    protected $fillable=['user_id','campaigns_name','status','campaigns_id'];


    public function getUser(){
        return $this->belongsTo('App\User','user_id');
    }

    public function getautomations(){
        return $this->belongsTo('App\Models\AutomationSeries','campaign_id');
    }
}
