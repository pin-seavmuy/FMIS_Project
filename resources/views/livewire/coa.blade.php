<div>
    <div class="p-8">
        {{-- Header --}}
        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-3xl font-bold text-base-content mb-1">{{ __('Chart of Accounts') }}</h1>
                <p class="text-sm text-base-content/70">{{ __('Manage your financial structure') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <x-search wire:model.live.debounce.300ms="search" placeholder="{{ __('Search') }}" />
                <x-btn variant="primary" icon="icon-[tabler--plus]" wire:click="openModal">
                    {{ __('Add Account') }}
                </x-btn>
            </div>
        </div>

        {{-- Success Alert --}}
        <div id="coa-success-alert"
            class="fixed top-6 right-6 z-[9999] hidden items-center gap-2.5 px-6 py-4 bg-base-100 border-l-4 border-green-500 rounded-lg shadow-xl"
            style="display:none">
            <span class="icon-[tabler--circle-check] w-5 h-5 text-green-600"></span>
            <span id="coa-success-msg" class="text-sm font-semibold text-green-700"></span>
            <button onclick="document.getElementById('coa-success-alert').style.display='none'"
                class="ml-auto text-base-content/60 hover:text-base-content transition-colors">
                <span class="icon-[tabler--x] w-5 h-5"></span>
            </button>
        </div>

        {{-- AG Grid Table --}}
        <x-ag-grid id="coaGrid" :rowData="$accounts" :columnDefs="$columnDefs" updateEvent="coa-updated" height="600px" />
    </div>

    {{-- Create / Edit Account Modal --}}
    <x-modal id="coaFormModal" :title="$isEditMode ? 'Edit Account' : 'Create New Account'" icon="icon-[tabler--list-tree]">
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-base-content/70 mb-1.5">Code</label>
                <input type="text" wire:model="code"
                    class="w-full px-3.5 py-2.5 border border-base-300 rounded-lg bg-base-100 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors"
                    placeholder="e.g. 1001" />
                @error('code')
                    <span class="block text-error text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-base-content/70 mb-1.5">Type</label>
                <select wire:model="type"
                    class="w-full px-3.5 py-2.5 border border-base-300 rounded-lg bg-base-100 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors appearance-none">
                    <option value="asset">Asset</option>
                    <option value="liability">Liability</option>
                    <option value="equity">Equity</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
                @error('type')
                    <span class="block text-error text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-base-content/70 mb-1.5">Name</label>
            <input type="text" wire:model="name"
                class="w-full px-3.5 py-2.5 border border-base-300 rounded-lg bg-base-100 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors"
                placeholder="Account Name" />
            @error('name')
                <span class="block text-error text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-base-content/70 mb-1.5">Classification</label>
            <select wire:model="classification"
                class="w-full px-3.5 py-2.5 border border-base-300 rounded-lg bg-base-100 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors appearance-none">
                <option value="">Select Classification</option>
                @foreach ($classifications as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="label cursor-pointer justify-start gap-4">
                <span class="label-text font-medium text-base-content/70">Active Status</span>
                <input type="checkbox" wire:model="is_active" class="checkbox checkbox-primary" />
            </label>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-base-content/70 mb-1.5">Description</label>
            <textarea wire:model="description"
                class="w-full px-3.5 py-2.5 border border-base-300 rounded-lg bg-base-100 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors h-24"
                placeholder="Optional description..."></textarea>
        </div>

        <x-slot:footer>
            <x-btn variant="cancel"
                onclick="document.getElementById('coaFormModal').style.display='none'">Cancel</x-btn>
            <x-btn variant="primary" icon="icon-[tabler--check]" :loading="true" loadingTarget="store, update"
                loadingText="Saving..." wire:click="{{ $isEditMode ? 'update' : 'store' }}"
                wire:loading.attr="disabled">
                {{ $isEditMode ? 'Update' : 'Create' }}
            </x-btn>
        </x-slot:footer>
    </x-modal>

    {{-- View Account Modal --}}
    <x-modal id="viewAccountModal" title="Account Details" icon="icon-[tabler--eye]">
        @if ($viewAccount)
            <div class="flex justify-between items-center py-3 border-b border-base-200 last:border-0">
                <span class="text-sm font-medium text-base-content/70">Code</span>
                <span class="text-sm font-semibold text-base-content">{{ $viewAccount->code }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-base-200 last:border-0">
                <span class="text-sm font-medium text-base-content/70">Name</span>
                <span class="text-sm font-semibold text-base-content">{{ $viewAccount->name }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-base-200 last:border-0">
                <span class="text-sm font-medium text-base-content/70">Type</span>
                <span class="text-sm font-semibold text-base-content capitalize">{{ $viewAccount->type }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-base-200 last:border-0">
                <span class="text-sm font-medium text-base-content/70">Classification</span>
                <span class="text-sm font-semibold text-base-content">{{ $viewAccount->classification ?? '—' }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-base-200 last:border-0">
                <span class="text-sm font-medium text-base-content/70">Status</span>
                <span
                    class="badge {{ $viewAccount->is_active ? 'badge-success' : 'badge-error' }} badge-sm">{{ $viewAccount->is_active ? 'Active' : 'Inactive' }}</span>
            </div>
            @if ($viewAccount->description)
                <div class="py-3">
                    <span class="text-sm font-medium text-base-content/70 block mb-1">Description</span>
                    <p class="text-sm text-base-content/80">{{ $viewAccount->description }}</p>
                </div>
            @endif
        @endif

        <x-slot:footer>
            <x-btn variant="cancel"
                onclick="document.getElementById('viewAccountModal').style.display='none'">Close</x-btn>
            @if ($viewAccount)
                <x-btn variant="primary" icon="icon-[tabler--pencil]" wire:click="edit({{ $viewAccount->id }})"
                    onclick="document.getElementById('viewAccountModal').style.display='none'">
                    Edit
                </x-btn>
            @endif
        </x-slot:footer>
    </x-modal>

    {{-- Delete Confirmation Modal --}}
    <x-modal id="deleteAccountModal" title="Delete Account" icon="icon-[tabler--alert-triangle]" titleClass="text-red-600">
        <p class="text-base-content/80">Are you sure you want to delete this account? This action cannot be undone.</p>

        <x-slot:footer>
            <x-btn variant="cancel"
                onclick="document.getElementById('deleteAccountModal').style.display='none'">Cancel</x-btn>
            <x-btn variant="danger" icon="icon-[tabler--trash]" wire:click="delete"
                onclick="document.getElementById('deleteAccountModal').style.display='none'">
                Delete
            </x-btn>
        </x-slot:footer>
    </x-modal>

    <script>
        document.addEventListener('livewire:init', () => {
            const showSuccess = (message) => {
                const formModal = document.getElementById('coaFormModal');
                const viewModal = document.getElementById('viewAccountModal');
                const deleteModal = document.getElementById('deleteAccountModal');
                const alert = document.getElementById('coa-success-alert');
                const msg = document.getElementById('coa-success-msg');

                if (formModal) formModal.style.display = 'none';
                if (viewModal) viewModal.style.display = 'none';
                if (deleteModal) deleteModal.style.display = 'none';
                
                if (alert && msg) {
                    msg.textContent = message;
                    alert.style.display = 'flex';
                    setTimeout(() => {
                        // Check if alert still exists before hiding
                        const currentAlert = document.getElementById('coa-success-alert');
                        if (currentAlert) currentAlert.style.display = 'none';
                    }, 4000);
                }
            };

            Livewire.on('open-coa-form-modal', () => {
                const el = document.getElementById('coaFormModal');
                if (el) el.style.display = 'flex';
            });

            Livewire.on('open-view-coa-modal', () => {
                const el = document.getElementById('viewAccountModal');
                if (el) el.style.display = 'flex';
            });

            Livewire.on('open-delete-coa-modal', () => {
                const el = document.getElementById('deleteAccountModal');
                if (el) el.style.display = 'flex';
            });

            Livewire.on('coa-created', () => showSuccess('Account created successfully!'));
            Livewire.on('coa-updated-msg', () => showSuccess('Account updated successfully!'));
            Livewire.on('coa-deleted', () => showSuccess('Account deleted successfully!'));
        });
    </script>
</div>
