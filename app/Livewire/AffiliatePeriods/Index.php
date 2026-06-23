<?php

namespace App\Livewire\AffiliatePeriods;

use App\Models\AffiliatePeriod;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.affiliate-periods.index', [
            'periods' => AffiliatePeriod::query()
                ->with(['affiliate', 'project'])
                ->when($this->search, function ($query) {
                    $query->whereHas('affiliate', function ($q) {
                        $q->where('name', 'like', "%{$this->search}%")
                          ->orWhere('no_affiliate', 'like', "%{$this->search}%");
                    });
                })
                ->latest()
                ->paginate(10),
        ]);
    }
}