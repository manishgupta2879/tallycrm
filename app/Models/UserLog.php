<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable; // Added
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLog extends Model
{
    use Prunable; // Added

    /**
     * Get the prunable model query.
     */
    public function prunable()
    {
        return static::where('date', '<', now()->subYear());
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'date',
        'log_in',
        'log_out',
        'last_activity',
        'detail',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'log_in' => 'datetime',
            'log_out' => 'datetime',
            'last_activity' => 'datetime',
        ];
    }

    /**
     * Get the user that performed the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
