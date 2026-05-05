<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\FormUser;
use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public FormUser $form;
    public User $user;

    public function isCreated(): bool
    {
        return false;
    }

    public function isEdit(): bool
    {
        return false;
    }

    public function isShow(): bool
    {
        return true;
    }

    public function save()
    {
        abort(403);
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->form->setUser($user);
    }

    public function render()
    {
        return view('livewire.users.create');
    }
}
