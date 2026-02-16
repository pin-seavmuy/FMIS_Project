<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <script>
            (function() {
                const saved = localStorage.getItem('fmis-theme');
                const theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
                if (localStorage.getItem('fmis-sidebar') === 'collapsed') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            })();
        </script>
    </head>
    <body>
        {{-- SSO bootstrap --}}
        @include('partials.smis-session', [
            'appKey' => config('smis-sso.app_key'),
            'authBaseUrl' => config('smis-sso.auth_base_url'),
        ])

        {{-- App Shell: Sidebar + Main Content --}}
        <div class="app-shell" id="app-shell">
            <x-sidebar />

            <main class="main-content" id="main-content">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
        <script src="{{ asset('vendor/flyonui/flyonui.js') }}"></script>

        <script>
            // ── Theme Toggle ──
            (function() {
                function applyTheme(theme) {
                    document.documentElement.setAttribute('data-theme', theme);
                    localStorage.setItem('fmis-theme', theme);
                    document.querySelectorAll('#theme-icon-light, .theme-icon-light-app').forEach(el => {
                        el.classList.toggle('hidden', theme !== 'dark');
                    });
                    document.querySelectorAll('#theme-icon-dark, .theme-icon-dark-app').forEach(el => {
                        el.classList.toggle('hidden', theme === 'dark');
                    });
                    document.querySelectorAll('#theme-label, .theme-label-app').forEach(el => {
                        el.textContent = theme === 'dark' ? 'Light Mode' : 'Dark Mode';
                    });
                }

                const current = document.documentElement.getAttribute('data-theme') || 'light';
                applyTheme(current);

                document.addEventListener('click', (e) => {
                    const btn = e.target.closest('#theme-toggle, #theme-toggle-app');
                    if (!btn) return;
                    const current = document.documentElement.getAttribute('data-theme');
                    applyTheme(current === 'dark' ? 'light' : 'dark');
                });
            })();

            // ── SSO Session → Livewire ──
            (function() {
                document.addEventListener('smis-session:needs-login', () => {
                    // Not logged in on an app page → redirect to home
                    window.location.href = '/';
                });

                document.addEventListener('smis-session:ready', (event) => {
                    const d = event.detail || {};
                    Livewire.dispatch('smis-session-ready', {
                        accessToken: d.accessToken || '',
                        roles: d.roles || [],
                        permissions: d.permissions || [],
                        profile: d.profile || null,
                    });
                });
            })();

            // ── Sidebar Toggle ──
            (function() {
                const sidebar = document.getElementById('sidebar');
                const collapseBtn = document.getElementById('sidebar-collapse-btn');
                const signoutBtn = document.getElementById('sidebar-signout-btn');
                const hamburger = document.getElementById('sidebar-hamburger');
                const overlay = document.getElementById('sidebar-overlay');

                collapseBtn?.addEventListener('click', () => {
                    document.documentElement.classList.toggle('sidebar-collapsed');
                    const collapsed = document.documentElement.classList.contains('sidebar-collapsed');
                    localStorage.setItem('fmis-sidebar', collapsed ? 'collapsed' : 'expanded');
                });

                signoutBtn?.addEventListener('click', () => {
                    Livewire.dispatch('smis-logout');
                });

                hamburger?.addEventListener('click', () => {
                    sidebar?.classList.add('sidebar-mobile-open');
                    overlay?.classList.add('active');
                });

                overlay?.addEventListener('click', () => {
                    sidebar?.classList.remove('sidebar-mobile-open');
                    overlay?.classList.remove('active');
                });
            })();

            // ── SSO Logout ──
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('smis-logout', async () => {
                    if (window.__smisClient) {
                        try { await window.__smisClient.signOut(); } catch (e) {}
                        window.__smisClient.clearSession();
                    }
                    window.location.href = '/';
                });
            });
        </script>
    </body>
</html>
