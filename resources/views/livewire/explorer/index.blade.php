<div>
    <!-- Botón crear -->
    <div class="mb-4 flex items-center justify-between gap-4">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 relative pl-4">
            <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2 bg-primary rounded"></span>
            Explorador de archivos
        </h2>

        <p class="text-sm text-gray-500 dark:text-gray-400 sm:text-right">
            {{ $folders->count() + $files->count() }} elementos
        </p>

        <div class="flex gap-2">
            <flux:modal.trigger name="upload-folder">
                <flux:button wire:navigate variant="primary" color="amber" size="sm" icon="folder-plus">
                    Subir carpeta
                </flux:button>
            </flux:modal.trigger>

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

<!-- MODAL SUBIR CARPETA -->
<flux:modal name="upload-folder" class="md:w-96">
    <form wire:submit="storeFolderUpload" class="space-y-6" enctype="multipart/form-data">

        <div>
            <flux:heading size="lg">
                Subir carpeta completa
            </flux:heading>
        </div>

        <flux:input
            type="file"
            wire:model="uploadedFolderFiles"
            name="uploadedFolderFiles"
            label="Selecciona una carpeta con todos sus archivos y subcarpetas"
            multiple
            webkitdirectory
            directory
            x-on:change="
                $wire.folderUploading = true;

                $wire.set(
                    'folderUploadPayload',
                    Array.from($event.target.files).map(file => ({
                        relativePath: file.webkitRelativePath || file.name
                    }))
                )
            "
            required
        />

        <div class="flex">
            <flux:spacer />

            <flux:button
                type="submit"
                variant="primary"
                icon="folder-plus"
                wire:loading.attr="disabled"
                wire:target="uploadedFolderFiles"
                x-bind:disabled="$wire.folderUploading"
            >
                Subir carpeta
            </flux:button>
        </div>

    </form>
