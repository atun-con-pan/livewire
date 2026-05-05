<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Hash;
use Livewire\Form;

class FormUser extends Form
{
    public $name, $email, $password, $role;
    public ?User $user;

    public function store()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,user,root',
        ]);

        User::create($validated);

        Flux::toast(
            variant: 'success',
            heading: 'Registro Creado.',
            text: "El registro se ha creado exitosamente.",
            duration: 3000
        );
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        $this->fill($user->toArray());
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin,user,root',
        ]);

        // 👉 Si no hay password, lo quitamos
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            // 👉 Si sí hay, lo encriptamos
            $validated['password'] = Hash::make($validated['password']);
        }

        $this->user->fill($validated)->save();

        Flux::toast(
            variant: 'warning',
            heading: 'Registro Editado.',
            text: "El registro se ha editado exitosamente.",
            duration: 3000
        );
    }
}
