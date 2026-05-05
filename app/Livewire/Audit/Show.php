<?php

namespace App\Livewire\Audit;

use Livewire\Component;
use OwenIt\Auditing\Models\Audit;

class Show extends Component
{
    public Audit $audit;

    public function mount(Audit $audit)
    {
        $this->audit = $audit->load('user');
    }

    public function render()
    {
        return view('livewire.audit.show');
    }
}