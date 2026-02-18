@props([
    'type' => null,
    'icon' => null,
    'tooltip' => null,
])

@php
    $presets = [
        'view' => ['icon' => 'tabler--eye', 'color' => 'text-blue-500 hover:bg-blue-500/10', 'tooltip' => 'View'],
        'edit' => ['icon' => 'tabler--pencil', 'color' => 'text-amber-500 hover:bg-amber-500/10', 'tooltip' => 'Edit'],
        'delete' => ['icon' => 'tabler--trash', 'color' => 'text-red-500 hover:bg-red-500/10', 'tooltip' => 'Delete'],
        'download' => [
            'icon' => 'tabler--download',
            'color' => 'text-green-500 hover:bg-green-500/10',
            'tooltip' => 'Download',
        ],
    ];

    $preset = $presets[$type] ?? null;
    $iconName = $icon ?? ($preset['icon'] ?? 'tabler--dots');
    $colorClass = $preset['color'] ?? 'text-base-content/60 hover:bg-base-200';
    $title = $tooltip ?? ($preset['tooltip'] ?? '');
@endphp

<button
    {{ $attributes->merge([
        'class' =>
            'inline-flex items-center justify-center p-1.5 border-none bg-transparent cursor-pointer rounded-lg transition-all duration-200 hover:-translate-y-0.5 active:scale-90 ' .
            $colorClass,
        'title' => $title,
    ]) }}>
    <span class="icon-[{{ $iconName }}] w-[18px] h-[18px] block"></span>
</button>
