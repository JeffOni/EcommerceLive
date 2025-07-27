<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 dark:text-cream-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-cream-50 via-secondary-50 to-primary-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg border border-secondary-200/50">
                <x-welcome />
            </div>
        </div>
    </div>
</x-app-layout>