<div>
    <div class="p-8">
        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-3xl font-bold text-base-content mb-1">Journal Entries</h1>
                <p class="text-sm text-base-content/70">Manage and record financial transactions</p>
            </div>
            <button wire:click="create" class="btn btn-primary">
                <span class="icon-[tabler--plus] w-5 h-5"></span>
                New Entry
            </button>
        </div>

        <div class="card bg-base-100 shadow-xl border border-base-200">
            <div class="card-body p-0">
                <x-ag-grid 
                    id="journalEntriesGrid"
                    :column-defs="[
                        ['field' => 'date', 'headerName' => 'Date', 'sortable' => true, 'filter' => true],
                        ['field' => 'reference', 'headerName' => 'Reference', 'sortable' => true, 'filter' => true],
                        ['field' => 'description', 'headerName' => 'Description', 'flex' => 1, 'sortable' => true, 'filter' => true],
                        ['field' => 'status', 'headerName' => 'Status', 'width' => 120, 'cellClass' => 'text-center'],
                        ['field' => 'total_amount', 'headerName' => 'Amount', 'type' => 'rightAligned', 'valueFormatter' => 'value.toLocaleString(\'en-US\', {style: \'currency\', currency: \'USD\'})'],
                        ['field' => 'created_by', 'headerName' => 'Created By'],
                        ['field' => 'actions', 'headerName' => 'Actions', 'cellRenderer' => 'FmisRenderers.journalActions', 'sortable' => false, 'filter' => false, 'width' => 100],
                    ]"
                    :row-data="$entries"
                    class="h-[600px]"
                />
            </div>
        </div>
    </div>

    {{-- Journal Entry Form Modal --}}
    <x-modal id="journalFormModal" title="Journal Entry" icon="icon-[tabler--file-invoice]" maxWidth="1000px">
        <livewire:journal-entry.journal-entry-form />
        {{-- Footer removed as per user request --}}
    </x-modal>

    {{-- Success Alert --}}
    <div id="entry-success-alert"
        class="fixed top-6 right-6 z-[99999] items-center gap-2.5 px-6 py-4 bg-base-100 border-l-4 border-green-500 rounded-lg shadow-xl animate-[slideInRight_0.3s_ease]"
        style="display:none">
        <span class="icon-[tabler--circle-check] w-6 h-6 text-green-600"></span>
        <span id="entry-success-msg" class="text-sm font-semibold text-green-700"></span>
        <button onclick="document.getElementById('entry-success-alert').style.display='none'"
            class="ml-auto text-base-content/60 hover:text-base-content transition-colors">
            <span class="icon-[tabler--x] w-5 h-5"></span>
        </button>
    </div>

    {{-- Error Alert --}}
    <div id="entry-error-alert"
        class="fixed top-20 right-6 z-[99999] items-center gap-2.5 px-6 py-4 bg-base-100 border-l-4 border-error rounded-lg shadow-xl animate-[slideInRight_0.3s_ease]"
        style="display:none">
        <span class="icon-[tabler--alert-circle] w-6 h-6 text-error"></span>
        <span id="entry-error-msg" class="text-sm font-semibold text-error"></span>
        <button onclick="document.getElementById('entry-error-alert').style.display='none'"
            class="ml-auto text-base-content/60 hover:text-base-content transition-colors">
            <span class="icon-[tabler--x] w-5 h-5"></span>
        </button>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            const showSuccess = (message) => {
                const alert = document.getElementById('entry-success-alert');
                const msg = document.getElementById('entry-success-msg');
                
                if (alert && msg) {
                    msg.textContent = message;
                    alert.style.display = 'flex';
                    setTimeout(() => {
                        if (alert) alert.style.display = 'none';
                    }, 4000);
                }
            };

            const showError = (message) => {
                const alert = document.getElementById('entry-error-alert');
                const msg = document.getElementById('entry-error-msg');
                
                if (alert && msg) {
                    msg.textContent = message;
                    alert.style.display = 'flex';
                    setTimeout(() => {
                        if (alert) alert.style.display = 'none';
                    }, 5000);
                }
            };

            Livewire.on('open-journal-form-modal', () => {
                const el = document.getElementById('journalFormModal');
                if (el) el.style.display = 'flex';
            });

            Livewire.on('close-journal-form-modal', () => {
                const el = document.getElementById('journalFormModal');
                if (el) el.style.display = 'none';
            });

            Livewire.on('journal-entry-saved-msg', () => showSuccess('Journal Entry saved successfully!'));
            Livewire.on('journal-entry-deleted', () => showSuccess('Journal Entry deleted successfully!'));
            Livewire.on('journal-entry-posted', () => showSuccess('Journal Entry posted successfully!'));
            
            // Error listeners
            Livewire.on('show-error', (data) => {
                const msg = typeof data === 'string' ? data : (data.message || 'An error occurred');
                showError(msg);
            });
            Livewire.on('error', (message) => showError(message)); 
        });
    </script>
</div>
