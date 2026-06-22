<div>
    <!-- Botón crear -->
    <div class="mb-4 flex items-center justify-between gap-2">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Colaboradores
        </h2>

        <flux:button href="{{ route('collaborators.create') }}" wire:navigate variant="primary" color="blue"
            size="sm" icon="plus">
            Crear registro
        </flux:button>
    </div>

    <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar colaborador ..." class="mb-4" />

    <!-- TABLA (Desktop y Tablet) -->
    <div class="hidden md:block">
        <div
            class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark shadow-md">
            <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
                <thead
                    class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                    <tr>
                        <th class="px-4">No</th>
                        <th class="p-2">Nombre completo</th>
                        <th class="p-2">DPI</th>
                        <th class="p-2">Teléfono</th>
                        <th class="p-2">Correo</th>
                        <th class="p-2 nowrap w-1 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-outline dark:divide-outline-dark">
                    @forelse ($collaborators as $collaborator)
                        <tr class="border-t">
                            <td class="px-4">
                                {{ $collaborators->firstItem() + $loop->index }}
                            </td>

                            <td class="p-2">
                                {{ $collaborator->first_name }}
                                {{ $collaborator->middle_name }}
                                {{ $collaborator->first_surname }}
                                {{ $collaborator->second_last_name }}
                            </td>

                            <td class="p-2">{{ $collaborator->dpi }}</td>

                            <td class="p-2">{{ $collaborator->phone }}</td>

                            <td class="p-2">
                                {{ $collaborator->email }}
                            </td>

                            <td class="px-4 py-2">
                                <flux:button href="{{ route('collaborators.show', $collaborator) }}" wire:navigate
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
        @forelse ($collaborators as $collaborator)
            <div
                class="rounded-lg border border-outline dark:border-outline-dark bg-white dark:bg-surface-dark shadow-md p-4">

                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Nombre completo
                    </p>

                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ $collaborator->first_name }}
                        {{ $collaborator->middle_name }}
                        {{ $collaborator->first_surname }}
                        {{ $collaborator->second_last_name }}
                    </p>
                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        DPI
                    </p>

                    <p class="text-sm">
                        {{ $collaborator->dpi }}
                    </p>
                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Teléfono
                    </p>

                    <p class="text-sm">
                        {{ $collaborator->phone }}
                    </p>
                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Correo
                    </p>

                    <p class="text-sm break-all">
                        {{ $collaborator->email }}
                    </p>
                </div>

                <div class="mt-4">
                    <flux:button href="{{ route('collaborators.show', $collaborator) }}" wire:navigate
                        variant="primary" color="emerald" size="sm" icon="exclamation-circle">
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
        {{ $collaborators->links() }}
    </div>
</div>
