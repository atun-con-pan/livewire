<div>
    <div class="mb-4 flex align-items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            @if($this->isCreated())
                Crear colaborador
            @elseif($this->isEdit())
                Editar colaborador
            @elseif($this->isShow())
                Detalles del colaborador
            @endif
        </h2>

        <div class="flex gap-2">
            @auth
                @if($this->isShow())
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'root')
                        <flux:button href="{{ route('collaborators.edit', $collaborator) }}" wire:navigate variant="primary" color="yellow" size="sm" icon="pencil-square">
                            Editar
                        </flux:button>
                    @endif

                    <flux:button href="{{ route('collaborators.file', $collaborator) }}" wire:navigate variant="primary" color="green" size="sm" icon="folder">
                        Archivos
                    </flux:button>
                @elseif($this->isEdit())
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'root')
                        <flux:button
                            onclick="confirm('¿Estás seguro de eliminar este colaborador?') || event.stopImmediatePropagation()"
                            wire:click="delete"
                            variant="danger" size="sm" icon="trash">
                            Eliminar
                        </flux:buttononclick=>
                    @endif
                
                    <flux:button href="{{ route('collaborators.file', $collaborator) }}" wire:navigate variant="primary" color="green" size="sm" icon="folder">
                        Archivos
                    </flux:button>
                @endif
                <flux:button href="{{ route('collaborators.index') }}" wire:navigate variant="primary" size="sm" icon="chevron-left">
                    Regresar
                </flux:button>
            @endauth
        </div>
    </div>

    <form 
        wire:submit="save"
        class="space-y-5 w-full p-6 dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:input :readonly="($this->isShow())" label="Primer nombre" placeholder="Ingrese el primer nombre" name="first_name" wire:model="form.first_name" required />
            <flux:input :readonly="($this->isShow())" label="Segundo nombre" placeholder="Ingrese el segundo nombre" name="middle_name" wire:model="form.middle_name" />
            <flux:input :readonly="($this->isShow())" label="Primer apellido" placeholder="Ingrese el primer apellido" name="first_surname" wire:model="form.first_surname" required />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:input :readonly="($this->isShow())" label="Segundo apellido" placeholder="Ingrese el segundo apellido" name="second_last_name" wire:model="form.second_last_name" />
            <flux:input :readonly="($this->isShow())" mask="9999-99999-9999" label="DPI" placeholder="Ingrese el DPI" name="dpi" wire:model="form.dpi" required />
            <flux:input :readonly="($this->isShow())" label="Fecha de nacimiento" placeholder="Ingrese la fecha de nacimiento" name="birthdate" type="date" wire:model="form.birthdate" required />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
                @if($this->isShow())
                    <flux:input readonly label="Estado civil" name="marital_status" wire:model="form.marital_status" />
                @else
                    <flux:select label="Estado civil" name="marital_status" wire:model="form.marital_status" required >
                        <option value="">Seleccione el estado civil</option>
                        <option value="Soltero">Soltero</option>
                        <option value="Casado">Casado</option>
                        <option value="Viudo">Viudo</option>
                        <option value="Divorciado">Divorciado</option>
                    </flux:select>
                @endif
            </div>

            <div class="md:col-span-2">
                <flux:input :readonly="($this->isShow())" label="Residencia" placeholder="Ingrese la residencia" name="residence" wire:model="form.residence" required />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:input :readonly="($this->isShow())" mask="9999-9999" label="Teléfono" placeholder="Ingrese el teléfono" name="phone" type="tel" wire:model="form.phone" required />
            <flux:input :readonly="($this->isShow())" label="Correo electrónico" placeholder="Ingrese el email" name="email" type="email" wire:model="form.email" />
            <flux:input :readonly="($this->isShow())" label="Cargo" placeholder="Ingrese el cargo" name="position" wire:model="form.position" required />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:input :readonly="($this->isShow())" label="Fecha de inicio" placeholder="Ingrese la fecha de inicio" name="start_date" type="date" wire:model="form.start_date" required />
            <flux:input :readonly="($this->isShow())" label="Fecha de terminación" placeholder="Ingrese la fecha de terminación" name="termination_date" type="date" wire:model="form.termination_date" />

            <flux:input.group label="Salario">
                <flux:select class="max-w-fit">
                    <flux:select.option selected>Q</flux:select.option>
                </flux:select>
                <flux:input :readonly="($this->isShow())" mask:dynamic="$money($input)" name="salary" placeholder="Ingrese el salario" wire:model="form.salary" required />
            </flux:input.group>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if(request()->routeIs('collaborators.show'))
                <flux:input readonly label="Tipo de contrato" name="contract" wire:model="form.contract" />
            @else
                <flux:select label="Tipo de contrato" name="contract" wire:model="form.contract" required>
                    <option value="">Seleccione el tipo de contrato</option>
                    <option value="Indefinido">Indefinido</option>
                    <option value="Temporal">Temporal</option>
                    <option value="Practicante">Practicante</option>
                </flux:select>
            @endif

            <flux:input :readonly="($this->isShow())" label="Jefe" placeholder="Ingrese el jefe" name="pattern" wire:model="form.pattern" required />
            <flux:input :readonly="($this->isShow())" label="Cuenta bancaria" placeholder="Ingrese la cuenta bancaria" name="bank_account" wire:model="form.bank_account" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:input :readonly="($this->isShow())" label="Banco" placeholder="Ingrese el banco" name="bank" wire:model="form.bank" />
            <flux:input :readonly="($this->isShow())" label="Nombre de la cuenta" placeholder="Ingrese el nombre de la cuenta" name="bank_account_name" wire:model="form.bank_account_name" />
            <flux:input :readonly="($this->isShow())" label="No. IGSS" placeholder="Ingrese el IGSS" name="no_igss" wire:model="form.no_igss" />
        </div>

        <div class="flex justify-end">
            @if($this->isCreated())
                <flux:button variant="primary" color="blue" icon="bookmark" type="submit">
                    Guardar
                </flux:button>
            @elseif($this->isEdit())
                <flux:button variant="primary" color="yellow" icon="pencil-square" type="submit">
                    Editar
                </flux:button>
            @endif
        </div>
    </form>
</div>