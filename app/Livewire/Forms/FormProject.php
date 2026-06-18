<?php

namespace App\Livewire\Forms;

use App\Models\Project;
use Flux\Flux;
use Livewire\Form;

class FormProject extends Form
{
    public $nog, $event, $name, $url, $client, $presentation_date, $start_date, $end_date, $price, $status;
    public ?Project $project;

    public function store()
    {
        $validated = $this->validate([
            'nog' => 'required|digits_between:5,20|unique:projects,nog',
            'event' => 'required|in:Licitacion,Cotizacion,Compra Directa,Otros',
            'name' => 'required|string|max:255',
            'url' => 'nullable|url|max:500',
            'client' => 'required|string|max:255',
            'presentation_date' => 'required|date|before_or_equal:today',
            'start_date' => 'nullable|date|after_or_equal:presentation_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'price' => 'required|string',
            'status' => 'required|in:En curso,Finalizado,Suspendido,Rechazado,No presentado',
        ]);

        Project::create($validated);

        Flux::toast(variant: 'success', heading: 'Registro Creado.', text: 'El registro se ha creado exitosamente.', duration: 3000);
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
        $this->nog = $project->nog;
        $this->event = $project->event;
        $this->name = $project->name;
        $this->url = $project->url;
        $this->client = $project->client;
        $this->presentation_date = $project->presentation_date ? $project->presentation_date->format('Y-m-d') : null;
        $this->start_date = $project->start_date ? $project->start_date->format('Y-m-d') : null;
        $this->end_date = $project->end_date ? $project->end_date->format('Y-m-d') : null;
        $this->price = $project->price;
        $this->status = $project->status;
    }

    public function update()
    {
        $validated = $this->validate([
            'nog' => 'required|digits_between:5,20|unique:projects,nog,' . $this->project->id,
            'event' => 'required|in:Licitacion,Cotizacion,Compra Directa,Otros',
            'name' => 'required|string|max:255',
            'url' => 'nullable|url|max:500',
            'client' => 'required|string|max:255',
            'presentation_date' => 'required|date|before_or_equal:today',
            'start_date' => 'nullable|date|after_or_equal:presentation_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'price' => 'required|string',
            'status' => 'required|in:En curso,Finalizado,Suspendido,Rechazado,No presentado',
        ]);

        $this->project->fill($validated)->save();

        Flux::toast(variant: 'warning', heading: 'Registro Editado.', text: 'El registro se ha editado exitosamente.', duration: 3000);
    }
}
