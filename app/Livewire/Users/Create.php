<?php

namespace App\Livewire\Users;

use App\Helpers\WithToast;
use App\Models\BranchOffice;
use App\Models\User;
use Flux\Flux;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Create extends Component
{
    use WithToast;

    public ?User $user = null;

    public $editMode;

    public $name;

    public $username;

    public $email;

    public $unitbisnis_code;

    public $password;

    public $password_confirmation;

    public $userId = null;

    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3'],
            'username' => ['required', 'min:3', 'lowercase',
                Rule::unique('users')->ignore($this->user),
            ],
            'email' => ['required', 'min:6', 'email',
                Rule::unique('users')->ignore($this->user),
            ],
            'unitbisnis_code' => ['nullable', 'numeric', 'exists:branch_offices,unitbisnis_code'],
            'password' => [
                'nullable',
                'confirmed:password_confirmation',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }

    public function setUser(?User $user = null): void
    {
        $this->user = $user;

        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->unitbisnis_code = $user->unitbisnis_code;
    }

    public function save()
    {
        $validated = $this->validate();

        if (! $this->user) {
            // Hash password only if it's set
            if (! empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
            $user = User::create($validated);
            event(new Registered(($user)));
            $this->toast('User successfully created.', 'success');
        } else {
            // Remove password fields if not provided
            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }
            $this->user->update($validated);
            $this->toast('User successfully updated.', 'success');
        }
        $this->reset();

        $this->dispatch('users-updated');
        Flux::modals()->close();

        return to_route('users.index')->with('success', 'User created.');
    }

    public function render(): View
    {
        return view('livewire.users.create', ['branches' => BranchOffice::all()]);
    }

    #[On('editUser')]
    public function edit(?User $user = null): void
    {
        $this->editMode = true;
        Flux::modal('add-user')->show();
        $this->setUser($user);
    }

    public function resetForm(): void
    {
        $this->reset();
    }
}
