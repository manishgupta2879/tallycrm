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
        'c_urls' => 'encrypted:array',
    ];

    /**
     * Get the distributors for this company.
     */
    public function distributors()
    {
        return $this->hasMany(Distributor::class, 'company_code', 'pid');
    }

    /**
     * Get the count of distributors for this company.
     */
    public function distributorsCount()
    {
        return $this->distributors()->count();
    }
}
