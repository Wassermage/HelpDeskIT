@php
    use App\Enums\TicketStatus;
@endphp

<section class="w-full" xmlns:flux="http://www.w3.org/1999/html">
    @include('partials.tickets-heading', ['heading' => 'New ticket', 'subheading' => 'Create a new ticket',])

    <x-tickets.layout>
        <form wire:submit.prevent="submit" class="my-6 w-full space-y-6">

            {{-- Title --}}
            <flux:input
                wire:model="title"
                :label="__('Title')"
                type="text"
                autofocus
                autocomplete="off"
            />

            {{-- Status --}}
            <flux:select
                wire:model="status"
                :label="__('Status')"
            >
                @foreach(TicketStatus::cases() as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </flux:select>

            {{-- Service --}}
            <flux:select
                wire:model="service_id"
                wire:change="serviceChanged($event.target.value)"
                :label="__('Service')"
            >
                <option value="" {{ $service_id !== null ? 'disabled' : '' }} selected></option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </flux:select>

            {{-- Description --}}
            <flux:textarea
                wire:model="description"
                :label="__('Description')"
                autocomplete="off"
            />

            {{-- Opened by --}}
            <flux:select
                wire:model="requested_by"
                :label="__('Opened by')"
                disabled
            >
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </flux:select>

            <flux:separator variant="subtle" />

            {{-- Support group --}}
            <flux:select
                wire:model="group_id"
                wire:change="groupChanged($event.target.value)"
                :label="__('Support group')"
                required
            >
                @foreach($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                @endforeach
            </flux:select>

            {{-- Assigned to --}}
            <flux:select
                wire:model="assigned_to"
                :label="__('Assigned to')"
            >
                <option value="" selected></option>
                @foreach($groupUsers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </flux:select>

            <flux:separator variant="subtle" />

            {{-- Submit --}}
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Submit') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="ticket-created">
                    {{ __('Ticket created.') }}
                </x-action-message>
            </div>
        </form>
    </x-tickets.layout>
</section>
