<div>
    <div class="p-8">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-base-content mb-1">Chart of Accounts</h1>
                <p class="text-sm text-base-content/70">Manage your financial structure</p>
            </div>
            <button wire:click="openModal" class="btn btn-primary btn-sm gap-2">
                <span class="icon-[tabler--plus] w-4 h-4"></span>
                Add Account
            </button>
        </div>

        {{-- Success Alert --}}
        <div id="coa-success-alert" class="fixed top-6 right-6 z-[9999] hidden items-center gap-2.5 px-6 py-4 bg-base-100 border-l-4 border-green-500 rounded-lg shadow-xl" style="display:none">
            <span class="icon-[tabler--circle-check] w-5 h-5 text-green-600"></span>
            <span id="coa-success-msg" class="text-sm font-semibold text-green-700"></span>
            <button onclick="document.getElementById('coa-success-alert').style.display='none'" class="ml-auto text-base-content/60 hover:text-base-content transition-colors">
                <span class="icon-[tabler--x] w-5 h-5"></span>
            </button>
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
    <div id="deleteAccountModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[9000] hidden" style="display:none" onclick="if(event.target===this)this.style.display='none'">
        <div class="bg-base-100 border border-base-200 rounded-2xl w-full max-w-lg m-4 shadow-xl">
            <div class="flex items-center justify-between p-5 border-b border-base-200">
                <h3 class="flex items-center gap-2 text-lg font-semibold text-error">
                    <span class="icon-[tabler--alert-triangle] w-5 h-5"></span>
                    Delete Account
                </h3>
                <button onclick="document.getElementById('deleteAccountModal').style.display='none'" class="btn btn-ghost btn-sm btn-circle">
                    <span class="icon-[tabler--x] w-5 h-5"></span>
                </button>
            </div>
            <div class="p-6">
                <p class="text-base-content/80">Are you sure you want to delete this account? This action cannot be undone.</p>
            </div>
            <div class="flex justify-end gap-3 p-4 border-t border-base-200">
                <button onclick="document.getElementById('deleteAccountModal').style.display='none'" class="btn btn-ghost border border-base-200 text-base-content/70 hover:text-base-content">Cancel</button>
                <button wire:click="delete" onclick="document.getElementById('deleteAccountModal').style.display='none'" class="btn btn-error text-white gap-2">
                    <span class="icon-[tabler--trash] w-4 h-4"></span>
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

    {{-- Modal --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[9000]" style="display:flex" wire:click.self="closeModal">
        <div class="bg-base-100 border border-base-200 rounded-2xl w-full max-w-lg m-4 shadow-xl">
            <div class="flex items-center justify-between p-5 border-b border-base-200">
                <h3 class="flex items-center gap-2 text-lg font-semibold text-base-content">
                    <span class="icon-[tabler--list-tree] w-5 h-5"></span>
                    {{ $isEditMode ? 'Edit Account' : 'Create New Account' }}
                </h3>
                <button wire:click="closeModal" class="btn btn-ghost btn-sm btn-circle">
                    <span class="icon-[tabler--x] w-5 h-5"></span>
                </button>
            </div>
            
            <div class="p-6">
                <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-base-content/70 mb-1.5">Code</label>
                            <input type="text" wire:model="code" class="w-full px-3.5 py-2.5 border border-base-300 rounded-lg bg-base-100 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors" placeholder="e.g. 1001" />
                            @error('code') <span class="block text-error text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-base-content/70 mb-1.5">Type</label>
                            <select wire:model="type" class="w-full px-3.5 py-2.5 border border-base-300 rounded-lg bg-base-100 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors appearance-none">
                                <option value="asset">Asset</option>
                                <option value="liability">Liability</option>
                                <option value="equity">Equity</option>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                            </select>
                            @error('type') <span class="block text-error text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-base-content/70 mb-1.5">Name</label>
                        <input type="text" wire:model="name" class="w-full px-3.5 py-2.5 border border-base-300 rounded-lg bg-base-100 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors" placeholder="Account Name" />
                        @error('name') <span class="block text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-base-content/70 mb-1.5">Classification</label>
                        <select wire:model="classification" class="w-full px-3.5 py-2.5 border border-base-300 rounded-lg bg-base-100 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors appearance-none">
                            <option value="">Select Classification</option>
                            @foreach($classifications as $option)
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
                        <textarea wire:model="description" class="w-full px-3.5 py-2.5 border border-base-300 rounded-lg bg-base-100 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors h-24" placeholder="Optional description..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-base-200 mt-6">
                        <button type="button" wire:click="closeModal" class="btn btn-ghost border border-base-200 text-base-content/70 hover:text-base-content">Cancel</button>
                        <button type="submit" class="btn btn-primary text-white gap-2" wire:loading.attr="disabled">
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
