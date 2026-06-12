<?php

namespace App\Livewire\Affiliates;

use App\Models\Affiliate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $start_date = '';
    public $end_date = '';
    public $showConflicts = 'all'; // all, conflicted, not_conflicted

    // 🔄 Resetear paginación
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $affiliates = Affiliate::query()

            // 🔍 Búsqueda por texto
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('dpi', 'like', '%' . $this->search . '%')
                        ->orWhere('no_affiliate', 'like', '%' . $this->search . '%');
                });
            })

            // 📅 Solo fecha de inicio
            ->when($this->start_date && !$this->end_date, function ($query) {
                if ($this->showConflicts === 'conflicted') {
                    // Registros activos en esa fecha
                    $query->where(function ($q) {
                        $q->where('end_date', '>=', $this->start_date)->orWhereNull('end_date');
                    });
                } elseif ($this->showConflicts === 'not_conflicted') {
                    // Registros NO activos en esa fecha
                    $query->where(function ($q) {
                        $q->whereNotNull('end_date')->where('end_date', '<', $this->start_date);
                    });
                }
            })

            // 📅 Rango de fechas
            ->when($this->start_date && $this->end_date, function ($query) {
                if ($this->showConflicts === 'conflicted') {
                    // TRASLAPADOS
                    $query->where(function ($q) {
                        $q->where('start_date', '<=', $this->end_date)->where(function ($sub) {
                            $sub->where('end_date', '>=', $this->start_date)->orWhereNull('end_date');
                        });
                    });
                } elseif ($this->showConflicts === 'not_conflicted') {
                    // NO TRASLAPADOS
                    $query->where(function ($q) {
                        $q->where('start_date', '>', $this->end_date)
                        ->orWhere(function ($sub) {
                            $sub->whereNotNull('end_date')->where('end_date', '<', $this->start_date);
                        });
                    });
                }

                // Si es "all" no aplica filtro de fechas
            })

            ->latest()
            ->paginate(10);

        return view('livewire.affiliates.index', compact('affiliates'));
    }
}
