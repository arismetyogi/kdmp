<section class="w-full">
    {{--    <livewire:breadcrumbs :items="[--}}
    {{--            ['href' => route('customers.index'), 'label' => 'customers']--}}
    {{--        ]"--}}
    {{--    />--}}

    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Data Penjamin') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Kelola data penjamin') }}</flux:subheading>
        <flux:separator variant="subtle"/>
    </div>

    <div class="bg-muted flex w-full max-h-full flex-col gap-6 items-center">
        <div class="p-4 overflow-y-auto bg-zinc-50 shadow-xl sm:rounded-lg dark:bg-zinc-700/30 w-full">
            <div class="flex gap-2 mb-4 items-center justify-between">
                <flux:button type="primary" wire:click="export" icon="document-arrow-down">Export</flux:button>
                <flux:button type="primary" wire:click="addCustomer" icon="document-plus">Tambah Penjamin</flux:button>
                <flux:spacer/>
                <div class="ml-auto w-96">
                    <flux:input icon="magnifying-glass" placeholder="Search..." wire:model.live.debounce="search"
                                clearable="true"/>
                </div>
            </div>
            <div class="flex gap-2 items-center">
                <label for="perPage">per Page:</label>
                <select wire:model.live="perPage" id="perPage"
                        class="border px-2 py-1 border-zinc-300 dark:border-zinc-500 dark:bg-zinc-700 rounded">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <x-table.index id="customersTable">
                <x-slot name="head">
                    <x-table.heading class="text-left">
                        <flux:checkbox wire:model.live="checkPage"/>
                    </x-table.heading>
                    <x-table.heading sortable>#</x-table.heading>
                    <x-table.heading sortable class="w-fit"></x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('customers.name')"
                                     :direction="$sortField === 'customers.name' ? $sortDirection : null">Customer
                    </x-table.heading>
                    <x-table.heading sortable>Kode Area
                    </x-table.heading>
                    <x-table.heading sortable>
                        Nama Customer
                    </x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('customers.updated_at')"
                                     :direction="$sortField === 'customers.updated_at' ? $sortDirection : null">Last Update
                    </x-table.heading>
                </x-slot>
                <x-slot name="body">
                    @forelse($customers as $customer)
                        <x-table.row :even="$loop->even">
                            <x-table.cell>
                                <flux:checkbox value="{{ $customer->id }}" wire:key="{{ $customer->id }}"
                                               wire:model.live="checked"/>
                            </x-table.cell>
                            <x-table.cell index>{{ $customers->firstItem() + $loop->index }}</x-table.cell>
                            <x-table.cell>
                                <flux:dropdown>
                                    <flux:button icon-trailing="chevron-down">Action</flux:button>
                                    <flux:menu>
                                        <flux:modal.trigger name="add-customer"
                                                            wire:click="editcustomer({{ $customer->id }})">
                                            <flux:menu.item icon="pencil" class="cursor-pointer">Edit</flux:button>
                                        </flux:modal.trigger>

                                        <flux:separator/>

                                        <flux:modal.trigger variant="danger" name="delete-modal"
                                                            x-data="{ customerId: {{  json_encode([$customer->id]) }} }"
                                                            wire:click="$dispatch('bulkDelete', {recordIds: customerId, model: 'Customer' })"
                                        >
                                            <flux:menu.item variant="danger" class="cursor-pointer" icon="document-minus">
                                                {{  __('Delete') }}
                                            </flux:menu.item>
                                        </flux:modal.trigger>
                                    </flux:menu>
                                </flux:dropdown>
                            </x-table.cell>
                            <x-table.cell>
                                    <div class="flex flex-col">
                                        <flux:heading>{{ $customer->name }}</flux:heading>
                                        <flux:text>{{ $customer->code }}</flux:text>
                                    </div>
                            </x-table.cell>
                            <x-table.cell>
                                <flux:subheading>{{ $customer->area_code }}</flux:subheading>
                            </x-table.cell>
                            <x-table.cell>
                                <flux:subheading>{{ $customer->customer_name }}</flux:subheading>
                            </x-table.cell>
                            <x-table.cell>
                                <flux:text>{{ $customer->updated_at->diffForHumans() ?? null }}</flux:text>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-zinc-500 bg-zinc-50 dark:bg-zinc-700">There
                                are no customers with that name!
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-table.index>
            <div
                class="flex flex-wrap items-center justify-between py-4 space-y-4 flex-column md:flex-row md:space-y-0">
                <div class="flex gap-2 items-center">
                    <label for="perPage">per Page:</label>
                    <select wire:model.live="perPage" id="perPage"
                            class="border px-2 py-1 border-zinc-300 dark:border-zinc-500 dark:bg-zinc-700 rounded">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div>
                    {{ $customers->links('simple-pagination', data: ['scrollTo' => false]) }}
                </div>
            </div>
        </div>
    </div>

    @livewire('customers.create')
    @livewire('delete-modal')
</section>
