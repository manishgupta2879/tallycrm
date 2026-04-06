<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

use Illuminate\Database\Eloquent\Casts\Attribute;

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

    /**
     * Accessor and Mutator for formatted tss expiry date
     */
    protected function tssExpiryDate(): Attribute
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

// Removed company/distributor relations to match simplified schema
}
