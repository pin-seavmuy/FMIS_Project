<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Placeholder extends Component
{
    public string $title;

    public function mount($title)
    {
        $this->title = $title;
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            <div class="users-page">
                <div class="users-header">
                    <h1 class="users-title">{{ $title }}</h1>
                    <p class="users-subtitle">Feature coming soon</p>
                </div>
                <div class="users-grid-card" style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                    <span class="icon-[tabler--cone]" style="width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.5;"></span>
                    <p>This module is under development.</p>
                </div>
            </div>
        </div>
        HTML;
    }
}
