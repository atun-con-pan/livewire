@props([
    'label',
    'name'
])

<div class="flex flex-col gap-1">
    <label class="text-sm font-medium">{{ $label }}</label>
    <div class="relative"> 
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">Q</span>
        <input
            {{ $attributes }}
            type="number" step="0.01" wire:model.defer="{{ $name }}"
            class="w-full pl-7 rounded-radius border border-outline px-3 py-2 text-sm focus-visible:outline-2 focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50" />
    </div>

    @error($name)
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>
