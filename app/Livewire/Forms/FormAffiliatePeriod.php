<?php

namespace App\Livewire\Forms;

use App\Models\AffiliatePeriod;
use Flux\Flux;
use Livewire\Form;

class FormAffiliatePeriod extends Form
{
    public ?AffiliatePeriod $period = null;

    public $affiliate_id = '';
    public $project_id = '';
    public $start_date = '';
    public $end_date = null;

    public function rules()
    {
        return [
            'affiliate_id' => 'required|exists:affiliates,id',
            'project_id' => 'required|exists:projects,id',
            'start_date' => 'required|date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    /**
     * Crear período
     */
    public function store(): bool
    {
        $data = $this->validate();

        $data['end_date'] = $data['end_date'] ?: null;

        if ($this->checkConflicts()) {

            Flux::toast(
                variant: 'danger',
                heading: 'Conflicto de fechas',
                text: 'Este afiliado ya tiene un período que se traslapa con estas fechas.'
            );

            return false;
        }

        AffiliatePeriod::create($data);

        Flux::toast(
            variant: 'success',
            heading: 'Período creado',
            text: 'Creado correctamente'
        );

        $this->reset();

        return true;
    }

    /**
     * Editar período
     */
    public function update(): bool
    {
        $data = $this->validate();

        $data['end_date'] = $data['end_date'] ?: null;

        if ($this->checkConflicts()) {

            Flux::toast(
                variant: 'danger',
                heading: 'Conflicto de fechas',
                text: 'Este afiliado ya tiene un período que se traslapa con estas fechas.'
            );

            return false;
        }

        $this->period->update($data);

        Flux::toast(
            variant: 'success',
            heading: 'Período actualizado',
            text: 'Actualizado correctamente'
        );

        return true;
    }

    /**
     * Cargar período
     */
    public function setPeriod(AffiliatePeriod $period): void
    {
        $this->period = $period;

        $this->affiliate_id = $period->affiliate_id;
        $this->project_id = $period->project_id;

        $this->start_date = $period->start_date
            ? $period->start_date->format('Y-m-d')
            : '';

        $this->end_date = $period->end_date
            ? $period->end_date->format('Y-m-d')
            : null;
    }

    /**
     * Validar traslapes
     */
    public function checkConflicts(): bool
    {
        if (!$this->affiliate_id || !$this->start_date) {
            return false;
        }

        $newStart = $this->start_date;
        $newEnd = $this->end_date ?: '9999-12-31';

        return AffiliatePeriod::query()

            ->where('affiliate_id', $this->affiliate_id)

            // Excluir el mismo registro cuando se edita
            ->when($this->period, function ($query) {
                $query->where('id', '!=', $this->period->id);
            })

            // Detectar traslape
            ->whereDate('start_date', '<=', $newEnd)

            ->where(function ($query) use ($newStart) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $newStart);
            })

            ->exists();
    }
}