<?php

namespace App\Livewire;

use App\Enums\TicketStatus;
use App\Models\Group;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Collection;

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
    public string $status = TicketStatus::OPEN->value;
    public ?int $requested_by = null;
    public ?int $assigned_to = null;
    public ?int $service_id = null;
    public ?int $group_id = null;

    public Collection $users;
    public Collection $groupUsers;
    public Collection $services;
    public Collection $groups;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'service_id' => 'required|exists:services,id',
        'group_id' => 'nullable|exists:groups,id',
        'assigned_to' => 'nullable|exists:users,id',
        'status' => 'required|in:open,in_progress,on_hold,resolved,canceled',
    ];

    /**
     * Initialize form data.
     */
    public function mount(): void
    {
        $this->requested_by = auth()->id();
        $this->group_id = Group::where('name', 'default')->first()->id;
        $this->users = User::orderBy('name')->get();
        $this->groupUsers = User::orderBy('name')->get();
        $this->services = Service::orderBy('name')->get();
        $this->groups = Group::orderBy('name')->get();
    }

    protected $listeners = ['serviceChanged', 'groupChanged'];

    /**
     * Update a support group when service changes.
     *
     * @param $value
     */
    public function serviceChanged($value): void
    {
        $value = $value === '' ? null : (int) $value;
        $service = Service::find($value);

        if ($service && $service->default_group_id) {
            $this->group_id = $service->default_group_id;
            $this->groupChanged($this->group_id);
        } else {
            $this->group_id = null;
            $this->groupChanged(null);
        }
    }


    /**
     * Filter available users when support group changes.
     *
     * @param $value
     */
    public function groupChanged($value): void
    {
        $value = $value === '' ? null : (int) $value;

        if ($value) {
            $group = Group::with('users')->find($value);
            $this->groupUsers = $group ? $group->users : collect();
            $this->assigned_to = null;
        } else {
            $this->groupUsers = User::orderBy('name')->get();
        }
    }

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
            'group_id' => $this->group_id ?? 1,
            'assigned_to' => $this->assigned_to,
            'status' => $this->status,
            'requested_by' => auth()->id(),
        ]);

        $this->reset(['title', 'status', 'description', 'requested_by', 'service_id', 'group_id', 'assigned_to']);

        $this->dispatch('ticket-created');
    }

    /**
     * Render the Livewire view with necessary data for selects.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.ticket-create-form');
    }
}
