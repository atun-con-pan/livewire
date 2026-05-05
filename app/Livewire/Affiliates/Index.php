<?php

namespace App\Livewire\Affiliates;

use App\Models\Affiliate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $start_date;
    public $end_date;

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
        $documents = Affiliate::query()

            // 🔍 Búsqueda por texto
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('dpi', 'like', '%' . $this->search . '%')
                      ->orWhere('no_affiliate', 'like', '%' . $this->search . '%');
                });
            })

            // 📅 SOLO FECHA INICIO
            ->when($this->start_date && !$this->end_date, function ($query) {
                $query->where(function ($q) {
                    $q->where('end_date', '>=', $this->start_date)
                      ->orWhereNull('end_date');
                });
            })

            // 📅 INTERSECCIÓN DE RANGO (inicio + fin)
            ->when($this->start_date && $this->end_date, function ($query) {
                $query->where(function ($q) {
                    $q->where('start_date', '<=', $this->end_date)
                      ->where(function ($sub) {
                          $sub->where('end_date', '>=', $this->start_date)
                              ->orWhereNull('end_date');
                      });
                });
            })

            ->latest()
            ->paginate(10);

        return view('livewire.affiliates.index', compact('documents'));
    }
}