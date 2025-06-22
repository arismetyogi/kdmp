<?php

namespace App\Livewire\Customers;

use App\Helpers\WithToast;
use App\Models\Customer;
use Flux\Flux;
use Illuminate\View\View;
use Livewire\Component;

class Create extends Component
{
    use WithToast;

    public ?Customer $customer = null;

    public $name;
    public $code;
    public $segmen_standardisasi;
    public $area_code;
    public $area_code_description;
    public $customer_name;
    public $insurer_id;

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'code' => 'required|int|digits:8',
            'segmen_standardisasi' => 'nullable|string|min:3|max:255',
            'area_code' => 'required|string|digits:8',
            'area_code_description' => 'nullable|string|min:4|max:4',
            'customer_name' => 'required|string|min:3|max:255',
            'insurer_id' => 'nullable|string',
        ];
    }

    public function setCustomer(?Customer $customer = null): void
    {
        $this->customer = $customer;

        $this->name = $customer->name;
        $this->code = $customer->code;
        $this->segmen_standardisasi = $customer->segmen_standarisasi;
        $this->area_code = $customer->area_code;
        $this->area_code_description = $customer->area_code_description;
        $this->insurer_id = $customer->insurer_id;
    }

    public function save(): void
    {
        $validated = $this->validate();
        $validated['customer_name'] = $this->name . '-' . $this->area_code;

        if (!$this->customer) {
            Customer::create($validated);
            $this->toast('Penjamin berhasil ditambahkan', 'success');
        } else {
            $this->customer->update($validated);
            $this->toast('Penjamin berhasil diperbaharui', 'success');
        }

        $this->reset();

        $this->dispatch('reload-customers');
        Flux::modals()->close();
    }
    public function render(): View
    {
        return view('livewire.customers.create');
    }
}
