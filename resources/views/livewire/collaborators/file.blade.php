<div>
    <div class="mb-4 flex align-items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Archivos del colaborador
        </h2>

        <flux:button href="{{ route('collaborators.index') }}" wire:navigate variant="primary" size="sm" icon="chevron-left">
            Regresar
        </flux:button>
    </div>

    <form wire:submit="store"
        class="space-y-5 w-full mb-4 p-6 dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">

        <flux:input type="file" wire:model="file" label="Archivo" multiple />

        <div class="flex justify-end">
            <flux:button variant="primary" color="blue" icon="bookmark" type="submit">Guardar</flux:button>
        </div>
    </form>

    <!-- Tabla -->
    <div class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
            <thead class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                <tr>
                    <th class="px-4">No</th>
                    <th class="p-2">Archivo</th>
                    <th class="p-2 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline dark:divide-outline-dark">
                @forelse ($files as $file)
                    <tr class="border-t">
                        <td class="p-2">{{ $files->firstItem() + $loop->index }}</td>
                        <td class="p-2">
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="text-info">{{ $file->file_name }}</a>
                        </td>
                        <td class="px-4 nowrap w-1">
                            @auth
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'root')
                                    <flux:button
                                        onclick="confirm('¿Estás seguro de eliminar este colaborador?') || event.stopImmediatePropagation()"
                                        wire:click="delete({{ $file->id }})"
                                        variant="danger" size="sm" icon="trash">
                                        Eliminar
                                    </flux:button>
                                @endif
                            @endauth
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
        {{ $files->links() }}
    </div>
</div>