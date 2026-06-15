<?php

namespace App\Livewire\Explorer;

use Livewire\Component;

class Index extends Component
{
    public array $folders = [];
    public array $files = [];

    public function createFolder()
    {
        echo "New Directory";
    }

    public function createFile()
    {
        echo "New File";
    }

    public function openFolder($id)
    {
        // cambiar directorio actual
    }

    public function render()
    {
        return view('livewire.explorer.index');
    }
}
