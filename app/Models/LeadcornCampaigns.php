<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class LeadcornCampaigns extends Model
{
    protected $table='lead_corn_campaigns';
    public $timestamps=true;

    protected $fillable=['user_id','name','mail_id','mobile_no','automation_type','delivery_date_time','message','image','status'];
}
