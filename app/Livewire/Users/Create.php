<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\FormUser;
use Livewire\Component;

class Create extends Component
{
    public FormUser $form;

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
        $this->redirectRoute('users.index', navigate: true);
    } 

    public function render()
    {
        return view('livewire.users.create');
    }
}
