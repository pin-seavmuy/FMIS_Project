@props(['id', 'title' => '', 'icon' => 'icon-[tabler--layout-grid]', 'titleClass' => '', 'maxWidth' => '500px'])

<div id="{{ $id }}"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-9000 animate-[fadeIn_0.2s_ease]"
    style="display:none" onclick="if(event.target===this)this.style.display='none'">
    <div class="bg-base-100 border border-base-200 rounded-2xl w-full mx-4 shadow-2xl animate-[slideUp_0.3s_ease]"
        style="max-width: {{ $maxWidth }}">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-base-200">
            <h3 class="flex items-center gap-2 text-lg font-semibold text-base-content m-0 {{ $titleClass }}">
                @if ($icon)
                    <span class="{{ $icon }} w-5 h-5"></span>
                @endif
                {{ $title }}
            </h3>
            <button onclick="document.getElementById('{{ $id }}').style.display='none'"
                class="bg-transparent border-none text-base-content/40 hover:text-base-content text-2xl cursor-pointer p-1 leading-none transition-colors duration-200">
                &times;
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5 flex flex-col gap-4">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        @if (isset($footer))
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-base-200">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

@pushOnce('styles')
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>
@endPushOnce
