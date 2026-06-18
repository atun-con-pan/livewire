<?php

namespace App\Livewire\AffiliatePeriods;

use App\Models\AffiliatePeriod;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.affiliate-periods.index', [
            'periods' => AffiliatePeriod::query()
                ->with(['affiliate', 'project'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function delete($id)
    {
        AffiliatePeriod::findOrFail($id)->delete();
    }
}