<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class TallyLog extends Model
{
    use HasFactory, LogsActivity;

    const UPDATED_AT = null;

    protected $fillable = [
        'tally_serial_no',
        'tally_version',
        'tally_release',
        'tally_edition',
        'account_id',
        'tss_expiry_date',
        'created_at'
    ];

// Removed company/distributor relations to match simplified schema
}
