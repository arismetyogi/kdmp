<div class="w-full h-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Form Upload') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Upload Penjamin') }}</flux:subheading>
        <flux:separator variant="subtle"/>
    </div>

    <div class="bg-muted flex w-full max-h-full flex-col gap-6 items-center">
        <div class="p-4 bg-zinc-50 shadow-xl sm:rounded-lg dark:bg-zinc-700/30 max-w-full justify-center items-center mx-auto">
            <flux:subheading size="lg">{{ __('Upload Data Penjamin') }}</flux:subheading>
            <flux:separator/>

            <form id="uploadClaim" wire:submit="uploadClaim" class="flex flex-col gap-6" enctype="multipart/form-data">
                @csrf
                <flux:field class="mt-4">
                    <flux:input.group>
                        <flux:input id="claimFile" name="claimFile" wire:model="claimFile" type="file">
                            <x-slot name="iconTrailing">
                                <flux:button size="sm" variant="subtle" icon="arrow-up-tray" class="-mr-1"/>
                            </x-slot>
                        </flux:input>
                    </flux:input.group>
                    <flux:text>Upload File hanya ekstensi *.xlsx</flux:text>
                    <flux:error name="claimFile"/>
                </flux:field>

                <div class="flex items-center justify-between gap-2">
                    <flux:button :loading="true" type="submit" variant="primary" class="w-full">
                        {{ __('Submit') }}
                    </flux:button>
                    <flux:button variant="filled" class="w-full" href="files/claim_uploads.xlsx" icon:trailing="arrow-up-right"> {{ __('Download Template') }}</flux:button>
                </div>
            </form>
        </div>

        <div class="p-4 overflow-y-auto bg-zinc-50 shadow-xl sm:rounded-lg dark:bg-zinc-700/30 w-full">
            <div wire:model.live="perPage" class="flex gap-2 items-center">
                <label for="perPage">per Page:</label>
                <select id="perPage"
                        class="border px-2 py-1 border-zinc-300 dark:border-zinc-500 dark:bg-zinc-700 rounded">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <x-table.index id="uploadClaimTable">
                <x-slot name="head">
                    <x-table.heading sortable>#</x-table.heading>
                    <x-table.heading sortable>Waktu</x-table.heading>
                    <x-table.heading sortable>Periode</x-table.heading>
                    <x-table.heading sortable>Batch</x-table.heading>
                    <x-table.heading sortable>Unit Bisnis</x-table.heading>
                    <x-table.heading sortable>User</x-table.heading>
                    <x-table.heading sortable>Action</x-table.heading>
                </x-slot>
                <x-slot name="body">
                @forelse($claimUploads as $uploadData)
                    <x-table.row :even="$loop->even">
                        <x-table.cell>{{ $loop->iteration }}</x-table.cell>
                        <x-table.cell>{{ \Carbon\Carbon::parse($uploadData->created_at)->format('d-M-Y h:m') }}</x-table.cell>
                        <x-table.cell>{{ \Carbon\Carbon::parse($uploadData->period)->format('d-M-Y') }}</x-table.cell>
                        <x-table.cell>{{ $uploadData->batch_id }}</x-table.cell>
                        <x-table.cell>{{ $uploadData->unitBisnis->name }}</x-table.cell>
                        <x-table.cell>{{ $uploadData->user->name }}</x-table.cell>
                        <x-table.cell>
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-fill align-middle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    {{-- <li><a href="/dashboard/debitur/{{ Crypt::encryptString($user->id) }}/edit" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li> --}}
                                    <li>
                                        <form action="/dashboard/upload/delete_upload/{{ $uploadData->batch_id }}" method="post" class="d-inline">
                                            @method('put')
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $uploadData->batch_id }}"/>
                                            {{-- <button class="badge bg-danger border-0" onclick="return confirm('Yakin hapus data ini ?')"><span data-feather="x-circle"></span></button> --}}
                                            <button class="dropdown-item remove-item-btn" onclick="return confirm('Yakin Hapus Data ini ?')">
                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Hapus
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                    @empty
                        <tr>
                            <x-table.cell colspan="10" class="text-center py-4 text-zinc-500 bg-zinc-50 dark:bg-zinc-700">There are data found!
                            </x-table.cell>
                        </tr>
                    @endforelse
                </x-slot>
            </x-table.index>
            <div class="flex flex-wrap items-center justify-between py-4 space-y-4 flex-column md:flex-row md:space-y-0">
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
                    {{ $claims->links('simple-pagination', data: ['scrollTo' => false]) }}
                </div>
            </div>
        </div>
    </div>
</div>
