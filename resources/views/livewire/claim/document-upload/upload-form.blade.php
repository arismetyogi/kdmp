<div class=" gap-6 mb-4 w-full drop-shadow-md">
    <form wire:submit="save">
        <flux:heading size="lg" class="text-start">Lengkapi data dokumen klaim berikut</flux:heading>
        <flux:separator/>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 my-8">
            <flux:input disabled label="Debitur" value="{{ $claimUpload->customer?->customer_name }}"/>
            <flux:input label="Nomor Invoice" wire:model="invoice_number" name="invoice_number"/>
            <flux:input label="Tanggal Invoice" wire:model="invoice_date" name="invoice_date" type="date"/>
            <flux:input label="Upload Invoice" wire:model="upload_invoice_file" name="upload_invoice_file"
                        type="file" :required="false"
                        accept=".pdf,.jpg,.jpeg"/>
            <flux:input label="Upload Faktur Pajak" wire:model="tax_invoice_file" name="tax_invoice_file"
                        type="file" :required="false"
                        accept=".pdf,.jpg,.jpeg"/>
            <flux:input.group label="Nominal Invoice">
                <flux:input.group.prefix>Rp</flux:input.group.prefix>
                <flux:input wire:model="invoice_value" name="invoice_value" type="text"
                            x-mask:dynamic="$money($input)"/>
            </flux:input.group>
            <flux:input label="Tgl. Pengiriman Alat Tagih" wire:model="delivery_date" name="delivery_date"
                        type="date"/>
            <flux:input label="Bukti Kirim Tanda Terima Alat Tagih" wire:model="receipt_file"
                        name="receipt_file" type="file" :required="false"
                        accept=".pdf,.jpg,.jpeg"/>
            <flux:input label="Nomor Tracking Customer" wire:model="customer_tracking_number"
                        name="customer_tracking_number" type="text"/>
            <flux:input label="Upload PO Customer" wire:model="po_customer_file"
                        name="po_customer_file" type="file" :required="false"
                        accept=".pdf,.jpg,.jpeg"/>
            <flux:input label="Upload BA Penyerahan Barang" wire:model="receipt_order_file"
                        name="receipt_order_file" type="file" :required="false"
                        accept=".pdf,.jpg,.jpeg"/>
        </div>

        <div class="flex gap-2">
            <flux:button wire:click="resetForm" variant="filled">
                {{ __('Reset') }}
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ __('Submit') }}
            </flux:button>

            <flux:spacer/>
            <flux:button href="{{ route('claim-document-upload.index') }}" variant="danger" icon="arrow-uturn-left" wire:navigate>Kembali</flux:button>
        </div>
    </form>
</div>
