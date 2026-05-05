@props([
    'name',
    'label'
])

<div class="flex flex-col gap-1">
    <label class="cursor-pointer text-sm font-medium" for="{{ $name }}">{{ $label }}</label>
    <input
        {{ $attributes }}
        id="{{ $name }}" 
        type="file" 
        wire:model="{{ $name }}"
        multiple
        class="cursor-pointer w-full overflow-clip rounded-radius border border-outline text-sm text-on-surface file:mr-4 file:border-none file:bg-surface-alt file:px-4 file:py-2 file:font-medium file:text-on-surface-strong focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:file:bg-surface-dark-alt dark:file:text-on-surface-dark-strong dark:focus-visible:outline-primary-dark" />

    @error($name)
        <span class="text-sm text-red-500">{{ $message }}</span>
    @enderror
</div>
