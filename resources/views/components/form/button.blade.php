@props([
    'label' => 'Guardar',
    'color' => 'primary',
])

@php
    $colors = [
        'primary' => 'bg-primary text-white',
        'success' => 'bg-green-600 text-white',
        'danger' => 'bg-red-600 text-white',
        'warning' => 'bg-yellow-500 text-black',
    ];

    $colorClass = $colors[$color] ?? $colors['primary'];
@endphp

<div class="flex justify-end gap-2 pt-2">
    <button 
        type="submit"
        {{ $attributes->merge([
            'class' => "cursor-pointer px-4 py-2 text-sm rounded-radius transition flex items-center gap-2 hover:opacity-90 $colorClass"
        ]) }}
    >

        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>

        {{ $label }}
    </button>
</div>