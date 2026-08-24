<div>
    <!-- Botón crear -->
    <div class="mb-4 flex items-center justify-between gap-4">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Explorador de archivos
        </h2>

        <div class="flex gap-2">
            <flux:modal.trigger name="create-folder">
                <flux:button wire:navigate variant="primary" color="blue" size="sm" icon="folder-plus">
                    Crear carpetas
                </flux:button>
            </flux:modal.trigger>

            <flux:modal.trigger name="upload-file">
                <flux:button wire:navigate variant="primary" color="lime" size="sm" icon="arrow-up-tray">
                    Subir archivos
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>


    <!-- BUSCADOR -->
    {{-- <flux:input wire:model.live="search" icon="magnifying-glass" placeholder="Buscar archivos..." class="mb-4" /> --}}

    <div class="flex w-full items-center justify-center mb-4">
        <!-- Línea izquierda -->
        <div class="h-px flex-1 bg-gray-700"></div>

        <!-- Contenedor de botones -->
        <div class="flex overflow-hidden rounded-lg border border-gray-700 bg-gray-800/80">

        </div>

        <!-- Línea derecha -->
        <div class="h-px flex-1 bg-gray-700"></div>
    </div>

    @php
        $breadcrumbItems = collect();
        $currentFolder = $folder;

        while ($currentFolder) {
            $breadcrumbItems->prepend([
                'name' => $currentFolder->name,
                'url' => route('folder.show', $currentFolder->id),
            ]);

            $currentFolder = $currentFolder->parent;
        }

        $backHref = $folder && $folder->parent
            ? route('folder.show', $folder->parent->id)
            : route('folder.index');

        $fileIcons = [
            'pdf' => 'bi-file-earmark-pdf-fill text-red-500',
            'doc' => 'bi-file-earmark-word-fill text-blue-500',
            'docx' => 'bi-file-earmark-word-fill text-blue-500',
            'xls' => 'bi-file-earmark-excel-fill text-green-500',
            'xlsx' => 'bi-file-earmark-excel-fill text-green-500',
            'csv' => 'bi-file-earmark-spreadsheet-fill text-green-600',
            'ppt' => 'bi-file-earmark-ppt-fill text-orange-500',
            'pptx' => 'bi-file-earmark-ppt-fill text-orange-500',
            'zip' => 'bi-file-zip-fill text-yellow-500',
            'rar' => 'bi-file-zip-fill text-yellow-500',
            'gz' => 'bi-file-zip-fill text-yellow-500',
            'tar' => 'bi-file-zip-fill text-yellow-500',
            'jpg' => 'bi-file-earmark-image-fill text-pink-500',
            'jpeg' => 'bi-file-earmark-image-fill text-pink-500',
            'png' => 'bi-file-earmark-image-fill text-pink-500',
            'gif' => 'bi-file-earmark-image-fill text-pink-500',
            'webp' => 'bi-file-earmark-image-fill text-pink-500',
            'svg' => 'bi-file-earmark-image-fill text-pink-500',
            'mp4' => 'bi-film text-violet-500',
            'mov' => 'bi-film text-violet-500',
            'avi' => 'bi-film text-violet-500',
            'mp3' => 'bi-music-note-beamed text-indigo-500',
            'wav' => 'bi-music-note-beamed text-indigo-500',
            'txt' => 'bi-file-earmark-text-fill text-gray-500',
            'json' => 'bi-braces text-cyan-500',
            'js' => 'bi-filetype-js text-yellow-500',
            'php' => 'bi-filetype-php text-indigo-500',
            'sql' => 'bi-database-fill text-sky-500',
            'xml' => 'bi-filetype-xml text-amber-500',
            'default' => 'bi-file-earmark-fill text-gray-500',
        ];
    @endphp

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-1 text-sm text-gray-600 dark:text-gray-300">
            @foreach ($breadcrumbItems as $index => $crumb)
                @if ($index > 0)
                    <span class="inline-flex items-center text-gray-400">
                        <i class="bi bi-chevron-right relative top-[1px]"></i>
                    </span>
                @endif

                @if ($loop->last)
                    <span class="font-medium text-gray-800 dark:text-gray-100">
                        {{ $crumb['name'] }}
                    </span>
                @else
                    <a href="{{ $crumb['url'] }}" wire:navigate
                        class="hover:text-primary-600 transition-colors">
                        {{ $crumb['name'] }}
                    </a>
                @endif
            @endforeach
        </nav>

        @if ($folder && $folder->parent)
            <a href="{{ $backHref }}" wire:navigate
                class="inline-flex items-center gap-2 rounded-md border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-zinc-700 dark:bg-zinc-800 dark:text-gray-200 dark:hover:bg-zinc-700">
                <i class="bi bi-arrow-left"></i>
                Retroceder
            </a>
        @elseif ($folder)
            <a href="{{ route('folder.index') }}" wire:navigate
                class="inline-flex items-center gap-2 rounded-md border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-zinc-700 dark:bg-zinc-800 dark:text-gray-200 dark:hover:bg-zinc-700">
                <i class="bi bi-arrow-left"></i>
                Retroceder
            </a>
        @endif
    </div>


    <!-- MODAL CREAR CARPETA -->
    <flux:modal name="create-folder" class="md:w-96">
        <form wire:submit="storeFolder" class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Crear nueva carpeta
                </flux:heading>
            </div>

            <flux:input wire:model="name" name="name" label="Nombre de la carpeta" placeholder="Nombre de la carpeta"
                required />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" icon="bookmark">
                    Crear
                </flux:button>
            </div>

        </form>
    </flux:modal>


    <!-- MODAL SUBIR ARCHIVO -->
    <!-- MODAL SUBIR ARCHIVO -->
