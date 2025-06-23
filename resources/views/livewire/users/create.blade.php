<div>
    <flux:modal name="add-user" :show="$errors->isNotEmpty() || $user" variant="flyout" :dismissible="false"
                @close="resetForm()"
                class="max-w-lg space-y-6">
        <form wire:submit="save">
            <div>
                <flux:heading size="lg">
                    @if(!$editMode)
                        Tambah Pengguna Baru
                    @else
                        Perbaharui Data Pengguna
                    @endif</flux:heading>
                <flux:subheading>@if(!$editMode)
                        Lengkapi detil pengguna berikut.
                    @else
                        Make changes to your personal details.
                    @endif</flux:subheading>
            </div>

            <div class="flex flex-col gap-4 my-8">

                <flux:input label="Name" placeholder="Your name" wire:model.blur="name" autofocus/>
                <flux:input label="Username" placeholder="Username" wire:model.blur="username"/>
                <flux:input label="Email" placeholder="mail@example.com" wire:model.blur="email"/>
                <flux:select label="Unit Bisnis" wire:model="unitbisnis_code" placeholder="Pilih BM">
                    @foreach($branches ?? [] as $branch)
                        <flux:select.option.variants.default
                            value="{{ $branch->unitbisnis_code }}">{{ substr($branch->name,12,20) }}</flux:select.option.variants.default>
                    @endforeach
                </flux:select>
                @if(!$editMode)
                    <flux:input label="Password" type="password" viewable wire:model.live="password"/>
                    <flux:input label="Password Confirmation" type="password" viewable
                                wire:model.live="password_confirmation"/>
                @endif
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
