<?php

namespace App\Livewire\Collaborators;

use App\Models\Collaborator;
use App\Models\FilesCollaborator;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class File extends Component
{
    use WithFileUploads, WithPagination;

    public Collaborator $collaborator;
    public $file = [];

    public function mount(Collaborator $collaborator)
    {
        $this->collaborator = $collaborator;
    }

    public function store()
    {
        $this->validate([
            'file' => 'required|array',
            'file.*' => 'file|max:102400',
        ]);

        foreach ($this->file as $uploadedFile) {

            $file_name = $uploadedFile->getClientOriginalName();
            $directory = 'collaborators/' . $this->collaborator->id . '/';
            $file_path = "{$directory}/{$file_name}";

            // 🔥 Validar duplicado SOLO para este colaborador
            if (FilesCollaborator::where('file_path', $file_path)
                ->where('collaborator_id', $this->collaborator->id)
                ->exists()) {

                $this->addError('file', "El archivo '{$file_name}' ya existe.");
                continue;
            }

            // Guardar archivo
            $uploadedFile->storeAs($directory, $file_name, 'public');

            // Guardar en BD
            FilesCollaborator::create([
                'file_name' => $file_name,
                'file_path' => $file_path,
                'collaborator_id' => $this->collaborator->id, // 🔥 clave
            ]);
        }

        $this->reset('file');
        
        Flux::toast(variant: 'success', text: 'Registro creado correctamente');
    }

    public function delete(FilesCollaborator $file)
    {
        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        Flux::toast(variant: 'success', text: 'Registro eliminado correctamente');
    }

    public function render()
    {
        return view('livewire.collaborators.file', [
            'files' => FilesCollaborator::latest()->where('collaborator_id', $this->collaborator->id)
                ->Paginate(10),
        ]);
    }
}