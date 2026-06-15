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
        $conflictedAffiliates = collect();

        if ($this->start_date && $this->end_date) {
            $conflictedAffiliates = Affiliate::query()
                ->whereDate('start_date', '<=', $this->end_date)
                ->where(function ($query) {
                    $query->whereNull('end_date')->orWhereDate('end_date', '>=', $this->start_date);
                })
                ->pluck('no_affiliate')
                ->unique();
        }

        $affiliates = Affiliate::query()

            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('dpi', 'like', "%{$this->search}%")
                        ->orWhere('no_affiliate', 'like', "%{$this->search}%");
                });
            })

            ->when($this->showConflicts === 'conflicted' && $this->start_date && $this->end_date, function ($query) use ($conflictedAffiliates) {
                $query->whereIn('no_affiliate', $conflictedAffiliates);
            })

            ->when($this->showConflicts === 'not_conflicted' && $this->start_date && $this->end_date, function ($query) use ($conflictedAffiliates) {
                $query->whereNotIn('no_affiliate', $conflictedAffiliates);
            })

            ->latest()
            ->paginate(10);

        return view('livewire.affiliates.index', compact('affiliates'));
    }
}
