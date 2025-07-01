<div class="w-full h-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Dokumen Klaim') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Upload Dokumen Klaim') }}</flux:subheading>
        <flux:separator variant="subtle"/>
    </div>

    <div class="bg-muted flex w-full max-h-full flex-col gap-6 items-center">
        <div class="p-4 overflow-y-auto bg-zinc-50 shadow-xl sm:rounded-lg dark:bg-zinc-700/30 w-full">
            <div class="flex justify-around gap-2 mb-4">
                <div class="flex w-96 gap-3">
                    <flux:select wire:model.live="unitBisnisCode" :disabled="(auth()->user()->role_id != 99)">
                        @foreach($branchOffice as $branch)
                            <flux:select.option
                                value="{{ $branch->unitbisnis_code }}">{{ substr($branch->name, 12,20) }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select wire:model.live="period" name="period" id="period">
                        <flux:select.option selected hidden
                                            value="">Pilih Periode
                        </flux:select.option>
                        @foreach($periods as $opt)
                            <flux:select.option
                                value="{{ $opt }}">{{ \Carbon\Carbon::parse($opt)->format("Y-M") }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                <flux:spacer/>
                <div class="ml-auto w-96">
                    <flux:input icon="magnifying-glass" placeholder="Search..." wire:model.live.debounce="search"
                                clearable="true"/>
                </div>
                <flux:button type="primary" wire:click="export">Export</flux:button>
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
            <x-table.index id="uploadClaimDocsTable">
                <x-slot name="head">
                    <x-table.heading sortable>#</x-table.heading>
                    <x-table.heading sortable></x-table.heading>
                    <x-table.heading sortable>Unit Bisnis</x-table.heading>
                    <x-table.heading sortable>Debitur</x-table.heading>
                    <x-table.heading sortable>Periode</x-table.heading>
                    <x-table.heading sortable>Omset</x-table.heading>
                    <x-table.heading sortable>Nilai Klaim</x-table.heading>
                    <x-table.heading sortable>Selisih</x-table.heading>
                    <x-table.heading sortable>Alasan</x-table.heading>
                    <x-table.heading sortable>Ket</x-table.heading>
                    <x-table.heading sortable>Status</x-table.heading>
                </x-slot>
                <x-slot name="body">
                    @forelse($claimUploads as $uploadData)
                        <x-table.row :even="$loop->even">
                            <x-table.cell index>{{ $claimUploads->firstItem() + $loop->index }}</x-table.cell>
                            <x-table.cell>
                                <flux:dropdown>
                                    <flux:button size="sm" variant="filled" icon-trailing="chevron-down">Action
                                    </flux:button>
                                    <flux:menu>
                                        <flux:menu.group>
                                            <flux:menu.item class="cursor-pointer" icon="document-arrow-up" wire:navigate
                                                            href="{{ route('claim-document-upload.upload', ['id' => Crypt::encryptString($uploadData->id)]) }}">
                                                {{ __('Upload Dokumen') }}
                                            </flux:menu.item>
                                            @if(isset($uploadData->claim->id) && ($uploadData->claim->invoice_value - $uploadData->total) != 0)
                                                <flux:menu.item icon="question-mark-circle"
                                                                wire:click="setReason({{ $uploadData->claim->id }})">
                                                    Alasan Selisih
                                                </flux:menu.item>
                                            @endif
                                        </flux:menu.group>
                                    </flux:menu>
                                </flux:dropdown>
                            </x-table.cell>
                            <x-table.cell>{{ substr(str()->headline($uploadData->branch?->name), 12, 20) }}</x-table.cell>
                            <x-table.cell>
                                <flux:tooltip>
                                    <flux:tooltip.index>
                                        {{ substr($uploadData->customer_name, 0, 25) }}...
                                    </flux:tooltip.index>
                                    <flux:tooltip.content
                                        class="bg-accent-content">{{ $uploadData->customer_name }}</flux:tooltip.content>
                                </flux:tooltip>
                            </x-table.cell>
                            <x-table.cell>{{ \Carbon\Carbon::parse($uploadData->period)->translatedFormat('M-Y') }}</x-table.cell>
                            <x-table.cell>{{ $uploadData->total }}</x-table.cell>
                            <x-table.cell>{{ $uploadData->claimDetails?->sum('invoice_value') }}</x-table.cell>
                            <x-table.cell
                                class="{{ $uploadData->claimDetails?->sum('invoice_value') - $uploadData->total != 0 ? '!text-white bg-red-500' : '' }}">{{ $uploadData->claimDetails?->sum('invoice_value') - $uploadData->total }}</x-table.cell>
                            <x-table.cell>{{ \App\Enums\Reasons::fromName($uploadData->claim?->reason) ?? '' }}</x-table.cell>
                            <x-table.cell>{{ $uploadData->claim?->notes }}</x-table.cell>
                            <x-table.cell>{{ $uploadData->status ? 'Closed' : 'Open' }}</x-table.cell>
                        </x-table.row>
                    @empty
                        <tr>
                            <td colspan="15"
                                class="text-center py-4 text-zinc-500 bg-zinc-50 dark:bg-zinc-700">
                                There are no data found!
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

    <flux:modal name="set-reason" class="w-[20-rem]">
        <form wire:submit="updateReason">
            <flux:heading>Input Alasan Selisih</flux:heading>
            <flux:separator></flux:separator>
            <div>
                <flux:select label="Alasan" wire:model="reason">
                    <flux:select.option value="" selected hidden>Pilih Alasan</flux:select.option>
                    @foreach(\App\Enums\Reasons::cases() as $reason)
                        <flux:select.option value="{{ $reason->name }}">{{ $reason->value }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:textarea label="Keterangan" wire:model="notes"/>
            </div>
            <div class="flex gap-2 mt-4">
                <flux:spacer/>

                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button type="submit">Submit</flux:button>
            </div>
        </form>
    </flux:modal>

</div>
