<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table='project_master';
    public $timestamps=true;

    protected $fillable=['user_id','project_name','status'];


}
