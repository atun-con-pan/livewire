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
     * Detectar cambios en tiempo real
     */
    public function updatedFormDpi()
    {
        $this->form->checkConflicts();
    }

    public function updatedFormNoAffiliate()
    {
        $this->form->checkConflicts();
    }

    public function updatedFormStartDate()
    {
        $this->form->checkConflicts();
    }

    public function updatedFormEndDate()
    {
        $this->form->checkConflicts();
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

        if (!empty($this->form->conflictingAffiliates)) {
            return;
        }

        $this->redirectRoute('affiliates.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.affiliates.create');
    }
}