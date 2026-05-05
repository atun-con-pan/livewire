<?php

namespace App\Livewire\Forms;

use App\Models\Collaborator;
use Flux\Flux;
use Livewire\Form;

class FormCollaborator extends Form
{
    public ?Collaborator $collaborator;
    public $first_name, $middle_name, $first_surname, $second_last_name, $dpi, $birthdate, $marital_status, $residence, $phone, $email, $position, $start_date, $termination_date, $salary, $contract, $pattern, $bank_account, $bank, $bank_account_name, $no_igss;
    
    public function store()
    {
        $validated = $this->validate([
            'first_name' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
            'middle_name' => 'nullable|string|max:255|regex:/^[\pL\s]+$/u',
            'first_surname' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
            'second_last_name' => 'nullable|string|max:255|regex:/^[\pL\s]+$/u',
            'dpi' => 'required|string|unique:collaborators,dpi',
            'birthdate' => 'required|date|before:today',
            'marital_status' => 'required|in:Soltero,Casado,Divorciado,Viudo',
            'residence' => 'required|string|max:500',
            'phone' => 'required|string|max:10',
            'email' => 'required|email|max:255|unique:collaborators,email',
            'position' => 'required|string|max:255',
            'start_date' => 'required|date|before_or_equal:today',
            'termination_date' => 'nullable|date|after_or_equal:start_date',
            'salary' => 'required|string',
            'contract' => 'required|string|max:255',
            'pattern' => 'required|string|max:255',
            'bank_account' => 'nullable|digits_between:8,20|unique:collaborators,bank_account',
            'bank' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'no_igss' => 'nullable|string|unique:collaborators,no_igss',
        ]);

        Collaborator::create($validated);

        Flux::toast(
            variant: 'success',
            heading: 'Registro Creado.',
            text: "El registro se ha creado exitosamente.",
            duration: 3000
        );
    }

    public function setCollaborator(Collaborator $collaborator)
    {
        $this->collaborator = $collaborator;
        // Esto llena los inputs automáticamente sin hacerlo uno por uno
        $this->fill($collaborator->toArray());
    }

    public function update()
    {
        $validated = $this->validate([
            'first_name' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
            'middle_name' => 'nullable|string|max:255|regex:/^[\pL\s]+$/u',
            'first_surname' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
            'second_last_name' => 'nullable|string|max:255|regex:/^[\pL\s]+$/u',
            'dpi' => 'required|string|unique:collaborators,dpi,' . $this->collaborator->id,
            'birthdate' => 'required|date|before:today',
            'marital_status' => 'required|in:Soltero,Casado,Divorciado,Viudo',
            'residence' => 'required|string|max:500',
            'phone' => 'required|string',
            'email' => 'required|email|max:255|unique:collaborators,email,' . $this->collaborator->id,
            'position' => 'required|string|max:255',
            'start_date' => 'required|date|before_or_equal:today',
            'termination_date' => 'nullable|date|after_or_equal:start_date',
            'salary' => 'required|string',
            'contract' => 'required|string|max:255',
            'pattern' => 'required|string|max:255',
            'bank_account' => 'nullable|digits_between:8,20|unique:collaborators,bank_account,' . $this->collaborator->id,
            'bank' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'no_igss' => 'nullable|string|unique:collaborators,no_igss,' . $this->collaborator->id,
        ]);

        $this->collaborator->fill($validated)->save();

        Flux::toast(
            variant: 'warning',
            heading: 'Registro Editado.',
            text: "El registro se ha editado exitosamente.",
            duration: 3000,
        );
    }
}
