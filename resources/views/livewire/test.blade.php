<div>
    <div class="users-page">
        {{-- Header with Create Button --}}
        <div class="users-header">
            <div>
                <h1 class="users-title">Users</h1>
                <p class="users-subtitle">Manage system users</p>
            </div>
            <div class="flex items-center gap-3">
                <x-search wire:model.live.debounce.300ms="search" placeholder="Search users..." />
                <button wire:click="openCreateModal" class="users-create-btn">
                    <span class="icon-[tabler--plus]" style="width:16px;height:16px"></span>
                    Create User
                </button>
            </div>
        </div>

        {{-- Success Alert (overlay top-right) --}}
        <div id="user-success-alert" class="users-alert" style="display:none">
            <span class="icon-[tabler--circle-check] users-alert-icon"></span>
            <span id="user-success-msg"></span>
            <button onclick="document.getElementById('user-success-alert').style.display='none'" class="users-alert-close">&times;</button>
        </div>

        {{-- AG Grid Table --}}
        <div class="users-grid-card">
            <x-ag-grid
                id="usersGrid"
                :rowData="$datas"
                :columnDefs="$columns"
                updateEvent="users-updated"
            />
        </div>
    </div>

    {{-- User Modal --}}
    <div id="createUserModal" class="modal-overlay" style="display:none" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title">
                    <span class="icon-[tabler--user]" style="width:20px;height:20px"></span>
                    {{ $isEditMode ? 'Edit User' : 'Create New User' }}
                </h3>
                <button onclick="document.getElementById('createUserModal').style.display='none'" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-field">
                    <label>Name</label>
                    <input wire:model="userName" placeholder="Full name" class="users-input" />
                    @error('userName') <span class="users-error">{{ $message }}</span> @enderror
                </div>
                <div class="modal-field">
                    <label>Email</label>
                    <input wire:model="userEmail" placeholder="Email address" class="users-input" />
                    @error('userEmail') <span class="users-error">{{ $message }}</span> @enderror
                </div>
                <div class="modal-field">
                    <label>Password {{ $isEditMode ? '(Leave blank to keep current)' : '' }}</label>
                    <input wire:model="userPassword" type="password" placeholder="Password" class="users-input" />
                    @error('userPassword') <span class="users-error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="document.getElementById('createUserModal').style.display='none'" class="modal-cancel-btn">Cancel</button>
                <button wire:click="{{ $isEditMode ? 'updateUser' : 'createUser' }}" class="users-create-btn" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="createUser, updateUser" class="flex items-center gap-2">
                        <span class="icon-[tabler--check]" style="width:16px;height:16px"></span>
                        {{ $isEditMode ? 'Update' : 'Create' }}
                    </span>
                    <span wire:loading.flex wire:target="createUser, updateUser" class="items-center gap-2">
                        <span class="icon-[tabler--loader-2] animate-spin" style="width:16px;height:16px"></span>
                        Saving...
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteUserModal" class="modal-overlay" style="display:none" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title text-red-600">
                    <span class="icon-[tabler--alert-triangle]" style="width:20px;height:20px"></span>
                    Delete User
                </h3>
                <button onclick="document.getElementById('deleteUserModal').style.display='none'" class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button onclick="document.getElementById('deleteUserModal').style.display='none'" class="modal-cancel-btn">Cancel</button>
                <button wire:click="deleteUser({{ $userId }})" onclick="document.getElementById('deleteUserModal').style.display='none'" class="users-delete-confirm-btn" style="background-color: #ef4444; color: white; padding: 8px 16px; border-radius: 6px; display: flex; align-items: center; gap: 8px;">
                    <span class="icon-[tabler--trash]" style="width:16px;height:16px"></span>
                    Delete
                </button>
            </div>
        </div>
    </div>

    <script>
        // Listen for Livewire events
        document.addEventListener('livewire:init', () => {
            const createModal = document.getElementById('createUserModal');
            const deleteModal = document.getElementById('deleteUserModal');
            const alert = document.getElementById('user-success-alert');
            const msg = document.getElementById('user-success-msg');

            const showSuccess = (message) => {
                if (createModal) createModal.style.display = 'none';
                if (deleteModal) deleteModal.style.display = 'none';
                if (alert && msg) {
                    msg.textContent = message;
                    alert.style.display = 'flex';
                    setTimeout(() => { alert.style.display = 'none'; }, 4000);
                }
            };

            Livewire.on('open-modal', () => {
                if (createModal) createModal.style.display = 'flex';
            });

            Livewire.on('open-delete-modal', () => {
                if (deleteModal) deleteModal.style.display = 'flex';
            });

            Livewire.on('user-created', () => showSuccess('User created successfully!'));
            Livewire.on('user-updated', () => showSuccess('User updated successfully!'));
            Livewire.on('user-deleted', () => showSuccess('User deleted successfully!'));
        });
    </script>
</div>