<flux:modal name="upload-file" class="md:w-96">
    <form wire:submit="storeFile" class="space-y-6" enctype="multipart/form-data">

        <div>
            <flux:heading size="lg">
                Subir archivos
            </flux:heading>
        </div>

        <flux:input
            type="file"
            wire:model="uploadedFiles"
            name="uploadedFiles"
            label="Selecciona uno o varios archivos"
            multiple
            required
        />

        <div class="flex">
            <flux:spacer />

            <flux:button
                type="submit"
                variant="primary"
                icon="arrow-up-tray"
                wire:loading.attr="disabled"
                wire:target="uploadedFiles,storeFile"
            >
                Subir
            </flux:button>
        </div>

    </form>
</flux:modal>


    <!-- MODAL RENOMBRAR -->
    <flux:modal name="rename-item" class="md:w-96">

        <form wire:submit="renameItem" class="space-y-6">

            <div>
                <flux:heading size="lg">
                    Renombrar
                </flux:heading>
            </div>

            <flux:input wire:model="renameName" label="Nuevo nombre" placeholder="Nuevo nombre" required />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" icon="pencil">
                    Renombrar
                </flux:button>
            </div>

        </form>

    </flux:modal>


    <!-- EXPLORADOR -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2">

        {{-- ========================================================= --}}
        {{-- CARPETAS --}}
        {{-- ========================================================= --}}

        @foreach ($folders as $folderItem)
            <a href="{{ route('folder.show', $folderItem->id) }}" wire:navigate data-context-type="folder"
                data-context-id="{{ $folderItem->id }}" data-context-name="{{ $folderItem->name }}"
                class="context-item group flex flex-col items-center justify-center rounded-md border border-transparent p-3 hover:bg-gray-100 dark:hover:bg-zinc-800 transition">

                <div class="text-5xl leading-none mb-2">
                    📁
                </div>

                <div class="w-full text-center text-sm text-gray-700 dark:text-gray-200 truncate"
                    title="{{ $folderItem->name }}">
                    {{ $folderItem->name }}
                </div>

            </a>
        @endforeach


        {{-- ========================================================= --}}
        {{-- ARCHIVOS --}}
        {{-- ========================================================= --}}

        @foreach ($files as $fileItem)
            @php
                $fileExtension = strtolower(pathinfo($fileItem->name, PATHINFO_EXTENSION));
                $fileIcon = $fileIcons[$fileExtension] ?? $fileIcons['default'];
            @endphp

            <a href="{{ asset('storage/files/' . $fileItem->physical_name) }}" target="_blank" data-context-type="file"
                data-context-id="{{ $fileItem->id }}" data-context-name="{{ $fileItem->name }}"
                class="context-item group flex flex-col items-center justify-center rounded-md border border-transparent p-3 hover:bg-gray-100 dark:hover:bg-zinc-800 transition">

                <div class="mb-2 text-4xl leading-none" aria-label="{{ $fileItem->name }}">
                    <i class="bi {{ $fileIcon }}"></i>
                </div>

                <div class="w-full text-center text-sm text-gray-700 dark:text-gray-200 truncate"
                    title="{{ $fileItem->name }}">
                    {{ $fileItem->name }}
                </div>

            </a>
        @endforeach

    </div>


    <!-- ============================================================= -->
    <!-- MENÚ CONTEXTUAL -->
    <!-- ============================================================= -->

    <div id="context-menu"
        class="hidden fixed z-[9999] w-48 rounded-lg border border-gray-200 bg-white py-1 shadow-xl dark:border-zinc-700 dark:bg-zinc-800">

        <!-- VER -->
        <button type="button" id="context-view"
            class="flex w-full items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-zinc-700">

            <flux:icon.eye class="size-4" />

            <span>
                Ver
            </span>

        </button>


        <!-- RENOMBRAR -->
        <button type="button" id="context-rename"
            class="flex w-full items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-zinc-700">

            <flux:icon.pencil class="size-4" />

            <span>
                Renombrar
            </span>

        </button>


        <!-- SEPARADOR -->
        <div class="my-1 border-t border-gray-200 dark:border-zinc-700"></div>


        <!-- ELIMINAR -->
        <button type="button" id="context-delete"
            class="flex w-full items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30">

            <flux:icon.trash class="size-4" />

            <span>
                Eliminar
            </span>

        </button>

    </div>

