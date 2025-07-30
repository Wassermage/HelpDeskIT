<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Role permissions.
 */
class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'label',
    ];

    /**
     * Get roles that have this permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
