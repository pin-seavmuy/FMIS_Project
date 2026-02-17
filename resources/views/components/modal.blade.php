@props(['id', 'title' => '', 'icon' => 'tabler--layout-grid', 'titleClass' => '', 'maxWidth' => '500px'])

<div id="{{ $id }}" class="modal-overlay" style="display:none"
    onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-card" style="max-width: {{ $maxWidth }}">
        {{-- Header --}}
        <div class="modal-header">
            <h3 class="modal-title {{ $titleClass }}">
                @if ($icon)
                    <span class="icon-[{{ $icon }}]" style="width:20px;height:20px"></span>
                @endif
                {{ $title }}
            </h3>
            <button onclick="document.getElementById('{{ $id }}').style.display='none'"
                class="modal-close">&times;</button>
        </div>

        {{-- Body --}}
        <div class="modal-body">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        @if (isset($footer))
            <div class="modal-footer">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
