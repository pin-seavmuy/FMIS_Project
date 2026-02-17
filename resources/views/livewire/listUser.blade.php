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
                <x-btn variant="primary" icon="tabler--plus" wire:click="openCreateModal">
                    Create User
                </x-btn>
            </div>
        </div>

        {{-- Success Alert (overlay top-right) --}}
        <div id="user-success-alert" class="users-alert" style="display:none">
            <span class="icon-[tabler--circle-check] users-alert-icon"></span>
            <span id="user-success-msg"></span>
            <button onclick="document.getElementById('user-success-alert').style.display='none'"
                class="users-alert-close">&times;</button>
        </div>

        {{-- AG Grid Table --}}
        <div class="users-grid-card">
            <x-ag-grid id="usersGrid" :rowData="$datas" :columnDefs="$columns" updateEvent="users-updated" />
        </div>
    </div>

    {{-- User Modal --}}
    <x-modal id="createUserModal" :title="$isEditMode ? 'Edit User' : 'Create New User'" icon="tabler--user">
        <div class="modal-field">
            <label>Name</label>
            <input wire:model="userName" placeholder="Full name" class="users-input" />
            @error('userName')
                <span class="users-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="modal-field">
            <label>Email</label>
            <input wire:model="userEmail" placeholder="Email address" class="users-input" />
            @error('userEmail')
                <span class="users-error">{{ $message }}</span>
            @enderror
        </div>
        <div class="modal-field">
            <label>Password {{ $isEditMode ? '(Leave blank to keep current)' : '' }}</label>
            <input wire:model="userPassword" type="password" placeholder="Password" class="users-input" />
            @error('userPassword')
                <span class="users-error">{{ $message }}</span>
            @enderror
        </div>

        <x-slot:footer>
            <x-btn variant="cancel"
                onclick="document.getElementById('createUserModal').style.display='none'">Cancel</x-btn>
            <x-btn variant="primary" icon="tabler--check" :loading="true" loadingTarget="createUser, updateUser"
                loadingText="Saving..." wire:click="{{ $isEditMode ? 'updateUser' : 'createUser' }}"
                wire:loading.attr="disabled">
                {{ $isEditMode ? 'Update' : 'Create' }}
            </x-btn>
        </x-slot:footer>
    </x-modal>

    {{-- View User Modal --}}
    <x-modal id="viewUserModal" title="User Details" icon="tabler--eye">
        @if ($viewUser)
            <div class="view-field">
                <span class="view-label">Name</span>
                <span class="view-value">{{ $viewUser->name }}</span>
            </div>
            <div class="view-field">
                <span class="view-label">Email</span>
                <span class="view-value">{{ $viewUser->email }}</span>
            </div>
            <div class="view-field">
                <span class="view-label">Email Verified</span>
                <span
                    class="view-value">{{ $viewUser->email_verified_at ? \Carbon\Carbon::parse($viewUser->email_verified_at)->format('M d, Y') : 'Not verified' }}</span>
            </div>
            <div class="view-field">
                <span class="view-label">Created</span>
                <span
                    class="view-value">{{ \Carbon\Carbon::parse($viewUser->created_at)->format('M d, Y h:i A') }}</span>
            </div>
        @endif

        <x-slot:footer>
            <x-btn variant="cancel"
                onclick="document.getElementById('viewUserModal').style.display='none'">Close</x-btn>
            @if ($viewUser)
                <x-btn variant="primary" icon="tabler--pencil" wire:click="editUser({{ $viewUser->id }})"
                    onclick="document.getElementById('viewUserModal').style.display='none'">
                    Edit
                </x-btn>
            @endif
        </x-slot:footer>
    </x-modal>

    {{-- Delete Confirmation Modal --}}
    <x-modal id="deleteUserModal" title="Delete User" icon="tabler--alert-triangle" titleClass="text-red-600">
        <p>Are you sure you want to delete this user? This action cannot be undone.</p>

        <x-slot:footer>
            <x-btn variant="cancel"
                onclick="document.getElementById('deleteUserModal').style.display='none'">Cancel</x-btn>
            <x-btn variant="danger" icon="tabler--trash" wire:click="deleteUser({{ $userId }})"
                onclick="document.getElementById('deleteUserModal').style.display='none'">
                Delete
            </x-btn>
        </x-slot:footer>
    </x-modal>

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
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 4000);
                }
            };

            Livewire.on('open-modal', () => {
                if (createModal) createModal.style.display = 'flex';
            });

            Livewire.on('open-view-modal', () => {
                const viewModal = document.getElementById('viewUserModal');
                if (viewModal) viewModal.style.display = 'flex';
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
