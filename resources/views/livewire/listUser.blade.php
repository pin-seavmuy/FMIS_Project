<div>
    <div class="p-8">
        {{-- Header with Create Button --}}
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-base-content mb-1">{{ __('Users') }}</h1>
                <p class="text-sm text-base-content/70">{{ __('Manage system users') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <x-search wire:model.live.debounce.300ms="search" placeholder="{{ __('Search') }}" />
                <x-btn variant="primary" icon="icon-[tabler--plus]" wire:click="openCreateModal">
                    {{ __('Create User') }}
                </x-btn>
            </div>
        </div>

        {{-- Success Alert (overlay top-right) --}}
        <div id="user-success-alert"
            class="fixed top-6 right-6 z-[9999] hidden items-center gap-2.5 px-6 py-4 bg-base-100 border-l-4 border-green-500 rounded-lg shadow-xl"
            style="display:none">
            <span class="icon-[tabler--circle-check] w-5 h-5 text-green-600"></span>
            <span id="user-success-msg" class="text-sm font-semibold text-green-700"></span>
            <button onclick="document.getElementById('user-success-alert').style.display='none'"
                class="ml-auto text-base-content/60 hover:text-base-content transition-colors">
                <span class="icon-[tabler--x] w-5 h-5"></span>
            </button>
        </div>

        {{-- AG Grid Table --}}
        <x-ag-grid id="usersGrid" :rowData="$datas" :columnDefs="$columns" updateEvent="users-updated" />
    </div>

    {{-- User Modal --}}
    <x-modal id="createUserModal" :title="$isEditMode ? 'Edit User' : 'Create New User'" icon="icon-[tabler--user]">
        <div class="mb-4">
            <label class="block text-sm font-medium text-base-content/70 mb-1.5">Name</label>
            <input wire:model="userName" placeholder="Full name"
                class="w-full px-3.5 py-2.5 border border-base-300 rounded-lg bg-base-100 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors" />
            @error('userName')
                <span class="block text-error text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-base-content/70 mb-1.5">Email</label>
            <input wire:model="userEmail" placeholder="Email address"
                class="w-full px-3.5 py-2.5 border border-base-300 rounded-lg bg-base-100 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors" />
            @error('userEmail')
                <span class="block text-error text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-base-content/70 mb-1.5">Password
                {{ $isEditMode ? '(Leave blank to keep current)' : '' }}</label>
            <input wire:model="userPassword" type="password" placeholder="Password"
                class="w-full px-3.5 py-2.5 border border-base-300 rounded-lg bg-base-100 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-colors" />
            @error('userPassword')
                <span class="block text-error text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <x-slot:footer>
            <x-btn variant="cancel"
                onclick="document.getElementById('createUserModal').style.display='none'">Cancel</x-btn>
            <x-btn variant="primary" icon="icon-[tabler--check]" :loading="true" loadingTarget="createUser, updateUser"
                loadingText="Saving..." wire:click="{{ $isEditMode ? 'updateUser' : 'createUser' }}"
                wire:loading.attr="disabled">
                {{ $isEditMode ? 'Update' : 'Create' }}
            </x-btn>
        </x-slot:footer>
    </x-modal>

    {{-- View User Modal --}}
    <x-modal id="viewUserModal" title="User Details" icon="icon-[tabler--eye]">
        @if ($viewUser)
            <div class="flex justify-between items-center py-3 border-b border-base-200 last:border-0">
                <span class="text-sm font-medium text-base-content/70">Name</span>
                <span class="text-sm font-semibold text-base-content">{{ $viewUser->name }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-base-200 last:border-0">
                <span class="text-sm font-medium text-base-content/70">Email</span>
                <span class="text-sm font-semibold text-base-content">{{ $viewUser->email }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-base-200 last:border-0">
                <span class="text-sm font-medium text-base-content/70">Email Verified</span>
                <span
                    class="text-sm font-semibold text-base-content">{{ $viewUser->email_verified_at ? \Carbon\Carbon::parse($viewUser->email_verified_at)->format('M d, Y') : 'Not verified' }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-base-200 last:border-0">
                <span class="text-sm font-medium text-base-content/70">Created</span>
                <span
                    class="text-sm font-semibold text-base-content">{{ \Carbon\Carbon::parse($viewUser->created_at)->format('M d, Y h:i A') }}</span>
            </div>
        @endif

        <x-slot:footer>
            <x-btn variant="cancel"
                onclick="document.getElementById('viewUserModal').style.display='none'">Close</x-btn>
            @if ($viewUser)
                <x-btn variant="primary" icon="icon-[tabler--pencil]" wire:click="editUser({{ $viewUser->id }})"
                    onclick="document.getElementById('viewUserModal').style.display='none'">
                    Edit
                </x-btn>
            @endif
        </x-slot:footer>
    </x-modal>

    {{-- Delete Confirmation Modal --}}
    <x-modal id="deleteUserModal" title="Delete User" icon="icon-[tabler--alert-triangle]" titleClass="text-red-600">
        <p class="text-base-content/80">Are you sure you want to delete this user? This action cannot be undone.</p>

        <x-slot:footer>
            <x-btn variant="cancel"
                onclick="document.getElementById('deleteUserModal').style.display='none'">Cancel</x-btn>
            <x-btn variant="danger" icon="icon-[tabler--trash]" wire:click="deleteUser({{ $userId }})"
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
