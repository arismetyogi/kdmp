<?php

namespace App\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class UpdateRole extends Component
{
    public ?User $user = null;
    public $name, $email, $unitbisnis_code, $role_id;

    public function mount(?User $user = null): void
    {
        $this->user = $user;
    }

    public function rules(): array
    {
        return [
            'role_id' => ['required', 'numeric', 'exists:roles,id']
        ];
    }
    public function setUser(?User $user = null): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->unitbisnis_code = $user->unitbisnis_code;
        $this->role_id = $user->role_id;
    }

    #[On('editRole')]
    public function editRole($id): void
    {
        $this->user = User::find($id);
        $this->setUser($this->user);
        Flux::modal('edit-role')->show();
    }

    public function save(): void
    {
        $validated = $this->validate();
        $this->user->update($validated);

        $this->dispatch('users-updated');

        $this->dispatch('notify', title: 'success', message: 'Roles berhasil diupdate!');

        Flux::modal('edit-role')->close();
    }

    public function render(): View
    {
        return view('livewire.users.update-role' , ['user' => $this->user, 'roles' => Role::all()]);
    }

    public function resetForm(): void
    {
        $this->reset();
    }
}
