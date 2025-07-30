<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'requested_by',
        'assigned_to',
        'service_id',
        'group_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'status' => TicketStatus::class,
        ];
    }

    /**
     * Get the User who created the ticket.
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the User assigned to the ticket.
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the Service assigned to the ticket.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the Support Group assigned to the ticket.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
