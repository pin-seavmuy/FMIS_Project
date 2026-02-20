@props([
    'id' => 'fmis-confirm-modal',
])

<div id="{{ $id }}" 
    x-data="{ 
        open: false, 
        title: '', 
        message: '', 
        type: 'danger', 
        confirmText: 'Confirm', 
        cancelText: 'Cancel', 
        onConfirm: null,
        
        show(options) {
            this.title = options.title || 'Are you sure?';
            this.message = options.message || '';
            this.type = options.type || 'danger';
            this.confirmText = options.confirmText || 'Confirm';
            this.cancelText = options.cancelText || 'Cancel';
            this.onConfirm = options.onConfirm || null;
            this.open = true;
        },
        
        confirm() {
            if (this.onConfirm) this.onConfirm();
            this.open = false;
        }
    }"
    x-init="window.fmisConfirm = (options) => show(options)"
    x-show="open"
    class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
    style="display: none"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div @click.away="open = false" 
        class="bg-base-100 border border-base-200 rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden animate-[slideUp_0.3s_ease]"
    >
        {{-- Icon & Header --}}
        <div class="p-6 text-center">
            <div class="mb-4 flex justify-center">
                <div class="w-16 h-16 rounded-full flex items-center justify-center"
                    :class="{
                        'bg-red-100 text-red-600 dark:bg-red-500/10': type === 'danger',
                        'bg-amber-100 text-amber-600 dark:bg-amber-500/10': type === 'warning',
                        'bg-blue-100 text-blue-600 dark:bg-blue-500/10': type === 'info'
                    }"
                >
                    <span class="w-8 h-8" 
                        :class="{
                            'icon-[tabler--trash]': type === 'danger',
                            'icon-[tabler--alert-triangle]': type === 'warning',
                            'icon-[tabler--info-circle]': type === 'info'
                        }"
                    ></span>
                </div>
            </div>
            
            <h3 class="text-xl font-bold text-base-content mb-2" x-text="title"></h3>
            <p class="text-sm text-base-content/60 leading-relaxed" x-text="message"></p>
        </div>

        {{-- Footer Buttons --}}
        <div class="bg-base-200/50 p-4 gap-3 flex flex-col sm:flex-row-reverse">
            <button @click="confirm" 
                class="btn flex-1"
                :class="{
                    'btn-error': type === 'danger',
                    'btn-warning': type === 'warning',
                    'btn-info text-white': type === 'info'
                }"
            >
                <span x-text="confirmText"></span>
            </button>
            <button @click="open = false" class="btn btn-ghost flex-1">
                <span x-text="cancelText"></span>
            </button>
        </div>
    </div>
</div>
