<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name') }} - Welcome</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            (function() {
                const saved = localStorage.getItem('fmis-theme');
                const theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            })();
        </script>
    </head>
    <body>
        @include('partials.smis-session', [
            'appKey' => config('smis-sso.app_key'),
            'authBaseUrl' => config('smis-sso.auth_base_url'),
        ])

        <div class="landing" id="landing-page">
            <button class="theme-toggle" id="theme-toggle" aria-label="Toggle theme">
                <svg id="theme-icon-light" class="theme-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <svg id="theme-icon-dark" class="theme-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                <span id="theme-label"></span>
            </button>

            <div class="landing-content">
                <div class="landing-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                </div>
                <h1 class="landing-title">FMIS</h1>
                <p class="landing-subtitle">Financial Management Information System</p>
                <p class="landing-desc">Sign in with your SMIS account to access the dashboard, manage data, and view reports.</p>

                <div class="landing-loading" id="landing-loading">
                    <div class="sso-spinner"></div>
                    <p style="margin-top: 1rem; font-size: 0.8125rem;">Checking session&hellip;</p>
                </div>

                <div class="landing-actions" id="landing-actions" style="display:none">
                    <button onclick="document.dispatchEvent(new Event('smis-session:start'))" class="sso-login-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Sign in with SMIS
                    </button>
                </div>
            </div>

            <div class="landing-features">
                <div class="landing-feature">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <h3>Dashboard</h3>
                    <p>Overview of your data and activity</p>
                </div>
                <div class="landing-feature">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <h3>Role-Based Access</h3>
                    <p>Secure access based on your role</p>
                </div>
                <div class="landing-feature">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    <h3>Financial Reports</h3>
                    <p>Generate and manage reports</p>
                </div>
            </div>

            <p class="landing-footer">Powered by SMIS Single Sign-On</p>
        </div>

        <script>
            // Theme toggle
            (function() {
                function applyTheme(theme) {
                    document.documentElement.setAttribute('data-theme', theme);
                    localStorage.setItem('fmis-theme', theme);
                    document.querySelectorAll('#theme-icon-light').forEach(el => el.classList.toggle('hidden', theme !== 'dark'));
                    document.querySelectorAll('#theme-icon-dark').forEach(el => el.classList.toggle('hidden', theme === 'dark'));
                    document.querySelectorAll('#theme-label').forEach(el => el.textContent = theme === 'dark' ? 'Light Mode' : 'Dark Mode');
                }
                applyTheme(document.documentElement.getAttribute('data-theme') || 'light');
                document.getElementById('theme-toggle')?.addEventListener('click', () => {
                    applyTheme(document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
                });
            })();

            // SSO: show login button or redirect
            document.addEventListener('smis-session:needs-login', () => {
                document.getElementById('landing-loading').style.display = 'none';
                document.getElementById('landing-actions').style.display = 'block';
            });
            document.addEventListener('smis-session:ready', () => {
                window.location.href = '/dashboard';
            });
        </script>
    </body>
</html>
