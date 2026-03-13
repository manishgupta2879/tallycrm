<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'module',
        'view',
        'create',
        'edit',
        'delete',
        'export',
        'import',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'view' => 'boolean',
            'create' => 'boolean',
            'edit' => 'boolean',
            'delete' => 'boolean',
            'export' => 'boolean',
            'import' => 'boolean',
        ];
    }

    /**
     * Get all roles assigned to this permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
                    ->withPivot(['view', 'create', 'edit', 'delete', 'export', 'import'])
                    ->withTimestamps();
    }

    /**
     * Get unique modules from all permissions.
     */
    public static function getModules()
    {
        return self::distinct()->pluck('module')->sort();
    }

    /**
     * Get all permissions for a specific module.
     */
    public static function getModulePermissions(string $module)
    {
        return self::where('module', $module)->get();
    }
}
