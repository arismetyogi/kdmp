<div class="w-full h-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Dokumen Klaim') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Upload Dokumen Klaim') }}</flux:subheading>
        <flux:separator variant="subtle"/>
    </div>

    <div class="bg-muted flex w-full max-h-full flex-col gap-6 items-center">
        <div class="p-4 overflow-y-auto bg-zinc-50 shadow-xl sm:rounded-lg dark:bg-zinc-700/30 w-full">
            <div class="grid grid-cols-6 gap-2 mb-4">
                <flux:select wire:model.live="unitBisnisCode">
                    @foreach(\App\Models\BranchOffice::all() as $branch)
                        <flux:select.option
                            value="{{ $branch->unitbisnis_code }}">{{ substr(str()->headline($branch->name), 12,20) }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.live="period">
                    <flux:select.option selected hidden
                                        value="">Pilih Periode
                    </flux:select.option>
                    @foreach($periods as $opt)
                        <flux:select.option
                            value="{{ $opt }}">{{ \Carbon\Carbon::parse($opt)->format("Y-M") }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:spacer/>
                <flux:button type="primary" wire:click="export">Export</flux:button>
            </div>
            <div wire:model.live="perPage" class="flex gap-2 items-center">
                <label for="perPage">per Page:</label>
                <select id="perPage"
                        class="border px-2 py-1 border-zinc-300 dark:border-zinc-500 dark:bg-zinc-700 rounded">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <x-table.index id="uploadClaimDocsTable">
                <x-slot name="head">
                    <x-table.heading sortable>#</x-table.heading>
                    <x-table.heading sortable>Unit Bisnis</x-table.heading>
                    <x-table.heading sortable>Debitur</x-table.heading>
                    <x-table.heading sortable>Periode</x-table.heading>
                    <x-table.heading sortable>Omset</x-table.heading>
                    <x-table.heading sortable>Nilai Klaim</x-table.heading>
                    <x-table.heading sortable>Selisih</x-table.heading>
                    <x-table.heading sortable>Alasan</x-table.heading>
                    <x-table.heading sortable>Ket</x-table.heading>
                    <x-table.heading sortable>Status</x-table.heading>
                    <x-table.heading sortable>Aksi</x-table.heading>
                </x-slot>
                <x-slot name="body">
                    @forelse($claimUploads as $uploadData)
                        <x-table.row :even="$loop->even">
                            <x-table.cell>{{ $claimUploads->firstItem() + $loop->index }}</x-table.cell>
                            <x-table.cell>{{ substr(str()->headline($uploadData->branch?->name), 12,20) }}</x-table.cell>
                            <x-table.cell>{{ $uploadData->customer_name }}</x-table.cell>
                            <x-table.cell>{{ \Carbon\Carbon::parse($uploadData->period)->translatedFormat('M-Y') }}</x-table.cell>
                            <x-table.cell>{{ currency_format($uploadData->total) }}</x-table.cell>
                            <x-table.cell>{{ currency_format($uploadData->claim?->invoice_value) }}</x-table.cell>
                            <x-table.cell
                                class="min-w-full">{{ currency_format($uploadData->claim?->invoice_value - $uploadData->total) }}</x-table.cell>
                            <x-table.cell>{{ $uploadData->claim?->reason }}</x-table.cell>
                            <x-table.cell>{{ $uploadData->claim?->notes }}</x-table.cell>
                            <x-table.cell>{{ $uploadData->status ? 'Closed' : 'Open' }}</x-table.cell>
                            <x-table.cell>
                                @if($uploadData->status)
                                    <flux:text>Dokumen sudah diupload</flux:text>
                                @else
                                    <flux:dropdown>
                                        <flux:button icon-trailing="chevron-down">Action</flux:button>
                                        <flux:menu>
                                            <flux:menu.item class="cursor-pointer" icon="document-arrow-up"
                                                            wire:click="$dispatch('upload-docs' , {'batchId': '{{ $uploadData->batch_id }}'})">
                                                {{ __('Upload Dokumen') }}
                                            </flux:menu.item>
                                        </flux:menu>
                                        @if(isset($uploadData->claim->id))
                                            <flux:menu.item icon="question-mark-circle">
                                                Alasan Selisih
                                            </flux:menu.item>
                                        @endif
                                    </flux:dropdown>
                                @endif
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <tr>
                            <td colspan="10"
                                class="text-center py-4 text-zinc-500 bg-zinc-50 dark:bg-zinc-700">There are no
                                data
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
