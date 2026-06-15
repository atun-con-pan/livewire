<?php

namespace App\Livewire\Forms;

use App\Models\Affiliate;
use Flux\Flux;
use Livewire\Form;

class FormAffiliate extends Form
{
    public ?Affiliate $affiliate = null;

    public string $name = '';
    public string $dpi = '';
    public string $no_affiliate = '';
    public string $project = '';
    public string $nog = '';
    public string $start_date = '';
    public ?string $end_date = null;

    public array $conflictingAffiliates = [];

    /**
     * Reglas
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'dpi' => 'required|string',
            'no_affiliate' => 'required|string',
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

        if (
            empty($this->start_date) ||
            (
                empty($this->dpi) &&
                empty($this->no_affiliate)
            )
        ) {
            return;
        }

        // Si no tiene fecha final, se considera vigente hasta hoy
        $endDate = $this->end_date ?: now()->toDateString();

        $conflicts = Affiliate::query()

            // Excluir el registro actual cuando se edita
            ->when($this->affiliate, function ($query) {
                $query->where('id', '!=', $this->affiliate->id);
            })

            // Buscar por DPI o número de afiliado
            ->where(function ($query) {
                $query->where('dpi', $this->dpi)
                    ->orWhere('no_affiliate', $this->no_affiliate);
            })

            // Detectar traslape
            ->whereDate('start_date', '<=', $endDate)

            ->where(function ($query) {
                $query->whereNull('end_date')
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

        // Convertir cadena vacía a NULL
        $validated['end_date'] = !empty($validated['end_date'])
            ? $validated['end_date']
            : null;

        Affiliate::create($validated);

        Flux::toast(
            variant: 'success',
            heading: 'Registro creado',
            text: 'El registro se creó exitosamente.',
            duration: 3000
        );

        $this->resetForm();
    }

    /**
     * Actualizar
     */
    public function update()
    {
        if (!$this->affiliate) {

            Flux::toast(
                variant: 'danger',
                heading: 'Error',
                text: 'No se encontró el afiliado a actualizar.',
                duration: 3000
            );

            return;
        }

        // Revisar conflictos antes de actualizar
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

        // Convertir cadena vacía a NULL
        $validated['end_date'] = !empty($validated['end_date'])
            ? $validated['end_date']
            : null;

        $this->affiliate->update($validated);

        Flux::toast(
            variant: 'success',
            heading: 'Registro actualizado',
            text: 'El registro se actualizó exitosamente.',
            duration: 3000
        );

        $this->resetForm();
    }

    /**
     * Cargar afiliado para editar o ver
     */
    public function setAffiliate(Affiliate $affiliate)
    {
        $this->affiliate = $affiliate;

        $this->name = $affiliate->name;
        $this->dpi = $affiliate->dpi;
        $this->no_affiliate = $affiliate->no_affiliate;
        $this->project = $affiliate->project;
        $this->nog = $affiliate->nog;

        $this->start_date = $affiliate->start_date
            ? $affiliate->start_date->format('Y-m-d')
            : '';

        $this->end_date = $affiliate->end_date
            ? $affiliate->end_date->format('Y-m-d')
            : null;
    }

    /**
     * Reiniciar formulario
     */
    public function resetForm()
    {
        $this->reset([
            'name',
            'dpi',
            'no_affiliate',
            'project',
            'nog',
            'start_date',
            'end_date',
            'conflictingAffiliates',
        ]);

        $this->affiliate = null;

        $this->end_date = null;
    }
}