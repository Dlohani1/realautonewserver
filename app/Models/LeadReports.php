<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class LeadReports extends Model
{
    protected $table='lead_reports';
    public $timestamps=true;

    protected $fillable=['user_id','name','mail_id','mobile_no','automation_type','delivery_date_time','message','image','status'];
}
