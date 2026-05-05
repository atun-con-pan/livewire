<?php

namespace App\Livewire\Forms;

use App\Models\Contract;
use Flux\Flux;
use Livewire\Form;

class FormContract extends Form
{
    public $no_contract, $contract_registration_date, $contract_subscription_date, $start_date_activities, $final_date_activities, $nog_contract, $contract_name, $execution_address, $number_workers, $salary_amount, $status, $filial, $person_charge;
    public ?Contract $contract;

    public function store()
    {
        $validated = $this->validate([
            'no_contract' => 'required|string|max:100|regex:/^[A-Za-z0-9\-\/]+$/',
            'contract_registration_date' => 'required|date|before_or_equal:today',
            'contract_subscription_date' => 'required|date|before_or_equal:contract_registration_date',
            'start_date_activities' => 'required|date|after_or_equal:contract_subscription_date',
            'final_date_activities' => 'nullable|date|after_or_equal:start_date_activities',
            'nog_contract' => 'required|digits_between:5,20|unique:contracts,nog_contract',
            'contract_name' => 'required|string|max:255',
            'execution_address' => 'required|string|max:500',
            'number_workers' => 'required|integer|min:1|max:10000',
            'salary_amount' => 'required|string',
            'status' => 'required|in:En curso,Finalizado,Reparado,Suspendido',
            'filial' => 'required|string|max:255|unique:contracts,filial',
            'person_charge' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
        ]);

        Contract::create($validated);

        Flux::toast(
            variant: 'success',
            heading: 'Registro Creado.',
            text: "El registro se ha creado exitosamente.",
            duration: 3000
        );        
    }

    public function setContract(Contract $contract)
    {
        $this->contract = $contract;
        $this->fill($contract->toArray());
    }

    public function update()
    {
        $validated = $this->validate([
            'no_contract' => 'required|string|max:100|regex:/^[A-Za-z0-9\-\/]+$/',
            'contract_registration_date' => 'required|date|before_or_equal:today',
            'contract_subscription_date' => 'required|date|before_or_equal:contract_registration_date',
            'start_date_activities' => 'required|date|after_or_equal:contract_subscription_date',
            'final_date_activities' => 'nullable|date|after_or_equal:start_date_activities',
            'nog_contract' => 'required|digits_between:5,20|unique:contracts,nog_contract,' . $this->contract->id,
            'contract_name' => 'required|string|max:255',
            'execution_address' => 'required|string|max:500',
            'number_workers' => 'required|integer|min:1|max:10000',
            'salary_amount' => 'required|string',
            'status' => 'required|in:En curso,Finalizado,Reparado,Suspendido',
            'filial' => 'required|string|max:255|unique:contracts,filial,' . $this->contract->id,
            'person_charge' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
        ]);

        $this->contract->fill($validated)->save();

        Flux::toast(
            variant: 'warning',
            heading: 'Registro Editado.',
            text: "El registro se ha editado exitosamente.",
            duration: 3000
        );
    }
}
