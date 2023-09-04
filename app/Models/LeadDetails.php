<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class LeadDetails extends Model
{
    protected $table='lead_details';
    public $timestamps=true;

    protected $fillable=['lead_id','automation_messages_id','delivery_date_time'];
}
