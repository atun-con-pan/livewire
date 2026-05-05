<?php

namespace App\Livewire\Contracts;

use App\Livewire\Forms\FormContract;
use App\Models\Contract;
use Livewire\Component;

class Show extends Component
{
    public Contract $contract;
    public FormContract $form;

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

    public function mount(Contract $contract)
    {
        $this->form->setContract($contract);
    }

    public function render()
    {
        return view('livewire.contracts.create');
    }
}
