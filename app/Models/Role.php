<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use staabm\SideEffectsDetector\SideEffect;

/**
 * Roles used in the permission system.
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property-read int $id
 * @property string $name
 * @property string $label
 * @property string $description
 * @property bool $is_locked
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 */
class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'label',
        'description',
        'is_locked',
    ];

    /**
     * Get the permissions associated with the role.
     *
     * @return BelongsToMany<Permission>
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Get users assigned to the role.
     *
     * @return BelongsToMany<User>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Grant one or more permissions to the role.
     *
     * @param array|string ...$permissionNames
     * @return static
     */
    public function grantPermissions(array|string ...$permissionNames): static
    {
        if (!is_array($permissionNames)) {
            $permissionNames = func_get_args();
        }

        $permissionIds = Permission::whereIn('name', $permissionNames)->pluck('id');

        $this->permissions()->syncWithoutDetaching($permissionIds);

        return $this;
    }

    /**
     * Revoke one or more permissions from the role.
     *
     * @param array|string ...$permissionNames
     * @return static
     */
    public function revokePermissions(array|string ...$permissionNames): static
    {
        if (!is_array($permissionNames)) {
            $permissionNames = func_get_args();
        }

        $permissionIds = Permission::whereIn('name', $permissionNames)->pluck('id');

        $this->permissions()->detach($permissionIds);

        return $this;
    }

    /**
     * Override the default delete method to prevent deletion of locked roles.
     *
     * @throws \Exception If the role is locked and cannot be deleted.
     * @return bool|null
     */
    public function delete(): ?bool
    {
        if ($this->is_locked) {
            throw new \Exception("This role is locked and cannot be deleted.");
        }

        return parent::delete();
    }
}
