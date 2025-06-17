<div class="w-full h-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Upload Dokumen Klaim') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ $claimUpload->customer->customer_name }}</flux:subheading>
        <flux:separator variant="subtle"/>
    </div>

    <div class="bg-muted flex w-full max-h-full flex-col gap-6 items-center">
        <div class="p-4 overflow-y-auto bg-zinc-50 shadow-xl sm:rounded-lg dark:bg-zinc-700/30 w-full">
            <div class=" gap-6 mb-4 w-full drop-shadow-md">
                <form wire:submit="save">
                    <flux:heading size="lg" class="text-start">Lengkapi data dokumen klaim berikut</flux:heading>
                    <flux:separator/>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 my-8">
                        <flux:input disabled label="Debitur" value="{{ $claimUpload->customer_name }}"/>
                        <flux:input label="Nomor Invoice" wire:model="invoice_number" name="invoice_number"/>
                        <flux:input label="Tanggal Invoice" wire:model="invoice_date" name="invoice_date" type="date"/>
                        <flux:input label="Upload Invoice" wire:model="upload_invoice_file" name="upload_invoice_file"
                                    type="file" :required="false"
                                    accept="pdf,jpg,jpeg"/>
                        <flux:input label="Upload Faktur Pajak" wire:model="tax_invoice_file" name="tax_invoice_file"
                                    type="file" :required="false"
                                    accept="pdf,jpg,jpeg"/>
                        <flux:input.group label="Nominal Invoice">
                            <flux:input.group.prefix>Rp</flux:input.group.prefix>
                            <flux:input wire:model="invoice_value" name="invoice_value" type="text"
                                        mask="999,999,999,999"/>
                        </flux:input.group>
                        <flux:input label="Tgl. Pengiriman Alat Tagih" wire:model="delivery_date" name="delivery_date"
                                    type="date"/>
                        <flux:input label="Bukti Kirim Tanda Terima Alat Tagih" wire:model="receipt_file"
                                    name="receipt_file" type="file" :required="false"
                                    accept="pdf,jpg,jpeg"/>
                        <flux:input label="Nomor Tracking Customer" wire:model="customer_tracking_number"
                                    name="customer_tracking_number" type="text"/>
                        <flux:input label="Upload PO Customer" wire:model="po_customer_file"
                                    name="po_customer_file" type="file" :required="false"
                                    accept="pdf,jpg,jpeg"/>
                        <flux:input label="Upload BA Penyerahan Barang" wire:model="receipt_order_file"
                                    name="receipt_order_file" type="file" :required="false"
                                    accept="pdf,jpg,jpeg"/>
                    </div>

                    <div class="flex gap-2">
                        <flux:button icon="arrow-uturn-left" href="{{ route('claim-document-upload.index') }}"
                                     variant="filled">{{ __('Cancel') }}</flux:button>

                        <flux:button type="submit" variant="primary">
                            Submit
                        </flux:button>
                    </div>
                </form>
            </div>

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
                                    @isset($claim->upload_invoice_file)
                                        <flux:menu.item href="#" target="_blank">File sudah diupload</flux:menu.item>
                                    @endisset
                                    <flux:button>Upload</flux:button>
                                </x-table.cell>
                                <x-table.cell>
                                    @isset($claim->receipt_file)
                                        <flux:menu.item href="#" target="_blank">File sudah diupload</flux:menu.item>
                                    @endisset
                                    <flux:button>Upload</flux:button>
                                </x-table.cell>
                                <x-table.cell>
                                    @isset($claim->tax_invoice_file)
                                        <flux:menu.item href="#" target="_blank">File sudah diupload</flux:menu.item>
                                    @endisset
                                    <flux:button>Upload</flux:button>
                                </x-table.cell>
                                <x-table.cell>Action</x-table.cell>
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
