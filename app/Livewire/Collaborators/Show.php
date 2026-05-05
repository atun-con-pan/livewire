<?php

namespace App\Livewire\Collaborators;

use App\Livewire\Forms\FormCollaborator;
use App\Models\Collaborator;
use Livewire\Component;

class Show extends Component
{
    public FormCollaborator $form;
    public Collaborator $collaborator;

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

    public function mount(Collaborator $collaborator)
    {
        $this->form->setCollaborator($collaborator);
    }

    public function save()
    {
        abort(403);
    }

    public function render()
    {
        return view('livewire.collaborators.create');
    }
}
