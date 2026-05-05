<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\FormUser;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public FormUser $form;
    public User $user;

    public function isCreated(): bool
    {
        return false;
    }

    public function isEdit(): bool
    {
        return true;
    }

    public function isShow(): bool
    {
        return false;
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->form->setUser($user);
    }

    public function save()
    {
        $this->form->update();
        $this->redirectRoute('users.index', navigate: true);
    }

    public function delete()
    {
        $this->user->delete();

        $this->redirectRoute('users.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.users.create');
    }
}
