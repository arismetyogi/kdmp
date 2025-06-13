<flux:modal name="delete-modal" class="min-w-[22rem]">
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

