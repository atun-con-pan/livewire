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
                ->where(function ($query) {
                    $query->where('start_date', '<=', $this->end_date)->where(function ($q) {
                        $q->where('end_date', '>=', $this->start_date)->orWhereNull('end_date');
                    });
                })
                ->select('no_affiliate')
                ->groupBy('no_affiliate')
                ->havingRaw('COUNT(*) > 0')
                ->pluck('no_affiliate');
        }

        $affiliates = Affiliate::query()

            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('dpi', 'like', "%{$this->search}%")
                        ->orWhere('no_affiliate', 'like', "%{$this->search}%");
                });
            })

            ->when($this->showConflicts === 'conflicted', function ($query) use ($conflictedAffiliates) {
                $query->whereIn('no_affiliate', $conflictedAffiliates)->where(function ($q) {
                    $q->where('start_date', '<=', $this->end_date)->where(function ($sub) {
                        $sub->where('end_date', '>=', $this->start_date)->orWhereNull('end_date');
                    });
                });
            })

            ->when($this->showConflicts === 'not_conflicted', function ($query) use ($conflictedAffiliates) {
                $query->whereNotIn('no_affiliate', $conflictedAffiliates);
            })

            ->latest()
            ->paginate(10);

        return view('livewire.affiliates.index', compact('affiliates'));
    }
}
