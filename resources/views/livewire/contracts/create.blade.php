<div>
    <div class="mb-4 flex align-items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            @if ($this->isCreated())
                Crear contrato
            @elseif($this->isEdit())
                Editar contrato
            @elseif($this->isShow())
                Detalles del contrato
            @endif
        </h2>

        <div class="flex gap-2">
            @auth
                @if ($this->isShow())
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'root')
                        <flux:button href="{{ route('contracts.edit', $contract) }}" wire:navigate variant="primary" color="yellow" size="sm" icon="pencil-square">
                            Editar
                        </flux:button>
                    @endif

                    <flux:button href="{{ route('contracts.file', $contract) }}" wire:navigate variant="primary" color="green" size="sm" icon="folder">
                        Archivos
                    </flux:button>
                @elseif($this->isEdit())
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'root')
                        <flux:button
                            wire:confirm.prompt="¿Estás seguro de eliminar este colaborador?\n\nEscribe DELETE para confirmar|DELETE"
                            wire:click="delete"
                            variant="danger" size="sm" icon="trash">
                            Eliminar
                        </flux:button>
                    @endif
                    
                    <flux:button href="{{ route('contracts.file', $contract) }}" wire:navigate variant="primary" color="green" size="sm" icon="folder">
                        Archivos
                    </flux:button>
                @endif
                <flux:button href="{{ route('contracts.index') }}" wire:navigate variant="primary" size="sm" icon="chevron-left">
                    Regresar
                </flux:button>
            @endauth
        </div>
    </div>

    <form wire:submit="save"
        class="space-y-5 w-full p-6 dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <flux:input :readonly="($this->isShow())" wire:model="form.nog_contract" label="NOG" name="nog_contract" placeholder="Ingrese el NOG del contrato" required />
            </div>
            <div class="md:col-span-3">
                <flux:input :readonly="($this->isShow())" wire:model="form.contract_name" label="Nombre del contrato" name="contract_name" placeholder="Ingrese el nombre del contrato" required />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <flux:input :readonly="($this->isShow())" wire:model="form.contract_registration_date" label="Fecha de inscripción del contrato" name="contract_registration_date" type="date" required />
            <flux:input :readonly="($this->isShow())" wire:model="form.contract_subscription_date" label="Fecha de suscripción del contrato" name="contract_subscription_date" type="date" required />
            <flux:input :readonly="($this->isShow())" wire:model="form.start_date_activities" label="Fecha de inicio de actividades" name="start_date_activities" type="date" required />
            <flux:input :readonly="($this->isShow())" wire:model="form.final_date_activities" label="Fecha de finalización de actividades" name="final_date_activities" type="date" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <flux:input :readonly="($this->isShow())" wire:model="form.no_contract" label="Número del contrato" name="no_contract" placeholder="Ingrese el número del contrato" required />
            </div>
            <div class="md:col-span-3">
                <flux:input :readonly="($this->isShow())" wire:model="form.execution_address" label="Dirección del proyecto" name="execution_address" placeholder="Ingrese la descripción del proyecto" required />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <flux:input :readonly="($this->isShow())" wire:model="form.number_workers" label="Cantidad de trabajadores" name="number_workers" type="number" required placeholder="Ingrese la cantidad de trabajadores"/>
            <flux:input.group label="Monto de salarios">
                <flux:select class="max-w-fit">
                    <flux:select.option selected>Q</flux:select.option>
                    <!-- ... -->
                </flux:select>
                <flux:input :readonly="($this->isShow())" wire:model="form.salary_amount" mask:dynamic="$money($input)" name="salary_amount" placeholder="Ingrese el precio" required />
            </flux:input.group>
            <flux:input :readonly="($this->isShow())" wire:model="form.filial" label="Filial" name="filial" placeholder="Ingrese la filial" required />
            @if ($this->isShow())
                <flux:input :readonly="($this->isShow())" wire:model="form.status" label="Estado" name="status" />
                <flux:input :readonly="($this->isShow())" wire:model="form.person_charge" label="Persona a cargo" name="person_charge" />
            @else
                <flux:select wire:model="form.status" label="Estado" name="status" required>
                    <option value="">Seleccione una opción</option>
                    <option value="En curso">En curso</option>
                    <option value="Finalizado">Finalizado</option>
                    <option value="Suspendido">Suspendido</option>
                    <option value="Reparado">Reparado</option>
                </flux:select>
                <flux:select wire:model="form.person_charge" label="Persona a cargo" name="person_charge" required >
                    <option value="">Seleccione una opción</option>
                    <option value="Samanta Garcia">Samanta Garcia</option>
                    <option value="Dipconsa">Dipconsa</option>
                    <option value="Pedro Javier Santiago">Pedro Javier Santiago</option>
                    <option value="Jhoseline (Constructora Ramos)">Jhoseline (Constructora Ramos)</option>
                    <option value="Doña Mayra">Doña Mayra</option>
                    <option value="Manrique Chin">Manrique Chin</option>
                </flux:select>
            @endif
        </div>

        <div class="flex justify-end">
            @if ($this->isCreated())
                <flux:button variant="primary" color="blue" icon="bookmark" type="submit">Guardar</flux:button>
            @elseif($this->isEdit())
                <flux:button variant="primary" color="yellow" icon="pencil-square" type="submit">Guardar</flux:button>
            @endif
        </div>
    </form>
</div>
