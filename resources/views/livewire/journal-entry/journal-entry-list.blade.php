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
                        ['field' => 'actions', 'headerName' => 'Actions', 'cellRenderer' => 'FmisRenderers.journalActions', 'sortable' => false, 'filter' => false, 'width' => 180],
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

    <x-alert id="entry-success-alert" type="success" />
    <x-alert id="entry-error-alert" type="error" position="top-20 right-6" />

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-journal-form-modal', () => {
                const el = document.getElementById('journalFormModal');
                if (el) el.style.display = 'flex';
            });

            Livewire.on('close-journal-form-modal', () => {
                const el = document.getElementById('journalFormModal');
                if (el) el.style.display = 'none';
            });

            Livewire.on('open-delete-modal', () => {
                const el = document.getElementById('deleteEntryModal');
                if (el) el.style.display = 'flex';
            });

            Livewire.on('open-post-modal', () => {
                const el = document.getElementById('postEntryModal');
                if (el) el.style.display = 'flex';
            });

            Livewire.on('journal-entry-saved-msg', () => {
                const el = document.getElementById('journalFormModal');
                if (el) el.style.display = 'none';
                fmisAlerts.show('entry-success-alert', 'Journal Entry saved successfully!');
            });
            Livewire.on('journal-entry-deleted', () => {
                const el = document.getElementById('deleteEntryModal');
                if (el) el.style.display = 'none';
                fmisAlerts.show('entry-success-alert', 'Journal Entry deleted successfully!');
            });
            Livewire.on('journal-entry-posted', () => {
                const el = document.getElementById('postEntryModal');
                if (el) el.style.display = 'none';
                fmisAlerts.show('entry-success-alert', 'Journal Entry posted successfully!');
            });
            
            // Error listeners
            Livewire.on('show-error', (data) => {
                const msg = typeof data === 'string' ? data : (data.message || 'An error occurred');
                fmisAlerts.show('entry-error-alert', msg);
            });
            Livewire.on('error', (message) => fmisAlerts.show('entry-error-alert', message)); 
        });
    </script>
</div>
