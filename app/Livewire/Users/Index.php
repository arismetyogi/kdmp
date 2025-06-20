<?php

namespace App\Livewire\Users;

use App\Helpers\WithToast;
use App\Models\User;
use Carbon\Carbon;
use Flux\Flux;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[On('users-updated')]
class Index extends Component
{
    use WithPagination, WithToast;

    public
        $checked = [],
        $checkPage = false,
        $checkAll = false,
        $search = '',
        $selectedBranch = 3000,
        $perPage = 10,
        $sortField = 'users.updated_at',
        $sortDirection = 'desc',
        $dateRange = null,
        $branches,
        $roles,
        $dateArray = [];
    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->checked = [];
        $this->checkPage = false;
        $this->checkAll = false;
    }

    public function updatedSelectedBranch(): void
    {
        $this->resetPage();
        $this->checked = [];
        $this->checkPage = false;
        $this->checkAll = false;
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function getUsersQueryProperty()
    {
        return User::with(['role', 'branch'])
            ->leftJoin('branch_offices', 'users.unitbisnis_code', '=', 'branch_offices.unitbisnis_code')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'branch_offices.name as branch_name', 'roles.name as role_name')
            ->when($this->search, fn($query, $search) => $query
                ->whereAny(['users.name', 'users.username', 'users.email', 'branch_name', 'role_name'], 'like', "%{$search}%")
            )->when($this->selectedBranch != 3000, fn($query) => $query
                ->where('users.unitbisnis_code', $this->selectedBranch)
            )
            ->orderByRaw("CASE WHEN {$this->sortField} IS NULL THEN 1 ELSE 0 END, {$this->sortField} {$this->sortDirection}");
    }

    public function getUsersProperty()
    {
        return $this->usersQuery->paginate($this->perPage);
    }

    public function getSessionsProperty(): Collection
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            \DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) {
            return (object)[
                'user_id' => $session->user_id,
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                'last_activity' => $session->last_activity,
            ];
        });
    }

    public function render(): View
    {
        return view('livewire.users.index', [
            'users' => $this->users,
            'branches' => $this->branches,
            'roles' => $this->roles,
        ]);
    }

    public function updatedCheckPage($value): void
    {
        if ($value) {
            $this->checked = $this->users->pluck('id')->toArray();
        } else {
            $this->checked = [];
        }
    }

    public function selectAll(): void
    {
        $this->checkAll = true;
        $this->checked = $this->usersQuery->pluck('id')
            ->map(fn($item) => (string)$item)
            ->toArray();
    }

    public function addUser(): void
    {
        Flux::modal('add-user')->show();
    }

    public function delete($id): void
    {
        User::destroy($id);
        $this->toast('User successfully deleted.', 'success');
        $this->dispatch('users-updated');
    }

    public function getCheckedIdsProperty(): string
    {
        return json_encode($this->checked);
    }

    #[On('record-deleted')]
    public function deleteChecked(): void
    {
        $this->checkPage = false;
        $this->checkAll = false;
        $this->checked = [];
    }

    public function export(): void
    {
        $this->toast('Exporting...', 'success');
//        return new UsersSelectedExport($this->checked)->download('users.xlsx');
    }

    public function isChecked($record_id): bool
    {
        return in_array($record_id, $this->checked);
    }

    public function editUser($id): void
    {
        $this->dispatch('editUser', $id);
        Flux::modal('add-user')->show();
    }

    public function editRole($id): void
    {
        $this->dispatch('editRole', $id);
        Flux::modal('edit-role')->show();
    }
}
