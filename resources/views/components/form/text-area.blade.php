@props([
    'label',
    'name',
    'rows' => 3,
])

<div class="flex flex-col gap-1">
    <label for="{{ $name }}" class="text-sm font-medium">{{ $label }}</label>
    <textarea
        {{ $attributes }}
        id="{{ $name }}"
        wire:model.defer="{{ $name }}" rows="{{ $rows }}"
        class="w-full rounded-radius border border-outline px-3 py-2 text-sm focus-visible:outline-2 focus-visible:outline-primary dark:border-outline-dark dark:bg-surface-dark-alt/50"></textarea>

    @error($name)
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>
