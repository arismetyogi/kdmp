<section class="w-full">
    {{--    <livewire:breadcrumbs :items="[--}}
    {{--            ['href' => route('users.index'), 'label' => 'Users']--}}
    {{--        ]"--}}
    {{--    />--}}

    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Data Pengguna') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Kelola data pengguna') }}</flux:subheading>
        <flux:separator variant="subtle"/>
    </div>

    <div class="bg-muted flex w-full max-h-full flex-col gap-6 items-center">
        <div class="p-4 overflow-y-auto bg-zinc-50 shadow-xl sm:rounded-lg dark:bg-zinc-700/30 w-full">
            <div class="flex gap-2 mb-4 items-center justify-between">
                <div class="w-96">
                    <flux:select wire:model.live="unitBisnis">
                        @foreach(\App\Models\BranchOffice::all() as $branch)
                            <flux:select.option
                                value="{{ $branch->unitbisnis_code }}">{{ substr(str()->headline($branch->name), 12,20) }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                <flux:button type="primary" wire:click="export" icon="document-arrow-down">Export</flux:button>
                <flux:button type="primary" wire:click="addUser" icon="user-plus">New User</flux:button>
                <flux:spacer/>
                <div class="ml-auto w-96">
                    <flux:input icon="magnifying-glass" placeholder="Search..." wire:model.live.debounce="search"
                                clearable="true"/>
                </div>
            </div>
            <div wire:model.live="perPage" class="flex gap-2 items-center">
                <label for="perPage">per Page:</label>
                <select id="perPage"
                        class="border px-2 py-1 border-zinc-300 dark:border-zinc-500 dark:bg-zinc-700 rounded">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <x-table.index id="uploadClaimDocsTable">
                <x-slot name="head">
                    <x-table.heading class="text-left">
                        <flux:checkbox wire:model.live="checkPage"/>
                    </x-table.heading>
                    <x-table.heading sortable>#</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('users.name')"
                                     :direction="$sortField === 'users.name' ? $sortDirection : null">Name
                    </x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('branch_name')"
                                     :direction="$sortField === 'branch_name' ? $sortDirection : null">Unit
                    </x-table.heading>
                    <x-table.heading sortable>Role</x-table.heading>
                    <x-table.heading sortable wire:click="sortBy('users.updated_at')"
                                     :direction="$sortField === 'users.updated_at' ? $sortDirection : null">Last Update
                    </x-table.heading>
                    <x-table.heading sortable class="w-fit">Action</x-table.heading>
                </x-slot>
                <x-slot name="body">
                    @forelse($users as $user)
                        <x-table.row :even="$loop->even">
                            <x-table.cell>
                                <flux:checkbox value="{{ $user->id }}" wire:key="{{ $user->id }}"
                                               wire:model.live="checked"/>
                            </x-table.cell>
                            <x-table.cell>{{ $users->firstItem() + $loop->index }}</x-table.cell>
                            <x-table.cell>
                                <div class="flex items-center gap-2">
                                    <flux:avatar circle color="auto" color:seed="{{ $user->id }}"
                                                 src="{{ $user->avatar }}"
                                                 name="{{ $user->name }}"
                                                 badge badge:circle
                                                 :badge:color="$this->sessions->firstWhere('user_id', $user->id) ? 'emerald' : 'orange'"
                                    />
                                    <div class="flex flex-col">
                                        <flux:heading>{{ $user->name }}</flux:heading>
                                        <flux:text>{{ $user->email }}</flux:text>
                                    </div>
                                </div>
                            </x-table.cell>
                            <x-table.cell>
                                <flux:subheading>{{ $user->branch_name ?? null }}</flux:subheading>
                            </x-table.cell>
                            <x-table.cell>
                                <flux:subheading>{{ $user->role?->name ?? null }}</flux:subheading>
                            </x-table.cell>
                            <x-table.cell>
                                <flux:text>{{ $user->updated_at ? $user->updated_at->diffForHumans() : null }}</flux:text>
                            </x-table.cell>
                            <x-table.cell>
                                <flux:dropdown>
                                    <flux:button icon-trailing="chevron-down">Action</flux:button>
                                    <flux:menu>
                                        <flux:modal.trigger name="add-user"
                                                            wire:click="editUser({{ $user->id }})">
                                            <flux:menu.item icon="pencil" class="cursor-pointer">Edit</flux:button>
                                        </flux:modal.trigger>

                                        <flux:modal.trigger name="edit-roles"
                                                            wire:click="$dispatch('loadUser', { id: {{ $user->id }} })">
                                            <flux:menu.item icon="academic-cap" class="cursor-pointer">Update Role
                                            </flux:menu.item>
                                        </flux:modal.trigger>

                                        <flux:separator/>

                                        <flux:modal.trigger name="switch-modal"
                                                            wire:click="$dispatch('switchStatus', {id: {{ $user->id }}, model: 'User', attribute: 'is_active' })">
                                            @if($user->is_active)
                                                <flux:menu.item icon="lock-closed"
                                                                variant="danger">Block
                                                </flux:menu.item>
                                            @else
                                                <flux:menu.item icon="lock-open"
                                                                class="hover:!bg-emerald-500/30 hover:!text-emerald-500">
                                                    Activate
                                                </flux:menu.item>
                                            @endif
                                        </flux:modal.trigger>

                                        <flux:modal.trigger variant="danger" name="delete-modal"
                                                            x-data="{ userId: {{  json_encode([$user->id]) }} }"
                                                            wire:click="$dispatch('bulkDelete', {recordIds: userId, model: 'User' })"
                                        >
                                            <flux:menu.item variant="danger" class="cursor-pointer" icon="user-minus">
                                                {{  __('Delete') }}
                                            </flux:menu.item>
                                        </flux:modal.trigger>
                                    </flux:menu>
                                </flux:dropdown>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-zinc-500 bg-zinc-50 dark:bg-zinc-700">There
                                are no
                                users with that name!
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-table.index>
            <div
                class="flex flex-wrap items-center justify-between py-4 space-y-4 flex-column md:flex-row md:space-y-0">
                <div wire:model.live="perPage" class="flex gap-2 items-center">
                    <label for="perPage">per Page:</label>
                    <select id="perPage"
                            class="border px-2 py-1 border-zinc-300 dark:border-zinc-500 dark:bg-zinc-700 rounded">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div>
                    {{ $users->links('simple-pagination', data: ['scrollTo' => false]) }}
                </div>
            </div>
        </div>
    </div>

    @livewire('users.create')
    @livewire('delete-modal')
</section>
