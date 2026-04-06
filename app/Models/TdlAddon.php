<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;

class TdlAddon extends Model
{
    protected $fillable = [
        'batch_id',
        'tally_serial_no',
        'tcp_filename',
        'tcp_filepath',
        'tcp_file_format',
        'tcp_version',
        'tcp_expiry_date',
        'tcp_source_type',
        'tcp_author_name',
        'tcp_author_email_id',
        'tcp_author_website',
    ];

    /**
     * Accessor and Mutator for formatted expiry date
     */
    protected function tcpExpiryDate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) return $value;
                try {
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                        // If already in Y-m-d format, convert to d/m/Y for display
                        return \Carbon\Carbon::parse($value)->format('d/m/Y');
                    }
                    return \Carbon\Carbon::parse($value)->format('d/m/Y');
                } catch (\Exception $e) {
                    return null;
                }
            },
            set: function ($value) {
                if (empty($value)) return null;
                try {
                    return \Carbon\Carbon::parse($value)->format('Y-m-d');
                } catch (\Exception $e) {
                    return null;
                }
            }
        );
    }
}
