<?php

namespace App\Livewire\AffiliatePeriods;

use App\Livewire\Forms\FormAffiliatePeriod;
use App\Models\Affiliate;
use App\Models\Project;
use Livewire\Component;

class Create extends Component
{
    public FormAffiliatePeriod $form;

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
        $ok = $this->form->store();

        if (!$ok) {
            return; // ❌ NO redirigir si falló
        }

        return redirect()->route('periods.index');
    }

    public function render()
    {
        return view('livewire.affiliate-periods.create', [
            'affiliates' => Affiliate::orderBy('name')->get(),
            'projects' => Project::orderBy('name')->get(),
        ]);
    }
}
