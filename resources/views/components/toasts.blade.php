{{-- Lightweight toast stack. Trigger from Livewire with $this->dispatch('notify', message: '…')
     or from Alpine with $dispatch('notify', { message: '…', type: 'success' }) --}}
<div
    x-data="{
        toasts: [],
        add(detail) {
            const id = ++this._id;
            this.toasts.push({ id, message: detail.message ?? detail, type: detail.type ?? 'success' });
            setTimeout(() => this.remove(id), 3200);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        },
        _id: 0,
    }"
    x-on:notify.window="add($event.detail)"
    class="fixed bottom-6 left-1/2 z-[90] -translate-x-1/2 flex flex-col items-center gap-2 pointer-events-none"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto flex items-center gap-2.5 rounded-full bg-ink text-canvas pl-3.5 pr-4 py-2 text-sm font-medium shadow-xl"
        >
            <template x-if="toast.type === 'success'">
                <svg class="size-4 text-good" style="color:#5fd497" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.3 2.3L16 9"/></svg>
            </template>
            <template x-if="toast.type === 'error'">
                <svg class="size-4" style="color:#ff9d91" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16.5h.01"/></svg>
            </template>
            <span x-text="toast.message"></span>
        </div>
    </template>
</div>
