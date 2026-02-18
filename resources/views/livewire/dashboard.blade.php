<div>
    {{-- Authenticated Dashboard --}}
    <div class="max-w-full mx-auto py-8 px-12">
        <div class="mb-10">
            <div
                class="flex items-center justify-between p-7 bg-base-100/80 backdrop-blur-xl border border-base-200 rounded-[1.25rem] shadow-sm">
                <div class="flex items-center gap-5">
                    <div
                        class="w-[52px] h-[52px] rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-xl font-bold text-white shadow-lg shadow-indigo-500/30">
                        {{ strtoupper(substr($displayName ?? ($username ?? '?'), 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-base-content m-0">Welcome back, <span
                                class="bg-gradient-to-r from-indigo-500 to-violet-500 bg-clip-text text-transparent">{{ $displayName ?? $username }}</span>
                        </h1>
                        <p class="mt-1 text-base-content/60 text-sm">{{ $email }}</p>
                    </div>
                </div>
                <button
                    class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-br from-indigo-500 to-violet-500 border-none text-white rounded-xl text-sm font-semibold cursor-pointer transition-all hover:-translate-y-px hover:shadow-lg hover:shadow-indigo-500/30 hover:brightness-110"
                    id="theme-toggle-app" aria-label="Toggle theme">
                    {{-- Sun icon (shown in dark mode → click to go light) --}}
                    <svg class="theme-icon-light-app theme-icon w-5 h-5 flex-shrink-0 text-white"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5" />
                        <line x1="12" y1="1" x2="12" y2="3" />
                        <line x1="12" y1="21" x2="12" y2="23" />
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                        <line x1="1" y1="12" x2="3" y2="12" />
                        <line x1="21" y1="12" x2="23" y2="12" />
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
                    </svg>
                    {{-- Moon icon (shown in light mode → click to go dark) --}}
                    <svg class="theme-icon-dark-app theme-icon w-5 h-5 flex-shrink-0 text-white"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                    </svg>
                    <span class="theme-label-app"></span>
                </button>
            </div>
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
