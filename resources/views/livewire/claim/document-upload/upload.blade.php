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

                    <div class="grid grid-cols-2 gap-4 my-8">
                        <flux:input disabled label="Debitur" value="{{ $claimUpload->customer_name }}"/>
                        <flux:input label="No. Invoice" wire:model="invoice_number"/>
                        <flux:input label="Tgl. Invoice" wire:model="invoice_date" type="date"/>
                        <flux:input label="Upload Invoice" wire:model="invoice_date" type="file"/>
                        <flux:input label="Upload Faktur Pajak" wire:model="invoice_date" type="file"/>
                        <flux:input.group label="Nominal Invoice" >
                            <flux:input.group.prefix>Rp </flux:input.group.prefix>
                            <flux:input wire:model="invoice_number" type="number"/>
                        </flux:input.group>
                        <flux:input label="Tgl. Pengiriman ALat Tagih" wire:model="delivery_date" type="date"/>
                    </div>

                    <div class="flex gap-2">
                        <flux:modal.close>
                            <flux:button wire:click="resetForm()" variant="filled">{{ __('Cancel') }}</flux:button>
                        </flux:modal.close>

                        <flux:button type="submit" variant="primary">
                            Submit
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
