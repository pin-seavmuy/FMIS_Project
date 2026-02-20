@props([
    'id',
    'type' => 'success', // success, error, info, warning
    'position' => 'top-6 right-6',
    'autoHide' => 4000
])

@php
    $typeClasses = [
        'success' => 'border-green-500 text-green-700 bg-base-100',
        'error' => 'border-error text-error bg-base-100',
        'info' => 'border-info text-info bg-base-100',
        'warning' => 'border-warning text-warning bg-base-100',
    ];

    $iconClasses = [
        'success' => 'icon-[tabler--circle-check] text-green-600',
        'error' => 'icon-[tabler--alert-circle] text-error',
        'info' => 'icon-[tabler--info-circle] text-info',
        'warning' => 'icon-[tabler--alert-triangle] text-warning',
    ];

    $currentClass = $typeClasses[$type] ?? $typeClasses['success'];
    $currentIcon = $iconClasses[$type] ?? $iconClasses['success'];
@endphp

<div id="{{ $id }}"
    class="fixed {{ $position }} z-[99999] items-center gap-2.5 px-6 py-4 border-l-4 rounded-lg shadow-xl animate-[slideInRight_0.3s_ease] {{ $currentClass }}"
    style="display:none">
    
    <span class="{{ $currentIcon }} w-6 h-6"></span>
    
    <span id="{{ $id }}-msg" class="text-sm font-semibold max-w-[300px]"></span>
    
    <button onclick="document.getElementById('{{ $id }}').style.display='none'"
        class="ml-auto text-base-content/40 hover:text-base-content transition-colors">
        <span class="icon-[tabler--x] w-5 h-5"></span>
    </button>
</div>

<script>
    if (typeof window.fmisAlerts === 'undefined') {
        window.fmisAlerts = {
            show: (id, message, duration = {{ $autoHide }}) => {
                const el = document.getElementById(id);
                const msgEl = document.getElementById(id + '-msg');
                if (el && msgEl) {
                    msgEl.textContent = message;
                    el.style.display = 'flex';
                    if (duration > 0) {
                        setTimeout(() => {
                            el.style.display = 'none';
                        }, duration);
                    }
                }
            }
        };
    }
</script>
