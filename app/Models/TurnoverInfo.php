<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TurnoverInfo extends Model
{
    protected $fillable = [
        'tally_serial_no',
        'dist_name',
        'financial_year',
        'sales_turnover',
    ];
}
