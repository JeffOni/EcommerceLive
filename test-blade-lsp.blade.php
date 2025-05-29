{{-- Test file for Laravel Blade LSP functionality --}}
<div>
    <h1 class="text-2xl font-bold">{{ $title }}</h1>

    @if ($showContent)
        <p>{{ $content }}</p>
    @endif

    @livewire('test-component')

    <x-button wire:click="handleClick">
        Click me
    </x-button>

    <form wire:submit.prevent="submit">
        <input wire:model="name" type="text" placeholder="Name">
        <button type="submit">Submit</button>
    </form>
</div>
