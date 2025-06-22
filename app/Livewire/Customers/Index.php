<?php

namespace App\Livewire\Customers;

use App\Helpers\WithToast;
use App\Models\Customer;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[On('reload-customers')]
class Index extends Component
{
    use WithPagination, WithToast;

    public $checked = [];

    public $checkPage = false;

    public $checkAll = false;

    public $search = '';

    public $selectedBranch = 3000;

    public $perPage = 10;

    public $sortField = 'customers.updated_at';

    public $sortDirection = 'desc';

    public $dateRange = null;

    public $branches;

    public $roles;

    public $dateArray = [];

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

    public function getCustomersQueryProperty()
    {
        return Customer::
        when($this->search, function ($q) {
            $q->where(function ($q) {
                foreach (['customers.name', 'customers.area_code', 'customers.code'] as $column) {
                    $q->orWhere($column, 'like', "%{$this->search}%");
                }
            });
        })
            ->orderByRaw("CASE WHEN {$this->sortField} IS NULL THEN 1 ELSE 0 END, {$this->sortField} {$this->sortDirection}");
    }

    public function getCustomersProperty()
    {
        return $this->customersQuery->paginate($this->perPage);
    }

    public function render(): View
    {
        $customers = $this->customers;
        return view('livewire.customers.index', compact(['customers']));
    }

    public function updatedCheckPage($value): void
    {
        if ($value) {
            $this->checked = $this->customers->pluck('id')->toArray();
        } else {
            $this->checked = [];
        }
    }

    public function selectAll(): void
    {
        $this->checkAll = true;
        $this->checked = $this->customersQuery->pluck('id')
            ->map(fn($item) => (string)$item)
            ->toArray();
    }

    public function addCustomer(): void
    {
        Flux::modal('add-customer')->show();
    }

    public function delete(?Customer $customer = null): void
    {
        $customer->delete();
        $this->toast('Customer successfully deleted.', 'success');
        $this->dispatch('customers-updated');
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
        $this->toast('Exporting customers...', 'success');
        //        return new CustomersSelectedExport($this->checked)->download('customers.xlsx');
    }

    public function isChecked($record_id): bool
    {
        return in_array($record_id, $this->checked);
    }

    public function editCustomer($id): void
    {
        $this->dispatch('editCustomer', $id);
        Flux::modal('add-customer')->show();
    }
}
