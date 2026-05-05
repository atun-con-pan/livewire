<div>
    <div class="mb-4 flex align-items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            @if ($this->isCreated())
                Crear usuario
            @elseif ($this->isEdit())
                Editar usuario
            @elseif ($this->isShow())
                Detalles del usuario
            @endif
        </h2>

        <div class="flex gap-2">
            @if ($this->isEdit())
                <flux:modal.trigger name="delete-profile">
                    <flux:button
                        variant="danger" size="sm" icon="trash">
                        Eliminar
                    </flux:button>
                </flux:modal.trigger>

                <flux:modal name="delete-profile" class="min-w-[22rem]">
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">¿Eliminar registro?</flux:heading>
                            <flux:text class="mt-2">
                                Estás a punto de eliminar este registro.<br>
                                Esta acción no se puede revertir.
                            </flux:text>
                        </div>
                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:modal.close>
                                <flux:button variant="ghost">Cancelar</flux:button>
                            </flux:modal.close>
                            <flux:button type="submit" variant="danger" wire:click="delete">Eliminar registro</flux:button>
                        </div>
                    </div>
                </flux:modal>
            @elseif($this->isShow())
                <flux:button href="{{ route('users.edit', $user) }}" wire:navigate variant="primary" color="yellow" size="sm" icon="pencil-square">
                    Editar
                </flux:button>
            @endif

            <flux:button href="{{ route('users.index') }}" wire:navigate variant="primary" size="sm" icon="chevron-left">
                Regresar
            </flux:button>
        </div>
    </div>

    <form wire:submit="save"
        class="space-y-5 w-full p-6 dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            @if ($this->isShow())
                <flux:input readonly wire:model="form.name" label="Nombre completo" />
            @else
                <flux:input wire:model="form.name" label="Nombre completo" />
            @endif

            @if ($this->isShow())
                <flux:input readonly wire:model="form.email" label="Correo electrónico" />
            @else
                <flux:input wire:model="form.email" label="Correo electrónico" type="email" />
            @endif

            @if ($this->isShow())
                
            @else
                <flux:input wire:model="form.password" label="Contraseña" type="password" />
            @endif

            @if ($this->isShow())
                <flux:input readonly wire:model="form.role" label="Rol del usuario" />
            @else
                <flux:select label="Rol del usuario" name="role" wire:model="form.role">
                    <option value="">Seleccionar el rol del usuario</option>
                    <option value="root">Super usuario</option>
                    <option value="admin">Admininistrador</option>
                    <option value="user">Usuario</option>
                </flux:select>
            @endif
        </div>

        <div class="flex justify-end">
            @if ($this->isCreated())
                <flux:button variant="primary" color="blue" icon="bookmark" type="submit">Guardar</flux:button>
            @elseif ($this->isEdit())
                <flux:button variant="primary" color="yellow" icon="pencil-square" type="submit">Guardar</flux:button>
            @elseif ($this->isShow())
            
            @endif
            </div>
    </form>
</div>