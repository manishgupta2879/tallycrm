<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Searchable;
use App\Traits\LogsActivity;

class Company extends Model
{
    use HasFactory, Searchable, LogsActivity;

    protected $searchable = ['pid', 'name', 'contact_name', 'email', 'mobile'];

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
        'no_of_urls',
    ];

    protected $casts = [
        'd_types' => 'array',
        'd_parameter' => 'array',
        'c_urls' => 'array',
    ];
}