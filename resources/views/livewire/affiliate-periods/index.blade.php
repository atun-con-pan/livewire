<div>
    <!-- Botón crear -->
    <div class="mb-4 flex items-center justify-between gap-2">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Periodos
        </h2>

        <flux:button href="{{ route('periods.create') }}" wire:navigate variant="primary" color="blue" size="sm"
            icon="plus">
            Crear registro
        </flux:button>
    </div>

    <!-- TABLA (Desktop y Tablet) -->
    <div class="hidden md:block">
        <div
            class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark shadow-md">
            <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
                <thead
                    class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                    <tr>
                        <th class="px-4">No</th>
                        <th class="p-3">Afiliado</th>
                        <th class="p-3">NOG</th>
                        <th class="p-3">Inicio</th>
                        <th class="p-3">Fin</th>
                        <th class="p-3">Estado</th>
                        <th class="p-2 nowrap w-1 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($periods as $period)
                        <tr class="border-t dark:border-gray-700">

                            <td class="px-4">
                                {{ $periods->firstItem() + $loop->index }}
                            </td>

                            <td class="p-3">
                                {{ $period->affiliate->name }}
                            </td>

                            <td class="p-3">
                                <a href="{{ $period->project->url }}" target="_blank" class="text-info">
                                    {{ $period->project->nog }}
                                </a>
                            </td>

                            <td class="p-3">
                                {{ $period->start_date->format('d-m-Y') }}
                            </td>

                            <td class="p-3">
                                {{ $period->end_date?->format('d-m-Y') ?? 'Activo' }}
                            </td>

                            <td class="p-3">
                                @if ($period->hasConflict ?? false)
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded">
                                        Conflicto
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded">
                                        OK
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-2">
                                <flux:button href="{{ route('periods.edit', $period) }}" wire:navigate variant="primary"
                                    color="emerald" size="sm" icon="exclamation-circle">
                                    Detalles
                                </flux:button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-gray-500 text-center p-4">
                                No hay registros
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- CARDS (Móvil) -->
    <div class="md:hidden space-y-4">

        @forelse ($periods as $period)
            <div
                class="rounded-lg border border-outline dark:border-outline-dark bg-white dark:bg-surface-dark shadow-md p-4">

                <div class="flex items-start justify-between gap-2">

                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Afiliado
                        </p>

                        <p class="font-medium">
                            {{ $period->affiliate->name }}
                        </p>
                    </div>

                    @if ($period->hasConflict ?? false)
                        <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded">
                            Conflicto
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded">
                            OK
                        </span>
                    @endif

                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        NOG
                    </p>

                    <a href="{{ $period->project->url }}" target="_blank" class="text-info">
                        {{ $period->project->nog }}
                    </a>
                </div>

                <div class="mt-3 grid grid-cols-2 gap-3">

                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Inicio
                        </p>

                        <p class="text-sm">
                            {{ $period->start_date->format('d-m-Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Fin
                        </p>

                        <p class="text-sm">
                            {{ $period->end_date?->format('d-m-Y') ?? 'Activo' }}
                        </p>
                    </div>

                </div>

                <div class="mt-4">
                    <flux:button href="{{ route('periods.edit', $period) }}" wire:navigate variant="primary"
                        color="emerald" size="sm" icon="exclamation-circle">
                        Detalles
                    </flux:button>
                </div>

            </div>

        @empty

            <div class="text-center text-gray-500 p-4">
                No hay registros
            </div>
        @endforelse

    </div>

    <!-- PAGINACIÓN -->
    <div class="mt-4">
        {{ $periods->links() }}
    </div>
</div>
