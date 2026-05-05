<?php

namespace App\Livewire\Documents;

use App\Livewire\Forms\FormDocument;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;
    public FormDocument $form;
    
    public function isCreated(): bool
    {
        return true;
    }

    public function isEdit(): bool
    {
        return false;
    }

    public function isShow(): bool
    {
        return false;
    }

    public function save()
    {
        $this->form->store();
        
        // Verificar si hay errores de validación después del store()
        if ($this->getErrorBag()->isNotEmpty()) {
            // No redirigir, mantener en el formulario para mostrar errores
            return;
        }
        
        // Solo redirigir si NO hay errores
        $this->redirectRoute('documents.index', navigate: true);
    }    

    public function render()
    {
        return view('livewire.documents.create');
    }
}