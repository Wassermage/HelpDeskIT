<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Ticket model for tracking support requests.
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property-read int $id
 * @property string $title
 * @property string|null $description
 * @property TicketStatus $status
 * @property int $requested_by
 * @property int|null $assigned_to
 * @property int $service_id
 * @property int|null $group_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User $requester
 * @property-read \App\Models\User|null $assignee
 * @property-read \App\Models\Service $service
 * @property-read \App\Models\Group|null $group
 */
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
     *
     * @return BelongsTo<User>
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the User assigned to the ticket.
     *
     * @return BelongsTo<User>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the Service assigned to the ticket.
     *
     * @return BelongsTo<Service>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the Support Group assigned to the ticket.
     *
     * @return BelongsTo<Group>
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
