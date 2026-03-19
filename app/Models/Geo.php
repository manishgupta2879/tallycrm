<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Geo extends Model
{
    protected $table = 'geo';
    protected $primaryKey = 'geo_id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'pid',
        'rid',
        'id',
        'name',
        'nature',
    ];
}
