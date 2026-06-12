<div>
    <div class="mb-4 flex align-items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            @if ($this->isCreated())
                Crear afiliado
            @elseif ($this->isEdit())
                Editar afiliado
            @elseif ($this->isShow())
                Detalles del afiliado
            @endif
        </h2>

        <div class="flex gap-2">
            @auth
                @if ($this->isEdit())
                    <flux:button onclick="confirm('¿Estás seguro de eliminar este colaborador?') || event.stopImmediatePropagation()" wire:click="delete( {{ $affiliate }})" variant="danger" size="sm" icon="trash">
                        Eliminar
                    </flux:button>
                @endif
                @if ($this->isShow())
                    <flux:button href="{{ route('affiliates.edit', $affiliate) }}" wire:navigate variant="primary" color="yellow" size="sm" icon="pencil-square">
                        Editar
                    </flux:button>
                @endif
                <flux:button href="{{ route('affiliates.index') }}" wire:navigate variant="primary" size="sm" icon="chevron-left">
                    Regresar
                </flux:button>
            @endauth
        </div>
    </div>

    <form wire:submit="save" 
    class="space-y-5 w-full rounded-radius border border-outline p-6 shadow-md dark:border-outline-dark dark:bg-surface-dark-alt">

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <flux:input :readonly="($this->isShow())" wire:model.live="form.name" label="Nombre completo del afiliado" name="name" placeholder="Ingrese el nombre completo" required />
            <flux:input :readonly="($this->isShow())" wire:model.live="form.dpi" label="DPI" name="dpi" placeholder="Ingrese el DPI" mask="9999-99999-9999" required />
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <flux:input :readonly="($this->isShow())" wire:model.live="form.no_affiliate" label="No. Afiliado" name="no_affiliate" placeholder="Ingrese el número de afiliado" required />
            <flux:input :readonly="($this->isShow())" wire:model.live="form.nog" label="NOG del proyecto" name="nog" placeholder="Ingrese el NOG del proyecto" required />
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <flux:input :readonly="($this->isShow())" type="date" wire:model.live="form.start_date" label="Fecha de inicio" name="start_date" required />
            <flux:input :readonly="($this->isShow())" type="date" wire:model.live="form.end_date" label="Fecha de finalización" name="end_date" />
        </div>

        @if(!empty($form->conflictingAffiliates))
            <div class="rounded-lg border border-red-300 bg-red-100 p-4 text-red-800">
                <p class="mb-3 font-bold">
                    Existe un afiliado con fechas traslapadas.
                </p>

                <div class="space-y-3">
                    @foreach($form->conflictingAffiliates as $affiliate)
                        <div class="border-b border-red-300 pb-2">
                            <p><strong>Nombre:</strong> {{ $affiliate['name'] }}</p>

                            <p><strong>DPI:</strong> {{ $affiliate['dpi'] }}</p>

                            <p><strong>No. Afiliado:</strong> {{ $affiliate['no_affiliate'] }}</p>

                            <p>
                                <strong>Inicio:</strong>
                                {{ \Carbon\Carbon::parse($affiliate['start_date'])->format('d/m/Y') }}
                            </p>

                            <p>
                                <strong>Fin:</strong>

                                @if($affiliate['end_date'])
                                    {{ \Carbon\Carbon::parse($affiliate['end_date'])->format('d/m/Y') }}
                                @else
                                    Sin finalizar
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <flux:input :readonly="($this->isShow())" wire:model.live="form.project" label="Proyecto" name="project" placeholder="Ingrese el nombre del proyecto" required />

        <div class="flex justify-end">
            @if ($this->isCreated())
                <flux:button variant="primary" color="blue" icon="bookmark" type="submit">
                    Guardar
                </flux:button>
            @elseif ($this->isEdit())
                <flux:button variant="primary" color="yellow" icon="pencil-square" type="submit">
                    Editar
                </flux:button>
            @endif
        </div>
    </form>
</div>