</div>


<!-- ============================================================= -->
<!-- JAVASCRIPT -->
<!-- ============================================================= -->

<script>
if (!window.fileExplorerContextMenuInitialized) {

    window.fileExplorerContextMenuInitialized = true;


    /*
    |--------------------------------------------------------------------------
    | VARIABLES
    |--------------------------------------------------------------------------
    */

    let selectedType = null;
    let selectedId = null;
    let selectedName = null;


    /*
    |--------------------------------------------------------------------------
    | OBTENER MENÚ CONTEXTUAL
    |--------------------------------------------------------------------------
    */

    function getContextMenu() {

        return document.getElementById('context-menu');

    }


    /*
    |--------------------------------------------------------------------------
    | CERRAR MENÚ
    |--------------------------------------------------------------------------
    */

    function closeContextMenu() {

        const contextMenu = getContextMenu();

        if (contextMenu) {

            contextMenu.classList.add('hidden');

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CLIC DERECHO
    |--------------------------------------------------------------------------
    */

    document.addEventListener('contextmenu', function (event) {

        /*
        |--------------------------------------------------------------------------
        | Buscar archivo o carpeta
        |--------------------------------------------------------------------------
        */

        const item = event.target.closest('.context-item');


        /*
        | Si no estamos sobre un archivo o carpeta,
        | dejamos funcionar el menú normal del navegador.
        */

        if (!item) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Desactivar menú del navegador
        |--------------------------------------------------------------------------
        */

        event.preventDefault();


        /*
        |--------------------------------------------------------------------------
        | Guardar elemento seleccionado
        |--------------------------------------------------------------------------
        */

        selectedType = item.dataset.contextType;

        selectedId = item.dataset.contextId;

        selectedName = item.dataset.contextName;


        /*
        |--------------------------------------------------------------------------
        | Obtener menú
        |--------------------------------------------------------------------------
        */

        const contextMenu = getContextMenu();


        if (!contextMenu) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | POSICIÓN
        |--------------------------------------------------------------------------
        */

        let x = event.clientX;

        let y = event.clientY;


        const menuWidth = 192;

        const menuHeight = 150;

        const margin = 10;


        /*
        | Evitar que el menú salga por la derecha.
        */

        if (x + menuWidth > window.innerWidth) {

            x = window.innerWidth - menuWidth - margin;

        }


        /*
        | Evitar que el menú salga por abajo.
        */

        if (y + menuHeight > window.innerHeight) {

            y = window.innerHeight - menuHeight - margin;

        }


        /*
        |--------------------------------------------------------------------------
        | Aplicar posición
        |--------------------------------------------------------------------------
        */

        contextMenu.style.left = `${x}px`;

        contextMenu.style.top = `${y}px`;


        /*
        |--------------------------------------------------------------------------
        | Mostrar
        |--------------------------------------------------------------------------
        */

        contextMenu.classList.remove('hidden');

    });


    /*
    |--------------------------------------------------------------------------
    | CLIC NORMAL
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (event) {

        const contextMenu = getContextMenu();


        if (!contextMenu) {

            return;

        }


        /*
        | Si el clic fue dentro del menú,
        | no hacemos nada aquí.
        */

        if (contextMenu.contains(event.target)) {

            return;

        }


        /*
        | Si fue fuera, cerramos.
        */

        closeContextMenu();

    });


    /*
    |--------------------------------------------------------------------------
    | ESC
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            closeContextMenu();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | VER
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (event) {

        const button = event.target.closest('#context-view');


        if (!button) {

            return;

        }


        event.preventDefault();

        event.stopPropagation();


        /*
        |--------------------------------------------------------------------------
        | Verificar selección
        |--------------------------------------------------------------------------
        */

        if (!selectedType || !selectedId) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Cerrar menú
        |--------------------------------------------------------------------------
        */

        closeContextMenu();


        /*
        |--------------------------------------------------------------------------
        | Buscar elemento
        |--------------------------------------------------------------------------
        */

        const item = document.querySelector(
            `.context-item[data-context-type="${selectedType}"][data-context-id="${selectedId}"]`
        );


        /*
        |--------------------------------------------------------------------------
        | Abrir
        |--------------------------------------------------------------------------
        */

        if (item) {

            item.click();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | RENOMBRAR
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (event) {

        const button = event.target.closest('#context-rename');


        if (!button) {

            return;

        }


        event.preventDefault();

        event.stopPropagation();


        /*
        |--------------------------------------------------------------------------
        | Verificar selección
        |--------------------------------------------------------------------------
        */

        if (!selectedType || !selectedId) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Cerrar menú
        |--------------------------------------------------------------------------
        */

        closeContextMenu();


        /*
        |--------------------------------------------------------------------------
        | Preparar valor del modal
        |--------------------------------------------------------------------------
        */

        const root = document.querySelector('[wire\\:id]');

        if (!root) {
            return;
        }

        const componentId = root.getAttribute('wire:id');

        if (!componentId || !window.Livewire) {
            return;
        }

        const component = window.Livewire.find(componentId);

        component.set('renameType', selectedType);
        component.set('renameId', selectedId);
        component.set('renameName', selectedName || '');

        document.dispatchEvent(new CustomEvent('modal-show', {
            detail: { name: 'rename-item' }
        }));

    });


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function (event) {

        const button = event.target.closest('#context-delete');


        if (!button) {

            return;

        }


        event.preventDefault();

        event.stopPropagation();


        /*
        |--------------------------------------------------------------------------
        | Verificar selección
        |--------------------------------------------------------------------------
        */

        if (!selectedType || !selectedId) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Cerrar menú ANTES de mostrar confirmación
        |--------------------------------------------------------------------------
        */

        closeContextMenu();


        /*
        |--------------------------------------------------------------------------
        | Confirmación
        |--------------------------------------------------------------------------
        */

        const confirmed = window.confirm(
            `¿Estás seguro de que deseas eliminar "${selectedName}"?`
        );


        /*
        | Si cancela, no hacemos nada.
        */

        if (!confirmed) {

            return;

        }


        const component = window.Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));

        if (!component) {
            return;
        }

        component.call('deleteItem', selectedType, Number(selectedId));

    });


    /*
    |--------------------------------------------------------------------------
    | SCROLL
    |--------------------------------------------------------------------------
    */

    window.addEventListener('scroll', function () {

        closeContextMenu();

    });


    /*
    |--------------------------------------------------------------------------
    | RESIZE
    |--------------------------------------------------------------------------
    */

    window.addEventListener('resize', function () {

        closeContextMenu();

    });


    /*
    |--------------------------------------------------------------------------
    | LIVEWIRE NAVIGATION
    |--------------------------------------------------------------------------
    */

    document.addEventListener('livewire:navigated', function () {

        closeContextMenu();

    });

}
</script>