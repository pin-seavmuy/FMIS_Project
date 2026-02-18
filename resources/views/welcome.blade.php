<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Battambang:wght@300;400;700;900&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
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

    <div class="flex flex-col items-center justify-center min-h-screen p-8 text-center bg-base-100 text-base-content relative overflow-hidden" id="landing-page">
        {{-- Background blobs for visual interest --}}
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-violet-500/10 rounded-full blur-[100px] pointer-events-none"></div>

        <button class="fixed top-5 right-5 z-50 flex items-center gap-2 px-4 py-2 bg-base-100/80 backdrop-blur-md border border-base-200 rounded-xl text-xs font-medium cursor-pointer transition-all shadow-sm hover:-translate-y-px hover:shadow-md hover:border-base-300" id="theme-toggle" aria-label="Toggle theme">
            <svg id="theme-icon-light" class="theme-icon w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
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
            <svg id="theme-icon-dark" class="theme-icon w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
            </svg>
            <span id="theme-label"></span>
        </button>

        <div class="max-w-[520px] mb-12 relative z-10">
            <div class="w-[90px] h-[90px] mx-auto mb-6 rounded-3xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white shadow-xl shadow-indigo-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 2 7 12 12 22 7 12 2" />
                    <polyline points="2 17 12 22 22 17" />
                    <polyline points="2 12 12 17 22 12" />
                </svg>
            </div>
            <h1 class="text-5xl font-extrabold mb-2 bg-gradient-to-r from-indigo-500 to-violet-500 bg-clip-text text-transparent pb-1">FMIS</h1>
            <p class="text-lg font-medium text-base-content/70 mb-4">Financial Management Information System</p>
            <p class="text-[15px] text-base-content/60 leading-relaxed m-0">Sign in with your SMIS account to access the dashboard, manage data, and view reports.</p>

            <div class="mt-8 text-base-content/60 flex flex-col items-center" id="landing-loading">
                <span class="loading loading-spinner text-primary loading-lg"></span>
                <p style="margin-top: 1rem; font-size: 0.8125rem;">Checking session&hellip;</p>
            </div>

            <div class="mt-2" id="landing-actions" style="display:none">
                <button onclick="document.dispatchEvent(new Event('smis-session:start'))" class="inline-flex items-center justify-center gap-2.5 mt-7 px-8 py-3.5 bg-gradient-to-br from-indigo-500 to-violet-500 text-white border-none rounded-[14px] text-[15px] font-semibold cursor-pointer transition-all shadow-lg shadow-indigo-500/35 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/50 hover:brightness-110 active:translate-y-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" y1="12" x2="3" y2="12" />
                    </svg>
                    Sign in with SMIS
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-[720px] mb-8 w-full relative z-10">
            <div class="p-6 bg-base-100/60 backdrop-blur-xl border border-base-200 rounded-2xl transition-all hover:-translate-y-1 hover:border-primary/30 hover:shadow-lg group text-left">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary mb-3 w-6 h-6 group-hover:scale-110 transition-transform" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="14" y="14" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                </svg>
                <h3 class="text-[15px] font-semibold mb-1.5 text-base-content">Dashboard</h3>
                <p class="text-[13px] text-base-content/60 m-0 leading-snug">Overview of your data and activity</p>
            </div>
            <div class="p-6 bg-base-100/60 backdrop-blur-xl border border-base-200 rounded-2xl transition-all hover:-translate-y-1 hover:border-primary/30 hover:shadow-lg group text-left">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary mb-3 w-6 h-6 group-hover:scale-110 transition-transform" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                <h3 class="text-[15px] font-semibold mb-1.5 text-base-content">Role-Based Access</h3>
                <p class="text-[13px] text-base-content/60 m-0 leading-snug">Secure access based on your role</p>
            </div>
            <div class="p-6 bg-base-100/60 backdrop-blur-xl border border-base-200 rounded-2xl transition-all hover:-translate-y-1 hover:border-primary/30 hover:shadow-lg group text-left">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary mb-3 w-6 h-6 group-hover:scale-110 transition-transform" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23" />
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                </svg>
                <h3 class="text-[15px] font-semibold mb-1.5 text-base-content">Financial Reports</h3>
                <p class="text-[13px] text-base-content/60 m-0 leading-snug">Generate and manage reports</p>
            </div>
        </div>

        <p class="text-xs text-base-content/40 m-0">Powered by SMIS Single Sign-On</p>
    </div>

    <script>
        // Theme toggle
        (function() {
            function applyTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('fmis-theme', theme);
                document.querySelectorAll('#theme-icon-light').forEach(el => el.classList.toggle('hidden', theme !==
                    'dark'));
                document.querySelectorAll('#theme-icon-dark').forEach(el => el.classList.toggle('hidden', theme ===
                    'dark'));
                document.querySelectorAll('#theme-label').forEach(el => el.textContent = theme === 'dark' ?
                    'Light Mode' : 'Dark Mode');
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
