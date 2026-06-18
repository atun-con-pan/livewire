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

            ->when($this->search, function ($q) {
                $q->where(function ($s) {
                    $s->where('name', 'like', "%{$this->search}%")
                        ->orWhere('dpi', 'like', "%{$this->search}%")
                        ->orWhere('no_affiliate', 'like', "%{$this->search}%");
                });
            })

            // 🔥 FILTRO DE ESTADO
            ->when($this->showConflicts !== 'all', function ($q) {
                $start = $this->start_date ?? now()->toDateString();
                $end = $this->end_date ?: $start;

                $q->where(function ($sub) use ($start, $end) {
                    if ($this->showConflicts === 'conflicted') {
                        $sub->whereHas('periods', function ($p) use ($start, $end) {
                            $p->whereDate('start_date', '<=', $end)->where(function ($x) use ($start) {
                                $x->whereNull('end_date')->orWhereDate('end_date', '>=', $start);
                            });
                        });
                    } elseif ($this->showConflicts === 'not_conflicted') {
                        $sub->whereDoesntHave('periods', function ($p) use ($start, $end) {
                            $p->whereDate('start_date', '<=', $end)->where(function ($x) use ($start) {
                                $x->whereNull('end_date')->orWhereDate('end_date', '>=', $start);
                            });
                        });
                    }
                });
            })

            ->paginate(10);

        return view('livewire.affiliates.index', compact('affiliates'));
    }
}
