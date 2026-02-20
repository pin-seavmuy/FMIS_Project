@props([
    'type' => 'neutral', // primary, secondary, success, error, warning, info, neutral
    'variant' => 'soft',  // soft, solid, outline
    'className' => '',
])

@php
    $typeClasses = [
        'primary' => 'badge-primary',
        'secondary' => 'badge-secondary',
        'success' => 'badge-success',
        'error' => 'badge-error',
        'warning' => 'badge-warning',
        'info' => 'badge-info',
        'neutral' => 'badge-neutral',
    ];

    $variantClasses = [
        'soft' => 'badge-soft',
        'outline' => 'badge-outline',
        'solid' => '', // Default solid is just the base badge-color
    ];

    $colorClass = $typeClasses[$type] ?? $typeClasses['neutral'];
    $styleClass = $variantClasses[$variant] ?? $variantClasses['soft'];
@endphp

<span {{ $attributes->merge(['class' => "badge {$colorClass} {$styleClass} {$className}"]) }}>
    {{ $slot }}
</span>
