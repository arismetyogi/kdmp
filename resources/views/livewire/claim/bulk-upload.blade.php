<div class="w-full h-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Form Upload') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Upload Penjamin') }}</flux:subheading>
        <flux:separator variant="subtle"/>
    </div>

    <div class="bg-muted flex w-full max-h-full flex-col gap-6 items-center">
        <div
            class="p-4 bg-zinc-50 shadow-xl sm:rounded-lg dark:bg-zinc-700/30 w-full justify-center items-center mx-auto">
            <flux:subheading size="lg">{{ __('Upload Data Penjamin') }}</flux:subheading>
            <flux:separator/>

            <form id="uploadClaim" wire:submit="uploadClaim" class="flex flex-col gap-6 items-start"
                  enctype="multipart/form-data">
                @csrf
                <flux:field class="mt-4">
                    <flux:input id="claimFile" name="claimFile" wire:model="claimFile" type="file" accept=".xlsx"/>
                    <flux:text class="text-orange-400 dark:text-orange-300">Upload File hanya ekstensi *.xlsx
                    </flux:text>
                    <flux:error name="claimFile"/>
                </flux:field>

                <div class="flex items-center justify-between gap-2">
                    <flux:button :loading="true" type="submit" variant="primary" class="w-full">
                        {{ __('Submit') }}
                    </flux:button>
                    <flux:button variant="filled" class="w-full" href="files/claim_uploads.xlsx"
                                 icon:trailing="arrow-up-right"> {{ __('Download Template') }}</flux:button>
                </div>
            </form>
        </div>

        <div class="p-4 overflow-y-auto bg-zinc-50 shadow-xl sm:rounded-lg dark:bg-zinc-700/30 w-full">
            <div wire:model.live="perPage" class="flex gap-2 items-center">
                <label for="perPage">per Page:</label>
                <select id="perPage"
                        class="border px-2 py-1 border-zinc-300 dark:border-zinc-500 dark:bg-zinc-700 rounded">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <x-table.index id="uploadClaimTable">
                <x-slot name="head">
                    <x-table.heading sortable>#</x-table.heading>
                    <x-table.heading sortable>Waktu</x-table.heading>
                    <x-table.heading sortable>Periode</x-table.heading>
                    <x-table.heading sortable>Batch</x-table.heading>
                    <x-table.heading sortable>Unit Bisnis</x-table.heading>
                    <x-table.heading sortable>Total</x-table.heading>
                    <x-table.heading sortable>User</x-table.heading>
                    <x-table.heading sortable>Action</x-table.heading>
                </x-slot>
                <x-slot name="body">
                    @forelse($claimUploads as $uploadData)
                        <x-table.row :even="$loop->even">
                            <x-table.cell>{{ $loop->iteration }}</x-table.cell>
                            <x-table.cell>{{ \Carbon\Carbon::parse($uploadData->created_at)->format('d-M-Y h:m') }}</x-table.cell>
                            <x-table.cell>{{ \Carbon\Carbon::parse($uploadData->period)->format('d-M-Y') }}</x-table.cell>
                            <x-table.cell>{{ short_batch($uploadData->batch_id) }}</x-table.cell>
                            <x-table.cell>{{ substr(str()->headline($uploadData->branch->name), 12,20) }}</x-table.cell>
                            <x-table.cell>{{ currency_format($uploadData->total_claims) }}</x-table.cell>
                            <x-table.cell>{{ $uploadData->user?->name }}</x-table.cell>
                            <x-table.cell>
                                <flux:dropdown>
                                    <flux:button icon-trailing="chevron-down">Action</flux:button>
                                    <flux:menu>
                                        <flux:menu.heading>Actions</flux:menu.heading>
                                        <flux:menu.separator/>
                                        <flux:menu.item variant="danger" class="cursor-pointer" icon="trash"
                                                        wire:click="$dispatch('delete-batch' , {'batchId': '{{ $uploadData->batch_id }}'})">
                                            {{  __('Delete') }}
                                        </flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <tr>
                            <td colspan="10"
                                class="text-center py-4 text-zinc-500 bg-zinc-50 dark:bg-zinc-700">There are no data
                                found!
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-table.index>
            <div
                class="flex flex-wrap items-center justify-between py-4 space-y-4 flex-column md:flex-row md:space-y-0">
                <div wire:model.live="perPage" class="flex gap-2 items-center">
                    <label for="perPage">per Page:</label>
                    <select id="perPage"
                            class="border px-2 py-1 border-zinc-300 dark:border-zinc-500 dark:bg-zinc-700 rounded">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div>
                    {{ $claimUploads->links('simple-pagination', data: ['scrollTo' => false]) }}
                </div>
            </div>
        </div>
    </div>


    <flux:modal name="delete-upload" class="min-w-[22rem]">
        <form wire:submit="delete">
            <div>
                <flux:heading size="lg">Delete data?</flux:heading>

                <flux:subheading>
                    <p>You're about to delete this data.</p>
                    <p>This action cannot be reversed.</p>
                </flux:subheading>
            </div>

            <div class="flex gap-2 mt-4">
                <flux:spacer/>

                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="danger">Delete</flux:button>
            </div>
        </form>
    </flux:modal>

</div>
