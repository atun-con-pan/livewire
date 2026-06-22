<?php

namespace App\Livewire\Forms;

use App\Models\Contract;
use Flux\Flux;
use Livewire\Form;

class FormContract extends Form
{
    public $project_id;
    public $contract_registration_date;
    public $no_contract;
    public $number_workers;
    public $salary_amount;
    public $filial;
    public $status;
    public $person_charge;

    public ?Contract $contract = null;

    public function store()
    {
        $validated = $this->validate([
            'project_id' => 'required|exists:projects,id|unique:contracts,project_id',
            'contract_registration_date' => 'required|date|before_or_equal:today',
            'no_contract' => 'required|string|max:100',
            'number_workers' => 'required|integer|min:1',
            'salary_amount' => 'required|string',
            'filial' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'person_charge' => 'required|string|max:255',
        ]);

        Contract::create($validated);

        Flux::toast(variant: 'success', text: 'Registro creado correctamente');
    }

    public function setContract(Contract $contract)
    {
        $this->contract = $contract;
        $this->fill($contract->toArray());
    }

    public function update()
    {
        $validated = $this->validate([
            'project_id' => 'required|exists:projects,id|unique:contracts,project_id,' . $this->contract->id,
            'contract_registration_date' => 'required|date|before_or_equal:today',
            'no_contract' => 'required|string|max:100',
            'number_workers' => 'required|integer|min:1',
            'salary_amount' => 'required|string',
            'filial' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'person_charge' => 'required|string|max:255',
        ]);

        $this->contract->update($validated);

        Flux::toast(variant: 'success', text: 'Registro editado correctamente');
    }
}