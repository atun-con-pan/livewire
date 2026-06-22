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
            <flux:button onclick="confirm('¿Estás seguro de eliminar este colaborador?') || event.stopImmediatePropagation()"
                wire:click="delete"
                variant="danger"
                size="sm"
                icon="trash">
                Eliminar
            </flux:button>

            <flux:button href="{{ route('periods.index') }}" wire:navigate variant="primary" size="sm" icon="chevron-left">
                Regresar
            </flux:button>
        </div>
    </div>

    <form wire:submit="save"
        class="space-y-5 w-full rounded-radius border border-outline p-6 shadow-md dark:border-outline-dark dark:bg-surface-dark-alt">

        {{-- ===================== --}}
        {{-- AFILIADO --}}
        {{-- ===================== --}}
        <div class="w-full"
            x-data="selectAffiliate($wire)"
            x-init="init()">

            <label class="text-sm text-on-surface dark:text-on-surface-dark">
                Afiliado
            </label>

            <div class="relative w-full">

                <button type="button"
                    class="w-full flex items-center justify-between border border-outline rounded-radius bg-surface-alt px-4 py-2 text-sm dark:bg-surface-dark-alt dark:border-outline-dark"
                    @click="toggle()">

                    <span x-text="selected ? selected.label : 'Seleccione un afiliado'"></span>

                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open"
                    @click.outside="open = false"
                    class="absolute z-50 mt-2 w-full border border-outline rounded-radius bg-white dark:bg-surface-dark-alt dark:border-outline-dark">

                    <input type="text"
                        class="w-full border-b px-3 py-2 text-sm dark:bg-surface-dark-alt dark:text-white"
                        placeholder="Buscar afiliado..."
                        x-model="search"
                        @input="filter()" />

                    <div class="max-h-60 overflow-auto">
                        <template x-for="item in filtered" :key="item.id">
                            <div class="px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
                                @click="select(item)"
                                x-text="item.label">
                            </div>
                        </template>
                    </div>

                </div>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- PROYECTO --}}
        {{-- ===================== --}}
        <div class="w-full"
            x-data="selectProject($wire)"
            x-init="init()">

            <label class="text-sm text-on-surface dark:text-on-surface-dark">
                Proyecto
            </label>

            <div class="relative w-full">

                <button type="button"
                    class="w-full flex items-center justify-between border border-outline rounded-radius bg-surface-alt px-4 py-2 text-sm dark:bg-surface-dark-alt dark:border-outline-dark"
                    @click="toggle()">

                    <span x-text="selected ? selected.label : 'Seleccione un proyecto'"></span>

                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="open"
                    @click.outside="open = false"
                    class="absolute z-50 mt-2 w-full border border-outline rounded-radius bg-white dark:bg-surface-dark-alt dark:border-outline-dark">

                    <input type="text"
                        class="w-full border-b px-3 py-2 text-sm dark:bg-surface-dark-alt dark:text-white"
                        placeholder="Buscar proyecto..."
                        x-model="search"
                        @input="filter()" />

                    <div class="max-h-60 overflow-auto">
                        <template x-for="item in filtered" :key="item.id">
                            <div class="px-3 py-2 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
                                @click="select(item)"
                                x-text="item.label">
                            </div>
                        </template>
                    </div>

                </div>
            </div>
        </div>

        {{-- FECHAS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input type="date" :readonly="$this->isShow()" wire:model="form.start_date"
                label="Fecha inicio" required />

            <flux:input type="date" :readonly="$this->isShow()" wire:model="form.end_date"
                label="Fecha fin" />
        </div>

        {{-- BOTONES --}}
        <div class="flex justify-end">
            @if ($this->isCreated())
                <flux:button type="submit" variant="primary" color="blue" icon="bookmark">
                    Guardar
                </flux:button>
            @elseif ($this->isEdit())
                <flux:button type="submit" variant="primary" color="yellow" icon="pencil-square">
                    Actualizar
                </flux:button>
            @endif
        </div>
    </form>
</div>

{{-- ===================== --}}
{{-- ALPINE --}}
{{-- ===================== --}}
<script>
function selectAffiliate($wire) {
    return {
        open: false,
        search: '',
        selected: null,
        filtered: [],

        all: @js($affiliates->map(fn($a) => [
            'id' => $a->id,
            'label' => $a->no_affiliate . ' - ' . $a->name,
        ])),

        init() {
            this.filtered = this.all

            let current = this.all.find(i => i.id == @js($form->affiliate_id ?? null))
            if (current) this.selected = current
        },

        toggle() {
            this.open = !this.open
        },

        filter() {
            this.filtered = this.all.filter(i =>
                i.label.toLowerCase().includes(this.search.toLowerCase())
            )
        },

        select(item) {
            this.selected = item
            this.open = false
            this.search = ''

            $wire.set('form.affiliate_id', item.id)
        }
    }
}

function selectProject($wire) {
    return {
        open: false,
        search: '',
        selected: null,
        filtered: [],

        all: @js($projects->map(fn($p) => [
            'id' => $p->id,
            'label' => $p->nog . ' - ' . $p->name,
        ])),

        init() {
            this.filtered = this.all

            let current = this.all.find(i => i.id == @js($form->project_id ?? null))
            if (current) this.selected = current
        },

        toggle() {
            this.open = !this.open
        },

        filter() {
            this.filtered = this.all.filter(i =>
                i.label.toLowerCase().includes(this.search.toLowerCase())
            )
        },

        select(item) {
            this.selected = item
            this.open = false
            this.search = ''

            $wire.set('form.project_id', item.id)
        }
    }
}
</script>