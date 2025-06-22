<div class="align-middle min-w-full overflow-x-auto shadow overflow-auto sm:rounded-lg max-h-[500px]">
    <table class="min-w-full border-collapse">
        <thead  class="sticky top-0 z-10">
        <tr>
            {{ $head }}
        </tr>
        </thead>

        <tbody class="bg-white dark:bg-zinc-900 text-sm">
        {{ $body }}
        </tbody>

        <tfoot class="text-base">
        {{ $slot }}
        </tfoot>
    </table>
</div>
