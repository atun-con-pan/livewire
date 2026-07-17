<div>
    <!-- Botón crear -->
    <div class="mb-4 flex items-center justify-between gap-4">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Proyectos
        </h2>

        <flux:button href="{{ route('projects.create') }}" wire:navigate variant="primary" color="blue" size="sm"
            icon="plus">
            Crear registro
        </flux:button>
    </div>

    <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar proyecto ..." class="mb-4" />

    <!-- TABLA (Desktop y Tablet) -->
    <div class="hidden md:block">
        <div
            class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark shadow-md">
            <table class="w-full text-left text-xs text-on-surface dark:text-on-surface-dark">
                <thead
                    class="border-b border-outline bg-surface-alt text-xs text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                    <tr>
                        <th class="px-4">No</th>
                        <th class="p-2">Nog</th>
                        <th class="p-2">Nombre</th>
                        <th class="p-2">Precio</th>
                        <th class="p-2 whitespace-nowrap text-center">Contrato</th>
                        <th class="p-2 whitespace-nowrap text-center">Acta de Inicio</th>
                        <th class="p-2 whitespace-nowrap text-center">Acta de Recepción</th>
                        <th class="p-2 nowrap w-1 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-outline dark:divide-outline-dark">
                    @forelse ($projects as $project)
                        <tr class="border-t">
                            <td class="px-4">
                                {{ $projects->firstItem() + $loop->index }}
                            </td>

                            <td class="p-2">
                                <a href="{{ $project->url }}" target="_blank" class="text-info">
                                    {{ $project->nog }}
                                </a>
                            </td>

                            <td class="p-2 max-w-xl">
                                <span class="block whitespace-normal" title="{{ $project->name }}">
                                    {{ $project->name }}
                                </span>
                            </td>

                            <td class="p-2 whitespace-nowrap">
                                Q {{ $project->price }}
                            </td>

                            <td class="p-2 whitespace-nowrap text-center">
                                @php($contractPath = $project->getFilePathForCategory('contrato'))
                                @if ($contractPath)
                                    <a href="{{ Storage::url($contractPath) }}" target="_blank" rel="noopener noreferrer" class="text-info hover:underline">
                                        CONTRATO
                                    </a>
                                @else
                                    —
                                @endif
                            </td>

                            <td class="p-2 whitespace-nowrap text-center">
                                @php($startPath = $project->getFilePathForCategory('inicio'))
                                @if ($startPath)
                                    <a href="{{ Storage::url($startPath) }}" target="_blank" rel="noopener noreferrer" class="text-info hover:underline">
                                        ACTA INICIO
                                    </a>
                                @else
                                    —
                                @endif
                            </td>

                            <td class="p-2 whitespace-nowrap text-center">
                                @php($receptionPath = $project->getFilePathForCategory('recepcion'))
                                @if ($receptionPath)
                                    <a href="{{ Storage::url($receptionPath) }}" target="_blank" rel="noopener noreferrer" class="text-info hover:underline">
                                        ACTA RECEPCION
                                    </a>
                                @else
                                    —
                                @endif
                            </td>

                            <td class="px-4 py-2">
                                <flux:button href="{{ route('projects.show', $project) }}" wire:navigate
                                    variant="primary" color="emerald" size="sm" icon="exclamation-circle">
                                    Detalles
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-gray-500 p-4">
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
        @forelse ($projects as $project)
            <div
                class="rounded-lg border border-outline dark:border-outline-dark bg-white dark:bg-surface-dark shadow-md p-4">

                <div class="flex items-start justify-between gap-2">
                    <a href="{{ $project->url }}" target="_blank" class="text-info font-semibold">
                        {{ $project->nog }}
                    </a>

                    <span class="text-xs px-2 py-1 rounded bg-surface-alt dark:bg-surface-dark-alt">
                        {{ $project->status }}
                    </span>
                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Nombre del proyecto
                    </p>

                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ $project->name }}
                    </p>
                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Precio
                    </p>

                    <p class="text-sm font-medium">
                        Q {{ $project->price }}
                    </p>
                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Contrato
                    </p>

                    <p class="text-sm font-medium">
                        @php($contractPath = $project->getFilePathForCategory('contrato'))
                        @if ($contractPath)
                            <a href="{{ Storage::url($contractPath) }}" target="_blank" rel="noopener noreferrer" class="text-info hover:underline">
                                VER CONTRATO
                            </a>
                        @else
                            —
                        @endif
                    </p>
                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Acta de Inicio
                    </p>

                    <p class="text-sm font-medium">
                        @php($startPath = $project->getFilePathForCategory('inicio'))
                        @if ($startPath)
                            <a href="{{ Storage::url($startPath) }}" target="_blank" rel="noopener noreferrer" class="text-info hover:underline">
                                VER ACTA DE INICIO
                            </a>
                        @else
                            —
                        @endif
                    </p>
                </div>

                <div class="mt-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Acta de Recepción
                    </p>

                    <p class="text-sm font-medium">
                        @php($receptionPath = $project->getFilePathForCategory('recepcion'))
                        @if ($receptionPath)
                            <a href="{{ Storage::url($receptionPath) }}" target="_blank" rel="noopener noreferrer" class="text-info hover:underline">
                                VER ACTA DE RECEPCIÓN
                            </a>
                        @else
                            —
                        @endif
                    </p>
                </div>

                <div class="mt-4">
                    <flux:button href="{{ route('projects.show', $project) }}" wire:navigate variant="primary"
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
        {{ $projects->links() }}
    </div>
</div>
