<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'pid',
        'name',
        'contact_name',
        'designation',
        'email',
        'mobile',
        'territory',
        'status',
        'd_types',
        'd_parameter',
        'c_urls',
    ];

    protected $casts = [
        'd_types' => 'array',
        'd_parameter' => 'array',
        'c_urls' => 'array',
    ];
}