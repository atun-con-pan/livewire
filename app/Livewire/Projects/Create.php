<?php

namespace App\Livewire\Projects;

use App\Livewire\Forms\FormProject;
use App\Models\Project;
use Livewire\Component;

class Create extends Component
{
    public FormProject $form;
    public Project $project;

    public function isCreated(): bool
    {
        return true;
    }

    public function isEdit(): bool
    {
        return false;
    }

    public function isShow(): bool
    {
        return false;
    }

    public function save()
    {
        $this->form->store();
        $this->redirectRoute('projects.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.projects.create');
    }
}