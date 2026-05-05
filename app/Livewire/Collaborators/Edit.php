<?php

namespace App\Livewire\Collaborators;

use App\Livewire\Forms\FormCollaborator;
use App\Models\Collaborator;
use Livewire\Component;

class Edit extends Component
{
    public FormCollaborator $form;
    public Collaborator $collaborator;

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

    public function mount(Collaborator $collaborator)
    {
        $this->collaborator = $collaborator;
        $this->form->setCollaborator($collaborator);
    }

    public function save()
    {
        $this->form->update();
        $this->redirectRoute('collaborators.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.collaborators.create');
    }
}
