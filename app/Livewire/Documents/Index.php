<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // 🔥 Nuevo filtro por tipo
    public $typeFilter = '';

    // Resetear paginación al buscar
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Resetear paginación al cambiar filtro
    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $documents = Document::query()

            // 🔍 Buscar por texto
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('file_name', 'like', '%' . $this->search . '%')
                      ->orWhere('path', 'like', '%' . $this->search . '%')
                      ->orWhere('type', 'like', '%' . $this->search . '%');
                });
            })

            // 🔥 Filtrar por tipo aunque no haya búsqueda
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })

            ->latest()
            ->paginate(10);

        return view('livewire.documents.index', compact('documents'));
    }
}