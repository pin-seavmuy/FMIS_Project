<div>
    @if ($authenticated)
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
                    <button wire:click="logout" class="sso-logout-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Sign Out
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
    @elseif ($needsLogin)
        {{-- Login Button State --}}
        <div class="sso-loading">
            <div class="sso-loading-card">
                <div class="sso-lock-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <h2>SMIS Single Sign-On</h2>
                <p>Click below to sign in with your SMIS account. A login window will open.</p>
                <button onclick="document.dispatchEvent(new Event('smis-session:start'))" class="sso-login-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Sign in with SMIS
                </button>
            </div>
        </div>
    @else
        {{-- Initial Loading State --}}
        <div class="sso-loading">
            <div class="sso-loading-card">
                <div class="sso-spinner"></div>
                <h2>Checking session&hellip;</h2>
                <p>Verifying your SMIS authentication status.</p>
            </div>
        </div>
    @endif
</div>
