<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>

        @session('success')
        <div
            x-data="{show: true}"
            x-show="show"
            x-init="setTimeout(() => { show = false }, 3000)"
            class="fixed top-5 right-5 bg-green-600/30 text-white text-sm p-4 rounded-lg shadow-lg z-50"
            role="alert">
            <p>{{ $value }}</p>
        </div>
        @endsession('success')

        @session('error')
        <div
            x-data="{show: true}"
            x-show="show"
            x-init="setTimeout(() => { show = false }, 3000)"
            class="fixed top-5 right-5 bg-red-600/30 text-white text-sm p-4 rounded-lg shadow-lg z-50"
            role="alert">
            <p>{{ $value }}</p>
        </div>
        @endsession('success')

        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
