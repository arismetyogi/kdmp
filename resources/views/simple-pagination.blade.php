@php
    if (! isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = ($scrollTo !== false)
        ? <<<JS
           (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
        JS
        : '';
@endphp

<div>
    @if ($paginator->hasPages())

        <div class="flex items-end my-2">

            <div class="hidden md:flex-1 md:flex md:items-center md:justify-between px-4">
                <div>
                    <p class="text-sm text-zinc-700 leading-5 dark:text-zinc-400">
                        <span>{!! __('Showing') !!}</span>
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        <span>{!! __('to') !!}</span>
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        <span>{!! __('of') !!}</span>
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        <span>{!! __('results') !!}</span>
                    </p>
                </div>
            </div>

            @if ( ! $paginator->onFirstPage())
                {{-- First Page Link --}}
                <a
                    class="relative inline-flex items-center mx-1 px-2 py-1 text-sm font-medium text-zinc-500 bg-white border border-zinc-300 leading-5 rounded-md dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-300 dark:focus:border-blue-700 dark:active:bg-zinc-700 dark:active:text-zinc-300 cursor-pointer"
                    wire:click="gotoPage(1)"
                >
                    <<
                </a>
                @if($paginator->currentPage() > 2)
                    {{-- Previous Page Link --}}
                    <a
                        class="relative inline-flex items-center mx-1 px-2 py-1 text-sm font-medium text-zinc-500 bg-white border border-zinc-300 leading-5 rounded-md dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-300 dark:focus:border-blue-700 dark:active:bg-zinc-700 dark:active:text-zinc-300 cursor-pointer"
                        wire:click="previousPage"
                    >
                        <
                    </a>
                @endif
            @endif

            <!-- Pagination Elements -->
            @foreach ($elements as $element)
                <!-- Array Of Links -->
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <!--  Use three dots when current page is greater than 3.  -->
                        @if ($paginator->currentPage() > 3 && $page === 2)
                            <div class="border-blue-400 mx-1">
                                <span>.</span>
                                <span>.</span>
                                <span>.</span>
                            </div>
                        @endif

                        <!--  Show active page two pages before and after it.  -->
                        @if ($page == $paginator->currentPage())
                            <span
                                class="relative inline-flex items-center px-2 py-1 -ml-px text-sm font-medium text-zinc-400 bg-white border border-accent leading-5 dark:bg-zinc-800 dark:border-accent cursor-default rounded-md">{{ $page }}</span>
                        @elseif ($page === $paginator->currentPage() + 1 || $page === $paginator->currentPage() + 2 || $page === $paginator->currentPage() - 1 || $page === $paginator->currentPage() - 2)
                            <a class="items-center mx-1 px-2 py-1 text-sm font-medium text-zinc-500 bg-white border border-zinc-300 leading-5 rounded-md dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-300 dark:focus:border-blue-700 dark:active:bg-zinc-700 dark:active:text-zinc-300 cursor-pointer"
                               wire:click="gotoPage({{$page}})">{{ $page }}</a>
                        @endif

                        <!--  Use three dots when current page is away from end.  -->
                        @if ($paginator->currentPage() < $paginator->lastPage() - 2  && $page === $paginator->lastPage() - 1)
                            <div class="bord-blue-800 mx-1 dark:text-zinc-500">
                                <span>.</span>
                                <span>.</span>
                                <span>.</span>
                            </div>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                @if($paginator->lastPage() - $paginator->currentPage() >= 2)
                    <a class="items-center mx-1 px-2 py-1 text-sm font-medium text-zinc-500 bg-white border border-zinc-300 leading-5 rounded-md dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-300 dark:focus:border-blue-700 dark:active:bg-zinc-700 dark:active:text-zinc-300 cursor-pointer"
                       wire:click="nextPage"
                       rel="next">
                        >
                    </a>
                @endif
                <a
                    class="mx-1 items-center px-2 py-1 text-sm font-medium text-zinc-500 bg-white border border-zinc-300 leading-5 rounded-md dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-300 dark:focus:border-blue-700 dark:active:bg-zinc-700 dark:active:text-zinc-300 cursor-pointer"
                    wire:click="gotoPage({{ $paginator->lastPage() }})"
                >
                    >>
                </a>
            @endif
        </div>
    @endif
</div>
