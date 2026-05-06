<div>
    <div class="mb-4 flex align-items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            @if ($this->isCreated())
                Crear documento
            @elseif ($this->isEdit())
                Editar documento
            @elseif ($this->isShow())
                Detalles del documento
            @endif
        </h2>

        <div class="flex gap-2">
            @auth
                @if ($this->isEdit())
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'root')
                        <flux:button
                            onclick="confirm('¿Estás seguro de eliminar este colaborador?') || event.stopImmediatePropagation()"
                            wire:click="delete"
                            variant="danger" size="sm" icon="trash">
                            Eliminar
                        </flux:button>
                    @endif
                    
                @elseif($this->isShow())
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'root')
                        <flux:button href="{{ route('documents.edit', $document) }}" wire:navigate variant="primary" color="yellow" size="sm" icon="pencil-square">
                            Editar
                        </flux:button>
                    @endif
                @endif

                <flux:button href="{{ route('documents.index') }}" wire:navigate variant="primary" size="sm" icon="chevron-left">
                    Regresar
                </flux:button>
            @endauth
        </div>
    </div>

    <form wire:submit="save"
        class="space-y-5 w-full p-6 dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input :readonly="($this->isShow())" wire:model="form.name" label="Nombre completo del afiliado" name="name" placeholder="Ingrese el nombre completo" required />
            <flux:input :readonly="($this->isShow())" wire:model="form.dpi" label="DPI" name="dpi" placeholder="Ingrese el DPI" mask="9999-99999-9999" required />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input :readonly="($this->isShow())" wire:model="form.no_affiliate" label="No. Afiliado" name="no_affiliate" placeholder="Ingrese el número de afiliado" required />
            <flux:input :readonly="($this->isShow())" wire:model="form.nog" label="NOG del proyecto" name="nog" placeholder="Ingrese el nog del proyecto" required />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input :readonly="($this->isShow())" type="date" wire:model="form.start_date" label="Fecha de inicio" name="start_date" required />
            <flux:input :readonly="($this->isShow())" type="date" wire:model="form.end_date" label="Fecha de finalización" name="end_date" />
        </div>
        
        <flux:input :readonly="($this->isShow())" wire:model="form.project" label="Proyecto" name="project" placeholder="Ingrese el nombre del proyecto" required />
            
        <div class="flex justify-end">
            @if ($this->isCreated())
                <flux:button variant="primary" color="blue" icon="bookmark" type="submit">Guardar</flux:button>
            @elseif ($this->isEdit())
                <flux:button variant="primary" color="yellow" icon="pencil-square" type="submit">Editar</flux:button>
            @elseif ($this->isShow())
            
            @endif
            </div>
    </form>
</div>