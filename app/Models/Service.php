<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Services for categorizing tickets
 */
class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'default_group_id',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function defaultGroup()
    {
        return $this->belongsTo(Group::class, 'default_group_id');
    }

}
