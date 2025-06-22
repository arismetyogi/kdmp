<div>
    <flux:modal name="add-customer" :show="$errors->isNotEmpty() || $customer" :dismissible="false"
                @close="resetForm()"
                class="max-w-lg space-y-6">
        <form wire:submit="save">
            <div>
                <flux:heading size="lg">
                    @if(!$editMode)
                        Tambahkan Penjamin
                    @else
                        Perbaharui Penjamin
                    @endif</flux:heading>
                <flux:subheading>@if(!$editMode)
                        Fill in the details below.
                    @else
                        Make changes to customer details.
                    @endif</flux:subheading>
            </div>

            <div class="flex flex-col gap-4 my-8">

                <flux:input label="Name" placeholder="Nama Penjamin" wire:model.blur="name" autofocus/>
                <flux:input label="Kode Penjamin SAP" placeholder="012345678" wire:model.blur="code"/>
                <flux:input label="Kode Area" placeholder="KFD0" wire:model.blur="area_code_description"/>
                <flux:input label="Deskripsi Kode Area" placeholder="Default" wire:model.blur="area_code"/>
                <flux:input label="Kode Penjamin SS" placeholder="Default" wire:model.blur="insurer_id"/>
            </div>

            <div class="flex gap-2">
                <flux:modal.close>
                    <flux:button wire:click="resetForm()" variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">@if(!$editMode)
                        Create
                    @else
                        Update
                    @endif
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
