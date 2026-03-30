<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TdlAddon extends Model
{
    protected $fillable = [
        'batch_id',
        'tally_serial_no',
        'tcp_filename',
        'tcp_file_format',
        'tcp_version',
        'tcp_expiry_date',
        'tcp_source_type',
        'tcp_author_name',
        'tcp_author_email_id',
        'tcp_author_website',
    ];
}
