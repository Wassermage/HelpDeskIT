<?php

namespace App\Enums;

/**
 * Enum representing the possible statuses of a Ticket.
 *
 * @method static self OPEN()
 * @method static self IN_PROGRESS()
 * @method static self ON_HOLD()
 * @method static self RESOLVED()
 * @method static self CANCELED()
 */
enum TicketStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case ON_HOLD = 'on_hold';
    case RESOLVED = 'resolved';
    case CANCELED = 'canceled';

    /**
     * Get a human-readable label for the status.
     */
    public function label(): string
    {
        return match($this) {
            self::OPEN => 'Open',
            self::IN_PROGRESS => 'In Progress',
            self::ON_HOLD => 'On Hold',
            self::RESOLVED => 'Resolved',
            self::CANCELED => 'Canceled',
        };
    }

    /**
     * Check if the Ticket is in a finalized state.
     */
    public function isFinalized(): bool
    {
        return in_array($this, [self::RESOLVED, self::CANCELED]);
    }
}
