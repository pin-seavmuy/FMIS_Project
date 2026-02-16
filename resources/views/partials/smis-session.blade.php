@php
    $appKey = $appKey ?? config('smis-sso.app_key', '');
    $authBaseUrl = $authBaseUrl ?? config('smis-sso.auth_base_url');
    $clientSrc = asset('vendor/smis-sso/sso-client/sso-client.js');
@endphp

<div
    data-smis-sso="mount"
    data-app-key="{{ $appKey }}"
    data-auth-base-url="{{ $authBaseUrl }}"
    data-client-src="{{ $clientSrc }}"
>
    <script type="module">
        const currentScript = document.currentScript;
        const el = currentScript ? currentScript.parentElement : document.querySelector('[data-smis-sso="mount"]');
        if (!el) {
            console.error('SMIS session bootstrap failed: mount element not found');
        } else {
            const data = {
                appKey: el.dataset.appKey,
                authBaseUrl: el.dataset.authBaseUrl || undefined,
                clientSrc: el.dataset.clientSrc,
            };

            // Helper: fetch user profile from /api/users/me for consistent display name
            async function fetchProfile(baseUrl, accessToken, appKey) {
                try {
                    const res = await fetch(`${baseUrl}/api/users/me`, {
                        headers: {
                            'Authorization': `Bearer ${accessToken}`,
                            'X-SMIS-APP-KEY': appKey,
                        },
                    });
                    if (res.ok) return await res.json();
                } catch (e) {
                    console.warn('Failed to fetch user profile:', e);
                }
                return null;
            }

            // Helper: after getting a session, fetch authorizations + profile and dispatch
            async function dispatchWithAuth(client, session) {
                let roles = [];
                let permissions = [];
                let profile = null;

                try {
                    const authz = await client.loadAuthorizations(session);
                    roles = authz?.roles || [];
                    permissions = authz?.permissions || [];
                } catch (e) {
                    console.warn('Failed to load authorizations:', e);
                }

                // Fetch consistent profile from /api/users/me
                const baseUrl = data.authBaseUrl || '';
                profile = await fetchProfile(baseUrl, session.accessToken, data.appKey);

                document.dispatchEvent(new CustomEvent('smis-session:ready', {
                    detail: {
                        ...session,
                        roles,
                        permissions,
                        profile,
                    }
                }));
            }

            // Load the client and store it globally, but don't probe yet.
            try {
                const { AuthClient } = await import(data.clientSrc);
                const client = new AuthClient({
                    appKey: data.appKey,
                    authBaseUrl: data.authBaseUrl,
                });
                window.__smisClient = client;

                // Check if there's already a cached session (no popup needed)
                const cached = client.getCachedSession();
                if (cached && cached.accessToken) {
                    await dispatchWithAuth(client, cached);
                } else {
                    document.dispatchEvent(new CustomEvent('smis-session:needs-login'));
                }
            } catch (error) {
                console.error('SMIS client init failed', error);
                document.dispatchEvent(new CustomEvent('smis-session:needs-login'));
            }

            // Listen for manual trigger (user clicks login button)
            document.addEventListener('smis-session:start', async () => {
                if (!window.__smisClient) return;
                try {
                    const session = await window.__smisClient.ensureSession();
                    await dispatchWithAuth(window.__smisClient, session);
                } catch (error) {
                    console.error('SMIS session bootstrap failed', error);
                    document.dispatchEvent(new CustomEvent('smis-session:error', { detail: error }));
                }
            });
        }
    </script>
</div>
