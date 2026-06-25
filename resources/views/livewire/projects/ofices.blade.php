<div>
    <div class="mb-4 flex align-items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Oficios del proyecto
        </h2>

        <flux:button href="{{ route('projects.index') }}" wire:navigate variant="primary" size="sm" icon="chevron-left">
            Regresar
        </flux:button>
    </div>

    <form wire:submit="save" enctype="multipart/form-data"
        class="space-y-5 w-full mb-4 p-6 dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:input type="date" wire:model="date" label="Fecha del oficio" name="date" />
            <flux:input wire:model="ofice" label="Número del Oficio" name="ofice" placeholder="Ingrese el número o referencia del oficio" />
            <flux:select wire:model="option" label="Seleccione una opción" placeholder="Seleccione una opción...">
                <flux:select.option>Entregado</flux:select.option>
                <flux:select.option>Recibido</flux:select.option>
            </flux:select>
        </div>
        <flux:input wire:model="description" label="Descripción" name="description" placeholder="Ingrese la descripción o motivo del oficio" />
        <flux:textarea wire:model="notes" label="Notas" name="notes" />
        <flux:input type="file" wire:model="file" label="Archivo" />

        <div class="flex justify-end">
            <flux:button 
                variant="primary" 
                color="blue" 
                icon="bookmark" 
                type="submit"
                wire:loading.attr="disabled"
                wire:target="file,save">
                Guardar
            </flux:button>
        </div>
    </form>

    <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
        <thead class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
            <tr>
                <th class="px-4">No</th>
                <th class="p-2">Fecha</th>
                <th class="p-2">No. Oficio</th>
                <th class="p-2">Tipo</th>
                <th class="p-2">Archivo</th>
                <th class="p-2 nowrap w-1 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline dark:divide-outline-dark">
            @forelse($ofices as $index => $ofice)
                <tr>
                    <td class="px-4 py-2">{{ $ofices->firstItem() + $index }}</td>
                    <td class="p-2">{{ $ofice->date->format('d/m/Y') }}</td>
                    <td class="p-2">{{ $ofice->ofice }}</td>
                    <td class="p-2">{{ $ofice->option }}</td>
                    <td class="p-2">
                        <a href="{{ Storage::url($ofice->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                            Ver archivo
                        </a>
                    </td>
                    <td class="p-2 text-center whitespace-nowrap">
                        <flux:button onclick="confirm('¿Estás seguro de eliminar este colaborador?') || event.stopImmediatePropagation()" 
                            wire:click="delete( {{ $ofice->id }})" variant="danger" size="sm" icon="trash">
                            Eliminar
                        </flux:button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        No hay oficios registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $ofices->links() }}
    </div>
</div>
