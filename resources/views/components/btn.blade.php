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
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'danger' => 'btn-danger',
        'cancel' => 'btn-cancel',
        'ghost' => 'btn-ghost',
    ];

    $sizes = [
        'sm' => 'btn-sm',
        'md' => 'btn-md',
        'lg' => 'btn-lg',
    ];

    $classes = 'btn ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    @if ($loading && $loadingTarget)
        <span wire:loading.remove wire:target="{{ $loadingTarget }}" class="btn-content">
            @if ($icon)
                <span class="icon-[{{ $icon }}]" style="width:16px;height:16px"></span>
            @endif
            {{ $slot }}
        </span>
        <span wire:loading.flex wire:target="{{ $loadingTarget }}" class="btn-content">
            <span class="icon-[tabler--loader-2] animate-spin" style="width:16px;height:16px"></span>
            {{ $loadingText }}
        </span>
    @else
        @if ($icon)
            <span class="icon-[{{ $icon }}]" style="width:16px;height:16px"></span>
        @endif
        {{ $slot }}
    @endif
</button>
