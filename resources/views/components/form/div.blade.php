@props([
    'label',
    'value' => null,
])

<div class="flex flex-col gap-1">
    <label class="text-sm font-medium">{{ $label }}</label>

    <div
        class="w-full rounded-radius border border-outline px-3 py-2 text-sm text-gray-700 bg-gray-100 cursor-not-allowed
               dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-gray-300">
        
        {{ !empty($value) ? $value : 'N/A' }}
    </div>
</div>