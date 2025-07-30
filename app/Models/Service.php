<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Services for categorizing tickets.
 */
class Service extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'default_group_id',
    ];

    /**
     * Get Tickets with current Service assigned. 
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the Support Group that should be assigned
     * to this Service by default. 
     */
    public function defaultGroup()
    {
        return $this->belongsTo(Group::class, 'default_group_id');
    }

}
