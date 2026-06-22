<?php

namespace App\Livewire\Contracts;

use App\Livewire\Forms\FormContract;
use App\Models\Project;
use Livewire\Component;

class Create extends Component
{
    public FormContract $form;

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

        $this->redirectRoute('contracts.index', navigate: true);
    }

    public function render()
    {
        $projects = Project::doesntHave('contract')
            ->orderBy('name')
            ->get();

        return view('livewire.contracts.create', compact('projects'));
    }
}