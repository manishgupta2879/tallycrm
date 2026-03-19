<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Traits\Searchable;

class Role extends Model
{
    use Searchable, HasFactory;

    protected $searchable = ['name', 'slug'];
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get all users assigned to this role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user')
            ->withTimestamps();
    }

    /**
     * Get all permissions assigned to this role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withPivot(['view', 'create', 'edit', 'delete', 'export', 'import'])
            ->withTimestamps();
    }

    /**
     * Check if the role has a specific permission.
     */
    public function hasPermission(string $permissionModule, string $action = 'view'): bool
    {
        return $this->permissions()
            ->where('module', $permissionModule)
            ->wherePivot($action, true)
            ->exists();
    }

    /**
     * Get all permissions for a specific module.
     */
    public function getModulePermissions(string $module)
    {
        return $this->permissions()
            ->where('module', $module)
            ->get();
    }
}
