<?php

namespace App\Livewire\Projects;

use App\Models\Ofices as ModelsOfices;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Ofices extends Component
{
    use WithFileUploads, WithPagination;

    public ModelsOfices $ofices;
    public $file = '';
    public $description = '';
    

    public function render()
    {
        return view('livewire.projects.ofices');
    }
}
