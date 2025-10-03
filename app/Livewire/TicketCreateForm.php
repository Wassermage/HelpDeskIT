<?php

namespace App\Livewire;

use App\Enums\TicketStatus;
use App\Models\Group;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use Livewire\Component;

/**
 * Livewire component for creating new tickets in the system.
 *
 * Handles form input, validation, and ticket creation.
 *
 * @property string $title
 * @property string|null $description
 * @property int $service_id
 * @property int|null $group_id
 * @property int|null $assigned_to
 * @property TicketStatus $status
 */
class TicketCreateForm extends Component
{
    public string $title = '';
    public ?string $description = '';
    public int $service_id;
    public ?int $group_id = null;
    public ?int $assigned_to = null;
    public string $status = TicketStatus::OPEN->value;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'service_id' => 'required|exists:services,id',
        'group_id' => 'nullable|exists:groups,id',
        'assigned_to' => 'nullable|exists:users,id',
        'status' => 'required|in:open,in_progress,on_hold,resolved,canceled',
    ];

    /**
     * Handle form submission and create a new Ticket.
     *
     * @return void
     */
    public function submit(): void
    {
        $this->validate();

        Ticket::create([
            'title' => $this->title,
            'description' => $this->description,
            'service_id' => $this->service_id,
            'group_id' => $this->group_id,
            'assigned_to' => $this->assigned_to,
            'status' => $this->status,
            'requested_by' => auth()->id(),
        ]);

        session()->flash('message', 'Ticket created.');
        $this->reset();
    }

    /**
     * Render the Livewire view with necessary data for selects.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.ticket-create-form', [
            'users' => User::all(),
            'services' => Service::all(),
            'groups' => Group::all(),
        ]);
    }
}
