<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalOpportunity extends Model
{
    protected $table = 'additional_opportunity';

    protected $fillable = [
        'company_name',
        'category_id',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
