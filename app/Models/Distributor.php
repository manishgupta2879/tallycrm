<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    use HasFactory;

    protected $fillable = [
        'pid',
        'name',
        'distributor_type',
        'company_pid',
        'address',
        'city',
        'state',
        'pin_code',
        'gst_no',
        'pan_no',
        'contact_name',
        'designation',
        'email',
        'mobile',
        'distributor_location',
        'status',
        'd_parameters',
        'c_urls',
    ];

    protected $casts = [
        'd_parameters' => 'array',
        'c_urls'       => 'array',
    ];
}