<?php

namespace App\Livewire\Collaborators;

use App\Livewire\Forms\FormCollaborator;
use Livewire\Component;

class Create extends Component
{
    public FormCollaborator $form;

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
        $this->redirectRoute('collaborators.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.collaborators.create');
    }
}
