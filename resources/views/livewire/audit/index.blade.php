<div>
    <!-- Título -->
    <h2 class="mb-2 text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
        <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
        Historial de registros
    </h2>

    <!-- Buscador -->
    <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar registro ..." class="mb-4" />

    <!-- TABLA (Desktop y Tablet) -->
    <div class="hidden md:block">
        <div
            class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark shadow-md">
            <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
                <thead
                    class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
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

                            <td class="p-2">
                                {{ $audits->firstItem() + $loop->index }}
                            </td>

                            <td class="p-2">
                                <span
                                    class="px-2 py-1 rounded text-xs
                                    @if ($audit->event === 'created') bg-green-100 text-green-700
                                    @elseif ($audit->event === 'updated') bg-yellow-100 text-yellow-700
                                    @elseif ($audit->event === 'deleted') bg-red-100 text-red-700 @endif
                                ">
                                    {{ ucfirst($audit->event) }}
                                </span>
                            </td>

                            <td class="p-2">
                                {{ $this->getModelName($audit) }}
                            </td>

                            <td class="p-2 font-semibold max-w-xs">
                                <span class="truncate block">
                                    {{ $this->getAuditName($audit) }}
                                </span>
                            </td>

                            <td class="p-2">
                                {{ $audit->user?->name ?? 'Sistema' }}
                            </td>

                            <td class="p-2">
                                {{ $audit->created_at->diffForHumans() }}
                            </td>

                            <td class="px-4 py-2">
                                <flux:button href="{{ route('audit.show', $audit) }}" wire:navigate variant="primary"
                                    color="emerald" size="sm" icon="exclamation-circle">
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
    </div>

    <!-- CARDS (Móvil) -->
    <div class="md:hidden space-y-4">

        @forelse ($audits as $audit)
            <div
                class="rounded-lg border border-outline dark:border-outline-dark bg-white dark:bg-surface-dark shadow-md p-4">

                <div class="flex items-start justify-between gap-2">

                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Modelo
                        </p>

                        <p class="font-medium">
                            {{ $this->getModelName($audit) }}
                        </p>
                    </div>

                    <span
                        class="px-2 py-1 rounded text-xs
                        @if ($audit->event === 'created') bg-green-100 text-green-700
                        @elseif ($audit->event === 'updated') bg-yellow-100 text-yellow-700
                        @elseif ($audit->event === 'deleted') bg-red-100 text-red-700 @endif
                    ">
                        {{ ucfirst($audit->event) }}
                    </span>

                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Registro
                    </p>

                    <p class="text-sm font-medium break-words">
                        {{ $this->getAuditName($audit) }}
                    </p>
                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Responsable
                    </p>

                    <p class="text-sm">
                        {{ $audit->user?->name ?? 'Sistema' }}
                    </p>
                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Fecha
                    </p>

                    <p class="text-sm">
                        {{ $audit->created_at->diffForHumans() }}
                    </p>
                </div>

                <div class="mt-4">
                    <flux:button href="{{ route('audit.show', $audit) }}" wire:navigate variant="primary"
                        color="emerald" size="sm" icon="exclamation-circle">
                        Detalles
                    </flux:button>
                </div>

            </div>

        @empty

            <div class="text-center text-gray-500 p-4">
                No hay registros.
            </div>
        @endforelse

    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $audits->links() }}
    </div>
</div>
