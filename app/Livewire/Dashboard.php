<?php

namespace App\Livewire;

use App\Models\ChartOfAccount;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public bool $authenticated = false;
    public bool $needsLogin = false;
    public ?string $username = null;
    public ?string $displayName = null;
    public ?string $email = null;
    public array $roles = [];
    public array $permissions = [];
    public ?string $accessToken = null;

    #[On('smis-session-ready')]
    public function handleSession(string $accessToken = '', array $roles = [], array $permissions = [], ?array $profile = null): void
    {
        Log::info('SSO Session Ready', [
            'profile' => $profile,
            'roles' => $roles,
            'permissions' => $permissions
        ]);

        $this->accessToken = $accessToken;
        $this->needsLogin = false;
        $this->roles = $roles;
        $this->permissions = $permissions;

        // Use the profile from /api/users/me (consistent across login & refresh)
        if ($profile) {
            $this->displayName = $profile['displayName'] ?? $profile['username'] ?? null;
            $this->username = $profile['username'] ?? null;
            $this->email = $profile['email'] ?? null;
        }

        // Fallback: decode JWT if profile wasn't available
        if (!$this->displayName && $this->accessToken) {
            $parts = explode('.', $this->accessToken);
            if (count($parts) === 3) {
                $payloadJson = base64_decode(strtr($parts[1], '-_', '+/'));
                $info = json_decode($payloadJson, true) ?? [];
                $this->displayName = $info['username'] ?? $info['sub'] ?? null;
                $this->email = $info['email'] ?? null;
            }
        }

        $this->authenticated = true;
    }

    #[On('smis-needs-login')]
    public function handleNeedsLogin(): void
    {
        $this->needsLogin = true;
    }

    public function logout(): void
    {
        $this->reset(['authenticated', 'needsLogin', 'username', 'displayName', 'email', 'roles', 'permissions', 'accessToken']);
        $this->dispatch('smis-logout');
    }

    public function render()
    {
        $totalUsers = User::count();
        $totalCOA = ChartOfAccount::count();
        return view('livewire.dashboard', compact('totalUsers', 'totalCOA'));
    }
}
