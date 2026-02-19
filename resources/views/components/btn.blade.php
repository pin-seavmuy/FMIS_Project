@props([
    'variant' => 'primary',
    'icon' => null,
    'size' => 'md',
    'loading' => false,
    'loadingText' => 'Loading...',
    'loadingTarget' => null,
])

@php
    $variants = [
        'primary' => 'btn-primary shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all w-full sm:w-auto',
        'secondary' => 'btn-neutral',
        'danger' => 'btn-error text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all',
        'cancel' => 'btn-ghost text-base-content/70  text-white ',
        'ghost' => 'btn-ghost',
    ];

    $sizes = [
        'sm' => 'btn-sm',
        'md' => 'btn-md',
        'lg' => 'btn-lg',
    ];

    $classes =
        'btn ' .
        ($variants[$variant] ?? $variants['primary']) .
        ' ' .
        ($sizes[$size] ?? $sizes['md']) .
        ' gap-2 normal-case font-semibold rounded-xl border-none';
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    @if ($loading && $loadingTarget)
        <span wire:loading.remove wire:target="{{ $loadingTarget }}" class="btn-content">
            @if ($icon)
                <span class="{{ $icon }}" style="width:16px;height:16px"></span>
            @endif
            {{ $slot }}
        </span>
        <span wire:loading.flex wire:target="{{ $loadingTarget }}" class="btn-content">
            <span class="icon-[tabler--loader-2] animate-spin" style="width:16px;height:16px"></span>
            {{ $loadingText }}
        </span>
    @else
        @if ($icon)
            <span class="{{ $icon }}" style="width:16px;height:16px"></span>
        @endif
        {{ $slot }}
    @endif
</button>
