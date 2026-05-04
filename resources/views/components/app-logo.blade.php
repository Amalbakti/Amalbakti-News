@props([
    'sidebar' => false,
])

@if($sidebar)

    <a href="{{ route('dashboard') }}" class="flex align-center gap-x-2">
        <img src="{{ asset('images/Creative_1.png') }}" alt=" Creativ News Logo" {{ $attributes->merge(['class' => 'h-16 w-16']) }} />Creativ
        <span class=" font-bold text-orange-600 ">News</span>
    </a>
    
    
@else
    <img src="{{ asset('images/favicon.ico') }}" alt=" Creativ News Logo" {{ $attributes->merge(['class' => 'h-15 w-15 mx-auto']) }} />
    <span class="text-md font-bold flex align-center">Creativ News</span>
    
@endif


<!-- {{-- @if($sidebar)
    <flux:sidebar.brand name="Laravel Starter Kit" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Laravel Starter Kit" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:brand>
@endif --}} -->
