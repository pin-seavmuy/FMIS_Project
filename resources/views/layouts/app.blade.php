<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body>
        @include('partials.smis-session', [
            'appKey' => config('smis-sso.app_key'),
            'authBaseUrl' => config('smis-sso.auth_base_url'),
        ])

        {{ $slot }}

        @livewireScripts

        <script>
            // Forward SSO session events to Livewire
            document.addEventListener('smis-session:ready', (event) => {
                const d = event.detail || {};
                Livewire.dispatch('smis-session-ready', {
                    accessToken: d.accessToken || '',
                    roles: d.roles || [],
                    permissions: d.permissions || [],
                    profile: d.profile || null,
                });
            });

            // No cached session — show login button
            document.addEventListener('smis-session:needs-login', () => {
                Livewire.dispatch('smis-needs-login');
            });

            // Handle logout dispatched from Livewire
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('smis-logout', async () => {
                    if (window.__smisClient) {
                        try {
                            await window.__smisClient.signOut();
                        } catch (e) {
                            console.warn('SSO signOut error:', e);
                        }
                        window.__smisClient.clearSession();
                    }
                    // Reload to restart the SSO flow
                    window.location.reload();
                });
            });
        </script>
    </body>
</html>
