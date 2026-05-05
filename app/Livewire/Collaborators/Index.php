<?php

namespace App\Livewire\Collaborators;

use App\Models\Collaborator;
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
        $collaborators = Collaborator::query()
            ->when($this->search, function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('middle_name', 'like', '%' . $this->search . '%')
                      ->orWhere('first_surname', 'like', '%' . $this->search . '%')
                      ->orWhere('second_last_name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.collaborators.index', compact('collaborators'));
    }
}
