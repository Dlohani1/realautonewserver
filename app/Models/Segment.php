<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    protected $table='segment';
    public $timestamps=true;

    protected $fillable=['user_id','segment_name','for_source','status'];


}
