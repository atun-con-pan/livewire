<div>    
    <!-- Botón crear -->
    <div class="mb-4 flex items-center justify-between gap-2">
        <!-- Título -->
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Afiliados
        </h2>

        <flux:button href="{{ route('affiliates.create') }}" wire:navigate variant="primary" color="blue" size="sm" icon="plus">
            Crear registro
        </flux:button>
    </div>

    <!-- 📅 Filtros de fecha -->
    <div class="flex gap-2 mb-4">
        <!-- 🔍 Buscador -->
        <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar por nombre, DPI o afiliación..." class="mb-4" />
        <flux:input type="date" wire:model.live="start_date" />
        <flux:input type="date" wire:model.live="end_date" />
    </div>

    <!-- 📊 Tabla -->
    <div class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
            <thead class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                <tr>
                    <th class="px-4">No</th>
                    <th class="p-2">Nombre</th>
                    <th class="p-2">DPI</th>
                    <th class="p-2">No. Afiliado</th>
                    <th class="p-2">Fecha inicio</th>
                    <th class="p-2">Fecha fin</th>
                    <th class="p-2 nowrap w-1 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline dark:divide-outline-dark">
                @forelse ($affiliates as $affiliate)
                    <tr class="border-t">
                        <td class="px-4">{{ $affiliates->firstItem() + $loop->index }}</td>
                        <td class="p-2">{{ $affiliate->name }}</td>
                        <td class="p-2">{{ $affiliate->dpi }}</td>
                        <td class="p-2">{{ $affiliate->no_affiliate }}</td>
                        <td class="p-2">{{ $affiliate->start_date?->format('d/m/Y') }}</td>
                        <td class="p-2">{{ $affiliate->end_date?->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">
                            <flux:button href="{{ route('affiliates.show', $affiliate) }}" wire:navigate
                                variant="primary" color="emerald" size="sm" icon="exclamation-circle">
                                Detalles
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 p-4">No hay registros</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $affiliates->links() }}
    </div>
</div>