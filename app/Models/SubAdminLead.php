<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubadminLead extends Model
{
    public $table = "subadmin_leads";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'id',
        'admin_id',
        'project_id',
        'segment_id',
        'campaigns_id',
        'name',
        'mail_id',
        'mobile_no',
        'country',
        'state',
        'city',
        'zipcode',
        'company',
        'position',
        'address1',
        'address2',
        'fb_page_name',
        'fb_page_desc',
        'fb_id',
        'whats_your_business_name',
        'source',
        'project_name',
        'is_assigned',
        'status',
		'is_cron_disabled',
        'added_by'
    ];
}

