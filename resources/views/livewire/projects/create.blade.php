<div>
    <div class="mb-4 flex align-items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            @if ($this->isCreated())
                Crear proyecto
            @elseif($this->isEdit())
                Editar proyecto
            @elseif($this->isShow())
                Detalles del proyecto
            @endif
        </h2>

        <div class="flex gap-2">
            @auth
                @if ($this->isShow())
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'root')
                        <flux:button href="{{ route('projects.edit', $project) }}" wire:navigate variant="primary" color="yellow" size="sm" icon="pencil-square">
                            Editar
                        </flux:button>
                    @endif

                    <flux:button href="#" wire:navigate variant="primary" color="green" size="sm" icon="folder">
                        Archivos
                    </flux:button>
                @elseif($this->isEdit())
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'root')
                        <flux:button
                            onclick="confirm('¿Estás seguro de eliminar este colaborador?') || event.stopImmediatePropagation()"
                            wire:click="delete"
                            variant="danger" size="sm" icon="trash">
                            Eliminar
                        </flux:button>
                    @endif

                    <flux:button href="#" wire:navigate variant="primary" color="green" size="sm" icon="folder">
                        Archivos
                    </flux:button>
                @endif
                <flux:button href="{{ route('projects.index') }}" wire:navigate variant="primary" size="sm" icon="chevron-left">Regresar</flux:button>
            @endauth
        </div>
    </div>

    <form wire:submit="save"
        class="space-y-5 w-full p-6 dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">



        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input :readonly="($this->isShow())" wire:model="form.nog" label="Nog" name="nog" placeholder="Ingrese el nog" />
            @if($this->isShow())
                <flux:input readonly wire:model="form.event" label="Evento" name="event" />
            @else
                <flux:select wire:model="form.event" label="Evento" name="event">
                    <option value="">Seleccione un evento</option>
                    <option value="Licitacion">Licitacion</option>
                    <option value="Cotizacion">Cotizacion</option>
                    <option value="Compra Directa">Compra Directa</option>
                    <option value="Otros">Otros</option>
                </flux:select>
            @endif
        </div>

        <flux:input :readonly="($this->isShow())" wire:model="form.name" label="Nombre" name="name" placeholder="Ingrese el nombre del proyecto" />
        <flux:input :readonly="($this->isShow())" wire:model="form.url" label="URL del proyecto" name="url" placeholder="Ingrese la url del proyecto" />
        <flux:input :readonly="($this->isShow())" wire:model="form.client" label="Entidad contratante" name="client" placeholder="Ingrese la entidad contratante" />

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:input :readonly="($this->isShow())" wire:model="form.presentation_date" label="Fecha de presentación" name="presentation_date" type="date" />
            <flux:input :readonly="($this->isShow())" wire:model="form.start_date" label="Fecha de inicio" name="start_date" type="date" />
            <flux:input :readonly="($this->isShow())" wire:model="form.end_date" label="Fecha de finalización" name="end_date" type="date" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input.group label="Precio">
                <flux:select class="max-w-fit">
                    <flux:select.option selected>Q</flux:select.option>
                    <!-- ... -->
                </flux:select>
                <flux:input :readonly="($this->isShow())" mask:dynamic="$money($input)" name="price" placeholder="Ingrese el precio" wire:model="form.price" />
            </flux:input.group>
            @if ($this->isSHow())
                <flux:input readonly wire:model="form.status" label="Estado" name="status" />
            @else
                <flux:select wire:model="form.status" label="Estado" name="status">
                    <option value="">Seleccione un estado</option>
                    <option value="En curso">En curso</option>
                    <option value="Finalizado">Finalizado</option>
                    <option value="Suspendido">Suspendido</option>
                    <option value="Rechazado">Rechazado</option>
                    <option value="No presentado">No presentado</option>
                </flux:select>
            @endif
        </div>

        <div class="flex justify-end">
            @if ($this->isCreated())
                <flux:button variant="primary" color="blue" icon="bookmark" type="submit">Guardar</flux:button>
            @elseif($this->isEdit())
                <flux:button variant="primary" color="yellow" icon="pencil-square" type="submit">Editar</flux:button>               
            @endif
        </div>
    </form>
</div>
