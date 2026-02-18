<div>
    {{-- Authenticated Dashboard --}}
    <div class="max-w-full mx-auto py-8 px-12">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-base-content mb-1">Dashboard</h1>
            <p class="text-sm text-base-content/70">Overview of your account and system status</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <x-stat-card title="Total Users" :value="number_format($totalUsers)" subtitle="Registered users" icon="tabler--users"
                color="primary" />
            <x-stat-card title="Total COA" :value="number_format($totalCOA)" subtitle="Chart of Accounts" icon="tabler--list-tree"
                color="secondary" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- User Info Card --}}
            <div
                class="bg-base-100/60 backdrop-blur-2xl border border-base-200 rounded-2xl p-6 shadow-sm hover:border-primary/30 hover:-translate-y-0.5 hover:shadow-md transition-all">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-base-200/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-primary w-5 h-5" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <h3 class="text-sm font-semibold m-0 text-base-content/80">User Profile</h3>
                </div>
                <div>
                    <div class="flex justify-between items-center py-2.5 border-t border-base-200/50 first:border-0">
                        <span class="text-xs font-medium text-base-content/60">Display Name</span>
                        <span class="text-sm font-medium text-base-content">{{ $displayName ?? $username }}</span>
                    </div>
                    @if ($username && $username !== $displayName)
                        <div
                            class="flex justify-between items-center py-2.5 border-t border-base-200/50 first:border-0">
                            <span class="text-xs font-medium text-base-content/60">Username</span>
                            <span class="text-sm font-medium text-base-content">{{ $username }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center py-2.5 border-t border-base-200/50 first:border-0">
                        <span class="text-xs font-medium text-base-content/60">Email</span>
                        <span class="text-sm font-medium text-base-content">{{ $email }}</span>
                    </div>
                </div>
            </div>

            {{-- Roles Card --}}
            <div
                class="bg-base-100/60 backdrop-blur-2xl border border-base-200 rounded-2xl p-6 shadow-sm hover:border-primary/30 hover:-translate-y-0.5 hover:shadow-md transition-all">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-base-200/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-primary w-5 h-5" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                    <h3 class="text-sm font-semibold m-0 text-base-content/80">Roles</h3>
                </div>
                <div>
                    @forelse ($roles as $role)
                        <span
                            class="inline-block px-3 py-1.5 rounded-lg text-xs font-semibold m-1 capitalize bg-primary/10 border border-primary/25 text-primary">{{ $role }}</span>
                    @empty
                        <p class="text-base-content/40 text-xs italic m-0">No roles assigned</p>
                    @endforelse
                </div>
            </div>

            {{-- Permissions Card --}}
            <div
                class="bg-base-100/60 backdrop-blur-2xl border border-base-200 rounded-2xl p-6 shadow-sm hover:border-primary/30 hover:-translate-y-0.5 hover:shadow-md transition-all">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-base-200/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-primary w-5 h-5" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    <h3 class="text-sm font-semibold m-0 text-base-content/80">Permissions</h3>
                </div>
                <div>
                    @forelse ($permissions as $perm)
                        <span
                            class="inline-block px-3 py-1.5 rounded-lg text-xs font-semibold m-1 capitalize bg-success/10 border border-success/20 text-success">{{ $perm }}</span>
                    @empty
                        <p class="text-base-content/40 text-xs italic m-0">No permissions assigned</p>
                    @endforelse
                </div>
            </div>

            {{-- Session Info Card --}}
            <div
                class="bg-base-100/60 backdrop-blur-2xl border border-base-200 rounded-2xl p-6 shadow-sm hover:border-primary/30 hover:-translate-y-0.5 hover:shadow-md transition-all">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-base-200/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-primary w-5 h-5" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    <h3 class="text-sm font-semibold m-0 text-base-content/80">Session</h3>
                </div>
                <div>
                    <div class="flex justify-between items-center py-2.5 border-t border-base-200/50 first:border-0">
                        <span class="text-xs font-medium text-base-content/60">Status</span>
                        <span class="flex items-center gap-2 text-sm font-medium text-success">
                            <span
                                class="w-2 h-2 bg-success rounded-full animate-pulse shadow-[0_0_6px_rgba(34,197,94,0.4)]"></span>
                            Active
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2.5 border-t border-base-200/50 first:border-0">
                        <span class="text-xs font-medium text-base-content/60">Token</span>
                        <span
                            class="text-sm font-medium text-base-content font-mono text-xs opacity-70">{{ substr($accessToken ?? '', 0, 20) }}&hellip;</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
