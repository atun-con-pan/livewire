<?php

namespace App\Livewire\Forms;

use App\Models\Affiliate;
use Flux\Flux;
use Livewire\Form;

class FormAffiliate extends Form
{
    public ?Affiliate $affiliate = null;

    public $name = '';
    public $dpi = '';
    public $no_affiliate = '';
    public $project = '';
    public $nog = '';
    public $start_date = '';
    public $end_date = '';

    public $conflictingAffiliates = [];

    /**
     * Reglas
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'dpi' => 'required|string|unique:affiliates,dpi',
            'no_affiliate' => 'required|string|unique:affiliates,no_affiliate',
            'project' => 'required|string|max:255',
            'nog' => 'required|string|max:255',
            'start_date' => 'required|date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    /**
     * Buscar afiliados con fechas traslapadas
     */
    public function checkConflicts()
    {
        $this->conflictingAffiliates = [];

        // Validar datos mínimos
        if (
            empty($this->start_date) ||
            (
                empty($this->dpi) &&
                empty($this->no_affiliate)
            )
        ) {
            return;
        }

        $endDate = $this->end_date ?: now()->toDateString();

        $conflicts = Affiliate::query()

            ->where(function ($query) {

                $query
                    ->where('dpi', $this->dpi)
                    ->orWhere('no_affiliate', $this->no_affiliate);

            })

            // Traslape
            ->whereDate('start_date', '<=', $endDate)

            ->where(function ($query) {

                $query
                    ->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $this->start_date);

            })

            ->get();

        $this->conflictingAffiliates = $conflicts->toArray();
    }

    /**
     * Guardar
     */
    public function store()
    {
        // Revisar conflictos antes de guardar
        $this->checkConflicts();

        if (!empty($this->conflictingAffiliates)) {

            Flux::toast(
                variant: 'danger',
                heading: 'Conflicto detectado',
                text: 'Ya existe un afiliado con fechas traslapadas.',
                duration: 4000
            );

            return;
        }

        $validated = $this->validate();

        Affiliate::create($validated);

        Flux::toast(
            variant: 'success',
            heading: 'Registro creado',
            text: 'El registro se creó exitosamente.',
            duration: 3000
        );

        $this->reset();
    }
}