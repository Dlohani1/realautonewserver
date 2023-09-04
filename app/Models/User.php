<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table='users';
    public $timestamps=true;

    protected $fillable=['name','email','phone_no'];


}
