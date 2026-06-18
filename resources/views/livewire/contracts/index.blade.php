<div>    
    <!-- Botón crear -->
    <div class="mb-4 flex items-center justify-between gap-4">
        <!-- Título -->
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Contratos
        </h2>

        <flux:button href="{{ route('contracts.create') }}" wire:navigate variant="primary" color="blue" size="sm" icon="plus">Crear registro</flux:button>
    </div>

    <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar contrato ..." class="mb-4" />

    <div class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
            <thead class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
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
                        <td class="px-4">{{ $contracts->firstItem() + $loop->index }}</td>
                        <td class="p-2">{{ $contract->contract_name }}</td>
                        <td class="p-2">{{ $contract->person_charge }}</td>
                        <td class="p-2">{{ $contract->status }}</td>
                        <td class="px-4 py-2">
                            <flux:button href="{{ route('contracts.show', $contract) }}" wire:navigate
                                variant="primary" color="emerald" size="sm" icon="exclamation-circle">
                                Detalles
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 p-4">No hay registros</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $contracts->links() }}
    </div>
</div>
