@props([
    'title' => '',
    'value' => '0',
    'subtitle' => '',
    'icon' => 'icon-[tabler--chart-bar]',
    'color' => 'primary',
])

@php
    $colors = [
        'primary' => 'border-l-primary bg-primary/5 text-primary',
        'secondary' => 'border-l-secondary bg-secondary/5 text-secondary',
        'success' => 'border-l-success bg-success/5 text-success',
        'warning' => 'border-l-warning bg-warning/5 text-warning',
        'error' => 'border-l-error bg-error/5 text-error',
        'info' => 'border-l-info bg-info/5 text-info',
        'accent' => 'border-l-accent bg-accent/5 text-accent',
    ];

    $colorSet = $colors[$color] ?? $colors['primary'];
    // Extract just the border + bg classes (first two), and the text-color class (last one)
    $parts = explode(' ', $colorSet);
    $borderBg = $parts[0] . ' ' . $parts[1];
    $iconColor = $parts[2];
@endphp

<div
    {{ $attributes->merge([
        'class' => "rounded-xl border border-base-200 border-l-4 $borderBg bg-base-100 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all",
    ]) }}>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-base-content/60 mb-1">{{ $title }}</p>
            <h3 class="text-2xl font-bold text-base-content mb-1">{{ $value }}</h3>
            @if ($subtitle)
                <p class="text-xs text-base-content/50">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="w-10 h-10 rounded-xl {{ $borderBg }} flex items-center justify-center">
            <span class="{{ $icon }} w-5 h-5 {{ $iconColor }}"></span>
        </div>
    </div>
    @if ($slot->isNotEmpty())
        <div class="mt-3 pt-3 border-t border-base-200/50">
            {{ $slot }}
        </div>
    @endif
</div>
