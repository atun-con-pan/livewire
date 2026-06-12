<?php

namespace App\Livewire\Affiliates;

use App\Livewire\Forms\FormAffiliate;
use App\Models\Affiliate;
use Flux\Flux;
use Livewire\Component;

class Edit extends Component
{
    public FormAffiliate $form;
    public Affiliate $affiliate;

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

    public function mount(Affiliate $affiliate)
    {
        $this->form->setAffiliate($affiliate);
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
        $this->form->update();

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        if (!empty($this->form->conflictingAffiliates)) {
            return;
        }

        $this->redirectRoute('affiliates.index', navigate: true);
    }

    public function delete(Affiliate $affiliate)
    {
        $affiliate->delete();

        $this->redirectRoute('affiliates.index', navigate: true);

        Flux::toast(
            variant: 'danger',
            heading: 'Registro Eliminado.',
            text: "El registro se ha eliminado exitosamente.",
            duration: 3000,
        );
    }
    
    public function render()
    {
        return view('livewire.affiliates.create');
    }
}
