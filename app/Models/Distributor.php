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
        'country',
        'region',
        'city',
        'state',
        'pincode',
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
        'tcp_version',
        'tcp_source',
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
    ];

    protected $casts = [
        'params' => 'array',
        'tally_cloud' => 'boolean',
        'tally_expiry' => 'date',
        'rollout_request_date' => 'date',
        'tcp_generated_date' => 'date',
        'rollout_done_date' => 'date',
        'remarks_date' => 'date',
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
     * Modern Attribute: Tally Expiry
     */
    protected function tallyExpiry(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '',
            set: fn ($value) => $value ? \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null,
        );
    }

    /**
     * Modern Attribute: Rollout Request Date
     */
    protected function rolloutRequestDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '',
            set: fn ($value) => $value ? \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null,
        );
    }

    /**
     * Modern Attribute: TCP Generated Date
     */
    protected function tcpGeneratedDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '',
            set: fn ($value) => $value ? \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null,
        );
    }

    /**
     * Modern Attribute: Rollout Done Date
     */
    protected function rolloutDoneDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '',
            set: fn ($value) => $value ? \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null,
        );
    }

    /**
     * Modern Attribute: Remarks Date
     */
    protected function remarksDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '',
            set: fn ($value) => $value ? \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null,
        );
    }
}