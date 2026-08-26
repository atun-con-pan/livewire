<?php

namespace App\Livewire\Explorer;

use App\Models\File;
use App\Models\Folder;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public ?Folder $folder = null;
    public $name;
    public $uploadedFiles = [];
    public $uploadedFolderFiles = [];
    public $folderUploadPayload = [];
    public $renameType;
    public $renameId;
    public $renameName;
    public ?int $targetFolderId = null;
    public bool $folderUploading = false;

    public function mount(?Folder $folder = null)
    {
        $this->folder = $folder;
    }

    public function storeFolder()
    {
        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('folders', 'name')
                    ->where(function ($query) {
                        return $query->where('parent_id', $this->folder?->id);
                    }),
            ],
        ], [
            'name.unique' => 'Ya existe una carpeta con este nombre.',
        ]);

        Folder::create([
            'parent_id' => $this->folder?->id,
            'name' => $validated['name'],
        ]);

        Flux::toast(variant: 'success', text: 'Registro creado correctamente');

        if ($this->folder) {
            $this->redirectRoute('folder.show', ['folder' => $this->folder->id], navigate: true);
        } else {
            $this->redirectRoute('folder.index', navigate: true);
        }
    }

    public function storeFile()
    {
        $validated = $this->validate([
            'uploadedFiles' => ['required', 'array', 'min:1'],
            'uploadedFiles.*' => ['file', 'max:512000'],
        ]);

        $uploadedFiles = $validated['uploadedFiles'];
        $savedCount = 0;

        foreach ($uploadedFiles as $uploadedFile) {
            $name = $uploadedFile->getClientOriginalName();
            $normalizedName = trim($name);

            if ($normalizedName === '') {
                continue;
            }

            $exists = File::where('folder_id', $this->folder?->id)
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($normalizedName)])
                ->exists();

            if ($exists) {
                continue;
            }

            $extension = $uploadedFile->getClientOriginalExtension();
            $mimeType = $uploadedFile->getMimeType();
            $size = $uploadedFile->getSize();
            $physicalName = bin2hex(random_bytes(16)) . '.' . $extension;

            $uploadedFile->storeAs('files', $physicalName, 'public');

            File::create([
                'folder_id' => $this->folder?->id,
                'name' => $name,
                'physical_name' => $physicalName,
                'extension' => $extension,
                'mime_type' => $mimeType,
                'size' => $size,
            ]);

            $savedCount++;
        }

        $this->reset('uploadedFiles');

        Flux::toast(
            variant: 'success',
            text: $savedCount > 0
                ? 'Archivos subidos correctamente.'
                : 'No se subió ningún archivo porque ya existían en esta ubicación.'
        );

        if ($this->folder) {
            $this->redirectRoute('folder.show', ['folder' => $this->folder->id], navigate: true);
        } else {
            $this->redirectRoute('folder.index', navigate: true);
        }
    }

    public function storeFolderUpload()
    {
        $validated = $this->validate([
            'uploadedFolderFiles' => ['required', 'array', 'min:1'],
            'uploadedFolderFiles.*' => ['file', 'max:512000'],
            'folderUploadPayload' => ['array'],
        ]);

        $uploadedFiles = $validated['uploadedFolderFiles'];
        $payload = $validated['folderUploadPayload'];
        $savedCount = 0;

        foreach ($uploadedFiles as $index => $uploadedFile) {
            $relativePath = trim((string) ($payload[$index]['relativePath'] ?? $uploadedFile->getClientOriginalName()));
            $relativePath = str_replace('\\', '/', $relativePath);
            $relativePath = preg_replace('#/+#', '/', $relativePath);

            if ($relativePath === '') {
                continue;
            }

            $segments = array_values(array_filter(explode('/', $relativePath), fn ($segment) => trim((string) $segment) !== ''));

            if ($segments === []) {
                continue;
            }

            $fileName = array_pop($segments);
            $relativeFileName = trim((string) $fileName);

            if ($relativeFileName === '') {
                continue;
            }

            $targetFolderId = $this->folder?->id;

            foreach ($segments as $folderName) {
                $folderName = trim((string) $folderName);

                if ($folderName === '') {
                    continue;
                }

                $folder = Folder::where('parent_id', $targetFolderId)
                    ->whereRaw('LOWER(name) = ?', [mb_strtolower($folderName)])
                    ->first();

                if (! $folder) {
                    $folder = Folder::create([
                        'parent_id' => $targetFolderId,
                        'name' => $folderName,
                    ]);
                }

                $targetFolderId = $folder->id;
            }

            $exists = File::where('folder_id', $targetFolderId)
                ->whereRaw('LOWER(name) = ?', [mb_strtolower($relativeFileName)])
                ->exists();

            if ($exists) {
                continue;
            }

            $extension = $uploadedFile->getClientOriginalExtension();
            $mimeType = $uploadedFile->getMimeType();
            $size = $uploadedFile->getSize();
            $physicalName = bin2hex(random_bytes(16));

            if ($extension !== '') {
                $physicalName .= '.'.$extension;
            }

            $uploadedFile->storeAs('files', $physicalName, 'public');

            File::create([
                'folder_id' => $targetFolderId,
                'name' => $relativeFileName,
                'physical_name' => $physicalName,
                'extension' => $extension,
                'mime_type' => $mimeType,
                'size' => $size,
            ]);

            $savedCount++;
        }

        $this->reset(['uploadedFolderFiles', 'folderUploadPayload']);

        Flux::toast(
            variant: 'success',
            text: $savedCount > 0
                ? 'Carpeta y archivos subidos correctamente.'
                : 'No se subió ningún archivo porque ya existían en esta ubicación.'
        );

        if ($this->folder) {
            $this->redirectRoute('folder.show', ['folder' => $this->folder->id], navigate: true);
        } else {
            $this->redirectRoute('folder.index', navigate: true);
        }
    }

    public function updatedUploadedFolderFiles()
    {
        $this->folderUploading = false;
    }

    public function renameItem()
    {
        $validated = $this->validate([
            'renameType' => ['required', Rule::in(['folder', 'file'])],
            'renameId' => ['required', 'integer', 'min:1'],
            'renameName' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $name = trim((string) $value);

                    if ($name === '') {
                        $fail('El nombre no puede estar vacío.');
                        return;
                    }

                    if ($this->renameType === 'folder') {
                        $exists = Folder::where('parent_id', $this->folder?->id)
                            ->where('name', $name)
                            ->whereKeyNot($this->renameId)
                            ->exists();
                    } else {
                        $exists = File::where('folder_id', $this->folder?->id)
                            ->where('name', $name)
                            ->whereKeyNot($this->renameId)
                            ->exists();
                    }

                    if ($exists) {
                        $fail('Ya existe un elemento con este nombre en esta ubicación.');
                    }
                },
            ],
        ]);

        $name = trim((string) $validated['renameName']);

        if ($validated['renameType'] === 'folder') {
            $item = Folder::findOrFail($validated['renameId']);
            $item->update(['name' => $name]);
        } else {
            $item = File::findOrFail($validated['renameId']);
            $item->update(['name' => $name]);
        }

        Flux::toast(variant: 'success', text: 'Elemento renombrado correctamente');

        $this->reset(['renameType', 'renameId', 'renameName']);

        if ($this->folder) {
            $this->redirectRoute('folder.show', ['folder' => $this->folder->id], navigate: true);
        } else {
            $this->redirectRoute('folder.index', navigate: true);
        }
    }

    public function moveItem(string $type, int $itemId, int $targetFolderId): void
    {
        $this->moveItems([
            ['type' => $type, 'id' => $itemId],
        ], $targetFolderId);
    }

    public function moveItems(array $items, int $targetFolderId): void
    {
        $this->targetFolderId = $targetFolderId;

        $targetFolder = Folder::find($targetFolderId);

        if (! $targetFolder) {
            throw ValidationException::withMessages([
                'targetFolderId' => ['La carpeta destino no existe.'],
            ]);
        }

        $normalizedItems = [];
        foreach ($items as $item) {
            if (! is_array($item) || empty($item['type']) || empty($item['id'])) {
                continue;
            }

            $normalizedItems[] = [
                'type' => $item['type'],
                'id' => (int) $item['id'],
            ];
        }

        if ($normalizedItems === []) {
            throw ValidationException::withMessages([
                'items' => ['No hay elementos para mover.'],
            ]);
        }

        $seenNames = [];
        foreach ($normalizedItems as $itemData) {
            $type = $itemData['type'];
            $itemId = $itemData['id'];

            if ($type === 'folder') {
                $item = Folder::with('children')->find($itemId);

                if (! $item) {
                    throw ValidationException::withMessages([
                        'items' => ['Una de las carpetas seleccionadas no existe.'],
                    ]);
                }

                if ($item->id === $targetFolderId || $this->folderContains($item, $targetFolderId)) {
                    throw ValidationException::withMessages([
                        'targetFolderId' => ['No puedes mover una carpeta dentro de una de sus subcarpetas.'],
                    ]);
                }

                $key = 'folder:'.mb_strtolower($item->name);
                if (isset($seenNames[$key])) {
                    throw ValidationException::withMessages([
                        'items' => ['No puedes mover dos carpetas con el mismo nombre a la misma ubicación.'],
                    ]);
                }
                $seenNames[$key] = true;

                $exists = Folder::where('parent_id', $targetFolderId)
                    ->whereRaw('LOWER(name) = ?', [mb_strtolower($item->name)])
                    ->whereKeyNot($item->id)
                    ->exists();

                if ($exists) {
                    throw ValidationException::withMessages([
                        'items' => ['Ya existe una carpeta con este nombre en la ubicación destino.'],
                    ]);
                }

                continue;
            }

            if ($type === 'file') {
                $item = File::find($itemId);

                if (! $item) {
                    throw ValidationException::withMessages([
                        'items' => ['Uno de los archivos seleccionados no existe.'],
                    ]);
                }

                $key = 'file:'.mb_strtolower($item->name);
                if (isset($seenNames[$key])) {
                    throw ValidationException::withMessages([
                        'items' => ['No puedes mover dos archivos con el mismo nombre a la misma ubicación.'],
                    ]);
                }
                $seenNames[$key] = true;

                $exists = File::where('folder_id', $targetFolderId)
                    ->whereRaw('LOWER(name) = ?', [mb_strtolower($item->name)])
                    ->whereKeyNot($item->id)
                    ->exists();

                if ($exists) {
                    throw ValidationException::withMessages([
                        'items' => ['Ya existe un archivo con este nombre en la ubicación destino.'],
                    ]);
                }

                continue;
            }

            throw ValidationException::withMessages([
                'items' => ['El tipo del elemento no es válido.'],
            ]);
        }

        foreach ($normalizedItems as $itemData) {
            $type = $itemData['type'];
            $itemId = $itemData['id'];

            if ($type === 'file') {
                File::whereKey($itemId)->update(['folder_id' => $targetFolderId]);
                continue;
            }

            Folder::whereKey($itemId)->update(['parent_id' => $targetFolderId]);
        }

        $this->reset('targetFolderId');

        Flux::toast(variant: 'success', text: count($normalizedItems) > 1 ? 'Elementos movidos correctamente' : 'Elemento movido correctamente');

        if ($this->folder) {
            $this->redirectRoute('folder.show', ['folder' => $this->folder->id], navigate: true);
            return;
        }

        $this->redirectRoute('folder.index', navigate: true);
    }

    public function deleteItem(string $type, int $id): void
    {
        if ($type === 'file') {
            $file = File::findOrFail($id);
            $this->deletePhysicalFile($file);
            $file->delete();

            Flux::toast(variant: 'success', text: 'Archivo eliminado correctamente');

            return;
        }

        $folder = Folder::with('children')->findOrFail($id);
        $folderIds = $this->collectFolderIds($folder);

        $files = File::whereIn('folder_id', $folderIds)->get();

        foreach ($files as $file) {
            $this->deletePhysicalFile($file);
        }

        File::whereIn('folder_id', $folderIds)->delete();
        Folder::whereIn('id', $folderIds)->delete();

        Flux::toast(variant: 'success', text: 'Carpeta eliminada correctamente');

        if ($this->folder && $this->folder->id === $id) {
            $parent = $this->folder->parent;

            if ($parent) {
                $this->redirectRoute('folder.show', ['folder' => $parent->id], navigate: true);
                return;
            }

            $this->redirectRoute('folder.index', navigate: true);
        }
    }

    protected function folderContains(Folder $folder, int $targetFolderId): bool
    {
        if ($folder->id === $targetFolderId) {
            return true;
        }

        foreach ($folder->children as $child) {
            if ($this->folderContains($child, $targetFolderId)) {
                return true;
            }
        }

        return false;
    }

    protected function collectFolderIds(Folder $folder, array $ids = []): array
    {
        $ids[] = $folder->id;

        foreach ($folder->children as $child) {
            $ids = $this->collectFolderIds($child, $ids);
        }

        return $ids;
    }

    protected function deletePhysicalFile(File $file): void
    {
        if (! empty($file->physical_name)) {
            $path = 'files/' . $file->physical_name;

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    public function render()
    {
        $folder = $this->folder;

        $folders = $folder
            ? $folder->children()->orderBy('name')->get()
            : Folder::whereNull('parent_id')->orderBy('name')->get();

        $files = $folder
            ? $folder->files()->orderBy('name')->get()
            : File::whereNull('folder_id')->orderBy('name')->get();

        $filesCount = File::all()->count();

        return view('livewire.explorer.index', compact('folder', 'folders', 'files', 'filesCount'));
    }
}

/**
 * 
 * $folders = Folder::where('parent_id', $this->folder?->id)->get();
 *
 * $files = File::where('folder_id', $this->folder?->id)->get();
**/