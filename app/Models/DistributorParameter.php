<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributorParameter extends Model
{
    protected $table = 'distributor_parameters';
    protected $fillable = [
        'tallyserialno',
        'principalid',
        'distributorid',
        'distname',
        'p1',
        'p2',
        'p3',
        'p4',
        'p5',
        'p6',
        'p7',
        'p8',
        'p9',
        'p10',
    ];
}
