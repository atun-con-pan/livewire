<?php

namespace App\Livewire\Projects;

use App\Livewire\Forms\FormProject;
use App\Models\Project;
use Livewire\Component;

class Show extends Component
{
    public Project $project;
    public FormProject $form;

    public function isCreated(): bool
    {
        return false;
    }

    public function isEdit(): bool
    {
        return false;
    }

    public function isShow(): bool
    {
        return true;
    }

    public function save()
    {
        abort(403);
    }

    public function mount(Project $project)
    {
        $this->form->setProject($project);
    }

    public function render()
    {
        return view('livewire.projects.create');
    }
}
