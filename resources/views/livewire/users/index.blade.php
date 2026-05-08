<div>    
    <!-- Botón crear -->
    <div class="mb-4 flex items-center justify-between gap-2">
        <!-- Título -->
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Usuarios
        </h2>

        <flux:button href="{{ route('users.create') }}" wire:navigate variant="primary" color="blue" size="sm" icon="plus">Crear registro</flux:button>
    </div>

    <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar..." class="mb-4" />

    <div class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark shadow-md">
        <table class="w-full text-left text-sm text-on-surface dark:text-on-surface-dark">
            <thead class="border-b border-outline bg-surface-alt text-sm text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                <tr>
                    <th class="px-4">No</th>
                    <th class="p-2">Nombre</th>
                    <th class="p-2">Correo electrónico</th>
                    <th class="p-2">Rol</th>
                    <th class="p-2 nowrap w-1 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline dark:divide-outline-dark">
                @forelse ($users as $user)
                    <tr class="border-t">
                        <td class="px-4">{{ $users->firstItem() + $loop->index }}</td>
                        <td class="p-2">
                            <flux:link class="text-info" href="{{ route('users.show', $user) }}">{{ $user->name }}</flux:link>
                        </td>
                        <td class="p-2">{{ $user->email }}</td>
                        <td class="p-2">
                            @switch($user->role)
                                @case('root')
                                    <flux:badge rounded icon="user" color="red">Super usuario</flux:badge>
                                    @break

                                @case('admin')
                                    <flux:badge rounded icon="user" color="blue">Administrador</flux:badge>
                                    @break

                                @case('user')
                                    <flux:badge rounded icon="user" color="gray">Usuario</flux:badge>
                                    @break

                                @default
                                    {{ $user->role }}
                            @endswitch
                        </td>
                        <td class="px-4 py-2">
                            <flux:button href="{{ route('users.show', $user) }}" wire:navigate
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
        {{ $users->links() }}
    </div>
</div>
