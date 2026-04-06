<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; // Added for modern attributes

use App\Traits\Searchable;
use App\Traits\LogsActivity;

class Distributor extends Model
{
    use HasFactory, Searchable, LogsActivity;

    protected $searchable = [
        'code',
        'name',
        'type',
        'company_code',
        'city',
        'state',
        'company.name',
        'contacts.name',
        'contacts.mobile'
    ];

    protected $fillable = [
        'code',
        'name',
        'type',
        'company_code',
        'address',
        'country', // string
        'state',   // string
        'gst_number',
        'pan_number',
        'status',
        'params',
        // Tally details
        'tally_serial',
        'tally_version',
        'tally_release',
        'tally_expiry',
        'tally_edition',
        'tally_net_id',
        'tally_users',
        'tally_deployed',
        'no_of_computers',
        'existing_provider',
        'tally_data_volume',
        'tally_cloud',
        // Rollout / Additional details
        'rollout_request_date',
        'tcp_generated_date',
        'rollout_done_date',
        'rollout_done_by',
        'rollout_remarks',
        'remarks_date',
        // Sync information
        'dist_perm_pass',
        'last_sync_date',
        'no_of_sync_urls',
        'c_urls',
        'tan_no',
        'msme_no',
        // New fields
        'userid',
        'authcode',
        'authcode2',
    ];

    protected $casts = [
        'params' => 'array',
        'tally_cloud' => 'boolean',
        'c_urls' => 'encrypted:array',
        'dist_perm_pass' => 'encrypted',
    ];

    /**
     * Get the contacts for the distributor.
     */
    public function contacts()
    {
        return $this->morphMany(Contact::class, 'parent');
    }

    /**
     * Get the principal company for the distributor.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_code', 'pid');
    }

    public function geoCountry()
    {
        return $this->belongsTo(Geo::class, 'country', 'id');
    }

    public function geoRegion()
    {
        return $this->belongsTo(Geo::class, 'region', 'id');
    }

    public function geoState()
    {
        return $this->belongsTo(Geo::class, 'state', 'id');
    }

    public function geoCity()
    {
        return $this->belongsTo(Geo::class, 'city', 'id');
    }

    /**
     * Accessors and Mutators for formatted dates
     */
    protected function rolloutDoneDate(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $this->formatDateDisplay($v),
            set: fn($v) => $this->formatDateSave($v)
        );
    }

    protected function lastSyncDate(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $this->formatDateDisplay($v),
            set: fn($v) => $this->formatDateSave($v)
        );
    }

    protected function tallyExpiry(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $this->formatDateDisplay($v),
            set: fn($v) => $this->formatDateSave($v)
        );
    }

    protected function tcpGeneratedDate(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $this->formatDateDisplay($v),
            set: fn($v) => $this->formatDateSave($v)
        );
    }

    protected function rolloutRequestDate(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $this->formatDateDisplay($v),
            set: fn($v) => $this->formatDateSave($v)
        );
    }

    protected function remarksDate(): Attribute
    {
        return Attribute::make(
            get: fn($v) => $this->formatDateDisplay($v),
            set: fn($v) => $this->formatDateSave($v)
        );
    }

    private function formatDateDisplay($value)
    {
        if (!$value) return $value;
        try {
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) return $value;
            return \Carbon\Carbon::parse($value)->format('d/m/Y');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function formatDateSave($value)
    {
        if (empty($value)) return null;
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
