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

            @if ($this->isShow())
                <flux:input readonly wire:model="form.type" label="Tipo de documento" />
            @else
                <flux:select label="Tipo de documento" name="type" wire:model="form.type" required>
                    <option value="">Seleccionar el tipo de documento</option>
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
            @endif

            @if ($this->isEdit())
                <flux:input type="file" wire:model="form.file" label="Archivo" />
            @elseif ($this->isShow())
                <flux:input readonly wire:model="form.file_name" label="Archivo" />
            @elseif ($this->isCreated())
                <flux:input type="file" wire:model="form.file" label="Archivo" multiple />
            @endif
        </div>

        <div class="flex justify-end">
            @if ($this->isCreated())
                <flux:button
                    variant="primary"
                    color="blue"
                    icon="bookmark"
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="form.file,save">
                    Guardar
                </flux:button>
            @elseif ($this->isEdit())
                <flux:button 
                    variant="primary" 
                    color="yellow" 
                    icon="pencil-square" 
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="form.file,save">
                    Editar
                </flux:button>
            @elseif ($this->isShow())
            
            @endif
            </div>
    </form>
</div>