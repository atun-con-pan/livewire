<?php

namespace App\Livewire\Contracts;

use App\Livewire\Forms\FormContract;
use App\Models\Contract;
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

        session()->flash('message', 'Contrato eliminado exitosamente.');

        $this->redirectRoute('contracts.index', navigate: true);
    }

    public function mount(Contract $contract)
    {
        $this->form->setContract($contract);
    }

    public function render()
    {
        return view('livewire.contracts.create');
    }
}
