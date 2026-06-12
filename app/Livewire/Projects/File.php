<?php

namespace App\Livewire\Projects;

use App\Models\FilesProject;
use App\Models\Project;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class File extends Component
{
    use WithFileUploads, WithPagination;

    public Project $project;
    public $file = [];
    public $description = '';

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function store()
    {
        $this->validate([
            'description' => 'string|max:255',
            'file' => 'required|array',
            'file.*' => 'file|max:102400',
        ]);

        foreach ($this->file as $uploadedFile) {

            $file_name = $uploadedFile->getClientOriginalName();
            $directory = 'projects/' . $this->project->id . '/';
            $file_path = "{$directory}/{$file_name}";

            // 🔥 Validar duplicado SOLO para este proyecto
            if (FilesProject::where('file_path', $file_path)
                ->where('project_id', $this->project->id)
                ->exists()) {

                $this->addError('file', "El archivo '{$file_name}' ya existe.");
                continue;
            }

            // Guardar archivo
            $file_path = $uploadedFile->storeAs($directory, $file_name, 'public');

            // Guardar en BD
            FilesProject::create([
                'description' => $this->description,
                'file_name' => $file_name,
                'file_path' => $file_path,
                'project_id' => $this->project->id, // 🔥 clave
            ]);
        }

        $this->reset('file');
        
        Flux::toast(
            variant: 'success',
            heading: 'Registro Creado.',
            text: "El registro se ha creado exitosamente.",
            duration: 3000
        );
    }

    public function delete(FilesProject $file)
    {
        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        Flux::toast(
            variant: 'danger',
            heading: 'Registro Eliminado.',
            text: "El registro se ha eliminado exitosamente.",
            duration: 3000,
        );
    }
    
    public function render()
    {
        return view('livewire.projects.file', [
            'files' => FilesProject::latest()->where('project_id', $this->project->id)
                ->Paginate(10),
        ]);
    }
}
