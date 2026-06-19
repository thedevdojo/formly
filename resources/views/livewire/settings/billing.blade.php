<?php

use Devdojo\Billing\Models\Plan;
use Livewire\Component;

new class extends Component
{
    /**
     * @return array{subscribed: bool, plan: ?Plan}
     */
    public function with(): array
    {
        $user = auth()->user();
        $subscribed = $user->subscriber();
        $plan = $subscribed ? Plan::find($user->latestSubscription()?->plan_id) : Plan::where('default', true)->first();

        return [
            'subscribed' => $subscribed,
            'plan' => $plan,
        ];
    }

    public function cancel(): void
    {
        $user = auth()->user();
        $user->subscriptions()->update(['status' => 'canceled', 'ends_at' => now()]);
        $user->subscriptions()->delete();

        if (method_exists($user, 'syncRoles')) {
            $roles = $user->getRoleNames()->reject(fn ($r) => in_array($r, ['pro', 'business']))->push('registered')->unique()->all();
            $user->syncRoles($roles);
        }

        $this->dispatch('notify', message: 'Subscription canceled — you are on the Free plan.');
    }
};

?>

<div class="space-y-6">
    <div class="card rounded-2xl p-7">
        <h2 class="font-display text-lg font-extrabold">Current plan</h2>
        <p class="mt-1 text-sm text-muted">Your subscription and what it includes.</p>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-4 rounded-xl border border-line bg-canvas p-5">
            <div class="flex items-center gap-3.5">
                <span class="flex size-11 items-center justify-center rounded-xl bg-accent-soft text-accent">
                    <x-icon name="zap" class="size-5" />
                </span>
                <div>
                    <div class="flex items-center gap-2">
                        <p class="font-bold">{{ $plan?->name ?? 'Free' }} plan</p>
                        @if ($subscribed)
                            <span class="chip border-good/30 bg-good-soft text-good"><span class="size-1.5 rounded-full bg-good"></span> Active</span>
                        @endif
                    </div>
                    <p class="text-xs text-muted">
                        @if ($plan && (int) $plan->monthly_price > 0)
                            {{ $plan->currency }}{{ $plan->monthly_price }}/month
                        @else
                            Free forever — your first 100 responses each month are on us.
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('pricing') }}" class="btn btn-ink btn-sm">{{ $subscribed ? 'Change plan' : 'Upgrade' }}</a>
                @if ($subscribed)
                    <button wire:click="cancel" wire:confirm="Cancel your subscription and move to the Free plan?" class="btn btn-outline btn-sm">Cancel</button>
                @endif
            </div>
        </div>

        @if ($plan?->features)
            <ul class="mt-5 grid gap-2.5 sm:grid-cols-2">
                @foreach ($plan->features as $feature)
                    <li class="flex items-center gap-2 text-sm text-muted">
                        <span class="flex size-5 shrink-0 items-center justify-center rounded-full bg-good-soft text-good">
                            <x-icon name="check" class="size-3" />
                        </span>
                        {{ $feature }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="card rounded-2xl p-7">
        <h2 class="font-display text-lg font-extrabold">Payment provider</h2>
        <p class="mt-1 text-sm text-muted">
            Checkout and invoicing are powered by the DevDojo Billing package. Connect Stripe or Paddle credentials in your environment to enable live payments.
        </p>
        <div class="mt-4 flex items-center gap-2">
            <span class="chip"><x-icon name="credit-card" class="size-3.5" /> Stripe</span>
            <span class="chip"><x-icon name="credit-card" class="size-3.5" /> Paddle</span>
        </div>
    </div>
</div>
