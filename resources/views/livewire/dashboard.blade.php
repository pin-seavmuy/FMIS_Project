<div>
    {{-- Authenticated Dashboard --}}
    <div class="sso-dashboard">
        <div class="sso-header">
            <div class="sso-header-content">
                <div class="sso-user-info">
                    <div class="sso-avatar">
                        {{ strtoupper(substr($displayName ?? $username ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="sso-welcome">Welcome back, <span>{{ $displayName ?? $username }}</span></h1>
                        <p class="sso-email">{{ $email }}</p>
                    </div>
                </div>
                <button class="theme-toggle-inline" id="theme-toggle-app" aria-label="Toggle theme">
                    {{-- Sun icon (shown in dark mode → click to go light) --}}
                    <svg class="theme-icon-light-app theme-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                    {{-- Moon icon (shown in light mode → click to go dark) --}}
                    <svg class="theme-icon-dark-app theme-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                    <span class="theme-label-app"></span>
                </button>
            </div>
        </div>

        <div class="sso-grid">
            {{-- User Info Card --}}
            <div class="sso-card sso-card-user">
                <div class="sso-card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <h3>User Profile</h3>
                </div>
                <div class="sso-card-body">
                    <div class="sso-info-row">
                        <span class="sso-label">Display Name</span>
                        <span class="sso-value">{{ $displayName ?? $username }}</span>
                    </div>
                    @if($username && $username !== $displayName)
                    <div class="sso-info-row">
                        <span class="sso-label">Username</span>
                        <span class="sso-value">{{ $username }}</span>
                    </div>
                    @endif
                    <div class="sso-info-row">
                        <span class="sso-label">Email</span>
                        <span class="sso-value">{{ $email }}</span>
                    </div>
                </div>
            </div>

            {{-- Roles Card --}}
            <div class="sso-card sso-card-roles">
                <div class="sso-card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <h3>Roles</h3>
                </div>
                <div class="sso-card-body">
                    @forelse ($roles as $role)
                        <span class="sso-badge sso-badge-role">{{ $role }}</span>
                    @empty
                        <p class="sso-empty">No roles assigned</p>
                    @endforelse
                </div>
            </div>

            {{-- Permissions Card --}}
            <div class="sso-card sso-card-perms">
                <div class="sso-card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <h3>Permissions</h3>
                </div>
                <div class="sso-card-body">
                    @forelse ($permissions as $perm)
                        <span class="sso-badge sso-badge-perm">{{ $perm }}</span>
                    @empty
                        <p class="sso-empty">No permissions assigned</p>
                    @endforelse
                </div>
            </div>

            {{-- Session Info Card --}}
            <div class="sso-card sso-card-session">
                <div class="sso-card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <h3>Session</h3>
                </div>
                <div class="sso-card-body">
                    <div class="sso-info-row">
                        <span class="sso-label">Status</span>
                        <span class="sso-status-active">
                            <span class="sso-status-dot"></span>
                            Active
                        </span>
                    </div>
                    <div class="sso-info-row">
                        <span class="sso-label">Token</span>
                        <span class="sso-value sso-token-preview">{{ substr($accessToken ?? '', 0, 20) }}&hellip;</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