</flux:modal>

    <!-- MODAL SUBIR ARCHIVO -->
    {{-- <flux:modal name="upload-file" class="md:w-96">
        <form
            wire:submit="storeFile"
            class="space-y-6"
            enctype="multipart/form-data"
            x-data="{
                files: [],
                updateFiles(event) {
                    this.files = Array.from(event.target.files);
                }
            }"
        >

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
                x-on:change="updateFiles($event)"
            />

            <!-- ARCHIVOS SELECCIONADOS -->
            <div
                x-show="files.length > 0"
                x-cloak
                class="space-y-2"
            >
                <flux:text size="sm" class="font-medium">
                    Archivos seleccionados
                </flux:text>

                <div class="max-h-40 space-y-1 overflow-y-auto rounded-lg border border-zinc-200 bg-zinc-50 p-2 dark:border-zinc-700 dark:bg-zinc-900">

                    <template x-for="(file, index) in files" :key="index">
                        <div class="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm">

                            <flux:icon
                                name="document"
                                variant="micro"
                                class="shrink-0 text-zinc-500"
                            />

                            <span
                                class="min-w-0 flex-1 truncate"
                                x-text="file.name"
                                :title="file.name"
                            ></span>

                            <span
                                class="shrink-0 text-xs text-zinc-500"
                                x-text="(file.size / 1024 / 1024).toFixed(2) + ' MB'"
                            ></span>

                        </div>
                    </template>

                </div>
            </div>

            <!-- CARGANDO ARCHIVOS -->
            <div
                wire:loading
                wire:target="uploadedFiles"
                class="flex items-center gap-2 text-sm text-zinc-500"
            >
                <flux:icon
                    name="arrow-path"
                    class="size-4 animate-spin"
                />

                <span>
                    Cargando archivos...
                </span>
            </div>

            <!-- PROCESANDO ARCHIVOS -->
            <div
                wire:loading
                wire:target="storeFile"
                class="flex items-center gap-2 text-sm text-zinc-500"
            >
                <flux:icon
                    name="arrow-path"
                    class="size-4 animate-spin"
                />

                <span>
                    Procesando archivos...
                </span>
            </div>

            <div class="flex">
                <flux:spacer />

                <flux:button
                    type="submit"
                    variant="primary"
                    icon="arrow-up-tray"
                    wire:loading.attr="disabled"
                    wire:target="uploadedFiles,storeFile"
                >
                    <span wire:loading.remove wire:target="uploadedFiles,storeFile">
                        Subir
                    </span>

                    <span
                        wire:loading
                        wire:target="uploadedFiles,storeFile"
                        class="flex items-center gap-2"
                    >
                        <flux:icon
                            name="arrow-path"
                            class="size-4 animate-spin"
                        />

                        Cargando...
                    </span>
                </flux:button>
            </div>

        </form>
    </flux:modal> --}}


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
    <div data-explorer-root class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2">

        {{-- ========================================================= --}}
        {{-- CARPETAS --}}
        {{-- ========================================================= --}}

        @foreach ($folders as $folderItem)
            <div draggable="true"
                data-context-type="folder" data-context-id="{{ $folderItem->id }}"
                data-context-name="{{ $folderItem->name }}" data-draggable-item data-item-type="folder"
                data-item-id="{{ $folderItem->id }}" data-href="{{ route('folder.show', $folderItem->id) }}"
                data-open-mode="folder"
                class="context-item group flex flex-col items-center justify-center rounded-md border border-transparent p-3 hover:bg-gray-100 dark:hover:bg-zinc-800 transition drag-target selection-target cursor-pointer">

                <div class="text-5xl leading-none mb-2">
                    📁
                </div>

                <div class="w-full text-center text-sm text-gray-700 dark:text-gray-200 truncate"
                    title="{{ $folderItem->name }}">
                    {{ $folderItem->name }}
                </div>

            </div>
        @endforeach


        {{-- ========================================================= --}}
        {{-- ARCHIVOS --}}
        {{-- ========================================================= --}}

        @foreach ($files as $fileItem)
            @php
                $fileExtension = strtolower(pathinfo($fileItem->name, PATHINFO_EXTENSION));
                $fileIcon = $fileIcons[$fileExtension] ?? $fileIcons['default'];
            @endphp

            <div draggable="true"
                data-context-type="file" data-context-id="{{ $fileItem->id }}" data-context-name="{{ $fileItem->name }}"
                data-draggable-item data-item-type="file" data-item-id="{{ $fileItem->id }}"
                data-href="{{ asset('storage/files/' . $fileItem->physical_name) }}" data-open-target="_blank"
                class="context-item group flex flex-col items-center justify-center rounded-md border border-transparent p-3 hover:bg-gray-100 dark:hover:bg-zinc-800 transition drag-target selection-target cursor-pointer">

                <div class="mb-2 text-4xl leading-none" aria-label="{{ $fileItem->name }}">
                    <i class="bi {{ $fileIcon }}"></i>
                </div>

                <div class="w-full text-center text-sm text-gray-700 dark:text-gray-200 truncate"
                    title="{{ $fileItem->name }}">
                    {{ $fileItem->name }}
                </div>

            </div>
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

    function highlightDropFolder(folderElement) {
        if (!folderElement) {
            return;
        }

        folderElement.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50/50');
    }

    function unhighlightDropFolder(folderElement) {
        if (!folderElement) {
            return;
        }

        folderElement.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50/50');
    }

    const selection = new Set();
    let rectangleSelection = null;
    let dragStartInfo = null;

    function getItemKey(item) {
        return `${item.dataset.itemType}:${item.dataset.itemId}`;
    }

    function syncSelectionState() {
        document.querySelectorAll('[data-draggable-item]').forEach((item) => {
            const key = getItemKey(item);
            const isSelected = selection.has(key);
            item.dataset.selected = isSelected ? 'true' : 'false';
            item.classList.toggle('ring-2', isSelected);
            item.classList.toggle('ring-violet-500', isSelected);
            item.classList.toggle('bg-violet-50/60', isSelected);
        });
    }

    function selectSingleItem(item) {
        selection.clear();
        selection.add(getItemKey(item));
        syncSelectionState();
    }

    function toggleSelection(item) {
        const key = getItemKey(item);

        if (selection.has(key)) {
            selection.delete(key);
        } else {
            selection.add(key);
        }

        syncSelectionState();
    }

    function openItemByHref(item) {
        const href = item.dataset.href;

        if (!href) {
            return;
        }

        if (item.dataset.openMode === 'folder') {
            if (window.Livewire && typeof window.Livewire.navigate === 'function') {
                window.Livewire.navigate(href);
                return;
            }

            window.location.href = href;
            return;
        }

        const target = item.dataset.openTarget || '_blank';
        window.open(href, target);
    }

    function getDraggedItems(item) {
        const selectedItems = Array.from(document.querySelectorAll('[data-draggable-item][data-selected="true"]'));

        if (selectedItems.length > 1) {
            return selectedItems.map((selected) => ({
                type: selected.dataset.itemType,
                id: Number(selected.dataset.itemId),
            }));
        }

        if (!item) {
            return [];
        }

        return [{
            type: item.dataset.itemType,
            id: Number(item.dataset.itemId),
        }];
    }

    function handleExplorerDrop(event) {
        const targetFolder = event.target.closest('.drag-target[data-item-type="folder"]');

        if (!targetFolder) {
            return;
        }

        event.preventDefault();
        unhighlightDropFolder(targetFolder);

        const payload = event.dataTransfer?.getData('text/plain');

        if (!payload) {
            return;
        }

        try {
            const dragged = JSON.parse(payload);
            const items = Array.isArray(dragged) ? dragged : [dragged];

            if (!items.length || !window.Livewire) {
                return;
            }

            const root = document.querySelector('[wire\\:id]');

            if (!root) {
                return;
            }

            const component = window.Livewire.find(root.getAttribute('wire:id'));

            if (!component) {
                return;
            }

            const targetId = Number(targetFolder.dataset.itemId);

            if (items.some((item) => item && item.type === 'folder' && Number(item.id) === targetId)) {
                return;
            }

            component.call('moveItems', items, targetId);
        } catch (error) {
            console.error('Error al mover el elemento:', error);
        }
    }

    document.addEventListener('dragstart', function (event) {
        const item = event.target.closest('[data-draggable-item]');

        if (!item) {
            return;
        }

        const draggedItems = getDraggedItems(item);

        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', JSON.stringify(draggedItems));

        item.classList.add('opacity-50');
    });

    document.addEventListener('dragend', function (event) {
        const item = event.target.closest('[data-draggable-item]');

        if (item) {
            item.classList.remove('opacity-50');
        }

        document.querySelectorAll('.drag-target[data-item-type="folder"]').forEach((folder) => {
            unhighlightDropFolder(folder);
        });

        if (rectangleSelection && rectangleSelection.box) {
            rectangleSelection.box.remove();
            rectangleSelection = null;
        }
    });

    document.addEventListener('dragover', function (event) {
        const targetFolder = event.target.closest('.drag-target[data-item-type="folder"]');

        if (!targetFolder) {
            return;
        }

        event.preventDefault();
        highlightDropFolder(targetFolder);
    });

    document.addEventListener('dragleave', function (event) {
        const targetFolder = event.target.closest('.drag-target[data-item-type="folder"]');

        if (!targetFolder) {
            return;
        }

        const related = event.relatedTarget;

        if (!related || !targetFolder.contains(related)) {
            unhighlightDropFolder(targetFolder);
        }
    });

    document.addEventListener('pointerdown', function (event) {
        if (event.button !== 0) {
            return;
        }

        const item = event.target.closest('[data-draggable-item]');
        const explorer = event.target.closest('[data-explorer-root]');

        if (!explorer) {
            return;
        }

        if (item) {
            dragStartInfo = {
                item,
                startX: event.clientX,
                startY: event.clientY,
                moved: false,
            };

            if (event.ctrlKey || event.metaKey) {
                event.preventDefault();
                toggleSelection(item);
            }

            return;
        }

        const box = document.createElement('div');
        box.className = 'fixed z-50 border-2 border-violet-500 bg-violet-500/10';
        box.style.left = `${event.clientX}px`;
        box.style.top = `${event.clientY}px`;
        box.style.width = '0px';
        box.style.height = '0px';
        box.style.pointerEvents = 'none';
        document.body.appendChild(box);

        rectangleSelection = {
            box,
            startX: event.clientX,
            startY: event.clientY,
        };

        selection.clear();
        syncSelectionState();
    });

    document.addEventListener('pointermove', function (event) {
        if (dragStartInfo && !dragStartInfo.moved) {
            const distance = Math.hypot(event.clientX - dragStartInfo.startX, event.clientY - dragStartInfo.startY);

            if (distance > 6) {
                dragStartInfo.moved = true;

                if (!(event.ctrlKey || event.metaKey)) {
                    selection.clear();
                    selection.add(getItemKey(dragStartInfo.item));
                    syncSelectionState();
                }
            }
        }

        if (!rectangleSelection) {
            return;
        }

        const { box, startX, startY } = rectangleSelection;
        const left = Math.min(startX, event.clientX);
        const top = Math.min(startY, event.clientY);
        const width = Math.abs(event.clientX - startX);
        const height = Math.abs(event.clientY - startY);

        box.style.left = `${left}px`;
        box.style.top = `${top}px`;
        box.style.width = `${width}px`;
        box.style.height = `${height}px`;

        const rect = {
            left,
            top,
            right: left + width,
            bottom: top + height,
        };

        selection.clear();

        document.querySelectorAll('[data-draggable-item]').forEach((item) => {
            const itemRect = item.getBoundingClientRect();
            const overlaps = !(itemRect.right < rect.left || itemRect.left > rect.right || itemRect.bottom < rect.top || itemRect.top > rect.bottom);

            if (overlaps) {
                selection.add(getItemKey(item));
            }
        });

        syncSelectionState();
    });

    document.addEventListener('pointerup', function (event) {
        if (dragStartInfo && !dragStartInfo.moved) {
            const item = event.target.closest('[data-draggable-item]');

            if (item) {
                if (event.ctrlKey || event.metaKey) {
                    toggleSelection(item);
                } else {
                    selection.clear();
                    selection.add(getItemKey(item));
                    syncSelectionState();
                }
            }
        }

        dragStartInfo = null;

        if (rectangleSelection) {
            rectangleSelection.box.remove();
            rectangleSelection = null;
        }
    });

    document.addEventListener('drop', handleExplorerDrop);

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
        const item = event.target.closest('[data-draggable-item]');

        if (!item) {
            const contextMenu = getContextMenu();

            if (!contextMenu) {
                return;
            }

            if (contextMenu.contains(event.target)) {
                return;
            }

            closeContextMenu();
            return;
        }

        if (event.ctrlKey || event.metaKey) {
            event.preventDefault();
            event.stopPropagation();
            toggleSelection(item);
            return;
        }

        if (event.defaultPrevented) {
            return;
        }

        selection.clear();
        selection.add(getItemKey(item));
        syncSelectionState();
    });

    document.addEventListener('dblclick', function (event) {
        const item = event.target.closest('[data-draggable-item]');

        if (!item) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        openItemByHref(item);
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

            openItemByHref(item);

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