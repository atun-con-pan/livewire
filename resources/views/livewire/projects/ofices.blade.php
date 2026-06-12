<div>
    <div class="mb-4 flex align-items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Oficios del proyecto
        </h2>

        <flux:button href="{{ route('projects.index') }}" wire:navigate variant="primary" size="sm" icon="chevron-left">
            Regresar
        </flux:button>
    </div>

    <form wire:submit="store"
        class="space-y-5 w-full mb-4 p-6 dark:bg-surface-dark-alt rounded-radius border border-outline dark:border-outline-dark shadow-md">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:input type="date" wire:model="date" label="Fecha del oficio" name="date" />
            <flux:input wire:model="ofice" label="Número del Oficio" name="ofice" placeholder="Ingrese el número o referencia del oficio" />
            <flux:select wire:model="option" label="Seleccione una opción" placeholder="Seleccione una opción...">
                <flux:select.option>Entregado</flux:select.option>
                <flux:select.option>Recibido</flux:select.option>
            </flux:select>
        </div>
        <flux:input wire:model="description" label="Descripción" name="description" placeholder="Ingrese la descripción o motivo del oficio" />
        <flux:textarea wire:model="notes" label="Notas" name="notes" />
        <flux:input type="file" wire:model="file" label="Archivo" multiple />

        <div class="flex justify-end">
            <flux:button variant="primary" color="blue" icon="bookmark" type="submit">Guardar</flux:button>
        </div>
    </form>
</div>
