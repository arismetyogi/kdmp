<div class="w-full h-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Upload Dokumen Klaim') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ $claimUpload->customer?->customer_name }}</flux:subheading>
        <flux:separator variant="subtle"/>
    </div>

    <div class="bg-muted flex w-full max-h-full flex-col gap-6 items-center">
        <div class="p-4 overflow-y-auto bg-zinc-50 shadow-xl sm:rounded-lg dark:bg-zinc-700/30 w-full">
            @livewire('claim.document-upload.upload-form')
            <flux:separator/>

            <div class="mt-4">
                <x-table.index id="uploadClaimDocsTable">
                    <x-slot name="head">
                        <x-table.heading sortable>#</x-table.heading>
                        <x-table.heading sortable>No Invoice</x-table.heading>
                        <x-table.heading sortable>Nilai Invoice</x-table.heading>
                        <x-table.heading sortable>Tgl Kirim</x-table.heading>
                        <x-table.heading sortable>File Invoice</x-table.heading>
                        <x-table.heading sortable>Bukti Kirim</x-table.heading>
                        <x-table.heading sortable>Faktur Pajak</x-table.heading>
                        <x-table.heading sortable></x-table.heading>
                    </x-slot>
                    <x-slot name="body">
                        @php
                            $totalInvoiceValue = 0;
                        @endphp
                        @forelse($claimDetails as $claim)
                            <x-table.row :even="$loop->even">
                                <x-table.cell index>{{ $loop->iteration }}</x-table.cell>
                                <x-table.cell>{{ $claim->invoice_number }}</x-table.cell>
                                <x-table.cell
                                    class="text-end">{{ $claim->invoice_value }}</x-table.cell>
                                <x-table.cell>{{ $claim->delivery_date }}</x-table.cell>
                                <x-table.cell>
                                    @if(isset($claim->upload_invoice_file))
                                        <flux:menu.item href="#">File sudah diupload</flux:menu.item>
                                    @else
                                        <flux:button variant="subtle" icon-trailing="document-arrow-up">Upload</flux:button>
                                    @endif
                                </x-table.cell>
                                <x-table.cell>
                                    @if(isset($claim->receipt_file))
                                        <flux:menu.item href="#">File sudah diupload</flux:menu.item>
                                    @else
                                        <flux:button variant="subtle" icon-trailing="document-arrow-up">Upload</flux:button>
                                    @endif
                                </x-table.cell>
                                <x-table.cell>
                                    @if(isset($claim->tax_invoice_file))
                                        <flux:menu.item href="#">File sudah diupload</flux:menu.item>
                                    @else
                                        <flux:button variant="subtle" icon-trailing="document-arrow-up">Upload</flux:button>
                                    @endif
                                </x-table.cell>
                                <x-table.cell>
                                    <flux:button variant="filled" icon="pencil-square" wire:click="edit({{ $claim->id }})"></flux:button>
                                </x-table.cell>
                            </x-table.row>

                            @php
                                $totalInvoiceValue += $claim?->invoice_value;
                            @endphp
                        @empty
                            <tr>
                                <td colspan="15"
                                    class="text-center py-4 text-zinc-500 bg-zinc-50 dark:bg-zinc-700">There are no
                                    data found!
                                </td>
                            </tr>
                        @endforelse

                        <x-table.row :even="true" class="font-bold text-base">
                            <x-table.cell class="text-end" colspan="2">Total Klaim Invoice</x-table.cell>
                            <x-table.cell>{{ $totalInvoiceValue }}</x-table.cell>
                            <x-table.cell class="text-end">Total Omset</x-table.cell>
                            <x-table.cell>{{ $claimUpload->total }}</x-table.cell>
                            <x-table.cell colspan="3"/>
                        </x-table.row>
                    </x-slot>
                </x-table.index>
            </div>
        </div>
    </div>
</div>
