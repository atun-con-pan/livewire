<div>
    <!-- Botón crear -->
    <div class="mb-4 flex items-center justify-between gap-2">
        <!-- Título -->
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Periodos
        </h2>

        <flux:button href="{{ route('periods.create') }}" wire:navigate variant="primary" color="blue" size="sm"
            icon="plus">
            Crear registro
        </flux:button>
    </div>

    {{-- TABLA --}}
    <div class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
            <thead class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                <tr>
                    <th class="px-4">No</th>
                    <th class="p-3">Afiliado</th>
                    <th class="p-3">DPI</th>
                    <th class="p-3">NOG</th>
                    <th class="p-3">Inicio</th>
                    <th class="p-3">Fin</th>
                    <th class="p-3">Estado</th>
                    <th class="p-2 nowrap w-1 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($periods as $period)
                    <tr class="border-t dark:border-gray-700">

                        <td class="px-4">
                            {{ $periods->firstItem() + $loop->index }}
                        </td>

                        {{-- AFILIADO --}}
                        <td class="p-3">
                            {{ $period->affiliate->name }}
                        </td>

                        {{-- DPI --}}
                        <td class="p-3">
                            {{ $period->affiliate->dpi }}
                        </td>

                        {{-- NOG --}}
                        <td class="p-3">
                            <a href="{{ $period->project->url }}" target="_blank" class="text-info">{{ $period->project->nog }}</a>
                        </td>

                        {{-- FECHAS --}}
                        <td class="p-3">
                            {{ $period->start_date->format('d-m-Y') }}
                        </td>

                        <td class="p-3">
                            {{ $period->end_date?->format('d-m-Y') ?? 'Activo' }}
                        </td>

                        {{-- ESTADO --}}
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

                        {{-- ACCIONES --}}
                        <td class="px-4 py-2">
                            <flux:button href="{{ route('periods.edit', $period) }}" wire:navigate
                                variant="primary" color="emerald" size="sm" icon="exclamation-circle">
                                Detalles
                            </flux:button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGINACIÓN --}}
    <div class="mt-4">
        {{ $periods->links() }}
    </div>
</div>
