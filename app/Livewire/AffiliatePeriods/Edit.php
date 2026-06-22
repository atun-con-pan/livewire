<?php

namespace App\Livewire\AffiliatePeriods;

use App\Livewire\Forms\FormAffiliatePeriod;
use App\Models\Affiliate;
use App\Models\AffiliatePeriod;
use App\Models\Project;
use Flux\Flux;
use Livewire\Component;

class Edit extends Component
{
    public FormAffiliatePeriod $form;
    public AffiliatePeriod $period;

    public function mount(AffiliatePeriod $period)
    {
        $this->form->setPeriod($period);
    }

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
        $ok = $this->form->update();

        if (!$ok) {
            return; // ❌ No redirigir si hubo error o conflicto
        }

        return redirect()->route('periods.index');
    }

    public function delete()
    {
        $this->period->delete();
        $this->redirectRoute('periods.index', navigate: true);
        Flux::toast(variant: 'success', text: 'Registro eliminado correctamente');
    }

    public function render()
    {
        return view('livewire.affiliate-periods.create', [
            'affiliates' => Affiliate::orderBy('name')->get(),
            'projects' => Project::orderBy('name')->get(),
        ]);
    }
}
