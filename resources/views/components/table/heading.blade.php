@props(['sortable' => null, 'sortDirection'=>null])

<th
    {{ $attributes->merge(['class' => 'px-4 py-3 bg-zinc-200/60 dark:!bg-zinc-900 dark:!text-white !font-bold'])->only('class') }}
>
    @unless($sortable)
        <span
                class="text-left text-sm leading-4 font-bold text-zinc-500 dark:text-zinc-50 uppercase tracking-wider">{{ $slot }}</span>
    @else
        <button
                {{ $attributes->except('class') }} class="flex group items-center space-x-1 text-left text-sm uppercase leading-4 font-medium text-zinc-800 dark:text-zinc-50">
            <span>{{ $slot }}</span>

            <span>
                @if($sortDirection == 'asc')
                    <svg class="w-3 h-3"
                         xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m5 15 7-7 7 7"/>
                    </svg>
                @elseif($sortDirection == 'desc')
                    <svg class="w-3 h-3"
                         xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m19 9-7 7-7-7"/>
                    </svg>
                @else
                    <svg
                            class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 w-4 h-4"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                         <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                               d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                     </svg>

                @endif
            </span>
        </button>
    @endif
</th>
