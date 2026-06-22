<?php

namespace App\Livewire\Forms;

use App\Models\Affiliate;
use Flux\Flux;
use Livewire\Form;
use Illuminate\Validation\Rule;

class FormAffiliate extends Form
{
    public ?Affiliate $affiliate = null;

    public string $name = '';
    public string $no_affiliate = '';

    /**
     * Reglas
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'no_affiliate' => ['required', 'string', Rule::unique('affiliates', 'no_affiliate')->ignore($this->affiliate?->id)],
        ];
    }

    public function messages()
    {
        return [
            'no_affiliate.unique' => 'Ya existe un afiliado con este número de afiliación.',
        ];
    }

    /**
     * Guardar
     */
    public function store()
    {
        $validated = $this->validate();

        Affiliate::create($validated);

        Flux::toast(variant: 'success', text: 'Registro creado correctamente');

        $this->resetForm();
    }

    /**
     * Actualizar
     */
    public function update()
    {
        if (!$this->affiliate) {
            Flux::toast(variant: 'danger', heading: 'Error', text: 'No se encontró el afiliado a actualizar.', duration: 3000);

            return;
        }

        $validated = $this->validate();

        $this->affiliate->update($validated);

        Flux::toast(variant: 'success', text: 'Registro editado correctamente');

        $this->resetForm();
    }

    /**
     * Cargar afiliado para editar o ver
     */
    public function setAffiliate(Affiliate $affiliate)
    {
        $this->affiliate = $affiliate;

        $this->name = $affiliate->name;
        $this->no_affiliate = $affiliate->no_affiliate;
    }

    /**
     * Reiniciar formulario
     */
    public function resetForm()
    {
        $this->reset(['name', 'no_affiliate']);

        $this->affiliate = null;
    }
}