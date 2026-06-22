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
     * Guardar
     */
    public function save()
    {
        $this->form->update();

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        $this->redirectRoute('affiliates.index', navigate: true);
    }

    public function delete(Affiliate $affiliate)
    {
        $affiliate->delete();

        $this->redirectRoute('affiliates.index', navigate: true);

        Flux::toast(variant: 'success', text: 'Registro eliminado correctamente');
    }

    public function render()
    {
        return view('livewire.affiliates.create');
    }
}
