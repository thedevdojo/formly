<?php

use Illuminate\Support\Facades\Hash;
use Livewire\Component;

new class extends Component
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        $user = auth()->user();

        $rules = [
            'password' => 'required|string|min:8|confirmed',
        ];

        // Social-login accounts may not have a password yet.
        if (filled($user->password)) {
            $rules['current_password'] = 'required|current_password';
        }

        $this->validate($rules);

        $user->update(['password' => Hash::make($this->password)]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->dispatch('notify', message: 'Password updated.');
    }
};

?>

<div class="space-y-6">
    <div class="card rounded-2xl p-7">
        <h2 class="font-display text-lg font-extrabold">Password</h2>
        <p class="mt-1 text-sm text-muted">Use a long, unique password you don't use anywhere else.</p>

        <div class="mt-6 space-y-5">
            @if (filled(auth()->user()->password))
                <div>
                    <label class="label" for="current_password">Current password</label>
                    <input id="current_password" type="password" wire:model="current_password" class="input" autocomplete="current-password">
                    @error('current_password') <p class="mt-1 text-xs text-bad">{{ $message }}</p> @enderror
                </div>
            @endif

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="label" for="password">New password</label>
                    <input id="password" type="password" wire:model="password" class="input" autocomplete="new-password">
                    @error('password') <p class="mt-1 text-xs text-bad">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label" for="password_confirmation">Confirm new password</label>
                    <input id="password_confirmation" type="password" wire:model="password_confirmation" class="input" autocomplete="new-password">
                </div>
            </div>

            <div class="flex justify-end border-t border-line pt-5">
                <button wire:click="updatePassword" class="btn btn-ink">
                    <span wire:loading.remove wire:target="updatePassword">Update password</span>
                    <span wire:loading wire:target="updatePassword">Updating…</span>
                </button>
            </div>
        </div>
    </div>

    <div class="card rounded-2xl p-7">
        <h2 class="font-display text-lg font-extrabold">Sessions</h2>
        <p class="mt-1 text-sm text-muted">Signing out ends your session on this device. Two-factor authentication is handled by the DevDojo Auth package during login.</p>
        <form method="POST" action="{{ route('logout') }}" class="mt-5">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm">
                <x-icon name="logout" class="size-4" /> Sign out of this device
            </button>
        </form>
    </div>
</div>
