# SMIS SSO Integration Guide for fmis-dev

This document describes how the SMIS Single Sign-On (SSO) was integrated into the `fmis-dev` Laravel 12 / Livewire 4 application.

---

## Prerequisites

- **Auth Gateway** running at `http://localhost:3000` (from `D:\smis-sso\smis-sso\auth-gateway`)
- **fmis-dev** Laravel project with Livewire 4
- A valid `SMIS_APP_KEY` registered in the auth-gateway's `applications` table

---

## Why Direct Integration (Not Composer)?

The official `smis/sso-client-laravel` package requires **Livewire 3**, but `fmis-dev` uses **Livewire 4**. To avoid modifying the SSO package or downgrading Livewire, the necessary components were copied and adapted directly into `fmis-dev`.

---

## Step-by-Step Implementation

### Step 1: Copy the SSO Browser Bundle

Copy `sso-client.js` from the SSO repo into the public directory:

```
Source: D:\smis-sso\smis-sso\smis-sso-laravel\resources\dist\sso-client\sso-client.js
Target: D:\fmis-dev\public\vendor\smis-sso\sso-client\sso-client.js
```

This JS bundle handles the SSO probe, popup login, and token management in the browser.

---

### Step 2: Add Environment Variables

Add these to `.env`:

```dotenv
SMIS_APP_KEY=de995e1f857c6f821fadf0543e00ab5e2202c98e9dae6b7117b8c35c2c3410fb
SMIS_AUTH_BASE_URL=http://localhost:3000
```

And to `.env.example` (with placeholder values):

```dotenv
SMIS_APP_KEY=your-smis-app-key-here
SMIS_AUTH_BASE_URL=http://localhost:3000
```

---

### Step 3: Create the SSO Config File

**File:** `config/smis-sso.php`

```php
<?php

return [
    'app_key' => env('SMIS_APP_KEY', ''),
    'auth_base_url' => env('SMIS_AUTH_BASE_URL', 'http://localhost:3000'),
    'clock_skew' => env('SMIS_CLOCK_SKEW', 120),
];
```

This makes the SSO settings available via `config('smis-sso.app_key')` etc.

---

### Step 4: Create the EnsureSmisSso Middleware

**File:** `app/Http/Middleware/EnsureSmisSso.php`

This middleware:
1. Extracts the `Bearer` token from the request
2. Verifies the JWT signature (HS256) using `SMIS_APP_KEY`
3. Checks token expiry (with clock skew tolerance)
4. Hydrates `auth()->user()` with decoded token info
5. Returns 401 if the token is missing or invalid

---

### Step 5: Register the Middleware Alias

**File:** `bootstrap/app.php`

Add the alias inside `withMiddleware`:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'smis.sso' => \App\Http\Middleware\EnsureSmisSso::class,
    ]);
})
```

This allows you to protect routes with `middleware('smis.sso')`.

---

### Step 6: Create the SSO Session Bootstrap Partial

**File:** `resources/views/partials/smis-session.blade.php`

This Blade partial:
1. Loads `sso-client.js` as a module
2. Initializes `AuthClient` with `appKey` and `authBaseUrl`
3. Checks for a **cached session** (no popup needed)
4. If no cached session → dispatches `smis-session:needs-login` (shows login button)
5. On user click → calls `client.ensureSession()` (opens SSO popup)
6. After login → calls `client.loadAuthorizations()` to fetch roles/permissions
7. Fetches `/api/users/me` for consistent display name
8. Dispatches `smis-session:ready` with all data

> **Important:** The SSO popup is only opened on a user click (not auto-start) to avoid browser popup blockers.

---

### Step 7: Create the Dashboard Livewire Component

**File:** `app/Livewire/Dashboard.php`

The component:
- Listens for `smis-session-ready` event from JavaScript
- Receives `accessToken`, `roles`, `permissions`, and `profile` as named parameters
- Uses `profile.displayName` from `/api/users/me` for consistent naming
- Falls back to JWT payload decoding if profile fetch fails
- Provides a `logout()` method that dispatches `smis-logout`

**File:** `resources/views/livewire/dashboard.blade.php`

Three UI states:
1. **Loading** — "Checking session…" spinner (while checking cached session)
2. **Needs Login** — "Sign in with SMIS" button (no cached session found)
3. **Authenticated** — Dashboard cards showing Display Name, Email, Roles, Permissions, Session info

---

### Step 8: Update the Layout

**File:** `resources/views/layouts/app.blade.php`

Add the SSO bootstrap partial and event forwarding script:

```blade
{{-- SSO session bootstrap --}}
@include('partials.smis-session', [
    'appKey' => config('smis-sso.app_key'),
    'authBaseUrl' => config('smis-sso.auth_base_url'),
])

