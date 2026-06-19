<?php

use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component
{
    public string $name = '';

    public string $username = '';

    public string $email = '';

    public string $title = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name ?? '';
        $this->username = $user->username ?? '';
        $this->email = $user->email;
        $this->title = $user->title ?? '';
    }

    public function save(): void
    {
        $user = auth()->user();

        $validated = $this->validate([
            'name' => 'required|string|max:120',
            'username' => ['nullable', 'string', 'max:60', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'title' => 'nullable|string|max:120',
        ]);

        $user->update($validated);

        $this->dispatch('notify', message: 'Account updated.');
    }
};

?>

<div class="card rounded-2xl p-7">
    <div class="flex items-center gap-4">
        <x-avatar :user="auth()->user()" class="size-14 text-lg" />
        <div>
            <h2 class="font-display text-lg font-extrabold">Profile</h2>
            <p class="text-sm text-muted">How you appear across Formly.</p>
        </div>
    </div>

    <div class="mt-7 space-y-5">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="label" for="name">Name</label>
                <input id="name" type="text" wire:model="name" class="input">
                @error('name') <p class="mt-1 text-xs text-bad">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label" for="username">Username</label>
                <input id="username" type="text" wire:model="username" class="input" placeholder="username">
                @error('username') <p class="mt-1 text-xs text-bad">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="label" for="email">Email</label>
            <input id="email" type="email" wire:model="email" class="input">
            @error('email') <p class="mt-1 text-xs text-bad">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="label" for="title">What do you do?</label>
            <input id="title" type="text" wire:model="title" class="input" placeholder="e.g. Founder, Designer, People Ops">
            @error('title') <p class="mt-1 text-xs text-bad">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end border-t border-line pt-5">
            <button wire:click="save" class="btn btn-ink">
                <span wire:loading.remove wire:target="save">Save changes</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>
    </div>
</div>
