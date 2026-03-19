<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'role_id',
        'is_super_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
            'is_super_admin' => 'boolean',
        ];
    }

    /**
     * Get the primary role of the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get all roles assigned to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')
                    ->withTimestamps();
    }

    /**
     * Get all companies assigned to the user.
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_user')
                    ->withTimestamps();
    }

    /**
     * Get the user who handed over this user.
     */
    public function handedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handed_by_user_id');
    }

    /**
     * Get all users handed over by this user.
     */
    public function handedUsers(): HasMany
    {
        return $this->hasMany(User::class, 'handed_by_user_id');
    }

    /**
     * Get all activities recorded for this user.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(UserLog::class);
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists() ||
               $this->role?->name === $roleName;
    }

    /**
     * Check if the user has any of the given roles.
     */
    public function hasAnyRole(array $roleNames): bool
    {
        return $this->roles()->whereIn('name', $roleNames)->exists() ||
               in_array($this->role?->name, $roleNames);
    }

    /**
     * Check if the user is assigned to a specific company.
     */
    public function isInCompany(int $companyId): bool
    {
        return $this->companies()->where('company_id', $companyId)->exists();
    }

    /**
     * Check if the user is a super admin with all permissions.
     */
    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin;
    }
}
