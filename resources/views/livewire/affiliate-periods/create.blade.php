<div>
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            @if ($this->isCreated())
                Crear período
            @elseif ($this->isEdit())
                Editar período
            @elseif ($this->isShow())
                Detalles del período
            @endif
        </h2>

        <div class="flex gap-2">
            <flux:button
                onclick="confirm('¿Estás seguro de eliminar este colaborador?') || event.stopImmediatePropagation()"
                wire:click="delete" variant="danger" size="sm" icon="trash">
                Eliminar
            </flux:button>
            <flux:button href="{{ route('periods.index') }}"
                wire:navigate variant="primary" size="sm" icon="chevron-left">
                Regresar
            </flux:button>
        </div>

    </div>
    <form wire:submit="save" class="space-y-5 w-full rounded-radius border border-outline p-6 shadow-md dark:border-outline-dark dark:bg-surface-dark-alt">
        {{-- AFILIADO --}}
        <div class="grid grid-cols-1 gap-4">
            <flux:select :readonly="$this->isShow()" wire:model="form.affiliate_id" label="Afiliado" required>
                <option value="">Seleccione un afiliado</option>
                @foreach ($affiliates as $affiliate)
                    <option value="{{ $affiliate->id }}"> {{ $affiliate->name }} - {{ $affiliate->no_affiliate }}</option>
                @endforeach
            </flux:select>

        </div>
        
        {{-- PROYECTO --}}
        <div class="grid grid-cols-1 gap-4">
            <flux:select :readonly="$this->isShow()" wire:model="form.project_id" label="Proyecto" required>
                <option value="">Seleccione un proyecto</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}"> {{ $project->name }} ({{ $project->nog }}) </option>
                @endforeach
            </flux:select>
        </div>
        
        {{-- FECHAS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input type="date" :readonly="$this->isShow()" wire:model="form.start_date" label="Fecha inicio" required />
            <flux:input type="date" :readonly="$this->isShow()" wire:model="form.end_date" label="Fecha fin" />
        </div>
        
        {{-- BOTONES --}}
        <div class="flex justify-end">
            @if ($this->isCreated())
                <flux:button type="submit" variant="primary" color="blue" icon="bookmark">Guardar</flux:button>
            @elseif ($this->isEdit())
                <flux:button type="submit" variant="primary" color="yellow" icon="pencil-square">Actualizar</flux:button>
            @endif
        </div>
    </form>
</div>
