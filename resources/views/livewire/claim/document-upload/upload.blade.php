<div class="w-full h-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Upload Dokumen Klaim') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ $claimUpload->customer_name }}</flux:subheading>
        <flux:separator variant="subtle"/>
    </div>

    <div class="bg-muted flex w-full max-h-full flex-col gap-6 items-center">
        <div class="p-4 overflow-y-auto bg-zinc-50 shadow-xl sm:rounded-lg dark:bg-zinc-700/30 w-full">
            <div class=" gap-6 mb-4 w-full">
                <form wire:submit="save">
                    <flux:heading size="lg" class="text-start">Lengkapi data klaim berikut</flux:heading>

                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 my-8">
                        <flux:input disabled label="Debitur" value="{{ $claimUpload->customer_name }}"/>
                        <flux:input label="Nomor Invoice" wire:model="invoice_number" name="invoice_number"/>
                        <flux:input label="Tanggal Invoice" wire:model="invoice_date" name="invoice_date" type="date"/>
                        <flux:input label="Upload Invoice" wire:model="upload_invoice_file" name="upload_invoice_file"
                                    type="file"
                                    accept="pdf,jpg,jpeg"/>
                        <flux:input label="Upload Faktur Pajak" wire:model="tax_invoice_file" name="tax_invoice_file"
                                    type="file"
                                    accept="pdf,jpg,jpeg"/>
                        <flux:input.group label="Nominal Invoice">
                            <flux:input.group.prefix>Rp</flux:input.group.prefix>
                            <flux:input wire:model="invoice_value" name="invoice_value" type="text"
                                        mask="999,999,999,999"/>
                        </flux:input.group>
                        <flux:input label="Tgl. Pengiriman Alat Tagih" wire:model="delivery_date" name="delivery_date"
                                    type="date"/>
                        <flux:input label="Bukti Kirim Tanda Terima Alat Tagih" wire:model="receipt_file"
                                    name="receipt_file" type="file"
                                    accept="pdf,jpg,jpeg"/>
                        <flux:input label="Nomor Tracking Customer" wire:model="customer_tracking_number"
                                    name="customer_tracking_number" type="text"/>
                        <flux:input label="Upload PO Customer" wire:model="po_customer_file"
                                    name="po_customer_file" type="file"
                                    accept="pdf,jpg,jpeg"/>
                        <flux:input label="Upload BA Penyerahan Barang" wire:model="receipt_order_file"
                                    name="receipt_order_file" type="file"
                                    accept="pdf,jpg,jpeg"/>
                    </div>

                    <div class="flex gap-2">
                        <flux:button href="{{ route('claim-document-upload.index') }}"
                                     variant="filled">{{ __('Cancel') }}</flux:button>

                        <flux:button type="submit" variant="primary">
                            Submit
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
