<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'parent_type',
        'name',
        'desig',
        'email',
        'mobile',
        'loc',
    ];

    /**
     * Get the parent model (distributor, company, etc.) that the contact belongs to.
     */
    public function parent()
    {
        return $this->morphTo();
    }
}
