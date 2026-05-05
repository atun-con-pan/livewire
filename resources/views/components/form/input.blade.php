@props([
    'label',
    'name',
    'type' => 'text',
])

<div class="flex flex-col gap-1">
    <label for="{{ $name }}" class="text-sm font-medium">{{ $label }}</label>

    <input
        {{ $attributes }}
        id="{{ $name }}"
        type="{{ $type }}"
        wire:model.defer="{{ $name }}" 
        class="w-full rounded-radius border border-outline px-3 py-2 text-sm focus-visible:outline-2 focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50" />

    @error($name)
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>