<div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
    <table class="min-w-full border-collapse">
        <thead>
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
