<?php

namespace App\Livewire\Explorer;

use App\Models\File;
use App\Models\Folder;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public ?Folder $folder = null;
    public $name;
    public $uploadedFiles = [];
    public $renameType;
    public $renameId;
    public $renameName;

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
        $folders = $folder ? $folder->children : Folder::whereNull('parent_id')->get();
        $files = $folder ? $folder->files : File::whereNull('folder_id')->get();
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