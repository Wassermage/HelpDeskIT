<?php

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $title = '';
}; ?>

<section>
    <x-ticket.form.layout>
        <h2>Test</h2>
    </x-ticket.form.layout>
</section>
