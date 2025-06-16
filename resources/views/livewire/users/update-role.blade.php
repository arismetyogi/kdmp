<div>
    <flux:modal name="edit-role" :show="$errors->isNotEmpty() || $user"  :dismissible="false"
                @close="resetForm()"
                class="max-w-lg space-y-6">
        <form wire:submit="save">
            <div>
                <flux:heading size="lg">
                    Update User Role
                </flux:heading>
                <flux:subheading>
                    Make changes to user's Role. Make sure you assign appropriate role!
                </flux:subheading>
            </div>

            <div class="flex flex-col gap-4 my-8">

                <flux:field label="Name" readonly>{{ $user->name ?? null }}</flux:field>
                <flux:radio.group label="Role" wire:model="role_id" class="grid grid-cols-2">
                    @foreach($roles as $role)
                        <flux:radio value="{{ $role->id }}" label="{{ $role->name }}"/>
                    @endforeach
                </flux:radio.group>
            </div>

            <div class="flex gap-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">Update</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
