<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
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
        $contracts = Contract::with('project')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('no_contract', 'like', '%' . $this->search . '%')
                        ->orWhere('status', 'like', '%' . $this->search . '%')
                        ->orWhere('filial', 'like', '%' . $this->search . '%')
                        ->orWhere('person_charge', 'like', '%' . $this->search . '%')
                        ->orWhereHas('project', function ($project) {
                            $project->where('nog', 'like', '%' . $this->search . '%')->orWhere('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(8);

        return view('livewire.contracts.index', compact('contracts'));
    }
}
