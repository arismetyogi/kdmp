<div class="flex gap-2 items-center">
    <div
        class="w-auto mt-1 text-lg font-bold transition-all duration-300 ease-out brightness-0 md:w-1/6 hover:brightness-100 dark:grayscale dark:brightness-200 dark:contrast-200 dark:hover:grayscale-0 dark:hover:brightness-100 dark:hover:contrast-100">
        <x-app-logo-icon class="size-18 fill-current text-white dark:text-black"/>
    </div>
    <flux:spacer/>
    <span
        class="mb-0.5 truncate leading-tight font-semibold text-accent-content dark:text-zinc-50">{{ config('app.name', 'Laravel') }}</span>
</div>
