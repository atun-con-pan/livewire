<div>    
    <!-- Botón crear -->
    <div class="mb-4 flex items-center justify-between gap-2">
        <!-- Título -->
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Documentos
        </h2>

        <flux:button href="{{ route('documents.create') }}" wire:navigate variant="primary" color="blue" size="sm" icon="plus">Crear registro</flux:button>
    </div>

    

    <div class="flex flex-col md:flex-row gap-4 mb-4">

        {{-- Buscador --}}
        <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar..." class="mb-4" />

        {{-- Filtro por tipo --}}
        <div class="w-full md:w-1/3">
            <flux:select wire:model.live="typeFilter">
                <option value="">Todos los tipos</option>
                <option value="Contratos">Contratos</option>
                <option value="Actas">Actas</option>
                <option value="Fianzas">Fianzas</option>
                <option value="Cartas">Cartas</option>
                <option value="Oficios">Oficios</option>
                <option value="Planos">Planos</option>
                <option value="Facturas">Facturas</option>
                <option value="Recibos">Recibos</option>
                <option value="Planillas">Planillas</option>
                <option value="Cotizaciones">Cotizaciones</option>
                <option value="Documentos ofertas">Documentos ofertas</option>
                <option value="Informes fotográficos">Informes fotográficos</option>
                <option value="Impuestos">Impuestos</option>
                <option value="Otros">Otros</option>
            </flux:select>
        </div>

    </div>

    <div class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
            <thead class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                <tr>
                    <th class="px-4">No</th>
                    <th class="p-2">Tipo</th>
                    <th class="p-2">Archivo</th>
                    <th class="p-2 nowrap w-1 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline dark:divide-outline-dark">
                @forelse ($documents as $document)
                    <tr class="border-t">
                        <td class="px-4">{{ $documents->firstItem() + $loop->index }}</td>
                        <td class="p-2">{{ $document->type }}</td>
                        <td class="p-2">
                            <a class="text-info underline" href="{{ asset('storage/' . $document->file_path) }}" target="_blank">{{ $document->file_name }}</a>
                        </td>
                        <td class="px-4 py-2">
                            <flux:button href="{{ route('documents.show', $document) }}" wire:navigate
                                variant="primary" color="emerald" size="sm" icon="exclamation-circle">
                                Detalles
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-gray-500 p-4">No hay registros</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $documents->links() }}
    </div>
</div>
