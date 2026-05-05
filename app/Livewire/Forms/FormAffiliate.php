<?php

namespace App\Livewire\Forms;

use App\Models\Affiliate;
use Flux\Flux;
use Livewire\Form;

class FormAffiliate extends Form
{
    public ?Affiliate $affiliate;
    public $name, $dpi, $no_affiliate, $project, $nog, $start_date, $end_date;
    
    public function store()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'dpi' => 'required|string|unique:affiliates,dpi',
            'no_affiliate' => 'required|string|unique:affiliates,no_affiliate',
            'project' => 'required|string|max:255',
            'nog' => 'required|string|max:255',
            'start_date' => 'required|date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Affiliate::create($validated);

        Flux::toast(
            variant: 'success',
            heading: 'Registro Creado.',
            text: "El registro se ha creado exitosamente.",
            duration: 3000
        );
    }
}
