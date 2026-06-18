<div>
    <!-- Título -->
    <h2 class="mb-2 text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
        <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
        Historial de registros
    </h2>
    
    <!-- Buscador -->
    <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar ..." class="mb-4" />
    
    <!-- Tabla -->
    <div class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
            <thead class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                <tr>
                    <th class="p-2">No</th>
                    <th class="p-2">Evento</th>
                    <th class="p-2">Modelo</th>
                    <th class="p-2">Registro</th>
                    <th class="p-2">Responsable</th>
                    <th class="p-2">Fecha</th>
                    <th class="p-2 nowrap w-1 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-outline dark:divide-outline-dark">
                @forelse ($audits as $audit)
                    <tr class="border-t">

                        <!-- No -->
                        <td class="p-2">
                            {{ $audits->firstItem() + $loop->index }}
                        </td>

                        <!-- Evento -->
                        <td class="p-2">
                            <span class="px-2 py-1 rounded text-xs
                                @if ($audit->event === 'created') bg-green-100 text-green-700
                                @elseif ($audit->event === 'updated') bg-yellow-100 text-yellow-700
                                @elseif ($audit->event === 'deleted') bg-red-100 text-red-700
                                @endif
                            ">
                                {{ ucfirst($audit->event) }}
                            </span>
                        </td>

                        <!-- Modelo -->
                        <td class="p-2">
                            {{ $this->getModelName($audit) }}
                        </td>

                        <!-- Registro -->
                        <td class="p-2 font-semibold max-w-xs">
                            <span class="truncate block">
                                {{ $this->getAuditName($audit) }}
                            </span>
                        </td>

                        <!-- Usuario -->
                        <td class="p-2">
                            {{ $audit->user?->name ?? 'Sistema' }}
                        </td>

                        <!-- Fecha -->
                        <td class="p-2">
                            {{ $audit->created_at->diffForHumans() }}
                        </td>

                        <!-- Acciones -->
                        <td class="px-4 py-2">
                            <flux:button href="{{ route('audit.show', $audit) }}" wire:navigate
                                variant="primary" color="emerald" size="sm" icon="exclamation-circle">
                                Detalles
                            </flux:button>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-2 text-center text-gray-500">
                            No hay registros.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $audits->links() }}
    </div>
</div>