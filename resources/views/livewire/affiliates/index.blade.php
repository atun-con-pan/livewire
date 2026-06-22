<div>
    <!-- Botón crear -->
    <div class="mb-4 flex items-center justify-between gap-2">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Afiliados
        </h2>

        <div class="flex gap-2">
            <flux:button href="{{ route('affiliates.create') }}" wire:navigate variant="primary" color="blue"
                size="sm" icon="plus">
                Crear registro
            </flux:button>

            <flux:button href="{{ route('periods.index') }}" wire:navigate variant="primary" color="pink"
                size="sm" icon="plus">
                Periodos
            </flux:button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="flex flex-col md:flex-row gap-2 mb-4">

        <flux:input wire:model.live="search" icon="magnifying-glass"
            placeholder="Buscar por nombre, DPI o afiliación ..." />

        <flux:input type="date" wire:model.live="start_date" />

        <flux:input type="date" wire:model.live="end_date" />

        @if ($start_date && $end_date)
            <flux:select wire:model.live="showConflicts">
                <option value="all">Todos</option>
                <option value="conflicted">En uso</option>
                <option value="not_conflicted">Disponibles</option>
            </flux:select>
        @endif

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
                        <th class="p-2">Nombre</th>
                        <th class="p-2">DPI</th>
                        <th class="p-2">No. Afiliado</th>

                        @if ($start_date && $end_date)
                            <th class="p-2 text-center">Estado</th>
                        @endif

                        <th class="p-2 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-outline dark:divide-outline-dark">

                    @forelse ($affiliates as $affiliate)
                        <tr class="border-t">

                            <td class="px-4">
                                {{ $affiliates->firstItem() + $loop->index }}
                            </td>

                            <td class="p-2">
                                {{ $affiliate->name }}
                            </td>

                            <td class="p-2">
                                {{ $affiliate->dpi }}
                            </td>

                            <td class="p-2">
                                {{ $affiliate->no_affiliate }}
                            </td>

                            @if ($start_date && $end_date)
                                <td class="p-2 text-center">
                                    @php
                                        $hasConflict = $affiliate->hasConflictInRange($start_date, $end_date);
                                    @endphp

                                    @if ($hasConflict)
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded">
                                            En uso
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded">
                                            Disponible
                                        </span>
                                    @endif
                                </td>
                            @endif

                            <td class="px-4 py-2 text-center">
                                <flux:button href="{{ route('affiliates.show', $affiliate) }}" wire:navigate
                                    variant="primary" color="emerald" size="sm" icon="exclamation-circle">
                                    Detalles
                                </flux:button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 p-4">
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

        @forelse ($affiliates as $affiliate)

            @php
                $hasConflict = false;

                if ($start_date && $end_date) {
                    $hasConflict = $affiliate->hasConflictInRange($start_date, $end_date);
                }
            @endphp

            <div
                class="rounded-lg border border-outline dark:border-outline-dark bg-white dark:bg-surface-dark shadow-md p-4">

                <div class="flex items-start justify-between gap-2">

                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Nombre
                        </p>

                        <p class="font-medium">
                            {{ $affiliate->name }}
                        </p>
                    </div>

                    @if ($start_date && $end_date)
                        @if ($hasConflict)
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded">
                                En uso
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded">
                                Disponible
                            </span>
                        @endif
                    @endif

                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        DPI
                    </p>

                    <p class="text-sm">
                        {{ $affiliate->dpi }}
                    </p>
                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        No. Afiliado
                    </p>

                    <p class="text-sm">
                        {{ $affiliate->no_affiliate }}
                    </p>
                </div>

                <div class="mt-4">
                    <flux:button href="{{ route('affiliates.show', $affiliate) }}" wire:navigate variant="primary"
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

    <!-- Paginación -->
    <div class="mt-4">
        {{ $affiliates->links() }}
    </div>
</div>