{{ $slot }}

@livewireScripts

<script>
    // Forward SSO events to Livewire
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

    // Handle logout
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('smis-logout', async () => {
            if (window.__smisClient) {
                try { await window.__smisClient.signOut(); } catch (e) {}
                window.__smisClient.clearSession();
            }
            window.location.reload();
        });
    });
</script>
```

---

### Step 9: Update Routes

**File:** `routes/web.php`

Set the Dashboard component as the home page:

```php
use App\Livewire\Dashboard;

Route::get('/', Dashboard::class)->name('home');
```

---

### Step 10: Add CSS Styles

**File:** `resources/css/app.css`

Added styles for:
- `.sso-loading` — loading spinner and login button screen
- `.sso-dashboard` — authenticated dashboard layout
- `.sso-card` — glassmorphism info cards
- `.sso-badge` — role and permission badges
- `.sso-login-btn` — gradient login button with hover animation

---

## SSO Flow Diagram

```
Browser                    fmis-dev (8000)            auth-gateway (3000)
  |                              |                           |
  |--- GET / ------------------>|                           |
  |<-- Dashboard + sso-client.js|                           |
  |                              |                           |
  |--- Check cached session --->|                           |
  |    (no popup)               |                           |
  |                              |                           |
  | [If no cached session]       |                           |
  |<-- Show "Sign in" button    |                           |
  |                              |                           |
  | [User clicks "Sign in"]     |                           |
  |--- ensureSession() ---------|--- Probe /sso/probe ----->|
  |                              |                           |
  |    [If not logged in]        |                           |
  |<---------- Login popup ------|<--------------------------|
  |--- POST /auth/login --------|-------------------------->|
  |<---------- Tokens ----------|<--------------------------|
  |                              |                           |
  |--- loadAuthorizations() ----|--- GET /api/sso/authz --->|
  |<---------- roles/perms -----|<--------------------------|
  |                              |                           |
  |--- fetch /api/users/me -----|-------------------------->|
  |<---------- profile ---------|<--------------------------|
  |                              |                           |
  |--- Livewire dispatch ------>|                           |
  |<-- Dashboard with user info |                           |
```

---

## File Summary

| File | Type | Purpose |
|------|------|---------|
| `public/vendor/smis-sso/sso-client/sso-client.js` | Copied | Browser SSO client bundle |
| `config/smis-sso.php` | New | SSO configuration (app key, base URL) |
| `app/Http/Middleware/EnsureSmisSso.php` | New | JWT validation middleware |
| `resources/views/partials/smis-session.blade.php` | New | SSO bootstrap (JS probe + event dispatch) |
| `app/Livewire/Dashboard.php` | New | Dashboard component (SSO state management) |
| `resources/views/livewire/dashboard.blade.php` | New | Dashboard UI (loading → login → authenticated) |
| `bootstrap/app.php` | Modified | Registered `smis.sso` middleware alias |
| `resources/views/layouts/app.blade.php` | Modified | Added SSO partial + JS event forwarding |
| `routes/web.php` | Modified | Home route → Dashboard component |
| `resources/css/app.css` | Modified | SSO dashboard styles |
| `.env` / `.env.example` | Modified | Added `SMIS_APP_KEY`, `SMIS_AUTH_BASE_URL` |

---

## Running the Application

1. **Start the auth-gateway:**
   ```bash
   cd D:\smis-sso\smis-sso\auth-gateway
   npm run start:dev
   ```

2. **Start fmis-dev:**
   ```bash
   cd D:\fmis-dev
   php artisan serve    # Terminal 1
   npm run dev          # Terminal 2
   ```

3. **Open** `http://localhost:8000` → Click "Sign in with SMIS" → Login → Dashboard shows your info.
