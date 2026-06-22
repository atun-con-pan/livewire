<?php

namespace App\Livewire\Projects;

use App\Models\Ofices as ModelsOfices;
use App\Models\Project;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Ofices extends Component
{
    use WithFileUploads, WithPagination;

    public Project $project;

    public $date = '';
    public $ofice = '';
    public $option = '';
    public $description = '';
    public $notes = '';
    public $file;
    public $file_path;

    protected function rules()
    {
        return [
            'date' => 'required|date',
            'ofice' => 'required|string|max:255',
            'option' => 'required|in:Entregado,Recibido',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'file' => 'required|file|max:10240', // 10MB
        ];
    }

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function save()
    {
        $validated = $this->validate();

        $path = $this->file->store('ofices', 'public');

        ModelsOfices::create([
            'project_id' => $this->project->id,
            'date' => $validated['date'],
            'ofice' => $validated['ofice'],
            'option' => $validated['option'],
            'description' => $validated['description'],
            'notes' => $validated['notes'],
            'file_name' => $this->file->getClientOriginalName(),
            'file_path' => $path,
        ]);

        Flux::toast(variant: 'success', text: 'Registro creado correctamente');

        $this->reset([
            'date',
            'ofice',
            'option',
            'description',
            'notes',
            'file',
        ]);

        $this->resetValidation();
        $this->resetPage();
    }

    public function delete(ModelsOfices $ofice)
    {
        if ($ofice->file_path && Storage::disk('public')->exists($ofice->file_path)) {
            Storage::disk('public')->delete($ofice->file_path);
        }

        $ofice->delete();

        Flux::toast(variant: 'success', text: 'Registro eliminado correctamente');

        // Si estás usando paginación y eliminaste el último registro de la página
        if ($this->getPage() > 1 && $this->ofices()->count() === 0) {
            $this->previousPage();
        }
    }

    public function render()
    {
        $ofices = ModelsOfices::query()
            ->where('project_id', $this->project->id)
            ->latest()
            ->paginate(10);

        return view('livewire.projects.ofices', compact('ofices'));
    }
}