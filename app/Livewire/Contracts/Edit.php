<?php

namespace App\Livewire\Contracts;

use App\Livewire\Forms\FormContract;
use App\Models\Contract;
use App\Models\Project;
use Flux\Flux;
use Livewire\Component;

class Edit extends Component
{
    public Contract $contract;
    public FormContract $form;

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

    public function save()
    {
        $this->form->update();
        $this->redirectRoute('contracts.index', navigate: true);
    }

    public function delete()
    {
        $this->contract->delete();

        $this->redirectRoute('contracts.index', navigate: true);

        Flux::toast(variant: 'success', text: 'Registro eliminado correctamente');
    }

    public function mount(Contract $contract)
    {
        $this->contract = $contract;
        $this->form->setContract($contract);
    }

    public function render()
    {
        $projects = Project::orderBy('name')->get();

        return view('livewire.contracts.create', compact('projects'));
    }
}
