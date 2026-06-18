<?php

namespace App\Livewire\Affiliates;

use App\Livewire\Forms\FormAffiliate;
use Livewire\Component;

class Create extends Component
{
    public FormAffiliate $form;

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

    /**
     * Guardar
     */
    public function save()
    {
        $this->form->store();

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        $this->redirectRoute('affiliates.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.affiliates.create');
    }
}
