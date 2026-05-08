<?php

namespace App\Livewire\Affiliates;

use App\Livewire\Forms\FormAffiliate;
use App\Models\Affiliate;
use Flux\Flux;
use Livewire\Component;

class Show extends Component
{
    public FormAffiliate $form;
    public Affiliate $affiliate;

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

    public function mount(Affiliate $affiliate)
    {
        $this->form->setAffiliate($affiliate);
    }

    public function save()
    {
        abort(403);
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
