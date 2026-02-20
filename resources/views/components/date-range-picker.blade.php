@props([
    'start' => null,
    'end' => null,
    'label' => 'Select Date Range',
])

<div x-data="dateRangePicker({
    startDate: @entangle($start),
    endDate: @entangle($end)
})" class="relative inline-block w-full max-w-xs">
    
    {{-- Trigger Input --}}
    <div class="relative cursor-pointer" @click="toggleMenu">
        <div class="input input-sm input-bordered flex items-center pr-10 truncate bg-base-100 min-h-[38px]">
            <span class="icon-[tabler--calendar] w-4 h-4 mr-2 opacity-60"></span>
            <span x-text="displayText" class="text-sm"></span>
        </div>
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none opacity-40">
            <span class="icon-[tabler--chevron-down] w-4 h-4"></span>
        </div>
    </div>

    {{-- Dropdown Menu --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.away="open = false"
         class="absolute z-[9999] mt-2 w-[340px] max-w-[90vw] bg-base-100 border border-base-200 rounded-xl shadow-2xl p-4 right-0"
         style="display: none">
        
        <div class="grid grid-cols-2 gap-4">
            {{-- Presets Column --}}
            <div class="flex flex-col gap-1 border-r border-base-200 pr-4">
                <template x-for="preset in presets" :key="preset.id">
                    <button @click="selectPreset(preset)" 
                            class="text-left px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                            :class="activePreset === preset.id ? 'bg-primary text-primary-content' : 'hover:bg-base-200 text-base-content/70'">
                        <span x-text="preset.label"></span>
                    </button>
                </template>
            </div>

            {{-- Custom Range Column --}}
            <div class="flex flex-col gap-3">
                <div class="form-control">
                    <label class="label py-1">
                        <span class="label-text-alt text-base-content/50">From</span>
                    </label>
                    <input type="date" x-model="localStart" class="input input-xs input-bordered w-full" @change="activePreset = 'custom'" />
                </div>
                <div class="form-control">
                    <label class="label py-1">
                        <span class="label-text-alt text-base-content/50">To</span>
                    </label>
                    <input type="date" x-model="localEnd" class="input input-xs input-bordered w-full" @change="activePreset = 'custom'" />
                </div>
                <div class="mt-auto pt-3 flex items-center justify-between gap-2 border-t border-base-200">
                     <button @click="open = false" class="btn btn-ghost btn-xs">Cancel</button>
                     <button @click="apply" class="btn btn-primary btn-xs px-4">Apply</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dateRangePicker', (config) => ({
            open: false,
            activePreset: 'this_month',
            startDate: config.startDate,
            endDate: config.endDate,
            localStart: '',
            localEnd: '',
            displayText: 'Select dates...',

            presets: [
                { id: 'today', label: 'Today' },
                { id: 'yesterday', label: 'Yesterday' },
                { id: 'this_week', label: 'This Week' },
                { id: 'this_month', label: 'This Month' },
                { id: 'this_quarter', label: 'This Quarter' },
                { id: 'this_year', label: 'This Year' },
                { id: 'last_month', label: 'Last Month' },
                { id: 'last_year', label: 'Last Year' },
                { id: 'all_time', label: 'All Time' },
            ],

            init() {
                this.localStart = this.startDate;
                this.localEnd = this.endDate;
                this.updateDisplayText();
            },

            toggleMenu() {
                this.open = !this.open;
            },

            selectPreset(preset) {
                this.activePreset = preset.id;
                const now = new Date();
                let start = new Date();
                let end = new Date();

                switch (preset.id) {
                    case 'today':
                        start = now;
                        end = now;
                        break;
                    case 'yesterday':
                        start.setDate(now.getDate() - 1);
                        end.setDate(now.getDate() - 1);
                        break;
                    case 'this_week':
                        const day = now.getDay();
                        start.setDate(now.getDate() - day + (day === 0 ? -6 : 1)); // Mon
                        end.setDate(start.getDate() + 6); // Sun
                        break;
                    case 'this_month':
                        start = new Date(now.getFullYear(), now.getMonth(), 1);
                        end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                        break;
                    case 'this_quarter':
                        const quarter = Math.floor(now.getMonth() / 3);
                        start = new Date(now.getFullYear(), quarter * 3, 1);
                        end = new Date(now.getFullYear(), (quarter + 1) * 3, 0);
                        break;
                    case 'this_year':
                        start = new Date(now.getFullYear(), 0, 1);
                        end = new Date(now.getFullYear(), 11, 31);
                        break;
                    case 'last_month':
                        start = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                        end = new Date(now.getFullYear(), now.getMonth(), 0);
                        break;
                    case 'last_year':
                        start = new Date(now.getFullYear() - 1, 0, 1);
                        end = new Date(now.getFullYear() - 1, 11, 31);
                        break;
                    case 'all_time':
                        start = new Date(2000, 0, 1); // Or some logical epoch
                        end = new Date(2099, 11, 31);
                        break;
                }

                this.localStart = this.formatDate(start);
                this.localEnd = this.formatDate(end);
            },

            formatDate(date) {
                return date.toISOString().split('T')[0];
            },

            apply() {
                this.startDate = this.localStart;
                this.endDate = this.localEnd;
                this.updateDisplayText();
                this.open = false;
            },

            updateDisplayText() {
                if (!this.localStart || !this.localEnd) {
                    this.displayText = 'Select dates...';
                    return;
                }
                const s = new Date(this.localStart).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
                const e = new Date(this.localEnd).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
                this.displayText = `${s} - ${e}`;
            }
        }));
    });
</script>
