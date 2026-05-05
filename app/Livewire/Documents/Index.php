<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // 🔥 IMPORTANTE: resetear paginación al buscar
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $documents = Document::query()
            ->when($this->search, function ($query) {
                $query->where('file_name', 'like', '%' . $this->search . '%')
                      ->orWhere('path', 'like', '%' . $this->search . '%')
                      ->orWhere('type', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.documents.index', compact('documents'));
    }
}
