@props([
    'type' => null,
    'icon' => null,
    'tooltip' => null,
])

@php
    $presets = [
        'view' => ['icon' => 'tabler--eye', 'color' => 'action-view', 'tooltip' => 'View'],
        'edit' => ['icon' => 'tabler--pencil', 'color' => 'action-edit', 'tooltip' => 'Edit'],
        'delete' => ['icon' => 'tabler--trash', 'color' => 'action-delete', 'tooltip' => 'Delete'],
        'download' => ['icon' => 'tabler--download', 'color' => 'action-download', 'tooltip' => 'Download'],
    ];

    $preset = $presets[$type] ?? null;
    $iconName = $icon ?? ($preset['icon'] ?? 'tabler--dots');
    $colorClass = $preset['color'] ?? '';
    $title = $tooltip ?? ($preset['tooltip'] ?? '');
@endphp

<button {{ $attributes->merge(['class' => 'btn-action ' . $colorClass, 'title' => $title]) }}>
    <span class="icon-[{{ $iconName }}]" style="width:18px;height:18px;display:block"></span>
</button>
