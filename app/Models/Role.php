<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Roles used in the permission system.
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
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Get the users assigned to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Grant one or more permissions to the role.
     * 
     * @param array|string ...$permissionNames
     * @return $this
     */
    public function grantPermissions($permissionNames)
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
     * @return $this
     */
    public function revokePermissions($permissionName)
    {
        if (!is_array($permissions)) {
            $permissions = func_get_args();
        }

        $permissionIds = Permission::whereIn('name', $permissions)->pluck('id');

        $this->permissions()->detach($permissionIds);

        return $this;
    }

    /**
     * Override the default delete method to prevent deletion of locked roles.
     * 
     * @throws \Exception If the role is locked and cannot be deleted.
     * @return bool|null
     */
    public function delete()
    {
        if ($this->is_locked) {
            throw new \Exception("This role is locked and cannot be deleted.");
        }

        return parent::delete();
    }
}
