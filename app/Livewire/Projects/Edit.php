<?php

namespace App\Livewire\Projects;

use App\Livewire\Forms\FormProject;
use App\Models\Project;
use Livewire\Component;

class Edit extends Component
{
    public Project $project;
    public FormProject $form;

    public function isCreated(): bool
    {
        return false;
    }

    public function isEdit(): bool
    {
        return true;
    }

    public function isShow(): bool
    {
        return false;
    }

    public function mount(Project $project)
    {
        $this->form->setProject($project);
    }

    public function save()
    {
        $this->form->update();
        $this->redirectRoute('projects.index', navigate: true);
    }

    public function delete()
    {
        $this->project->delete();

        session()->flash('success', 'Proyecto eliminado correctamente.');

        $this->redirectRoute('projects.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.projects.create');
    }
}
