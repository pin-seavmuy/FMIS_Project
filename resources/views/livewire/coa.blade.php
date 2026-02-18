<div>
    <div class="coa-page max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-base-content">Chart of Accounts</h1>
                <p class="text-sm text-base-content/70">Manage your financial structure</p>
            </div>
            <button wire:click="openModal" class="btn btn-primary btn-sm gap-2">
                <span class="icon-[tabler--plus]" style="width:16px;height:16px"></span>
                Add Account
            </button>
        </div>

        {{-- Content --}}
        <div class="card bg-base-100 shadow-sm border border-base-200">
        {{-- Success Alert --}}
        <div id="coa-success-alert" class="users-alert" style="display:none">
            <span class="icon-[tabler--circle-check] users-alert-icon"></span>
            <span id="coa-success-msg"></span>
            <button onclick="document.getElementById('coa-success-alert').style.display='none'" class="users-alert-close">&times;</button>
        </div>

        {{-- Content --}}
        <div class="card bg-base-100 shadow-sm border border-base-200 relative">
             {{-- Loading Skeleton Overlay --}}
             <div wire:loading.flex wire:target="store, update, delete" class="absolute inset-0 z-10 bg-white/80 flex-col items-center justify-center gap-4 backdrop-blur-sm">
                <div class="flex flex-col gap-2 w-full px-8">
                    <div class="skeleton h-8 w-full bg-base-200/50"></div>
                    <div class="skeleton h-8 w-full bg-base-200/50"></div>
                    <div class="skeleton h-8 w-full bg-base-200/50"></div>
                    <div class="skeleton h-8 w-full bg-base-200/50"></div>
                    <div class="skeleton h-8 w-full bg-base-200/50"></div>
                </div>
                <div class="flex items-center gap-2 text-primary font-medium">
                    <span class="icon-[tabler--loader-2] animate-spin w-5 h-5"></span>
                    <span>Updating Chart of Accounts...</span>
                </div>
             </div>

             <x-ag-grid
                id="coaGrid"
                :rowData="$accounts"
                :columnDefs="$columnDefs"
                updateEvent="coa-updated"
                height="600px"
            />
        </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteAccountModal" class="modal-overlay" style="display:none" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title text-red-600">
                    <span class="icon-[tabler--alert-triangle]" style="width:20px;height:20px"></span>
                    Delete Account
                </h3>
                <button onclick="document.getElementById('deleteAccountModal').style.display='none'" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this account? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button onclick="document.getElementById('deleteAccountModal').style.display='none'" class="modal-cancel-btn">Cancel</button>
                <button wire:click="delete" onclick="document.getElementById('deleteAccountModal').style.display='none'" class="users-delete-confirm-btn" style="background-color: #ef4444; color: white; padding: 8px 16px; border-radius: 6px; display: flex; align-items: center; gap: 8px;">
                    <span class="icon-[tabler--trash]" style="width:16px;height:16px"></span>
                    Delete
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            const alert = document.getElementById('coa-success-alert');
            const msg = document.getElementById('coa-success-msg');
            const deleteModal = document.getElementById('deleteAccountModal');

            const showSuccess = (message) => {
                if (alert && msg) {
                    msg.textContent = message;
                    alert.style.display = 'flex';
                    setTimeout(() => { alert.style.display = 'none'; }, 4000);
                }
                if (deleteModal) deleteModal.style.display = 'none';
            };

            Livewire.on('open-delete-coa-modal', () => {
                if (deleteModal) deleteModal.style.display = 'flex';
            });

            Livewire.on('coa-created', () => showSuccess('Account created successfully!'));
            Livewire.on('coa-updated-msg', () => showSuccess('Account updated successfully!'));
            Livewire.on('coa-deleted', () => showSuccess('Account deleted successfully!'));
        });
    </script>
        </div>
    </div>

    {{-- Modal --}}
    @if($isOpen)
    <div class="modal-overlay" style="display:flex" wire:click.self="closeModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title">
                    <span class="icon-[tabler--list-tree]" style="width:20px;height:20px"></span>
                    {{ $isEditMode ? 'Edit Account' : 'Create New Account' }}
                </h3>
                <button wire:click="closeModal" class="modal-close">&times;</button>
            </div>
            
            <div class="modal-body">
                <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="modal-field">
                            <label>Code</label>
                            <input type="text" wire:model="code" class="users-input" placeholder="e.g. 1001" />
                            @error('code') <span class="users-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="modal-field">
                            <label>Type</label>
                            <select wire:model="type" class="users-input">
                                <option value="asset">Asset</option>
                                <option value="liability">Liability</option>
                                <option value="equity">Equity</option>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                            </select>
                            @error('type') <span class="users-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-field mt-4">
                        <label>Name</label>
                        <input type="text" wire:model="name" class="users-input" placeholder="Account Name" />
                        @error('name') <span class="users-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="modal-field mt-4">
                        <label>Classification</label>
                        <select wire:model="classification" class="users-input">
                            <option value="">Select Classification</option>
                            @foreach($classifications as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-field mt-4">
                        <label class="label cursor-pointer justify-start gap-4">
                            <span class="label-text font-medium">Active Status</span>
                            <input type="checkbox" wire:model="is_active" class="checkbox checkbox-primary" />
                        </label>
                    </div>

                    <div class="modal-field mt-4">
                        <label>Description</label>
                        <textarea wire:model="description" class="users-input h-24" placeholder="Optional description..."></textarea>
                    </div>

                    <div class="modal-footer mt-6">
                        <button type="button" wire:click="closeModal" class="modal-cancel-btn">Cancel</button>
                        <button type="submit" class="users-create-btn" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="store, update">
                                {{ $isEditMode ? 'Update' : 'Create' }}
                            </span>
                            <span wire:loading.flex wire:target="store, update" class="items-center gap-2">
                                <span class="icon-[tabler--loader-2] animate-spin"></span>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
