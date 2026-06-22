<div>
    <!-- Botón crear -->
    <div class="mb-4 flex items-center justify-between gap-4">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Contratos
        </h2>

        <flux:button href="{{ route('contracts.create') }}" wire:navigate variant="primary" color="blue" size="sm"
            icon="plus">
            Crear registro
        </flux:button>
    </div>

    <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar contrato ..." class="mb-4" />

    <!-- TABLA (Desktop y Tablet) -->
    <div class="hidden md:block">
        <div
            class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark shadow-md">
            <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
                <thead
                    class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                    <tr>
                        <th class="px-4">No</th>
                        <th class="p-2">Nombre del contrato</th>
                        <th class="p-2">Responsable</th>
                        <th class="p-2">Estado</th>
                        <th class="p-2 nowrap w-1 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-outline dark:divide-outline-dark">
                    @forelse ($contracts as $contract)
                        <tr class="border-t">

                            <td class="px-4">
                                {{ $contracts->firstItem() + $loop->index }}
                            </td>

                            <td class="p-2 max-w-md">
                                <a class="block text-info truncate" target="_blank"
                                    href="{{ $contract->project?->url }}" title="{{ $contract->project?->name }}">
                                    {{ $contract->project?->name }}
                                </a>
                            </td>

                            <td class="p-2">
                                {{ $contract->person_charge }}
                            </td>

                            <td class="p-2">
                                {{ $contract->status }}
                            </td>

                            <td class="px-4 py-2">
                                <flux:button href="{{ route('contracts.show', $contract) }}" wire:navigate
                                    variant="primary" color="emerald" size="sm" icon="exclamation-circle">
                                    Detalles
                                </flux:button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 p-4">
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

        @forelse ($contracts as $contract)
            <div
                class="rounded-lg border border-outline dark:border-outline-dark bg-white dark:bg-surface-dark shadow-md p-4">

                <div class="flex items-start justify-between gap-2">

                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Contrato
                        </p>

                        <a href="{{ $contract->project?->url }}" target="_blank" class="block text-info font-medium">
                            {{ $contract->project?->name }}
                        </a>
                    </div>

                    <span class="px-2 py-1 text-xs rounded bg-surface-alt dark:bg-surface-dark-alt">
                        {{ $contract->status }}
                    </span>

                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Responsable
                    </p>

                    <p class="text-sm">
                        {{ $contract->person_charge }}
                    </p>
                </div>

                <div class="mt-4">
                    <flux:button href="{{ route('contracts.show', $contract) }}" wire:navigate variant="primary"
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
        {{ $contracts->links() }}
    </div>
</div>
